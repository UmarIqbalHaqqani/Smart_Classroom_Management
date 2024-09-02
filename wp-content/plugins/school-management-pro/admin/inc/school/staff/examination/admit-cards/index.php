<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';
$page_url_exam_admit_cards_bulk_print = admin_url( 'admin.php?page=' . WLSM_MENU_STAFF_EXAM_ADMIT_CARDS_BULK_PRINT );
$page_url = WLSM_M_Staff_Examination::get_exams_page_url();
?>

<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-id-card"></i>
				<?php esc_html_e( 'Manage Exam Admit Cards', 'school-management' ); ?>
			</span>
			<span class="float-right">
			<a href="<?php echo esc_url( $page_url_exam_admit_cards_bulk_print ); ?>" class="btn btn-sm btn-outline-light">
						<?php esc_html_e( 'Print Admit Cards In Bulk', 'school-management' ); ?>
					</a>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-exams-admit-cards-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Exam Title', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Exam Center', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Start Date', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'End Date', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Generate Admit Cards', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'View Admit Cards', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
