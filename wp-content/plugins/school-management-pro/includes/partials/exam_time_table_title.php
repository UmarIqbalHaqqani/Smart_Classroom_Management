<?php
defined( 'ABSPATH' ) || die();
?>
<span>
<?php
printf(
	wp_kses(
		/* translators: 1: exam title, 2: start date, 3: end date */
		__( '<span class="wlsm-font-bold">Exam:</span> <span class="text-dark">%1$s (%2$s - %3$s)</span>', 'school-management' ),
		array( 'span' => array( 'class' => array() ) )
	),
	esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
	esc_html( WLSM_Config::get_date_text( $start_date ) ),
	esc_html( WLSM_Config::get_date_text( $end_date ) )
);
?>
</span>
<span class="wlsm-float-md-right float-md-right">
<?php
/* translators: %s: exam classes */
printf(
	wp_kses(
		__( '<span class="wlsm-font-bold">Class:</span> %s</span>', 'school-management' ),
		array( 'span' => array( 'class' => array() ) )
	),
	esc_html( $class_names )
);
?>
</span>
