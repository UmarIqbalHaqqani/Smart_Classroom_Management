<?php
defined( 'ABSPATH' ) || die();

// Registration settings.
$settings_registration           = WLSM_M_Setting::get_settings_registration( $school_id );
$school_registration_blood_group = $settings_registration['blood_group'];

$settings_dashboard       = WLSM_M_Setting::get_settings_dashboard( $school_id );
$school_enrollment_number = $settings_dashboard['school_enrollment_number'];
$school_admission_number  = $settings_dashboard['school_admission_number'];

$settings_background           = WLSM_M_Setting::get_settings_background($school_id);
$id_card_background     = $settings_background['id_card_background'];
?>
<div class="wlsm-print-id-card-container bg-img bg-cover" style="background: no-repeat center/100% url(<?php echo ( wp_get_attachment_url($id_card_background) );  ?>) !important;  ">

	<?php require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/school_header.php'; ?>

	<div class="row wlsm-print-id-card-details mt-1 mobile-id-card">
		<div class="col-8 wlsm-print-id-card-right" >
			<ul>
				<li>
					<span class="wlsm-font-bold"><?php esc_html_e( 'Student Name', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ); ?></span>
				</li>
				<?php if ( $school_enrollment_number ) : ?>
				<li>
					<span class="wlsm-font-bold"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( $student->enrollment_number ); ?></span>
				</li>
				<?php endif ?>
				<?php if ($school_admission_number): ?>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Admission Number', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( $student->admission_number ); ?></span>
					</li>
				<?php endif ?>

				<li>
					<span class="pr-3">
						<span class="wlsm-font-bold"><?php esc_html_e( 'Class', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Class::get_label_text( $student->class_label ) ); ?></span>
					</span>
					<span class="pl-3">
						<span class="wlsm-font-bold"><?php esc_html_e( 'Section', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Class::get_label_text( $student->section_label ) ); ?></span>
					</span>
				</li>
				<li>
					<span class="pr-3">
						<span class="wlsm-font-bold"><?php esc_html_e( 'Roll Number', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $student->roll_number ) ); ?></span>
					</span>

					<?php if ( $school_registration_blood_group ) : ?>
						<span class="pl-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Blood Group', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( $student->blood_group ); ?></span>
						</span>
					<?php endif ?>

				</li>
				<li>
					<span class="wlsm-font-bold"><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $student->father_name ) ); ?></span>
				</li>
				<li>
					<span class="wlsm-font-bold"><?php esc_html_e( 'Phone', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $student->phone ) ); ?></span>
				</li>
				<li>
					<span class="wlsm-font-bold"><?php esc_html_e( 'Address', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( $student->address ); ?></span>
				</li>
			</ul>
		</div>

		<div class="col-3 wlsm-print-id-card-left">
			<div class="wlsm-print-id-card-photo-box">
			<?php if ( ! empty( $photo_id ) ) { ?>
				<img src="<?php echo esc_url( wp_get_attachment_url( $photo_id ) ); ?>" class="wlsm-print-id-card-photo">
			<?php } ?>
			</div>
			<div class="wlsm-print-id-card-authorized-by">
				<?php if ( ! empty( $school_signature ) ) { ?>
					<img src="<?php echo esc_url( wp_get_attachment_url( $school_signature ) ); ?>" class="wlsm-print-id-card-signature">
				<?php } ?>
				<span><?php esc_html_e( 'Authorized By', 'school-management' ); ?></span>
			</div>
		</div>
	</div>

</div>
