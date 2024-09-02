<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

WLSM_Helper::enqueue_datatable_assets();

$page_url         = WLSM_M_Staff_Examination::get_exams_page_url();
$results_page_url = WLSM_M_Staff_Examination::get_exams_results_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );

if ( ! $exam ) {
	die;
}

$exam_title = $exam->exam_title;
$start_date = $exam->start_date;
$end_date   = $exam->end_date;
?>

<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					printf(
						wp_kses(
							/* translators: 1: exam title, 2: start date, 3: end date */
							__( 'Exam: %1$s (%2$s - %3$s)', 'school-management' ),
							array(
								'span' => array( 'class' => array() )
							)
						),
						esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
						esc_html( WLSM_Config::get_date_text( $start_date ) ),
						esc_html( WLSM_Config::get_date_text( $end_date ) )
					);
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . '&action=save&id=' . $exam_id ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-edit"></i>&nbsp;
					<?php esc_html_e( 'Edit Exam', 'school-management' ); ?>
				</a>&nbsp;
				<a href="<?php echo esc_url( $results_page_url . '&action=bulk_import_results&exam_id=' . $exam_id ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-upload"></i>&nbsp;
					<?php echo esc_html( 'Bulk Import Results', 'school-management' ); ?>
				</a>&nbsp;
				<a href="<?php echo esc_url( $results_page_url . '&action=save_results&exam_id=' . $exam_id ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-table"></i>&nbsp;
					<?php esc_html_e( 'Add Exam Results', 'school-management' ); ?>
				</a>
			</span>
		</div>

		<div class="row">
			<div class="col-md-12">
				<span class="wlsm-font-bold">
					<?php esc_html_e( 'Exam Results', 'school-management' ); ?>:
				</span>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="wlsm-table-block">
					<table class="table table-bordered wlsm-exam-results-table" id="wlsm-exam-results-table" data-exam="<?php echo esc_attr( $exam_id ); ?>">
						<thead>
							<tr class="text-white bg-primary">
								<th scope="col" class="text-nowrap"><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Exam Roll Number', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Section', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Percentage', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Rank', 'school-management' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
								<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
