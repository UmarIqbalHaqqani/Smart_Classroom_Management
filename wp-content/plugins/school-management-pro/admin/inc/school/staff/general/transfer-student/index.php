<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_General::get_transfer_student_page_url();
?>
<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-users"></i>
				<?php esc_html_e( 'Students Transferred', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . '&action=save' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-sign-in-alt"></i>&nbsp;
					<?php echo esc_html( 'Transfer Student', 'school-management' ); ?>
				</a>
			</span>
		</div>

		<div class="mt-3 mb-2 text-center">
			<div class="wlsm-font-bold">
				<?php esc_html_e( 'Students Transferred to Other School', 'school-management' ); ?>
			</div>
		</div>
		<div class="wlsm-table-block wlsm-form-section">
			<table class="table table-hover table-bordered" id="wlsm-transferred-to-school-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Admission Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Phone', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Email', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Section', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Roll Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Father\'s Phone', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Admission Date', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Status', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Transferred to', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Transfer Date', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Note', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>

		<div class="mt-2 mb-2 text-center">
			<div class="wlsm-font-bold">
				<?php esc_html_e( 'Students Transferred to this School', 'school-management' ); ?>
			</div>
		</div>
		<div class="wlsm-table-block wlsm-form-section">
			<table class="table table-hover table-bordered" id="wlsm-transferred-from-school-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Admission Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Phone', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Email', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Section', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Roll Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Father\'s Phone', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Admission Date', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Status', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Transferred From', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Transfer Date', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Note', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
