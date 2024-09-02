<?php
defined( 'ABSPATH' ) || die();

if ( ! count( $admit_cards ) ) {
	?>
	<div class="text-center">
		<span class="text-danger wlsm-font-bold">
			<?php esc_html_e( 'No student found.', 'school-management' ); ?>
		</span>
	</div>
	<?php
	return;
}

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
foreach ($exam_classes as $exam_class) {
	array_push($class_names, WLSM_M_Class::get_label_text($exam_class->label));
}

$class_names = implode(', ', $class_names);
?>

<!-- Print Admit Cards. -->
<div class="wlsm-container d-flex mb-2">
	<div class="col-md-12 wlsm-text-center">
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?> mt-2" id="wlsm-print-exam-admit-card-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-exam-admit-card.css' ); ?>"]' data-title="<?php
			printf(
				/* translators: 1: exam title, 2: start date, 3: end date, 4: exam classes */
				esc_attr__( 'Admit Card: %1$s (%2$s - %3$s), Class: %4$s', 'school-management' ),
				esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
				esc_html( WLSM_Config::get_date_text( $start_date ) ),
				esc_html( WLSM_Config::get_date_text( $end_date ) ),
				esc_html( $class_names )
			);
			?>"><?php esc_html_e( 'Print Admit Card', 'school-management' ); ?>
		</button>
	</div>
</div>

<?php
$settings_background           = WLSM_M_Setting::get_settings_background($school_id);
$invoice_card_background     = $settings_background['invoice_card_background'];
?>
<!-- Print Admit Cards section. -->
<div class="wlsm-container wlsm wlsm-form-section wlsm-print-exam-admit-card" id="wlsm-print-exam-admit-card" style="background: no-repeat center/100% url(<?php echo ( wp_get_attachment_url($invoice_card_background) );  ?>) !important; " >
	<div class="wlsm-print-exam-admit-card-container">
		<!-- Print Admit Cards section. -->
		<?php
		foreach ( $admit_cards as $card ) {
			$student_id        = $card->student_id;
			$photo_id          = $card->photo_id;
			$name              = $card->name;
			$enrollment_number = $card->enrollment_number;
			$session_label     = $card->session_label;
			$class_label       = $card->class_label;
			$section_label     = $card->section_label;
			$roll_number       = $card->roll_number;
			$phone             = $card->phone;
			$email             = $card->email;
		?>

		<?php require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/admit_card.php'; ?>
		<div class="page-break"></div>
		<?php
		}
		?>
	</div>
</div>
