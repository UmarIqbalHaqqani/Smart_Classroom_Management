<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$student_id = $student->ID;
$session_id = $student->session_id;

$leaves_per_page = WLSM_M::leaves_per_page();

$leaves_query = WLSM_M::leaves_query();

$leaves_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$leaves_query}) AS combined_table", $school_id, $session_id, $student_id ) );

$leaves_page = isset( $_GET['leaves_page'] ) ? absint( $_GET['leaves_page'] ) : 1;

$leaves_page_offset = ( $leaves_page * $leaves_per_page ) - $leaves_per_page;

$leaves = $wpdb->get_results( $wpdb->prepare( $leaves_query . ' ORDER BY lv.ID DESC LIMIT %d, %d', $school_id, $session_id, $student_id, $leaves_page_offset, $leaves_per_page ) );
$multiple_days = false;
$nonce_action  = 'submit-student-leave-request';
?>
<div class="wlsm-content-area wlsm-section-leave-request wlsm-student-leave-request">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Leave Request', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-leave-request-section">
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-submit-student-leave-request-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-p-st-submit-student-leave-request">

			<div class="wlsm-grid wlsm-form-group">
				<label class="wlsm-form-label wlsm-font-bold">
					<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'Number of Days', 'school-management' ); ?>:
				</label>

				<div class="wlsm-mb-3">
					<label class="radio-inline wlsm-mr-3">
						<input <?php checked( $multiple_days, false, true ); ?> type="radio" name="multiple_days" class="wlsm-mr-2" id="wlsm_multiple_days_0" value="0">
						<?php esc_html_e( 'Leave for Single Day', 'school-management' ); ?>
					</label>
					<label class="radio-inline wlsm-mr-3">
						<input <?php checked( $multiple_days, true, true ); ?> type="radio" name="multiple_days" class="wlsm-mr-2" id="wlsm_multiple_days_1" value="1">
						<?php esc_html_e( 'Leave for Multiple Days', 'school-management' ); ?>
					</label>
				</div>

				<div class="wlsm-col-6 wlsm-mb-2">
					<label for="wlsm_leave_start_date" class="wlsm-form-label wlsm-font-bold">
						<?php
						if ( $multiple_days ) {
							esc_html_e( 'Start Date:', 'school-management' );
						} else {
							esc_html_e( 'Leave Date:', 'school-management' );
						}
						?>
					</label>
					<br>
					<input data-single="<?php esc_attr_e( 'Leave Date:', 'school-management' ); ?>" data-multiple="<?php esc_attr_e( 'Start Date:', 'school-management' ); ?>" type="text" name="start_date" class="wlsm-font-control" id="wlsm_leave_start_date" placeholder="<?php
						if ( $multiple_days ) {
							esc_attr_e( 'Start Date', 'school-management' );
						} else {
							esc_attr_e( 'Leave Date', 'school-management' );
						}; ?>">
				</div>

				<div class="wlsm-col-6 wlsm_leave_end_date">
					<label for="wlsm_leave_end_date" class="wlsm-form-label wlsm-font-bold">
						<?php esc_html_e( 'End Date:', 'school-management' ); ?>
					</label>
					<br>
					<input type="text" name="end_date" class="wlsm-font-control" id="wlsm_leave_end_date" placeholder="<?php esc_attr_e( 'End Date', 'school-management' ); ?>">
				</div>
			</div>

			<div class="wlsm-form-group">
				<label for="wlsm_description" class="wlsm-form-label wlsm-font-bold">
					<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'Reason', 'school-management' ); ?>:
				</label>
				<br>
				<textarea required name="description" class="wlsm-font-control" id="wlsm_description" placeholder="<?php esc_attr_e( 'Enter reason', 'school-management' ); ?>" cols="30" rows="4"></textarea>
			</div>

			<div class="wlsm-border-top wlsm-pt-2 wlsm-mt-1">
				<button data-confirm="<?php esc_attr_e( 'Confirm! Are you sure to submit the leave request?', 'school-management' ); ?>" class="button wlsm-btn btn btn-primary" type="submit" id="wlsm-submit-student-leave-request-btn">
					<?php esc_html_e( 'Submit Leave Request', 'school-management' ); ?>
				</button>
			</div>

		</form>
	</div>

	<div class="wlsm-st-main-title wlsm-mt-3">
		<span>
		<?php esc_html_e( 'Recent Leave Requests', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-leaves-section">
		<?php
		if ( count( $leaves ) ) {
		?>
		<!-- Student leave requests. -->
		<div class="wlsm-table-section">
			<div class="table-responsive w-100 wlsm-w-100">
				<table class="table table-bordered wlsm-student-payment-history-table wlsm-w-100">
					<thead>
						<tr class="bg-primary text-white">
							<th><?php esc_html_e( 'Reason', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Leave Date', 'school-management' ); ?></th>
							<th class="text-nowrap"><?php esc_html_e( 'Status', 'school-management' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $leaves as $row ) {
						?>
						<tr>
							<td>
								<?php echo esc_html( WLSM_Config::limit_string( WLSM_M_Staff_Class::get_name_text( $row->description ) ) ); ?>
							</td>
							<td>
								<?php
								if ( $row->end_date ) {
									echo sprintf(
										wp_kses(
											/* translators: 1: leave start date, 2: leave end date */
											__( '<span class="wlsm-font-bold">%1$s</span> to <br><span class="wlsm-font-bold">%2$s</span>', 'school-management' ),
											array( 'span' => array( 'class' => array() ), 'br' => array() )
										),
										esc_html( WLSM_Config::get_date_text( $row->start_date ) ),
										esc_html( WLSM_Config::get_date_text( $row->end_date ) )
									);
								} else {
									echo '<span class="wlsm-font-bold">' . esc_html( WLSM_Config::get_date_text( $row->start_date ) ) . '</span>';
								}
								?>
							</td>
							<td>
								<?php echo WLSM_M_Staff_Class::get_leave_approval_text( $row->is_approved, true ); ?>
							</td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="wlsm-text-right wlsm-font-medium wlsm-font-bold wlsm-mt-2">
		<?php
		echo paginate_links(
			array(
				'base'      => add_query_arg( 'leaves_page', '%#%' ),
				'format'    => '',
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'total'     => ceil( $leaves_total / $leaves_per_page ),
				'current'   => $leaves_page,
			)
		);
		?>
		</div>
		<?php
		} else {
		?>
		<div>
			<span class="wlsm-font-medium wlsm-font-bold">
				<?php esc_html_e( "You haven't made any leave request yet.", 'school-management' ); ?>
			</span>
		</div>
		<?php
		}
		?>
	</div>
</div>
