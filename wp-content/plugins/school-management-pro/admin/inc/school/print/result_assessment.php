<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}

$student_id = $student->ID;

$admit_cards = WLSM_M_Staff_Examination::get_student_exam_results_assessment( $school_id, $student_id );
?>

<!-- Print result assessment. -->
<div class="wlsm-container d-flex mb-2">
	<div class="col-md-12 wlsm-text-center">
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?>" id="wlsm-print-result-assessment-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-result-assessment.css' ); ?>"]' data-title="<?php
		printf(
			/* translators: 1: student name, 2: enrollment number */
			esc_attr__( 'Overall Result - %1$s (%2$s)', 'school-management' ),
			esc_attr( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ),
			esc_attr( $student->enrollment_number ) );
		?>"><?php esc_html_e( 'Print Overall Result', 'school-management' ); ?>
		</button>
	</div>
</div>

<!-- Print exam results assessment section. -->
<div class="wlsm-container wlsm" id="wlsm-print-results-assessment">
	<div class="wlsm-print-results-assessment-container">

		<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/school_header.php'; ?>

		<div class="wlsm-heading wlsm-results-assessment-heading h5 wlsm-text-center">
			<span><?php esc_html_e( 'EXAM RESULTS ASSESSMENT', 'school-management' ); ?></span>
		</div>

		<div class="row">
			<div class="col-md-12">
				<ul>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Student Name', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( $student->enrollment_number ); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Session', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Session::get_label_text( $session_label ) ); ?></span>
					</li>
					<li>
						<span class="pr-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Class', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Class::get_label_text( $student->class_label ) ); ?></span>
						</span>
						<span class="pl-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Section', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Class::get_label_text( $student->section_label ) ); ?></span>
						</span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Roll Number', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $student->roll_number ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Phone', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $student->phone ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $student->father_name ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Father\'s Phone', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $student->father_phone ) ); ?></span>
					</li>
				</ul>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive w-100">
					<table class="table table-bordered wlsm-results-assessment-table">
						<?php require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/results_assessment.php'; ?>
					</table>
				</div>
			</div>
		</div>

	</div>
</div>
