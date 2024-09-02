<?php
/**
 * The admin-side view file for support form.
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
<div id="mo-procto-contact-box">
	<div class="mo-procto-support-button" id="btnContact" onClick="moProctoShowContact();">
		<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/mo_procto_support_logo.png' ); ?>" id="mo-procto-support-logo" />
	</div>

	<div id="mo-procto-frmContact">
		<button id="mo-procto-closeButton" onClick="moProctoCloseContact();"> <span class="dashicons dashicons-dismiss"></span></button>

		<h4>Support</h4>
		<p>Need any help? We are available anytime. Just send us a query so we can help you.</p>
		<?php wp_nonce_field( 'mo-procto-save-settings-nonce' ); ?>
		<input class="mo-procto-support-form" type="email" id="query_email" name="query_email" value="<?php echo esc_attr( wp_get_current_user()->user_email ); ?>" placeholder="Enter your email" required />
		<div>
			<input class="mo-procto-support-form" type="text" name="query_phone" id="query_phone" placeholder="Enter your phone" />
		</div>

		<div>
			<select class="mo-procto-support-form" id="select_quiz">
				<option selected>Select your LMS </option>
				<option value="LearnDash">LearnDash</option>
				<option value="MasterStudy">MasterStudy</option>
				<option value="LifterLMS">LifterLMS</option>
				<option value="Tutor_LMS">Tutor LMS</option>
				<option value="LearnPress">LearnPress</option>
				<option value="other">Other</option>
			</select>
		</div>
		<textarea class="mo-procto-support-form" id="query" name "query" style="height:200px;" placeholder="Write your query here"></textarea>
		<br>
		<input type="checkbox" name="get_config_in_query" class="mo-procto-config-checkbox" value="config_in_query">Send plugin configuration</input>
		<br><br>
		<div id="mo-procto-support-response"></div>
		<button name="mo_procto_send_query" id="mo_procto_send_query" value="Submit Query" class="btn btn-primary">Submit Query</button>
	</div>
</div>

<style>
	#mo-procto-closeButton {
		border: none;
		background: #faf8f8;
		margin-right: -300px;
	}
</style>

<script>
	function moProctoCloseContact() {
		document.getElementById('mo-procto-frmContact').style.display = 'none';
	}
</script>

