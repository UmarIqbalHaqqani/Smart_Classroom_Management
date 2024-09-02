<?php
defined( 'ABSPATH' ) || die();

$certificate_student_id = absint( $_GET['certificate_student_id'] );

$page_url = WLSM_M_Staff_General::get_certificates_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$certificate_student = WLSM_M_Staff_General::fetch_certificate_distributed( $school_id, $session_id, $certificate_student_id );

if ( ! $certificate_student ) {
	die;
}
$student_id = $certificate_student->student_id;
$exam_id    = $certificate_student->exam_id;
global $wpdb;
$admit_card_id = $wpdb->get_var('SELECT ID FROM '.WLSM_ADMIT_CARDS.' WHERE exam_id = '.$exam_id.' AND student_record_id = '.$student_id.'');

require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/certificate_result.php';

require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/certificate_student.php';

$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					printf(
						wp_kses(
							/* translators: %s: certificate title */
							__( 'Student Certificate: %s', 'school-management' ),
							array(
								'span' => array( 'class' => array() )
							)
						),
						esc_html( $certificate->label )
					);
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . "&action=distribute&id=" . $certificate->ID ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-certificate"></i>&nbsp;
					<?php esc_html_e( 'Distribute Certificate', 'school-management' ); ?>
				</a>
				<a href="<?php echo esc_url( $page_url . "&action=students&id=" . $certificate->ID ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-certificate"></i>&nbsp;
					<?php esc_html_e( 'Total Certificates Distributed', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/certificate.php'; ?>
	</div>
</div>
