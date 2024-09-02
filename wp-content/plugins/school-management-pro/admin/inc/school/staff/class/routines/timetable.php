<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_Class::get_timetable_page_url();

$school_id = $current_school['id'];

$section = NULL;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$section_id = absint( $_GET['id'] );

	$section = WLSM_M_Staff_Class::get_school_section( $school_id, $section_id );

	$class_label   = $section->class_label;
	$section_label = $section->label;
}

if ( ! $section ) {
	die;
}

$from_staff = true;
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
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
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . '&action=save' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-plus-square"></i>&nbsp;
					<?php echo esc_html( 'Add New Routine', 'school-management' ); ?>
				</a>
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-calendar-alt"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/class_time_table.php'; ?>
	</div>
</div>
