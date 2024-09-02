<?php
defined( 'ABSPATH' ) || die();
?>
<div class="wlsm-parent-student-detail">
	<span class="wlsm-font-bold"><?php esc_html_e( 'Student Name', 'school-management' ); ?>:</span>
	<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ); ?></span>
	<br>
	<span class="wlsm-font-bold"><?php esc_html_e( 'School', 'school-management' ); ?>:</span>
	<span><?php echo esc_html( WLSM_M_School::get_label_text( $student->school_name ) ); ?></span>
	<br>
	<span class="wlsm-font-bold"><?php esc_html_e( 'Session', 'school-management' ); ?>:</span>
	<span><?php echo esc_html( WLSM_M_Session::get_label_text( $student->session_label ) ); ?></span>
	<br>
	<span class="wlsm-font-bold"><?php esc_html_e( 'Class', 'school-management' ); ?>:</span>
	<span><?php echo esc_html( WLSM_M_Class::get_label_text( $student->class_label ) ); ?></span>
	<span class="wlsm-font-bold wlsm-ml-3"><?php esc_html_e( 'Section', 'school-management' ); ?>:</span>
	<span><?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $student->section_label ) ); ?></span>
	<br>
	<span class="wlsm-font-bold"><?php esc_html_e( 'Admission Number', 'school-management' ); ?>:</span>
	<span><?php echo esc_html( WLSM_M_Staff_Class::get_admission_no_text( $student->admission_number ) ); ?></span>
	<br>
	<span class="wlsm-font-bold"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?>:</span>
	<span><?php echo esc_html( $student->enrollment_number ); ?></span>
	<br>
	<span class="wlsm-font-bold"><?php esc_html_e( 'Roll Number', 'school-management' ); ?>:</span>
	<span><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $student->roll_number ) ); ?></span>
</div>
