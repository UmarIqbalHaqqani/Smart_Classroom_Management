<?php
defined( 'ABSPATH' ) || die();

$action_for = 'st';
if ( isset( $change_action ) ) {
	$action_for = $set_action_for;
}

$student_id = $student->ID;

$exam_results = WLSM_M_Staff_Examination::get_student_published_exam_results( $school_id, $student_id );
?>
<div class="wlsm-content-area wlsm-section-exams-time-table wlsm-student-exams-time-table">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'View Exam Results', 'school-management' ); ?>
		</span>
		<span class="wlsm-st-details-list-value">
		<a class="text-primary wlsm-result-subject-wise" data-nonce="<?php echo esc_attr( wp_create_nonce( 'result-subject-wise-' . $student_id ) ); ?>" data-student="<?php echo esc_attr( $student_id ); ?>" href="#" data-message-title="<?php echo esc_attr__( 'Subject-wise Results', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>"><?php esc_html_e( 'Overall', 'school-management' ); ?></a>
		</span>
	</div>

	<div class="wlsm-st-exams-results-section">
		<?php
		if ( count( $exam_results ) ) {
		?>
		<ul class="wlst-st-list wlsm-st-exam-results">
			<?php
			foreach ( $exam_results as $key => $value ) {
			?>
			<li>
				<span>
					<span class="wlsm-font-bold"><?php echo esc_html( stripslashes( $value->exam_title ) ); ?></span>
					<span class="wlsm-st-exam-date">
						<?php
							printf(
								/* translators: 1: exam start date, 2: exam end date */
								esc_html__( '(%1$s - %2$s)', 'school-management' ),
								esc_html( WLSM_Config::get_date_text( $value->start_date ) ),
								esc_html( WLSM_Config::get_date_text( $value->end_date ) )
							);
						?>
					</span>
					&nbsp;
					<a class="wlsm-<?php echo esc_attr( $action_for ); ?>-print-exam-results" data-exam-results="<?php echo esc_attr( $value->ID ); ?>" data-student="<?php echo esc_attr( $student_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( $action_for . '-print-exam-results-' . $value->ID ) ); ?>" href="#" data-message-title="<?php esc_attr_e( 'Print Exam Result', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>"><?php esc_attr_e( 'View', 'school-management' ); ?></a>
					<a href="<?php echo esc_url($value->answer_key); ?>" style="color: green;"> Download Answer Key</a>
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
				<?php esc_html_e( 'No exam result found.', 'school-management' ); ?>
			</span>
		</div>
		<?php
		}
		?>
	</div>

	<?php if ( count( $exam_results ) > 1 ) { ?>
	<div class="wlsm-st-main-title wlsm-mt-3">
		<span>
		<?php esc_html_e( 'Overall Result', 'school-management' ); ?>
		</span>
	</div>

	<a class="wlsm-st-print-results-assessment" data-nonce="<?php echo esc_attr( wp_create_nonce( 'st-print-results-assessment-' . $student_id ) ); ?>" data-student="<?php echo esc_attr( $student_id ); ?>" href="#" data-message-title="<?php echo esc_attr__( 'Overall Result', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>">
		<?php esc_html_e( 'View Overall Result', 'school-management' ); ?>
	</a>

	<div class="wlsm-st-main-title wlsm-mt-3">
		<span>
		<?php esc_html_e( 'Subject-wise Result', 'school-management' ); ?>
		</span>
	</div>

	<a class="wlsm-st-print-results-subject-wise" data-nonce="<?php echo esc_attr( wp_create_nonce( 'st-print-results-subject-wise-' . $student_id ) ); ?>" data-student="<?php echo esc_attr( $student_id ); ?>" href="#" data-message-title="<?php echo esc_attr__( 'Subject-wise Result', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>">
		<?php esc_html_e( 'View Subject-wise Result', 'school-management' ); ?>
	</a>
	<?php } ?>
</div>
