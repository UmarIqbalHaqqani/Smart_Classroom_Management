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

?>

<!-- Print Admit Cards. -->
<div class="wlsm-container d-flex mb-2">
	<div class="col-md-12 wlsm-text-center">
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?> mt-2" id="wlsm-print-exam-admit-card-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-exam-admit-card.css' ); ?>"]' data-title="<?php
			printf(
				/* translators: 1: exam title, 2: start date, 3: end date, 4: exam classes */
				esc_attr__( 'Results: %1$s (%2$s - %3$s), Class: %4$s', 'school-management' ),
				esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
				esc_html( WLSM_Config::get_date_text( $start_date ) ),
				esc_html( WLSM_Config::get_date_text( $end_date ) ),
				esc_html( $class_names )
			);
			?>"><?php esc_html_e( 'Print Results', 'school-management' ); ?>
		</button>
	</div>
</div>

<!-- Print Admit Cards section. -->
<div class="wlsm-container wlsm wlsm-form-section wlsm-print-exam-admit-card" id="wlsm-print-exam-admit-card">
	<div class="wlsm-print-exam-admit-card-container">
		<!-- Print Admit Cards section. -->
		<?php
		foreach ( $results as $result ) {		

			$exam_papers  = WLSM_M_Staff_Examination::get_exam_papers_by_admit_card( $school_id, $result->admit_card_id );
			$exam_results = WLSM_M_Staff_Examination::get_exam_results_by_admit_card( $school_id, $result->admit_card_id );

			$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id,  $result->exam_id );

			$exam_id     = $exam->ID;
			$show_rank   = $exam->show_rank;
			$show_remark = $exam->show_remark;
			$show_eremark = $exam->show_eremark;
			$psychomotor_enable = $exam->psychomotor_analysis;
			$psychomotor =  WLSM_Config::sanitize_psychomotor( $exam->psychomotor );
		?>
		
		<?php require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/result.php'; ?>
		<div class="page-break"></div>		
		<?php
		}
		?>
	</div>
</div>
