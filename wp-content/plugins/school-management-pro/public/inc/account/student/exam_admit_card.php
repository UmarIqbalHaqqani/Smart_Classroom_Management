<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$admit_cards = WLSM_M_Staff_Examination::get_student_admit_cards( $school_id, $student->ID );
?>
<div class="wlsm-content-area wlsm-section-exams-time-table wlsm-student-exams-time-table">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Download Admit Card', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-exam-admit-card-section">
		<?php
		if ( count( $admit_cards ) ) {
		?>
		<ul class="wlst-st-list wlsm-st-exam-admit-card">
			<?php
			foreach ( $admit_cards as $key => $admit_card ) {
			?>
			<li>
				<span>
					<a class="wlsm-st-print-exam-admit-card" data-exam-admit-card="<?php echo esc_attr( $admit_card->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'st-print-exam-admit-card-' . $admit_card->ID ) ); ?>" href="#" data-message-title="<?php esc_attr_e( 'Print Exam Admit Card', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>"><?php echo esc_html( stripslashes( $admit_card->exam_title ) ); ?></a>
					<span class="wlsm-st-exam-date">
						<?php
							printf(
								/* translators: 1: exam start date, 2: exam end date */
								esc_html__( '(%1$s - %2$s)', 'school-management' ),
								esc_html( WLSM_Config::get_date_text( $admit_card->start_date ) ),
								esc_html( WLSM_Config::get_date_text( $admit_card->end_date ) )
							);
						?>
					</span>
				</span>
			</li>
			<?php
			}
		?>
		</ul>
		<?php
		} else {
		?>
		<div>
			<span class="wlsm-font-medium wlsm-font-bold">
				<?php esc_html_e( 'No admit card found.', 'school-management' ); ?>
			</span>
		</div>
		<?php
		}
		?>
	</div>
</div>
