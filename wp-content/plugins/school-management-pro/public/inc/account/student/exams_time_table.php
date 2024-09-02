<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$class_school_id = $student->class_school_id;

$exams = WLSM_M_Staff_Examination::get_class_school_exams_time_table( $school_id, $class_school_id );
?>
<div class="wlsm-content-area wlsm-section-exams-time-table wlsm-student-exams-time-table">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Exam Time Table', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-exams-time-table-section">
		<?php
		if ( count( $exams ) ) {
		?>
		<ul class="wlst-st-list wlsm-st-exams-time-table">
			<?php
			foreach ( $exams as $key => $exam ) {
			?>
			<li>
				<span>
					<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'exams-time-table', 'id' => $exam->ID ), $current_page_url ) ); ?>"><?php echo esc_html( stripslashes( $exam->exam_title ) ); ?></a>
					<span class="wlsm-st-exam-date">
						<?php
							printf(
								/* translators: 1: exam start date, 2: exam end date */
								esc_html__( '(%1$s - %2$s)', 'school-management' ),
								esc_html( WLSM_Config::get_date_text( $exam->start_date ) ),
								esc_html( WLSM_Config::get_date_text( $exam->end_date ) )
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
				<?php esc_html_e( 'No exam found.', 'school-management' ); ?>
			</span>
		</div>
		<?php
		}
		?>
	</div>
</div>
