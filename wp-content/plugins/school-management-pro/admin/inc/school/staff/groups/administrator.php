<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/global.php';

$page_url_admins    = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ADMINS );
$page_url_roles     = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ROLES );
$page_url_employees = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EMPLOYEES );
?>
<div class="wlsm container-fluid">
	<?php
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/header.php';
	?>

	<div class="row">
		<div class="col-md-12">
			<div class="text-center wlsm-section-heading-block">
				<span class="wlsm-section-heading">
					<i class="fas fa-user-shield"></i>
					<?php esc_html_e( 'Administrator', 'school-management' ); ?>
				</span>
			</div>
		</div>
	</div>

	<div class="row mt-3 mb-3">
		<?php if ( WLSM_M_Role::check_permission( array( 'manage_admins' ), $current_school['permissions'] ) ) { ?>
		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Admins', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_admins ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Admins', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_admins . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Admin', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( WLSM_M_Role::check_permission( array( 'manage_roles' ), $current_school['permissions'] ) ) { ?>
		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Roles', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_roles ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Roles', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_roles . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Role', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( WLSM_M_Role::check_permission( array( 'manage_employees' ), $current_school['permissions'] ) ) { ?>
		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Staff', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_employees ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Staff', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_employees . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Staff', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_employees . '&action=save_bulk' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Bulk Import Staff', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
