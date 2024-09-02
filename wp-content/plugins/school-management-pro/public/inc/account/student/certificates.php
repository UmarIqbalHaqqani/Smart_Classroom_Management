<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$certificates_per_page = 10;

$certificates_query = 'SELECT cfsr.ID, cfsr.certificate_id, cfsr.certificate_number, cfsr.date_issued, cf.label, ss.label as session_label, ss.start_date as session_start_date, ss.end_date as session_end_date FROM ' . WLSM_CERTIFICATE_STUDENT . ' as cfsr 
	JOIN ' . WLSM_CERTIFICATES . ' as cf ON cf.ID = cfsr.certificate_id 
	JOIN ' . WLSM_STUDENT_RECORDS . ' as sr ON sr.ID = cfsr.student_record_id 
	JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id 
	JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id 
	JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id 
	JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id 
	WHERE cfsr.student_record_id = %d GROUP BY cfsr.ID';

$certificates_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$certificates_query}) AS combined_table", $student->ID ) );

$certificates_page = isset( $_GET['certificates_page'] ) ? absint( $_GET['certificates_page'] ) : 1;

$certificates_page_offset = ( $certificates_page * $certificates_per_page ) - $certificates_per_page;

$certificates = $wpdb->get_results( $wpdb->prepare( $certificates_query . ' ORDER BY cfsr.date_issued DESC LIMIT %d, %d', $student->ID, $certificates_page_offset, $certificates_per_page ) );
?>
<div class="wlsm-content-area wlsm-section-certificates wlsm-student-certificates">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Certificates', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-certificates-section">
		<?php
		if ( count( $certificates ) ) {
		?>
		<ul class="wlst-st-list wlsm-st-certificates">
			<?php
			foreach ( $certificates as $key => $certificate_student ) {
			?>
			<li>
				<span>
					<span class="wlsm-font-bold">
						<?php echo esc_html( WLSM_M_Staff_Class::get_certificate_label_text( $certificate_student->label ) ); ?>
						(<u><?php echo esc_html( $certificate_student->certificate_number ); ?></u>)
					</span>
					<span class="wlsm-st-certificate-date-issued">
						<?php echo esc_html( WLSM_Config::get_date_text( $certificate_student->date_issued ) ); ?>
					</span>
					&nbsp;
					<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'certificates', 'id' => $certificate_student->ID ), $current_page_url ) ); ?>" class="wlsm-font-bold">
						<?php esc_html_e( 'View', 'school-management' ); ?>
					</a>
				</span>
			</li>
			<?php
			}
		?>
		</ul>
		<div class="wlsm-text-right wlsm-font-medium wlsm-font-bold wlsm-mt-2">
		<?php
		echo paginate_links(
			array(
				'base'      => add_query_arg( 'certificates_page', '%#%' ),
				'format'    => '',
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'total'     => ceil( $certificates_total / $certificates_per_page ),
				'current'   => $certificates_page,
			)
		);
		?>
		</div>
		<?php
		} else {
		?>
		<div>
			<span class="wlsm-font-medium wlsm-font-bold">
				<?php esc_html_e( 'There is no certificate.', 'school-management' ); ?>
			</span>
		</div>
		<?php
		}
		?>
	</div>
</div>
