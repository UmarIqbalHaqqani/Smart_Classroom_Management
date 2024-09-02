<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/global.php';

$page_url_vehicles = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_HOSTELS );
$page_url_rooms    = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ROOMS );

global $wpdb;
$hostels_count       = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(id) FROM ' . WLSM_HOSTELS . ' WHERE school_id = %d', $current_school['id'] ) );
$rooms_count         = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(id) FROM ' . WLSM_ROOMS . '' ) );
$total_boys_hostels  = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(id) FROM ' . WLSM_HOSTELS . ' WHERE school_id = %d AND hostel_type = %d', $current_school['id'], 'boys' ) );
$total_girls_hostels = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(id) FROM ' . WLSM_HOSTELS . ' WHERE school_id = %d AND hostel_type = %d', $current_school['id'], 'girls' ) );

?>
<div class="wlsm container-fluid">
	<?php
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/header.php';
	?>

	<div class="row">
		<div class="col-md-12">
			<div class="text-center wlsm-section-heading-block">
				<span class="wlsm-section-heading">
					<i class="fas fa-home"></i>
					<?php esc_html_e( 'Hostels', 'school-management' ); ?>
				</span>
			</div>
		</div>
	</div>

	<div class="row mt-3 mb-3">
		<?php if ( WLSM_M_Role::check_permission( array( 'manage_transport' ), $current_school['permissions'] ) ) { ?>
			<div class="col-md-4 col-sm-6">
				<div class="wlsm-group">
					<span class="wlsm-group-title"><?php esc_html_e( 'Hostels ', 'school-management' ); ?></span>
					<div class="wlsm-group-actions">
						<a href="<?php echo esc_url( $page_url_vehicles ); ?>" class="btn btn-sm btn-primary">
							<?php esc_html_e( 'View Hostels', 'school-management' ); ?>
						</a>
						<a href="<?php echo esc_url( $page_url_vehicles . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
							<?php esc_html_e( 'Add New Hostel', 'school-management' ); ?>
						</a>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-sm-6">
				<div class="wlsm-group">
					<span class="wlsm-group-title"><?php esc_html_e( 'Rooms ', 'school-management' ); ?></span>
					<div class="wlsm-group-actions">
						<a href="<?php echo esc_url( $page_url_rooms ); ?>" class="btn btn-sm btn-primary">
							<?php esc_html_e( 'View Rooms', 'school-management' ); ?>
						</a>
						<a href="<?php echo esc_url( $page_url_rooms . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
							<?php esc_html_e( 'Add New Room', 'school-management' ); ?>
						</a>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>

	<div class="row mt-1 wlsm-stats-blocks">
		<div class="col-md-4 col-lg-3">
			<div class="wlsm-stats-block">
				<i class="fas fa-layer-group wlsm-stats-icon"></i>
				<div class="wlsm-stats-counter"><?php echo esc_html( $hostels_count ); ?></div>
				<div class="wlsm-stats-label"><?php esc_html_e( 'Total Hostels', 'school-management' ); ?></div>
			</div>
		</div>
		<div class="col-md-4 col-lg-3">
			<div class="wlsm-stats-block">
				<i class="fas fa-layer-group wlsm-stats-icon"></i>
				<div class="wlsm-stats-counter"><?php echo esc_html( $rooms_count ); ?></div>
				<div class="wlsm-stats-label"><?php esc_html_e( 'Total Rooms', 'school-management' ); ?></div>
			</div>
		</div>
		<div class="col-md-4 col-lg-3">
			<div class="wlsm-stats-block">
				<i class="fas fa-layer-group wlsm-stats-icon"></i>
				<div class="wlsm-stats-counter"><?php echo esc_html( $total_boys_hostels ); ?></div>
				<div class="wlsm-stats-label"><?php esc_html_e( 'Total Boys Hostel', 'school-management' ); ?></div>
			</div>
		</div>
		<div class="col-md-4 col-lg-3">
			<div class="wlsm-stats-block">
				<i class="fas fa-layer-group wlsm-stats-icon"></i>
				<div class="wlsm-stats-counter"><?php echo esc_html( $total_girls_hostels ); ?></div>
				<div class="wlsm-stats-label"><?php esc_html_e( 'Total Girls Hostel', 'school-management' ); ?></div>
			</div>
		</div>
	</div>
</div>
