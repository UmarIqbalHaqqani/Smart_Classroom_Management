<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/global.php';

$page_url_vehicles = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_VEHICLES );
$page_url_routes   = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ROUTES );
?>
<div class="wlsm container-fluid">
	<?php
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/header.php';
	?>

	<div class="row">
		<div class="col-md-12">
			<div class="text-center wlsm-section-heading-block">
				<span class="wlsm-section-heading">
					<i class="fas fa-bus-alt"></i>
					<?php esc_html_e( 'Transport', 'school-management' ); ?>
				</span>
			</div>
		</div>
	</div>

	<div class="row mt-3 mb-3">
		<?php if ( WLSM_M_Role::check_permission( array( 'manage_transport' ), $current_school['permissions'] ) ) { ?>
		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Transport Vehicles', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_vehicles ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Vehicles', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_vehicles . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Vehicle', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Transport Routes', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_routes ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Routes', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_routes . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Route', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
