<?php
defined( 'ABSPATH' ) || die();

$certificate_id = $certificate_student->certificate_id;
$student_id     = $certificate_student->student_id;

$certificate_number = $certificate_student->certificate_number;
$certificate_title  = WLSM_M_Staff_Class::get_certificate_label_text( $certificate_student->label );

$session_label      = $certificate_student->session_label;
$session_start_date = $certificate_student->session_start_date;
$session_end_date   = $certificate_student->session_end_date;

$fields = WLSM_Helper::get_certificate_dynamic_fields();

$certificate = WLSM_M_Staff_General::fetch_certificate( $school_id, $certificate_id );

$image_id  = $certificate->image_id;
$image_url = wp_get_attachment_url( $image_id );

if ( $certificate->fields ) {
	$saved_fields = unserialize( $certificate->fields );

	if ( is_array( $saved_fields ) && count( $saved_fields ) ) {
		foreach ( $fields as $field_key => $field_value ) {
			if ( array_key_exists( $field_key, $saved_fields ) ) {
				$fields[ $field_key ] = $saved_fields[ $field_key ];
			}
		}
	}
}
