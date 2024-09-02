<?php
/**
 * Provide a admin-facing view for the plugin
 *
 * This file is used to provide a view for detailed report tab.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    exam-and-quiz-proctoring-with-lms-integration
 * @subpackage exam-and-quiz-proctoring-with-lms-integration/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="container">
	<h3>Live Monitoring</h3><hr><br>
		<div class='mo-procto-stream-header'>
				<div class="mo-procto-quiz-select">
					<div>Select Exam for monitoring : </div>
					<select name="mo_procto_select_quiz_live_stream"  id="mo_procto_select_quiz_live_stream">
						<option selected>Select Exam</option>
						<?php
						foreach ( $all_quizzes as $quiz ) {
							echo "<option value='" . esc_attr( $quiz->ID ) . "'>" . esc_html( $quiz->post_title ) . '</option>';
						}
						?>
					</select>
				</div>
				<div class="mo-procto-search">
					<input type="text" name="search" id="mo-procto-video-search" placeholder="search..">
				</div>
				<div class="mo-procto-resize-video">
					Video Size : 
					<button id='mo-procto-small' class="mo-procto-control-btn">Small</button>
					<button id='mo-procto-medium' class="mo-procto-control-btn">Medium</button>
					<button id='mo-procto-large' class="mo-procto-control-btn">Large</button>
				</div>
		</div>


<div id='streams' class="mo-procto-live-streams">
	<div class="mo-procto-empty-msg">
		Oops! Can't find candidates.
	</div>
</div>
</div>
