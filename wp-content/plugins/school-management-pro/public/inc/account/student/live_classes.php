<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/class-bigbluebutton-api.php';

$class_school_id = $student->class_school_id;
$class_section_id= $student->section_id;

$meetings_per_page = WLSM_M::meetings_per_page();

$meetings_query = WLSM_M::meetings_query();

$meetings_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$meetings_query}) AS combined_table", $school_id, $class_school_id ) );

$meetings_page = isset( $_GET['meetings_page'] ) ? absint( $_GET['meetings_page'] ) : 1;

$meetings_page_offset = ( $meetings_page * $meetings_per_page ) - $meetings_per_page;

$meetings = $wpdb->get_results( $wpdb->prepare( $meetings_query . ' ORDER BY mt.start_at DESC, mt.ID DESC LIMIT %d, %d', $school_id, $class_school_id, $meetings_page_offset, $meetings_per_page ) );
?>
<div class="wlsm-content-area wlsm-section-meetings wlsm-student-meetings">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Live Classes', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-meetings-section">
		<?php
		if ( count( $meetings ) ) {
		?>
		<div class="wlsm-table-section">
			<div class="table-responsive w-100 wlsm-w-100">
				<table class="table table-bordered wlsm-student-meetings-table wlsm-w-100">
					<thead>
						<tr class="bg-primary text-white">
							<th><?php esc_html_e( 'Topic', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Duration (minutes)', 'school-management' ); ?></th>
							<th class="text-nowrap"><?php esc_html_e( 'Start Date / Time', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Type', 'school-management' ); ?></th>
							<th class="text-nowrap"><?php esc_html_e( 'Join', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Password', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Subject', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Teacher', 'school-management' ); ?></th>
							<th class="text-nowrap"><?php esc_html_e( 'Rate', 'school-management' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $meetings as $row ) {
						?>
						<?php if ($row->section_id === $class_section_id || $row->section_id === null): ?>
						
						<tr>
							<td>
								<?php echo esc_html( $row->topic ); ?>
							</td>
							<td>
								<?php echo esc_html( $row->duration ); ?>
							</td>
							<td class="text-nowrap">
								<?php echo esc_html( WLSM_Config::get_at_text( $row->start_at ) ); ?>
							</td>
							<td>
								<?php echo esc_html( WLSM_Helper::get_meeting_type( $row->type ) ); ?>
							</td>
							<td class="text-nowrap">
								<?php
								if ( $row->join_url ) {
								?>
								<a target="_blank" class="button btn btn-sm btn-outline-success" href="<?php echo esc_url( $row->join_url ); ?>"><?php esc_html_e( 'Join', 'school-management' ); ?></a>
								<?php
								} else {
									if ( is_user_logged_in() ) {
										$username = wp_get_current_user()->display_name;
									}
									$join_url = SM_Bigbluebutton_Api::get_join_meeting_url($username, 123, $row->password, $row->recordable, $row->meeting_id, $page_url=null, 0);
								?>
								<a target="_blank" class="button btn btn-sm btn-outline-success" href="<?php echo esc_url( $join_url ); ?>"><?php esc_html_e( 'Join', 'school-management' ); ?></a>
								<?php
								}
								?>
							</td>
							<td>
								<?php echo esc_html( $row->password ? $row->password : '-' ); ?>
							</td>
							<td class="text-nowrap">
								<?php echo esc_html( WLSM_M_Staff_Class::get_subject_label_text( $row->subject_name ) ); ?>
							</td>
							<td>
								<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ); ?>
							</td>
							<td>
								<!-- <a class="wlsm-st-print-staff-ratting" data-staff-ratting="<?php echo esc_attr( $row->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'st-print-staff-ratting-' ) ); ?>" href="#" data-message-title="<?php esc_attr_e( 'Ratting', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>"><?php echo esc_html( 'Rate' ); ?></a> -->

								<a href="#" class="wlsm-st-print-staff-ratting" data-class= "<?php echo esc_attr($row->ID); ?> " data-nonce="<?php echo ( wp_create_nonce( 'staff-ratting' ) );  ?> " > <?php echo esc_html__( 'Ratting', 'school-management' ) ?> </a>
							</td>
						</tr>
						<?php endif ?>
						<?php
						}
						?>
					</tbody>
				</table>
				<div class="wlsm-student-ratting-form"></div>
			</div>
		</div>
		<div class="wlsm-text-right wlsm-font-medium wlsm-font-bold wlsm-mt-2">
		<?php
		echo paginate_links(
			array(
				'base'      => add_query_arg( 'meetings_page', '%#%' ),
				'format'    => '',
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'total'     => ceil( $meetings_total / $meetings_per_page ),
				'current'   => $meetings_page,
			)
		);
		?>
		</div>
		<?php
		} else {
		?>
		<div>
			<span class="wlsm-font-medium wlsm-font-bold">
				<?php esc_html_e( 'There is no live class.', 'school-management' ); ?>
			</span>
		</div>
		<?php
		}
		?>
	</div>
</div>
