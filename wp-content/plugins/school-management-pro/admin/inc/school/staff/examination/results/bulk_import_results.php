<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

$page_url         = WLSM_M_Staff_Examination::get_exams_page_url();
$results_page_url = WLSM_M_Staff_Examination::get_exams_results_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$exam = NULL;

if ( isset( $_GET['exam_id'] ) && ! empty( $_GET['exam_id'] ) ) {
	$exam_id = absint( $_GET['exam_id'] );

	$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );
}

if ( ! $exam ) {
	die;
}

$exam_id     = $exam->ID;
$exam_title  = $exam->exam_title;
$exam_center = $exam->exam_center;
$start_date  = $exam->start_date;
$end_date    = $exam->end_date;

$nonce_action = 'bulk-import-results-' . $exam_id;
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
				<a href="<?php echo esc_url( $results_page_url . '&action=results&exam_id=' . $exam_id ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-table"></i>&nbsp;
					<?php esc_html_e( 'View Exam Results', 'school-management' ); ?>
				</a>
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-bulk-import-exam-results-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-bulk-import-exam-results">
			<input type="hidden" name="exam_id" id="wlsm_exam" value="<?php echo esc_attr( $exam_id ); ?>">

			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-6">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Import Exam Results From CSV File', 'school-management' ); ?>
							<br>
							<small class="text-dark">
								<em><?php esc_html_e( 'Click "Export Results CSV", fill exam results of students in the file, then choose the CSV file and click "Bulk Import".', 'school-management' ); ?></em>
							</small>
						</div>

						<div class="form-group">
							<label class="wlsm-font-bold">
								<?php esc_html_e( 'Export Results CSV File', 'school-management' ); ?>
							</label>
							<br>
							<button type="button" class="btn btn-sm btn-outline-info" id="wlsm-exam-results-csv-export-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'exam-results-csv-export' ) ); ?>">
								<i class="fas fa-file-export"></i>
								<?php esc_html_e( 'Export Results CSV', 'school-management' ); ?>
							</button>
						</div>
					</div>

					<div class="col-md-6">
						<div class="wlsm-form-sub-heading wlsm-font-bold"><?php esc_html_e( 'Exam Details', 'school-management' ); ?></div>

						<ul class="wlsm-exam-details">
							<li>
								<span class="wlsm-font-bold"><?php esc_html_e( 'Exam Title', 'school-management' ); ?>:</span>
								<span><?php echo esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ); ?></span>
							</li>
							<li>
								<span class="wlsm-font-bold"><?php esc_html_e( 'Exam Center', 'school-management' ); ?>:</span>
								<span><?php echo esc_html( WLSM_M_Staff_Examination::get_exam_center_text( $exam_center ) ); ?></span>
							</li>
							<li>
								<span class="wlsm-font-bold"><?php esc_html_e( 'Start Date', 'school-management' ); ?>:</span>
								<span><?php echo esc_html( WLSM_Config::get_date_text( $start_date ) ); ?></span>
							</li>
							<li>
								<span class="wlsm-font-bold"><?php esc_html_e( 'End Date', 'school-management' ); ?>:</span>
								<span><?php echo esc_html( WLSM_Config::get_date_text( $end_date ) ); ?></span>
							</li>
						</ul>
					</div>
				</div>

				<div class="form-row mt-2 justify-content-md-center">
					<div class="form-group col-md-4">
						<label for="wlsm_results_csv" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'CSV File', 'school-management' ); ?>:
						</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="wlsm_results_csv" name="csv">
							<label class="custom-file-label" for="wlsm_results_csv">
								<?php esc_html_e( 'Choose CSV File', 'school-management' ); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-sm btn-primary" id="wlsm-bulk-import-exam-results-btn" data-message-title="<?php esc_attr_e( 'Confirm Import!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'Are you sure to import these exam results?', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Import', 'school-management' ); ?>">
						<i class="fas fa-file-import"></i>
						<?php esc_html_e( 'Bulk Import Results', 'school-management' ); ?>
					</button>
				</div>
			</div>

		</form>

	</div>
</div>
