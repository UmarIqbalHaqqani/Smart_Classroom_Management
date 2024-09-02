<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}

$student_id = $student->ID;
$photo_id   = $student->photo_id;

$exam_groups = WLSM_M_Staff_Examination::get_class_school_exam_groups($school_id, $class_school_id);
$attendance = WLSM_M_Staff_General::get_student_attendance_stats($student->ID);
?>

<?php if ( $bulk_print !== true ) { ?>
<!-- Print result subject-wise. -->
<div class="wlsm-container d-flex mb-2">
	<div class="col-md-12 wlsm-text-center">
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?>" id="wlsm-print-result-subject-wise-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-result-subject-wise.css' ); ?>"]' data-title="<?php
		printf(
			/* translators: 1: student name, 2: enrollment number */
			esc_attr__( 'Subject-wise Result - %1$s (%2$s)', 'school-management' ),
			esc_attr( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ),
			esc_attr( $student->enrollment_number ) );
		?>"><?php esc_html_e( 'Print Subject-wise Result', 'school-management' ); ?>
		</button>
	</div>
</div>
<?php  } ?>

<!-- Print exam results subject-wise section. -->
<div class="wlsm-container wlsm" id="wlsm-print-results-subject-wise">
	<div class="wlsm-print-results-subject-wise-container">

		<?php require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/school_header.php'; ?>

		
		<div class="wlsm-heading wlsm-results-subject-wise-heading h5 wlsm-text-center">
			<span>
			<?php esc_html_e( 'Academic Report ', 'school-management' ); ?><?php 
			if ($academic_report) {
				echo ' - '. esc_html($academic_report->label);
			}
			 ?></span> 
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="wlsm-student-side-lists">
					<ul>
						<li>
							<span class="wlsm-font-bold"><?php esc_html_e( 'Student Name', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ); ?></span>
						</li>
						<!-- <li>
							<span class="wlsm-font-bold"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( $student->enrollment_number ); ?></span>
						</li> -->
						<li>
							<span class="wlsm-font-bold"><?php esc_html_e( 'Admission Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( $student->admission_number ); ?></span>
						</li>
						<li>
							<span class="wlsm-font-bold"><?php esc_html_e( 'Session', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Session::get_label_text( $session_label ) ); ?></span>
						</li>
					</ul>
					<ul>
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
					</ul>
					<ul>
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
						<li>
							<span class="wlsm-font-bold"><?php esc_html_e( 'Attendance', 'school-management' ); ?>:</span>
							<span><?php echo esc_html($attendance['percentage_text'] ); ?></span>
						</li>
					</ul>
					
					<div class="wlsm-print-photo-box wlsm-to-right">
					<?php if ( ! empty ( $photo_id ) ) { ?>
						<img src="<?php echo esc_url( wp_get_attachment_url( $photo_id ) ); ?>" class="wlsm-print-photo">
					<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive w-100">
					<table class="table table-bordered wlsm-results-subject-wise-table wlsm-results-subject-wise-table">
						<?php require WLSM_PLUGIN_DIR_PATH . 'includes/partials/results_subject_wise.php'; ?>
					</table>
				</div>
			</div>
		</div>

	</div>
</div>
