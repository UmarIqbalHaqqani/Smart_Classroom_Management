<?php
defined( 'ABSPATH' ) || die();

$school_id = NULL;
if ( isset( $attr['school_id'] ) ) {
	$school_id = absint( $attr['school_id'] );

	$school = WLSM_M_School::get_active_school( $school_id );
	if ( ! $school ) {
		$invalid_message = esc_html__( 'School not found.', 'school-management' );
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/partials/invalid.php';
	}

	$certificates = WLSM_M_Staff_General::get_school_certificates( $school_id );

} else {
	$school  = NULL;
	$schools = WLSM_M_School::get_active_schools();

	$certificates = array();
}

$nonce_action = 'get-certificate';

// certificate validation.
if (isset($_GET['id'])) {
	$certificate_id = $_GET['id'];

	global $wpdb;
	$certificate_student = $wpdb->get_row(
		$wpdb->prepare( 'SELECT cfsr.ID, cfsr.certificate_id, cfsr.student_record_id as student_id, cfsr.certificate_number, cfsr.date_issued, cf.label, ss.ID as session_id, ss.label as session_label, ss.start_date as session_start_date, ss.end_date as session_end_date FROM ' . WLSM_CERTIFICATE_STUDENT . ' as cfsr 
			JOIN ' . WLSM_CERTIFICATES . ' as cf ON cf.ID = cfsr.certificate_id 
			JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = cfsr.student_record_id 
			JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
			JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
			JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
			JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
			WHERE cs.school_id = %d AND cf.ID = %d', $school_id, $certificate_id
		)
	);

	if ( ! $certificate_student ) {
		throw new Exception( esc_html__( 'Certificate not found.', 'school-management' ) );
	}

	$session_id = $certificate_student->session_id;
	$student_id = $certificate_student->student_id;

	require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/certificate_student.php';

	$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );
	require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/certificate.php';
}

// certificate validation End.

?>
<br>
<div class="wlsm">
	<div id="wlsm-get-certificate-section">
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-get-certificate-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-p-get-certificate">
			<?php
			if ( ! $school ) {
			?>
			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label for="wlsm_school_certificate" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'School', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<select name="school_id" class="wlsm-form-control wlsm_school_certificate" id="wlsm_school_certificate" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-school-certificates' ) ); ?>">
						<option value=""><?php esc_html_e( 'Select School', 'school-management' ); ?></option>
						<?php foreach ( $schools as $value ) { ?>
						<option value="<?php echo esc_attr( $value->ID ); ?>">
							<?php echo esc_html( WLSM_M_School::get_label_text( $value->label ) ); ?>
						</option>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php
			} else {
			?>
			<input type="hidden" name="school_id" value="<?php echo esc_attr( $school_id ); ?>" id="wlsm_school_certificate">
			<?php
			}
			?>
			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label for="wlsm_certificate" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'Certificate', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<select name="certificate_id" class="wlsm-form-control" id="wlsm_certificate">
						<option value=""><?php esc_html_e( 'Select Certificate', 'school-management' ); ?></option>
						<?php foreach ( $certificates as $certificate ) { ?>
						<option value="<?php echo esc_attr( $certificate->ID ); ?>">
							<?php echo esc_html( WLSM_M_Staff_Class::get_certificate_label_text( $certificate->label ) ); ?>
						</option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="wlsm-form-group wlsm-row">
				<div class="wlsm-col-4">
					<label for="wlsm_enrollment_number" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'Enrollment Number', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<input type="text" name="enrollment_number" class="wlsm-form-control" id="wlsm_enrollment_number" placeholder="<?php esc_attr_e( 'Enter enrollment number', 'school-management' ); ?>">
				</div>
			</div>

			<div class="wlsm-border-top wlsm-pt-2 wlsm-mt-1">
				<button class="button wlsm-btn btn btn-primary" type="submit" id="wlsm-get-certificate-btn">
					<?php esc_html_e( 'Get Certificate', 'school-management' ); ?>
				</button>
			</div>

		</form>

		<div class="wlsm-shortcode-entity">
			<div class="wlsm-certificate"></div>
		</div>

	</div>
</div>
<?php
return ob_get_clean();
