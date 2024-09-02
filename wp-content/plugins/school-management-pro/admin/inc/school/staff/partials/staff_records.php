<?php
defined( 'ABSPATH' ) || die();

/* translators: %s: role name */
$add_new_label = sprintf( esc_html__( 'Add New %s', 'school-management' ), esc_html( WLSM_M_Role::get_role_text( $role ) ) );
?>
<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-user-shield"></i>
				<?php echo esc_html( $table_heading ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . '&action=save' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-plus-square"></i>
					<?php echo esc_html( $add_new_label ); ?>
				</a>&nbsp;&nbsp;
				<a href="<?php echo esc_url( $page_url . '&action=save_bulk' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-plus-square"></i>&nbsp;
					<?php echo esc_html( 'Bulk Import Staff' ); ?>
				</a>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-staff-table" data-role="<?php echo esc_attr( $role ); ?>">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Name', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Phone', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Email', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Salary', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Designation', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Role', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Login Email', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Login Username', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Joining Date', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Status', 'school-management' ); ?></th>
						<?php if ( WLSM_M_Role::get_employee_key() === $role ) { ?>
						<th scope="col"><?php esc_html_e( 'Attendance Report', 'school-management' ); ?></th>
						<?php } ?>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
