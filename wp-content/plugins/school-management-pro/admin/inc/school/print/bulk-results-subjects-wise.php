<?php
defined( 'ABSPATH' ) || die();
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}
?>

<!-- Print Admit Cards. -->
<div class="wlsm-container d-flex mb-2">
	<div class="col-md-12 wlsm-text-center">
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?> mt-2" id="wlsm-print-exam-admit-card-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-result-subject-wise.css' ); ?>"]' data-title="<?php
			printf(
				/* translators: 1: exam title, 2: start date, 3: end date, 4: exam classes */
				esc_attr__( 'Admit Card: %1$s (%2$s - %3$s), Class: %4$s', 'school-management' ),
				esc_html( '' ),
				esc_html( '' ),
				esc_html( '' ),
				esc_html( '' )
			);
			?>"><?php esc_html_e( 'Print', 'school-management' ); ?>
		</button>
	</div>
</div>

<!-- Print Admit Cards section. -->
<div class="wlsm-container wlsm wlsm-form-section wlsm-print-exam-admit-card" id="wlsm-print-exam-admit-card">
	<div class="wlsm-print-exam-admit-card-container">
		<!-- Print Admit Cards section. -->
		<?php
		foreach ( $students as $student ) {
		$class_school_id = $student->class_school_id;
		$class_id = $student->class_id;
		$bulk_print = true;
		?>
		<?php require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/result_subject_wise.php'; ?>
		<div class="page-break"></div>		
		<?php
		}
		?>
	</div>
</div>
