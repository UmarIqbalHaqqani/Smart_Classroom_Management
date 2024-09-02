<?php
defined( 'ABSPATH' ) || die();

$print_button_classes = 'btn btn-sm btn-success';
?>

<!-- Print staff attendance. -->
<div class="wlsm-container d-flex mt-2 mb-2">
	<div class="col-md-12 wlsm-text-center">
		<?php
		printf(
			wp_kses(
				/* translators: 1: month of attendance, 2: year of attendance */
				__( 'Month: <span class="text-dark wlsm-font-bold">%1$s %2$s</span>', 'school-management' ),
				array( 'span' => array( 'class' => array() ) )
			),
			esc_html( $month_format ),
			esc_html( $year_format )
		);
		?>
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?> mt-2" id="wlsm-print-staff-attendance-sheet-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-attendance-sheet.css' ); ?>"]' data-title="<?php
				esc_attr_e( 'Staff Attendance Sheet', 'school-management' );
		?>"><?php esc_html_e( 'Print Staff Attendance Sheet', 'school-management' ); ?>
		</button>
	</div>
</div>

<!-- Print staff attendance section. -->
<div class="wlsm-container wlsm-form-section" id="wlsm-print-staff-attendance-sheet">
	<div class="wlsm-print-staff-attendance-sheet-container">

		<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/school_header.php'; ?>

		<div class="wlsm-font-bold">
			<span>
			<?php esc_html_e( 'Staff Attendance', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
			<?php
			printf(
				wp_kses(
					/* translators: 1: month of attendance, 2: year of attendance */
					__( 'Month: <span class="text-dark wlsm-font-bold">%1$s %2$s</span>', 'school-management' ),
					array( 'span' => array( 'class' => array() ) )
				),
				esc_html( $month_format ),
				esc_html( $year_format )
			);
			?>
			</span>
		</div>
		<div class="table-responsive w-100">
			<table class="table table-sm table-bordered wlsm-view-staff-attendance-table">
				<thead>
					<tr class="bg-primary text-white">
						<th class="text-nowrap">
							<?php esc_html_e( 'Date', 'school-management' ); ?>&nbsp;&#8594;
							<br>
							<?php esc_html_e( 'Staff', 'school-management' ); ?>&nbsp;&#8595;
						</th>
						<?php foreach ( $date_range as $date ) { ?>
						<th><?php echo esc_html( $date->format( 'd' ) ); ?></th>
						<?php } ?>
						<th class=""><?php esc_html_e( 'Total Attendance', 'school-management' ); ?></th>
						<th class=""><?php esc_html_e( 'Total Present', 'school-management' ); ?></th>
						<th class=""><?php esc_html_e( 'Total Absent', 'school-management' ); ?></th>
						<th class=""><?php esc_html_e( 'Total Holidays', 'school-management' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $admins as $row ) {
						$attendance_records = array_filter( $saved_attendance, function ( $attendance ) use ( $row ) {
							return $attendance->admin_id == $row->ID;
						});

						$attendance_by_dates = array();
						foreach ( $attendance_records as $attendance_record ) {
							$attendance_by_dates[ $attendance_record->attendance_date ] = $attendance_record->status;
						}

						$total_present = 0;
						$total_absent  = 0;
						$total_holidays  = 0;
					?>
					<tr>
						<td class="text-nowrap">
							<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ); ?>
							<br>
							<?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $row->phone ) ); ?>
						</td>
						<?php
						foreach ( $date_range as $date ) {
							$status = '';
							if ( isset( $attendance_by_dates[ $date->format('Y-m-d') ] ) ) {
								$status = $attendance_by_dates[ $date->format('Y-m-d') ];
							}
						?>
						<td>
							<?php
							if ( ! $status ) {
							}
							else if ( 'p' === $status ) {
								$total_present++;
							?>
							<span class="text-success wlsm-font-bold"><?php echo esc_html( ucwords($status) ); ?></span>
							<?php
							} else if ( 'l' === $status ) {
								$total_present++;
							?>
							<span class="text-secondary wlsm-font-bold"><?php echo esc_html( ucwords($status) ); ?></span>
							<?php
							}
							else if('a' === $status){
								$total_absent++;
							?>
							<span class="text-danger wlsm-font-bold"><?php echo esc_html( ucwords($status) ); ?></span>
							<?php
							}
							else if('h' === $status){
								$total_holidays++;
							?>
							<span class="text-warning wlsm-font-bold"><?php echo esc_html( ucwords($status) ); ?></span>
							<?php
							}
							?>
						</td>
						<?php
						}
						?>
						<td class="">
							<span class="text-dark wlsm-font-bold"><?php echo esc_html( $total_present + $total_absent ); ?></span>
						</td>
						<td class="">
							<span class="text-success wlsm-font-bold"><?php echo esc_html( $total_present ); ?></span>
						</td>
						<td class="">
							<span class="text-danger wlsm-font-bold"><?php echo esc_html( $total_absent ); ?></span>
						</td>
						<td class="">
							<span class="text-warning wlsm-font-bold"><?php echo esc_html( $total_holidays ); ?></span>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>

	</div>
</div>
