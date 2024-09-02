<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/global.php';

$page_url_classes         = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_CLASSES );
$page_url_subjects        = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_SUBJECTS );
$page_url_attendance      = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_ATTENDANCE );
$page_url_study_materials = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_STUDY_MATERIALS );
$page_url_homework        = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_HOMEWORK );
$page_url_notices         = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_NOTICES );
$page_url_events          = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EVENTS );
?>
<div class="wlsm container-fluid">
	<?php
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/header.php';
	?>

	<div class="row">
		<div class="col-md-12">
			<div class="text-center wlsm-section-heading-block">
				<span class="wlsm-section-heading">
					<i class="fas fa-graduation-cap"></i>
					<?php esc_html_e( 'Academic', 'school-management' ); ?>
				</span>
			</div>
		</div>
	</div>

	<div class="row mt-3 mb-3">
		<?php if ( WLSM_M_Role::check_permission( array( 'manage_classes' ), $current_school['permissions'] ) ) { ?>
		<div class="col-sm-6 col-md-4">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Class Sections', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_classes ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'Add Sections / Manage', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( WLSM_M_Role::check_permission( array( 'manage_subjects' ), $current_school['permissions'] ) ) { ?>
		<div class="col-sm-6 col-md-4">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Subjects', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_subjects ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Subjects', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_subjects ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Subject', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( WLSM_M_Role::check_permission( array( 'manage_attendance' ), $current_school['permissions'] ) ) { ?>
		<div class="col-sm-6 col-md-4">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Attendance', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_attendance ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Attendance', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_attendance . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Take Attendance', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( WLSM_M_Role::check_permission( array( 'manage_study_materials' ), $current_school['permissions'] ) ) { ?>
		<div class="col-sm-6 col-md-4">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Study Materials', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_study_materials ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'Study Materials', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_study_materials . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Study Material', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( WLSM_M_Role::check_permission( array( 'manage_homework' ), $current_school['permissions'] ) ) { ?>
		<div class="col-sm-6 col-md-4">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Home Work', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_homework ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'Homework', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_homework . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Assign Homework', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( WLSM_M_Role::check_permission( array( 'manage_notices' ), $current_school['permissions'] ) ) { ?>
		<div class="col-sm-6 col-md-4">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Noticeboard', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_notices ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'Noticeboard', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_notices . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Notice', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( WLSM_M_Role::check_permission( array( 'manage_events' ), $current_school['permissions'] ) ) { ?>
		<div class="col-sm-6 col-md-4">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Events', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_notices ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Events', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_events . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Event', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
