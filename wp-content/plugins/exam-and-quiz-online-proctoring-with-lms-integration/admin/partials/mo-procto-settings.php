<?php
/**
 * View file for admin dashboard.
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
	<h3>Proctoring Settings</h3>
	<hr>

	<form method="post" action="" >
		<?php wp_nonce_field( 'mo-procto-save-settings-nonce' ); ?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row" class="mo-procto-setting-th" style="width: 400px !important;">
						<b>Select your Learning Management System : 
					</th>
					<td class="mo-procto-setting-td">
					<input type="radio" name="mo_procto_select_lms" value="master_study" <?php checked( get_site_option( 'mo_procto_select_lms', 'master_study' ) === 'master_study' ); ?> />&nbsp; &nbsp;MasterStudy LMS &emsp;&emsp;
						<input type="radio" name="mo_procto_select_lms" value="learn_dash"  <?php checked( get_site_option( 'mo_procto_select_lms' ) === 'learn_dash' ); ?> />&nbsp;LearnDash LMS  &emsp;&emsp;
						<input type="radio" name="mo_procto_select_lms"  disabled title="Upgrade to Premium to access this feature"/>&nbsp;Other LMS <img style="padding-bottom: 6px;" src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/logo.webp' ); ?> width="25" height="25" class="d-inline-block align-top"  alt="" title="Upgrade to Premium to access this feature" > &emsp;&emsp;

					</td>
				</tr>				
			</tbody>
		</table>
	<br>
		<div class="nav nav-tabs">
			<button class="mo-procto-nav-link active" id="defaultOpenTab" type="button"  onclick="mo_procto_openTab('procto-session-tab', this)" >Limit Simultaneous Session</button>
			<button class="mo-procto-nav-link" type="button"  onclick="mo_procto_openTab('procto-tabswitch-tab', this)" > Restrict Tab Switching</button>     
			<button class="mo-procto-nav-link" type="button" onclick="mo_procto_openTab('procto-Limitations-tab', this)" >Browser Limitations</button>
			<button class="mo-procto-nav-link" type="button"title="Upgrade to Premium to access this feature" onclick="mo_procto_openTab('procto-monitoring-tab', this)" > Candidate Monitoring &nbsp;<lable> <img src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/logo.webp' ); ?> width="25" height="20" class="d-inline-block align-top"  alt="" title="Upgrade to Premium to access this feature"> </lable></button>

		</div>
</div>
		<div id="procto-session-tab" class="mo-procto-sub-tabcontent"><br>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row" class="mo-procto-setting-th">
							<label>Enable <b>Session Restriction</b> </label>
						</th>
						<td class="mo-procto-setting-td">
						<input type="checkbox" class="mo-procto-switch" id="mo_procto_restrict_session"  name="mo_procto_restrict_session" value="mo_procto_restrict_session" onclick="mo_procto_sessionrestrict_checkbox()"
								<?php
								if ( get_site_option( 'mo_procto_restrict_session' ) ) {
									echo 'checked';}
								?>
								/><label for="mo_procto_restrict_session" class="mo-procto-toggle-button">Toggle</label>
						</td>

					</tr>
					<tr>
						<th scope="row" class="mo-procto-setting-th">
							<label>Set Maximum Simultaneous Sessions Limit</label>
						</th>
						<td class="mo-procto-setting-td">
							<input type="number" id="mo_procto_max_restrict_session" name="mo_procto_max_restrict_session"  
								min="1" value=<?php echo esc_attr( get_site_option( 'mo_procto_restrict_session' ) ); ?>  <?php
								if ( ! get_site_option( 'mo_procto_restrict_session' ) ) {
									echo 'disabled';}
								?>
								>
						</td>
					</tr>
					<tr>
						<th scope="row" class="mo-procto-setting-th">
						Choose an action upon reaching the Limit
						</th>
						<td class="mo-procto-setting-td">
							<input type="radio" id="mo_procto_max_session_action_enable_access" name="mo_procto_max_session_action" value="1"
								<?php
								checked( get_site_option( 'mo_procto_max_limit_action' ) === '1' );
								if ( ! get_site_option( 'mo_procto_restrict_session' ) ) {
									echo 'disabled';
								}
								?>
								/>&nbsp;Allow Access&nbsp;<span class="dashicons dashicons-info-outline" title="Enable the user to log in to the current session while terminating all other sessions."></span>
							&emsp;&emsp13;&emsp;&emsp;
							<input type="radio" id="mo_procto_max_session_action_disable_access" name="mo_procto_max_session_action" value="0"
								<?php
								checked( get_site_option( 'mo_procto_max_limit_action' ) === '0' );
								if ( ! get_site_option( 'mo_procto_restrict_session' ) ) {
									echo 'disabled';
								}
								?>
								/>&nbsp;Disable Access&nbsp;<span class="dashicons dashicons-info-outline" title="Disable the user from logging in until they log out from other devices."></span> <br><br>
						</td>

					</tr>


				</tbody>
			</table>	
		</div>

		<div id="procto-tabswitch-tab" class="mo-procto-sub-tabcontent">
		<br>

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row" class="mo-procto-setting-th">
							<label> Enable Tab Switch Warnings</label>
						</th>
						<td class="mo-procto-setting-td">
							<input type="checkbox" class="mo-procto-switch" id="restrict_tab_switch" name="restrict_tab_switch"
								value="restrict_tab_switch"
								<?php
								if ( get_site_option( 'mo_procto_restrict_tab_switch' ) ) {
									echo 'checked';}
								?>
								/><label for="restrict_tab_switch" class="mo-procto-toggle-button">Toggle</label>
						</td>

					</tr>
					<tr>
						<th scope=" row" class="mo-procto-setting-th">
							<label>Automatic Logout on Exceeding Maximum Tab Switches </label> &nbsp;<span class="dashicons dashicons-info-outline" title="Specify the maximum number of tab switches allowed. Upon reaching this limit, the user will be automatically logged out."></span> <img src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/logo.webp' ); ?> width="25" height="20" class="d-inline-block align-top"  alt="" title="Upgrade to Premium to access this feature">
						</th>
						<td class="mo-procto-setting-td"> 
							<input type="number" id="tab_max_warning" name="tab_max_warning" min="1" disabled
								value="1"
								title="Upgrade to Premium to access this feature">							
						</td>
					</tr>
					<tr>
					<th scope="row" class="mo-procto-setting-th">
						<label>Set Custom Warning Message <img src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/logo.webp' ); ?> width="25" height="20" class="d-inline-block align-top"  alt="" title="Upgrade to Premium to access this feature"> </label>
					</th>
					<td class="mo-procto-setting-td">
						<textarea id="mo_procto_custom_title" name="mo_procto_custom_title" rows="3" cols="50" disabled placeholder="Warning! Tab Switch Restricted." style="font-family: Arial, sans-serif; font-size: 14px; color: #333; background-color: #f7f7f7; border: 1px solid #ccc; padding: 5px;" title="Upgrade to Premium to access this feature"></textarea></td>
				</tr>
					<tr>
						<th scope="row" class="mo-procto-setting-th">
							<label>Set custom title<img src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/logo.webp' ); ?> width="25" height="20" class="d-inline-block align-top"  alt="" title="Upgrade to Premium to access this feature"> </label>
						</th>
						<td class="mo-procto-setting-td">
						<textarea id="mo_procto_custom_title" name="mo_procto_custom_title" rows="3" cols="50" disabled placeholder="MiniOrange Proctored Online Exam" style="font-family: Arial, sans-serif; font-size: 14px; color: #333; background-color: #f7f7f7; border: 1px solid #ccc; padding: 5px;" title="Upgrade to Premium to access this feature"></textarea>
						</td>
					</tr>

							<tr>
						<th scope="row" class="mo-procto-setting-th">
							<label>Warning Message for Disabled Camera or Microphone<img src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/logo.webp' ); ?> width="25" height="20" class="d-inline-block align-top"  alt="" title="Upgrade to Premium to access this feature"> </label>
						</th>
						<td class="mo-procto-setting-td">
						<textarea id="mo_procto_custom_title" name="mo_procto_custom_title" rows="3" cols="50" disabled placeholder="Camera and Microphone permissions are required." style="font-family: Arial, sans-serif; font-size: 14px; color: #333; background-color: #f7f7f7; border: 1px solid #ccc; padding: 5px;" title="Upgrade to Premium to access this feature"></textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="procto-Limitations-tab" class="mo-procto-sub-tabcontent">
		<br>

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row" class="mo-procto-setting-th">
							<label> Restrict Browser Inspection/Developer Tools</label>
						</th>
						<td class="mo-procto-setting-td">  
							<input type="checkbox" class="mo-procto-switch" id="restrict_inspect_browser"  name="restrict_inspect_browser"
								value="restrict_inspect_browser"
								<?php
								if ( get_site_option( 'mo_procto_restrict_inspect_browser' ) ) {
									echo 'checked';}
								?>
								/><label for="restrict_inspect_browser" class="mo-procto-toggle-button">Toggle</label>				
						</td>

					</tr>
					<tr>
						<th scope="row" class="mo-procto-setting-th">
							<label> Restrict Right-Click Functionality</label>
						</th>
						<td class="mo-procto-setting-td">
						<input type="checkbox" class="mo-procto-switch" id="disable_mouse_right_button"  name="disable_mouse_right_button"
								value="disable_mouse_right_button"
								<?php
								if ( get_site_option( 'mo_procto_disable_mouse_right_click' ) ) {
									echo 'checked'; }
								?>
								/><label for="disable_mouse_right_button" class="mo-procto-toggle-button">Toggle</label>					
						</td>
					</tr>
					<th scope="row" class="mo-procto-setting-th">
						<lable>Full-Screen Exam Mode <span class="dashicons dashicons-info-outline" title="This mandates candidates to complete the exam in full-screen mode."></span><img src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/logo.webp' ); ?> width="25" height="20" class="d-inline-block align-top"  alt="" title="Upgrade to Premium to access this feature"> </lable>
								</th>
								<td class="mo-procto-setting-td text-muted">
						<input type="checkbox" class="mo-procto-switch" id="complete_browser_lock" name="complete_browser_lock" value="complete_browser_lock" disabled /><label for="complete_browser_lock" class="mo-procto-toggle-button" title="Upgrade to Premium to access this feature">Toggle</label>
						<br><br>
					</td>
				</tbody>
			</table>
			<br>
		</div>
		<div id="procto-monitoring-tab" class="mo-procto-sub-tabcontent">
			<br>
		<table class="form-table">
			<tbody>
				<tr>
				<th scope="row" class="mo-procto-setting-th">
						<lable>Candidate Video Recording <span class="dashicons dashicons-info-outline" title="Enable video recording during tests for post-test review."></span> <img src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/logo.webp' ); ?> width="25" height="20" class="d-inline-block align-top"  alt="" title="Upgrade to Premium to access this feature"> </lable>
								</th>
								<td class="mo-procto-setting-td text-muted">
						<input type="checkbox" class="mo-procto-switch" id="complete_browser_lock" name="complete_browser_lock" value="complete_browser_lock" disabled /><label for="complete_browser_lock" class="mo-procto-toggle-button" title="Upgrade to Premium to access this feature">Toggle</label> 
						<br><br>
					</td>
				</tr>
				<tr>
				<th scope="row" class="mo-procto-setting-th">
						<lable>Candidate Verification <span class="dashicons dashicons-info-outline" title="Enable candidates to capture face and student ID for verification before starting the exam."></span><img src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/logo.webp' ); ?> width="25" height="20" class="d-inline-block align-top"  alt="" title="Upgrade to Premium to access this feature"> </lable>
								</th>
								<td class="mo-procto-setting-td text-muted">
						<input type="checkbox" class="mo-procto-switch" id="complete_browser_lock" name="complete_browser_lock" value="complete_browser_lock" disabled /><label for="complete_browser_lock" class="mo-procto-toggle-button" title="Upgrade to Premium to access this feature">Toggle</label>
						<br><br>
								</td>
				</tr>
				<tr>
				<th scope="row" class="mo-procto-setting-th">
						<lable>Candidate Face Detection and Recognization <span class="dashicons dashicons-info-outline" title="1. Real-time face detection&#10;2. Multi-face detection&#10;3. Identification using pre-captured images during Live Monitoring."></span><img src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/logo.webp' ); ?> width="25" height="20" class="d-inline-block align-top"  alt="" title="Upgrade to Premium to access this feature"> </lable>
								</th>
								<td class="mo-procto-setting-td text-muted">
						<input type="checkbox" class="mo-procto-switch" id="complete_browser_lock" name="complete_browser_lock" value="complete_browser_lock" disabled /><label for="complete_browser_lock" class="mo-procto-toggle-button" title="Upgrade to Premium to access this feature">Toggle</label>
						<br><br>
								</td>
				</tr>
			</tbody>
		</table>
		</div>
		<div>
		<button class="btn btn-primary " type="submit">Save Changes</button>
	</div>
</form>

