<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$certificate_student_id = $id;

$certificate_student = WLSM_M_Staff_General::fetch_certificate_distributed( $school_id, $session_id, $certificate_student_id );

if ( ! $certificate_student || ( $certificate_student->student_id !== $student->ID ) ) {
	die;
}

$session_id = $certificate_student->session_id;
$student_id = $student->ID;

require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/certificate_student.php';

$student = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $student_id );
?>
<div class="wlsm-content-area wlsm-section-certificate">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Certificate', 'school-management' ); ?>
		</span>
		<span class="wlsm-float-md-right wlsm-font-small">
			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'certificates' ), $current_page_url ) ); ?>">
				<?php esc_html_e( 'Back', 'school-management' ); ?>&nbsp;&rarr;
			</a>
		</span>
	</div>

	<div class="wlsm-st-certificate-section w-100 wlsm-w-100">
		<div class="wlsm-mb-1">
			<ul class="wlsm-list-group">
				<li class="wlsm-list-item">
					<span class="wlsm-font-bold"><?php esc_html_e( 'Certificate Title', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Staff_Class::get_certificate_label_text( $certificate_student->label ) ); ?></span>
				</li>
				<li class="wlsm-list-item">
					<span class="wlsm-font-bold"><?php esc_html_e( 'Certificate Number', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( $certificate_student->certificate_number ); ?></span>
				</li>
				<li class="wlsm-list-item">
					<span class="wlsm-font-bold"><?php esc_html_e( 'Date Issued', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_Config::get_date_text( $certificate_student->date_issued ) ); ?></span>
				</li>
			</ul>
			<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/certificate.php'; ?>
		</div>
	</div>
</div>
