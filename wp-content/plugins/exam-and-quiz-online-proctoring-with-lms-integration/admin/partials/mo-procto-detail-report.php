<?php
/**
 * Provides a admin-facing view for the plugin
 *
 * This file is used to provide a view for detailed report tab.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    exam-and-quiz-online-proctoring-with-lms-integration
 * @subpackage exam-and-quiz-online-proctoring-with-lms-integration/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class = "container">
<h3>Reports</h3>
<hr><br>
	<h4>Detailed Report <img style="margin-top: 1px;" src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/logo.webp' ); ?> width="25" height="25" class="d-inline-block align-top"  alt="" title="Upgrade to Premium to access this feature"> 
	</h4>
	<table class="form-table" title="Upgrade to Premium to access this feature">
		<tbody>
			<tr>
				<th scope="row" class="mo-procto-setting-th text-muted">Generate Detailed report : 
				</th>
				<td class="text-muted">
					<button class="btn btn-outline-secondary dropdown-toggle" type="button" disabled>
						Select Quiz/Exam
					</button>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="mo-procto-empty-msg">
		Please select a quiz.
	</div>
</div>
