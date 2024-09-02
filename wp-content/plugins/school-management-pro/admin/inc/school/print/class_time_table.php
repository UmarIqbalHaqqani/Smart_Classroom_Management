<?php
defined( 'ABSPATH' ) || die();

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}
?>

<!-- Print class timetable. -->
<div class="d-flex mt-2 mb-2">
	<div class="col-md-12 wlsm-text-center">
		<?php
		printf(
			wp_kses(
				/* translators: 1: class label, 2: section */
				__( 'Class Timetable: %1$s - %2$s', 'school-management' ),
				array(
					'span' => array( 'class' => array() )
				)
			),
			esc_html( WLSM_M_Class::get_label_text( $class_label ) ),
			esc_html( WLSM_M_Staff_Class::get_section_label_text( $section_label ) )
		);
		?>
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?> mt-2" id="wlsm-print-class-timetable-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-class-timetable.css' ); ?>"]' data-title="<?php
		printf(
			wp_kses(
				/* translators: 1: class label, 2: section */
				__( 'Class Timetable: %1$s - %2$s', 'school-management' ),
				array(
					'span' => array( 'class' => array() )
				)
			),
			esc_html( WLSM_M_Class::get_label_text( $class_label ) ),
			esc_html( WLSM_M_Staff_Class::get_section_label_text( $section_label ) )
		);
		?>"><?php esc_html_e( 'Print Class Timetable', 'school-management' ); ?>
		</button>
	</div>
</div>

<!-- Print class timetable section. -->
<div class="wlsm wlsm-form-section" id="wlsm-print-class-timetable">
	<div class="wlsm-print-class-timetable-container">

		<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/school_header.php'; ?>

		<div class="row">
			<div class="col-12">
				<div class="wlsm-font-bold">
					<span>
					<?php
					printf(
						wp_kses(
							/* translators: 1: class label, 2: section */
							__( 'Class Timetable: %1$s - %2$s', 'school-management' ),
							array(
								'span' => array( 'class' => array() )
							)
						),
						esc_html( WLSM_M_Class::get_label_text( $class_label ) ),
						esc_html( WLSM_M_Staff_Class::get_section_label_text( $section_label ) )
					);
					?>
					</span>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-12">
				<div class="wlsm-class-timetable-box table-responsive w-100">
					<table class="wlsm-class-timetable table table-bordered table-striped wlsm-font-small-medium">
					<?php require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/class_time_table.php'; ?>
					</table>
				</div>
			</div>
		</div>

	</div>
</div>
