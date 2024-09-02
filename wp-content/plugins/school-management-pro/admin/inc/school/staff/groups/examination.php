<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/global.php';

$page_url_exams                  = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EXAMS );
$page_url_exam_admit_cards       = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EXAM_ADMIT_CARDS );
$page_url_exam_results           = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EXAM_RESULTS );
$page_url_results_assessment     = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EXAM_ASSESSMENT );
?>
<div class="wlsm container-fluid">
	<?php
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/header.php';
	?>

	<div class="row">
		<div class="col-md-12">
			<div class="text-center wlsm-section-heading-block">
				<span class="wlsm-section-heading">
					<i class="fas fa-clock"></i>
					<?php esc_html_e( 'Examination', 'school-management' ); ?>
				</span>
			</div>
		</div>
	</div>

	<div class="row mt-3 mb-3">
		<?php if ( WLSM_M_Role::check_permission( array( 'manage_exams' ), $current_school['permissions'] ) ) { ?>
		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Manage Exams', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_exams ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'View Exams', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url_exams . '&action=save' ); ?>" class="btn btn-sm btn-outline-primary">
						<?php esc_html_e( 'Add New Exam', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Admit Cards', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_exam_admit_cards ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'Manage Admit Cards', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Exam Results', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_exam_results ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'Manage Exam Results', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6">
			<div class="wlsm-group">
				<span class="wlsm-group-title"><?php esc_html_e( 'Results Assessment', 'school-management' ); ?></span>
				<div class="wlsm-group-actions">
					<a href="<?php echo esc_url( $page_url_results_assessment ); ?>" class="btn btn-sm btn-primary">
						<?php esc_html_e( 'Exam Results Assessment', 'school-management' ); ?>
					</a>
				</div>
			</div>
		</div>

		<?php } ?>
	</div>
</div>
