<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}

$exam_title = $exam->exam_title;
$start_date = $exam->start_date;
$end_date   = $exam->end_date;

$class_names = array();
foreach ( $exam_classes as $exam_class ) {
	array_push( $class_names, WLSM_M_Class::get_label_text( $exam_class->label ) );
}

$class_names = implode( ', ', $class_names );
?>

<!-- Print exam time table. -->
<div class="wlsm-container wlsm d-flex mt-2 mb-2">
	<div class="col-md-12 wlsm-text-center">
		<?php
		printf(
			wp_kses(
				/* translators: 1: exam title, 2: start date, 3: end date, 4: exam classes */
				__( '<span class="wlsm-font-bold">Exam Time Table:</span> <span class="text-dark">%1$s (%2$s - %3$s)<br><span class="wlsm-font-bold">Class:</span> %4$s</span>', 'school-management' ),
				array( 'span' => array( 'class' => array() ), 'br' => array() )
			),
			esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
			esc_html( WLSM_Config::get_date_text( $start_date ) ),
			esc_html( WLSM_Config::get_date_text( $end_date ) ),
			esc_html( $class_names )
		);
		?>
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?> mt-2" id="wlsm-print-exam-time-table-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-exam-time-table.css' ); ?>"]' data-title="<?php
			printf(
				/* translators: 1: exam title, 2: start date, 3: end date, 4: exam classes */
				esc_attr__( 'Exam Time Table: %1$s (%2$s - %3$s), Class: %4$s', 'school-management' ),
				esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
				esc_html( WLSM_Config::get_date_text( $start_date ) ),
				esc_html( WLSM_Config::get_date_text( $end_date ) ),
				esc_html( $class_names )
			);
			?>"><?php esc_html_e( 'Print Exam Time Table', 'school-management' ); ?>
		</button>
	</div>
</div>

<!-- Print exam time table section. -->
<div class="wlsm-container wlsm wlsm-form-section" id="wlsm-print-exam-time-table">
	<div class="wlsm-print-exam-time-table-container">

		<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/school_header.php'; ?>

		<div class="wlsm-mt-1 mt-1">
			<?php require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/exam_time_table_title.php'; ?>
		</div>
		<div class="table-responsive w-100">
			<table class="table table-bordered wlsm-view-exam-time-table">
				<?php require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/exam_time_table.php'; ?>
			</table>
		</div>

	</div>
</div>
