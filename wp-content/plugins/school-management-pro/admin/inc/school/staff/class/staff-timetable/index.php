<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_Class::get_timetable_page_url();
?>

<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-calendar-alt"></i>
				<?php esc_html_e( 'Staff Timetable', 'school-management' ); ?>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-staff-timetable-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Section', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'subjects', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Room', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Time', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Day', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
