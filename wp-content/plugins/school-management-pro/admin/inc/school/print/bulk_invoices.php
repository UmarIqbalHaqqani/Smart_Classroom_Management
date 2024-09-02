<?php
defined( 'ABSPATH' ) || die();
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}
?>

<!-- invoices cards. -->
<div class="wlsm-container d-flex mb-2">
	<div class="col-md-12 wlsm-text-center">
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?>" id="wlsm-print-invoice-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-invoice.css' ); ?>"]' data-title="<?php		
		printf(
			/* translators: 1: class label, 2: section label */
			esc_attr__( 'Invoices - Class: %1$s, Section: (%2$s)', 'school-management' ),
			esc_attr( $class_label ),
			esc_attr( $section_label ) );
		?>"><?php esc_html_e( 'Print Invoices', 'school-management' ); ?>
		</button>
	</div>
</div>

<!-- invoices cards section. -->
<div class="wlsm-container wlsm" id="wlsm-print-invoice">
	<div class="wlsm-print-invoice-container">
		<?php
		foreach ( $invoices as $invoice ) {
		?>
		<?php require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/invoice.php'; ?>
		<?php
		}
		?>
	</div>
</div>
