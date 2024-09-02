<?php
/**
 * Provide a admin-facing view for the plugin
 *
 * This file is used to provide a view for admin dashboard.
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
<div >
	<nav class="navbar navbar-expand-lg navbar-light bg-light" id="mo-procto-top-nav">
	<img style="margin-top: 20px; margin-left:20px;" src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/miniorange_logo.png' ); ?> width="70" height="70" class="d-inline-block align-top" alt=""> 
		<a class="navbar-brand" href="#" style="margin-left:10px; font-size:25px;color: #fff; margin-top:30px;"> miniOrange Proctoring Solution - ProctoPress</a>
		<div class="ms-auto" style="margin-right:15px;">
		<span class="navbar-text" style="float:right;">
		<button id="mo-procto-upgradeButton" class="mo-procto-nav-button" style="color:#fff;">Upgrade</button>

</span>
		<span class="navbar-text" style="float:right;">
				<button class="mo-procto-nav-button" style="color:#fff;" onclick="moProctoShowContact();">Contact Us</button>	
		</span>

		</div>
	</nav> 
</div>
<br>
<div class="row" style="margin-right: unset !important;">
	<div class="col-2" id="mo-procto-side-nav">
		<button class="mo-procto-tablink active" id="defaultOpenPage" onclick="mo_procto_openPage('procto-setting', this)">Configuration</button>
		<button class="mo-procto-tablink" onclick="mo_procto_openPage('procto-detailed', this)">Reports</button>
		<button class="mo-procto-tablink" onclick="mo_procto_openPage('procto-live-streams', this)">Live Monitoring</button>
<hr>
<button style="margin-left: 20px; padding-left:10px; padding-right:10px; padding-bottom:10px;" class="btn btn-primary" onclick="window.open('https://plugins.miniorange.com/how-to-setup-wordpress-online-exam-proctoring-plugin-for-lms', '_blank')">Documentation</button>


	</div>
	<div class="col-10">
		<div id="procto-setting" class="mo-procto-tabcontent">
			<?php
			require_once plugin_dir_path( __FILE__ ) . 'mo-procto-settings.php';
			?>

		</div>
		<div id="procto-detailed" class="mo-procto-tabcontent">
			<?php require_once plugin_dir_path( __FILE__ ) . 'mo-procto-detail-report.php'; ?>
		</div>
		<div id="procto-live-streams" class="mo-procto-tabcontent">
			<?php
			$current_lms = new LMSFactory();
			$current_lms = $current_lms->selectedlms( get_site_option( 'mo_procto_select_lms' ) );
			if ( $current_lms ) {
				$all_quizzes = $current_lms->mo_procto_get_lms_posts();
			} else {
				$all_quizzes = get_posts(
					array(
						'post_type'   => 'stm-quizzes',
						'numberposts' => -1,
					)
				);
			}
			require_once plugin_dir_path( __FILE__ ) . 'mo-procto-live-streams.php';
			wp_enqueue_script( 'mo-procto-admin-streams', plugin_dir_url( dirname( __FILE__ ) ) . 'js/mo-procto-admin-streams.min.js', array( 'jquery' ), PROCTORING_FOR_LMS_VERSION, false );
			?>
		</div>
	</div>
	<?php require_once plugin_dir_path( __FILE__ ) . 'mo-procto-support.php'; ?>
</div>
