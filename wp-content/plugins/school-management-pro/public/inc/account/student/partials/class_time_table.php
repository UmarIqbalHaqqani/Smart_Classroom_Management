<?php
defined( 'ABSPATH' ) || die();

$action_for = 'st';
if ( isset( $change_action ) ) {
	$action_for = $set_action_for;
}

$student_id = $student->ID;
$section_id = $student->section_id;

$section = WLSM_M_Staff_Class::get_school_section( $school_id, $student->section_id );

if ( ! $section ) {
	die;
} 

$class_label   = $section->class_label;
$section_label = $section->label;
?>
<div class="wlsm-content-area wlsm-section-class-time-table wlsm-student-class-time-table">
	<div class="wlsm-st-main-title">
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
			<a href="#" class="wlsm-<?php echo esc_attr( $action_for ); ?>-print-class-time-table wlsm-font-small wlsm-font-bold wlsm-ml-1" data-class-time-table="<?php echo esc_attr( $section_id ); ?>" data-student="<?php echo esc_attr( $student_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( $action_for . '-print-class-time-table-' . $section_id ) ); ?>" data-message-title="<?php echo esc_attr__( 'Print Class Time Table', 'school-management' ); ?>"><?php esc_html_e( 'Print', 'school-management' ); ?></a>
		</span>
	</div>

	<div class="wlsm-st-class-time-table-section table-responsive w-100 wlsm-w-100">
		<table class="wlsm-st-class-time-table table table-hover table-bordered wlsm-w-100">
			<?php require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/class_time_table.php'; ?>
		</table>
	</div>
</div>
