<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to provide a modal for exam start.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    exam-and-quiz-proctoring-with-lms-integration
 * @subpackage exam-and-quiz-proctoring-with-lms-integration/public/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<style>
	.mo-procto-start-exam-modal {
		position: fixed;
		z-index: 99999;
		padding-top: 100px;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		overflow: auto;
		background-color: rgb(0, 0, 0);
		background-color: rgba(0, 0, 0, 0.4);
	}


.mo-procto-start-exam-modal-content {
	background-color: #fefefe;
	margin: auto;
	padding: 20px;
	border: 1px solid #888;
	width: 80%;
	display: flex;
	justify-content: center;
	align-items: center;
	flex-direction: column;
}

.mo-procto-btn-start{
	width:150px !important;
	display: block;
	border-radius: 5px !important;
	height:30px !important;
	padding-top: 5px !important;
	font-size: 15px !important;
}

#mo_procto_start_proctoring_btn {
	vertical-align: unset !important;
	color: unset !important;
	background-color: #0d6efd !important;
	color:#fff !important;
	text-align: center !important;
	cursor:pointer;
	margin-top: 30px;
	text-decoration: none;
}

#mo_procto_browser_container {
	display:flex;
	margin-top:20px;
	gap:10px;
}

#mo_procto_browser{
	flex:1;
	display:flex;
	gap:10px;
	align-items:center;
}
</style>



<div class="mo-procto-start-exam-modal">
	<div class="mo-procto-start-exam-modal-content">
		<h4>miniOrange Secure Exam</h4><br>
		<strong>System Requirements</strong>
		<div id='mo_procto_browser_container'> 
			<div style="flex:1">Browser:</div>
			<div id='mo_procto_browser'><img height="20px" width="20px" src=<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/google_chrome_logo.png' ); ?>>Chrome</div>
		</div>
		<div>
			<a id="mo_procto_start_proctoring_btn" class="mo-procto-btn-start"> Click here to Start </a>
		</div>	
	</div>
</div>

<script>

</script>
