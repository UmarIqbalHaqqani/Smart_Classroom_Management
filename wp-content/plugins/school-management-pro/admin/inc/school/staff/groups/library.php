<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/global.php';

$page_url_books         = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_BOOKS );
$page_url_books_issued  = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_BOOKS_ISSUED );
$page_url_library_cards = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_LIBRARY_CARDS );
?>
<div class="wlsm container-fluid">
	<?php
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/header.php';
	?>

	<div class="row">
		<div class="col-md-12">
			<div class="text-center wlsm-section-heading-block">
				<span class="wlsm-section-heading">
					<i class="fas fa-book"></i>
					<?php esc_html_e( 'Library', 'school-management' ); ?>
				</span>
			</div>
		</div>
	</div>

	<div class="row mt-3 mb-3">
		<?php if ( WLSM_M_Role::check_permission( array( 'manage_library' ), $current_school['permissions'] ) ) { ?>
		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Books', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_books ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Books', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_books . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Books', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_books . '&action=save_bulk' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Import Books', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Issue Books', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_books ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'Issue Books', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_books_issued ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'View Books Issued', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Library Cards', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_library_cards ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'Issue Library Cards', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_library_cards ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'View Library Cards', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
