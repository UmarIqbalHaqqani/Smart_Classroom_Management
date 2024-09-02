<?php
defined( 'ABSPATH' ) || die();

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}

$css = <<<EOT
#wlsm-print-certificate {
	font-size: 16px;
	color: #000;
}
.wlsm-certificate-fields {
	height: 31cm;
	overflow-y: auto;
}
.wlsm-print-certificate-container {
	box-sizing: border-box;
	position: relative;
	width: 25cm;
	height: 35.7cm;
}
.wlsm-certificate-image {
	width: 100%;
	height: 100%;
}
EOT;

foreach ( $fields as $field_key => $field_value ) {
	$css .= '.ctf-data-' . esc_attr( $field_key ) . ' { position: absolute; ';

	foreach ( $field_value['props'] as $key => $prop ) {
		$css .= $key . ': ' . $prop['value'] . $prop['unit'] . ';';
	}
	$css .= ' }';

	if ( $field_value['enable'] ) {
		$css .= '.ctf-data-' . esc_attr( $field_key ) . '{ visibility: visible; }';
	} else {
		$css .= '.ctf-data-' . esc_attr( $field_key ) . '{ visibility: hidden; }';
	}
}

if ( isset( $from_ajax ) ) {
?>
<style>
	<?php echo esc_attr( $css ); ?>
</style>
<?php
} else {
	wp_register_style( 'wlsm-certificate', false );
	wp_enqueue_style( 'wlsm-certificate' );
	wp_add_inline_style( 'wlsm-certificate', $css );
}
?>

<!-- Print certificate. -->
<div class="wlsm-container d-flex mb-2">
	<div class="col-md-12 wlsm-text-center">
		<br>
		<button type="button" data-css="<?php echo esc_attr( $css ); ?>" class="<?php echo esc_attr( $print_button_classes ); ?>" id="wlsm-print-certificate-btn" data-title="<?php esc_attr_e( 'Print Certificate', 'school-management' ); ?>" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-certificate.css' ); ?>"]'><?php esc_html_e( 'Print Certificate', 'school-management' ); ?></button>
	</div>
</div>

<div class="wlsm-container row">
	<div class="col-md-12 wlsm-flex wlsm-justify-center">
		<!-- Print certificate section. -->
		<div class="wlsm" id="wlsm-print-certificate">
			<div class="wlsm-print-certificate-container mx-auto">
				<?php
				if ( ! $image_url ) {
					$image_url = WLSM_PLUGIN_URL . 'assets/images/certificate.png';
				}
				?>
				<img class="ctf-data-field wlsm-certificate-image" src="<?php echo esc_url( $image_url ); ?>">
				<?php
				$school_name     = '';
				$school_phone    = '';
				$school_email    = '';
				$school_address  = '';
				$school_logo_url = '';

				if ( $school_id ) {
					$school         = WLSM_M_School::fetch_school( $school_id );
					$school_name    = esc_html( WLSM_M_School::get_label_text( $school->label ) );
					$school_phone   = esc_html( WLSM_M_School::get_phone_text( $school->phone ) );
					$school_email   = esc_html( WLSM_M_School::get_email_text( $school->email ) );
					$school_address = esc_html( WLSM_M_School::get_address_text( $school->address ) );

					$settings_general = WLSM_M_Setting::get_settings_general( $school_id );
					$school_logo      = $settings_general['school_logo'];
					if ( ! empty ( $school_logo ) ) {
						$school_logo_url = esc_url( wp_get_attachment_url( $school_logo ) );
					}
				}

				foreach ( $fields as $field_key => $field_value ) {
					if ( isset( $student ) ) {
						if ( 'name' === $field_key ) {
							$field_output = WLSM_M_Staff_Class::get_name_text( $student->student_name );

						} elseif ( 'certificate-number' === $field_key ) {
							$field_output = $certificate_number;

						} elseif ( 'certificate-title' === $field_key ) {
							$field_output = $certificate_title;

						} elseif ( 'photo' === $field_key ) {
							if ( ! empty ( $student->photo_id ) ) {
								$field_output = wp_get_attachment_url( $student->photo_id );
							} else {
								$field_output = '';
							}

						} elseif ( 'qcode' === $field_key ) {
							if ( ! empty ( $certificate_number ) ) {
								$settings_url            = WLSM_M_Setting::get_settings_certificate_qcode_url( $school_id );
								$school_certificate_url    = $settings_url['certificate_url'];
								$qr_code = $school_certificate_url.'?id='.$certificate_number;
								$field_output 	  = esc_url('https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $qr_code . '&choe=UTF-8');
							} else {
								$field_output = '';
							}

						} elseif ( 'enrollment-number' === $field_key ) {
							$field_output = $student->enrollment_number;

						} elseif ( 'admission-number' === $field_key ) {
							$field_output = WLSM_M_Staff_Class::get_admission_no_text( $student->admission_number );

						} elseif ( 'roll-number' === $field_key ) {
							$field_output = WLSM_M_Staff_Class::get_roll_no_text( $student->roll_number );

						} elseif ( 'session-label' === $field_key ) {
							$field_output = WLSM_M_Session::get_label_text( $session_label );

						} elseif ( 'session-start-date' === $field_key ) {
							$field_output = WLSM_Config::get_date_text( $session_start_date );

						} elseif ( 'session-end-date' === $field_key ) {
							$field_output = WLSM_Config::get_date_text( $session_end_date );

						} elseif ( 'session-start-year' === $field_key ) {
							$field_output = DateTime::createFromFormat( 'Y-m-d', $session_start_date );
							$field_output = $field_output->format( 'Y' );

						} elseif ( 'session-end-year' === $field_key ) {
							$field_output = DateTime::createFromFormat( 'Y-m-d', $session_end_date );
							$field_output = $field_output->format( 'Y' );

						} elseif ( 'class' === $field_key ) {
							$field_output = WLSM_M_Class::get_label_text( $student->class_label );

						} elseif ( 'section' === $field_key ) {
							$field_output = WLSM_M_Class::get_label_text( $student->section_label );

						} elseif ( 'dob' === $field_key ) {
							$field_output = WLSM_Config::get_date_text( $student->dob );

						} elseif ( 'caste' === $field_key ) {
							$field_output = stripcslashes( $student->caste );

						} elseif ( 'blood-group' === $field_key ) {
							$field_output = stripcslashes( $student->blood_group );

						} elseif ( 'father-name' === $field_key ) {
							$field_output = stripcslashes( $student->father_name );

						} elseif ( 'mother-name' === $field_key ) {
							$field_output = stripcslashes( $student->mother_name );

						} elseif ( 'class-teacher' === $field_key ) {
							$section_id = $student->section_id;

							$teacher = WLSM_M_Staff_Class::get_section_teachers( $school_id, $section_id, true );

							if ( $teacher ) {
								$teacher_name = $teacher->name;
							} else {
								$teacher_name = '';
							}

							$field_output = stripcslashes( $teacher_name );

						} elseif ( 'school-name' === $field_key ) {
							$field_output = $school_name;

						} elseif ( 'school-phone' === $field_key ) {
							$field_output = $school_phone;

						} elseif ( 'school-email' === $field_key ) {
							$field_output = $school_email;

						} elseif ( 'school-address' === $field_key ) {
							$field_output = $school_address;

						} elseif ( 'school-logo' === $field_key ) {
							$field_output = $school_logo_url;

						} elseif ( 'total-max-mark' === $field_key ) {
							$field_output = $total_maximum_marks;

						} elseif ( 'total-obtained-mark' === $field_key ) {
							$field_output = $total_obtained_marks;

						} elseif ( 'rank' === $field_key ) {
							$field_output = $student_rank;

						} elseif ( 'percentage' === $field_key ) {
							$field_output = $student_percentage;

						} else {
							$field_output = '';
						}
					} else {
						$field_output = WLSM_Helper::get_certificate_place_holder( $field_key, $school_id );
					}

					if ( 'text' === WLSM_Helper::get_certificate_place_holder_type( $field_key ) ) {
					?>
					<span class="ctf-data-field ctf-data-<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $field_output ); ?></span>
					<?php
					} elseif ( 'image' === WLSM_Helper::get_certificate_place_holder_type( $field_key ) && $field_output ) {
					?>
					<img class="ctf-data-field ctf-data-<?php echo esc_attr( $field_key ); ?>" src="<?php echo esc_url( $field_output ); ?>">
					<?php
					}
				?>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
