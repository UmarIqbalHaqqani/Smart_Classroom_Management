<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$class_school_id = $student->class_school_id;

$exam_id = $id;

// Checks if exam exists.
$exam = WLSM_M_Staff_Examination::get_class_school_exam_time_table( $school_id, $class_school_id, $exam_id );

if ( ! $exam ) {
	die;
}

$exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes_label( $school_id, $exam_id );
$exam_papers  = WLSM_M_Staff_Examination::fetch_exam_papers( $school_id, $exam_id );

$exam_title = $exam->exam_title;
$start_date = $exam->start_date;
$end_date   = $exam->end_date;

$class_names = array();
foreach ( $exam_classes as $exam_class ) {
	array_push( $class_names, WLSM_M_Class::get_label_text( $exam_class->label ) );
}

$class_names = implode( ', ', $class_names );
?>
<div class="wlsm-content-area wlsm-section-exam-time-table wlsm-student-exam-time-table">
	<div class="wlsm-st-main-title">
		<span>
			<?php echo esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ); ?>
			<a href="#" class="wlsm-st-print-exam-time-table wlsm-font-small wlsm-font-bold wlsm-ml-1" data-exam-time-table="<?php echo esc_attr( $exam_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'st-print-exam-time-table-' . $exam_id ) ); ?>" data-message-title="<?php echo esc_attr__( 'Print Exam Time Table', 'school-management' ); ?>"><?php esc_html_e( 'Print', 'school-management' ); ?></a>
		</span>
		<span class="wlsm-float-md-right wlsm-font-small">
			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'exams-time-table' ), $current_page_url ) ); ?>">
				<?php esc_html_e( 'Back', 'school-management' ); ?>&nbsp;&rarr;
			</a>
		</span>
	</div>

	<div class="wlsm-st-exam-time-table-section table-responsive w-100 wlsm-w-100">
		<div class="wlsm-mb-1">
			<?php require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/exam_time_table_title.php'; ?>
		</div>
		<table class="wlsm-st-exam-time-table table table-hover table-bordered wlsm-w-100">
			<?php require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/exam_time_table.php'; ?>
		</table>
	</div>
</div>
