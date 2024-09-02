<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

$school_id = NULL;
if ( isset( $attr['school_id'] ) ) {
	$school_id = absint( $attr['school_id'] );

	$school = WLSM_M_School::get_active_school( $school_id );
	if ( ! $school ) {
		$invalid_message = esc_html__( 'School not found.', 'school-management' );
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/partials/invalid.php';
	}

	if ( isset( $attr['exam_id'] ) ) {
		$school_id = absint( $attr['school_id'] );
		$exam_id   = absint( $attr['exam_id'] );
		$exam      = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );

		if ( ! $exam ) {
			$invalid_message = esc_html__( 'Exam not found.', 'school-management' );
			return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/partials/invalid.php';
		}
	}

} else {
	$school  = NULL;
	$schools = WLSM_M_School::get_active_schools();
}

$exams = WLSM_M_Staff_Examination::get_school_published_exams_result( $school_id );
$sessions         = WLSM_M_Session::fetch_sessions();
$nonce_action = 'get-exam-result';

// get qr code result from url
if (isset($_GET['exam_roll_number'])) {
	// Checks if admit card exists for exam roll number.
	$exam_roll_number = sanitize_text_field( $_GET['exam_roll_number'] );
	$exam_id = $_GET['id'];
	global $wpdb;
	$admit_card = $wpdb->get_row( $wpdb->prepare( 'SELECT ac.ID, ac.exam_id, ac.roll_number, ac.student_record_id as student_id, sr.name, sr.phone, sr.enrollment_number, sr.photo_id, sr.admission_number, sr.father_name, sr.email, c.label as class_label, se.label as section_label, ss.label as session_label FROM ' . WLSM_ADMIT_CARDS . ' as ac
	JOIN ' . WLSM_EXAMS . ' as ex ON ex.ID = ac.exam_id
	JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = ac.student_record_id
	JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
	JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
	JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
	JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
	WHERE ex.school_id = %d AND ac.exam_id = %d AND ac.roll_number = %s AND ex.results_published = 1 AND ex.is_active = 1', $school_id, $exam_id, $exam_roll_number ) );

	$admit_card_id = $admit_card->ID;

	$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $admit_card->exam_id );

	$exam_id     = $exam->ID;
	$exam_title  = $exam->exam_title;
	$exam_center = $exam->exam_center;
	$start_date  = $exam->start_date;
	$end_date    = $exam->end_date;
	$show_rank   = $exam->show_rank;
	$show_remark = $exam->show_remark;
	$show_eremark = $exam->show_eremark;
	$psychomotor_enable = $exam->psychomotor_analysis;

	$exam_papers  = WLSM_M_Staff_Examination::get_exam_papers_by_admit_card( $school_id, $admit_card_id );
	$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card( $school_id, $admit_card_id );

	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/exam_results.php';
}
//  end qr code result from url
?>
<br>
<div class="wlsm">
	<div id="wlsm-get-exam-result-section">
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-get-exam-result-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-p-get-exam-result">
			<?php
			if ( ! $school ) {
			?>
			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label for="wlsm_school_exams_result" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'School', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<select name="school_id" class="wlsm-form-control wlsm_school_exams_result" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-school-exams' ) ); ?>" id="wlsm_school_exams_result" data-live-search="true">
						<option value=""><?php esc_html_e( 'Select School', 'school-management' ); ?></option>
						<?php foreach ( $schools as $value ) { ?>
						<option value="<?php echo esc_attr( $value->ID ); ?>">
							<?php echo esc_html( WLSM_M_School::get_label_text( $value->label ) ); ?>
						</option>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php
			} else {
			?>
			<input type="hidden" name="school_id" value="<?php echo esc_attr( $school_id ); ?>" id="wlsm_school_exams_result">
			<?php
			}
			if ( ! isset( $exam ) ) {
			?>
			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label for="wlsm_school_exam" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'Exam', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<select name="exam_id" class="wlsm-form-control" id="wlsm_school_exam">
						<option value=""><?php esc_html_e( 'Select Exam', 'school-management' ); ?></option>
						<?php foreach ( $exams as $exam ) { ?>
						<option value="<?php echo esc_attr( $exam->ID ); ?>">
							<?php echo esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam->exam_title ) ); ?>
						</option>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php
			} else {
			?>
			<input type="hidden" name="exam_id" value="<?php echo esc_attr( $exam->ID ); ?>" id="wlsm_school_exam">

			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label class="wlsm-form-label wlsm-font-bold">
						<?php esc_html_e( 'Exam', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<span class="wlsm-font-normal">
					<?php
					printf(
						wp_kses(
							/* translators: 1: exam title, 2: start date, 3: end date */
							__( '<span class="text-dark">%1$s (%2$s - %3$s)</span>', 'school-management' ),
							array( 'span' => array( 'class' => array() ) )
						),
						esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam->exam_title ) ),
						esc_html( WLSM_Config::get_date_text( $exam->start_date ) ),
						esc_html( WLSM_Config::get_date_text( $exam->end_date ) )
					);
					?>
					</span>
				</div>
			</div>
			<?php
			}
			?>
			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label for="wlsm_school_session" class="wlsm-form-label wlsm-font-bold">
						<?php esc_html_e( 'Session', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<select name="session_id" class="wlsm-form-control" id="wlsm_school_session">
						<option value=""><?php esc_html_e( 'Select session', 'school-management' ); ?></option>

						<?php foreach ( $sessions as $session ) { ?>
							<option value="<?php echo esc_attr( $session->ID ); ?>">
								<?php echo esc_html( $session->label ); ?>
							</option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="wlsm-form-group wlsm-row">
				<div class="wlsm-col-4">
					<label for="wlsm_exam_roll_number" class="wlsm-form-label wlsm-font-bold">
						<?php esc_html_e( 'Exam Roll Number', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<input type="text" name="exam_roll_number" class="wlsm-form-control" id="wlsm_exam_roll_number" placeholder="<?php esc_attr_e( 'Enter exam roll number', 'school-management' ); ?>">
				</div>
			</div>

			<div class="wlsm-border-top wlsm-pt-2 wlsm-mt-1">
				<button class="button wlsm-btn btn btn-primary" type="submit" id="wlsm-get-exam-result-btn">
					<?php esc_html_e( 'Search Result', 'school-management' ); ?>
				</button>
			</div>

		</form>

		<div class="wlsm-exam-result"></div>

	</div>
</div>
<?php
return ob_get_clean();
