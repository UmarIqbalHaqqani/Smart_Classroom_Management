<?php
/**
 * The Require Free page structure
 *
 * @package LoginPress Pro
 */

?>
<!-- Style for activate Page -->
<style media="screen">

	/*=============================================>>>>>
	= Loginpress installation =
	===============================================>>>>>*/
	.loginpress-main-container{
		font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
	}
	.loginpress-main-container *{
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
	}
	.loginpress-plugin-info{
		text-align: center;
		padding: 5% 0;
		font-size: 30px;
		color: #23282d;
	}
	.toplevel_page_loginpress-settings #wpwrap{
		background-color: #f1f5fe;
	}
	.toplevel_page_loginpress-settings .loginpress-main-container  .loginpress-plugin-info{
		font-size: 22px;
		line-height: 32px;
		color: #516885;
		font-family: "Poppins", sans-serif;
		font-weight: 600;
		margin: 0;
	}
	.loginpress-installation{
		background-color: #fff;
		border: 2px solid #D2DDF2;
		-webkit-border-radius: 8px;
		-moz-border-radius: 8px;
		-ms-border-radius: 8px;
		-o-border-radius: 8px;
		border-radius: 8px;
		max-width: 528px;
		min-height: 365px;
		text-align: center;
		margin: 0 auto;
		padding: 30px 30px 35px 30px;
		display: flex;
	}
	.loginpress-install{
		display: flex;
		align-items: center;
		flex-direction: column;
		min-height: 305px;
		width: 428px;
		margin: auto;
		max-width: 100%;
		justify-content: center;
	}
	.loginpress-install img{
		margin: 0 0 20px;
	}
	.loginpress-install p{
		font-size: 17px;
		font-weight: 400;
		line-height: 26px;
		color: #5C7697;
		font-family: "Poppins", sans-serif;
		margin:0 0 15px;
	}
	.loginpress-install .loginpress-copyright{
		font-size: 16px;
		font-weight: 600;
		margin-bottom: 30px;
	}
	.loginpress-install p:last-of-type{
		margin-bottom: 30px;
	}
	.loginpress-install .loginpress-copyright a{
		color: #F6366A;
		font-weight: 600;
		text-decoration: underline;
		text-underline-position: under;
		text-decoration-style: dashed;
	}
	.loginpress-install .loginpress-copyright a:hover{
		text-decoration: none;
	}
	.loginpress-btn{
		border: 0;
		padding: 20px 30px 20px 30px;
		background-color: #5C7697;
		text-decoration: none;
		color: #fff;
		font-size: 20px;
		line-height: 24px;
		font-weight: 500;
		font-family: "Poppins", sans-serif;
		border-radius: 5px;
		transition: all 0.3s;
		display: inline-block;
		max-width: max-content;
		width: 100%;
		cursor: pointer;
	}
	.loginpress-btn:hover{
		background-color: #2B3D54;
	}
	.loginpress-logo-container{
		position: relative;
		width: 185px;
		height: 185px;
		text-align: center;
		line-height: 185px;
		margin: 0 auto;
	}
	.loginpress-logo-container svg{
		position: absolute;
		left: 0;
		top: 0;
	}
	.loginpress-logo-container img{
		vertical-align: middle;
	}
	.loader-path {
		stroke-dasharray: 150,200;
		stroke-dashoffset: -10;
		-webkit-animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
		animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
		stroke-linecap: round;
	}
	.activating p{
		font-size: 20px;
	}
	.activated p{
		font-size: 20px;
		color: #00c853;
	}
	@-webkit-keyframes rotate {
		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}

	@keyframes rotate {
		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
	@-webkit-keyframes dash {
		0% {
			stroke-dasharray: 1,200;
			stroke-dashoffset: 0;
		}
		50% {
			stroke-dasharray: 89,200;
			stroke-dashoffset: -35;
		}
		100% {
			stroke-dasharray: 89,200;
			stroke-dashoffset: -124;
		}
	}
	@keyframes dash {
		0% {
			stroke-dasharray: 1,200;
			stroke-dashoffset: 0;
		}
		50% {
			stroke-dasharray: 89,200;
			stroke-dashoffset: -35;
		}
		100% {
			stroke-dasharray: 89,200;
			stroke-dashoffset: -124;
		}
	}
	@-webkit-keyframes color {
		0% {
			stroke: #d8d8d8;
		}
		40% {
			stroke: #d8d8d8;
		}
		66% {
			stroke: #d8d8d8;
		}
		80%, 90% {
			stroke: #d8d8d8;
		}
	}
	@keyframes color {
		0% {
			stroke: #d8d8d8;
		}
		40% {
			stroke: #d8d8d8;
		}
		66% {
			stroke: #d8d8d8;
		}
		80%, 90% {
			stroke: #d8d8d8;
		}
	}
	.circle-loader {
		margin: 0 0 30px 0;
		border: 2px solid rgba(0, 0, 0, 0.2);
		border-left-color: #00c853;
		animation-name: loader-spin;
		animation-duration: 1s;
		animation-iteration-count: infinite;
		animation-timing-function: linear;
		position: relative;
		display: inline-block;
		vertical-align: top;
	}
	.circle-loader, .circle-loader:after {
		border-radius: 50%;
		width: 148px;
		height: 148px;
	}
	.load-complete {
		-webkit-animation: none;
		animation: none;
		border-color: #00c853;
		transition: border 500ms ease-out;
	}
	.checkmark {
		display: none;
	}
	.checkmark.draw:after {
		animation-duration: 800ms;
		animation-timing-function: ease;
		animation-name: checkmark;
		transform: scaleX(-1) rotate(135deg);
	}
	.checkmark:after {
		opacity: 1;
		height: 4em;
		width: 2em;
		transform-origin: left top;
		border-right: 2px solid #00c853;
		border-top: 2px solid #00c853;
		content: '';
		left: 42px;
		top: 70px;
		position: absolute;
	}
	@keyframes loader-spin {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}
	@keyframes checkmark {
		0% {
			height: 0;
			width: 0;
			opacity: 1;
		}
		20% {
			height: 0;
			width: 2em;
			opacity: 1;
		}
		40% {
			height: 4em;
			width: 2em;
			opacity: 1;
		}
		100% {
			height: 4em;
			width: 2em;
			opacity: 1;
		}
	}
	/*= End of Loginpress installation =*/
	/*=============================================<<<<<*/
</style>
<div class="loginpress-main-container">
	<p class="loginpress-plugin-info"><?php esc_html_e( 'LoginPress - Rebranding your boring WordPress Login pages', 'loginpress-pro' ); ?></p>
	<form action="#" method="post" class="loginpress-installation">
		<div id="loginpressInstallingFree" class="loginpress-install activating" style="display:none;">
			<div class="loginpress-logo-container">
				<img src="<?php echo esc_url( plugins_url( '../assets/img/loginpress-logo2.png', __FILE__ ) ); ?>" alt="LoginPress">
				<svg class="circular-loader"viewBox="25 25 50 50" >
				<circle class="loader-path" cx="50" cy="50" r="20" fill="none" stroke="#d8d8d8" stroke-width="1" />
				</svg>
			</div>
			<p><?php esc_html_e( 'Downloading LoginPress...', 'loginpress-pro' ); ?></p>
		</div>
		<div id="loginpressActivatingFree" class="loginpress-install activating" style="display:none;">
			<div class="loginpress-logo-container">
				<img src="<?php echo esc_url( plugins_url( '../assets/img/loginpress-logo2.png', __FILE__ ) ); ?>" alt="LoginPress">
				<svg class="circular-loader"viewBox="25 25 50 50" >
				<circle class="loader-path" cx="50" cy="50" r="20" fill="none" stroke="#d8d8d8" stroke-width="1" />
				</svg>
			</div>
			<p><?php esc_html_e( 'Activating LoginPress...', 'loginpress-pro' ); ?></p>
		</div>
		<!-- .loginpress-install activating-->
		<div id="loginpressActivatedFree" class="loginpress-install activated" style="display:none">
			<div class="circle-loader">
				<div class="checkmark draw"></div>
			</div>
			<p><?php esc_html_e( 'LoginPress Activated.', 'loginpress-pro' ); ?></p>
		</div>
		<!-- .loginpress-install activated-->
		<?php
		if ( ! file_exists( WP_PLUGIN_DIR . '/loginpress/loginpress.php' ) ) {

			add_action( 'admin_notices', 'lp_install_free' );
			?>
			<div id="loginpressInstallFree" class="loginpress-install">
				<img src="<?php echo esc_url( plugins_url( '../assets/img/loginpress-logo.png', __FILE__ ) ); ?>" alt="LoginPress">
				<?php echo sprintf( /* Translators: Pro Essential */ __( '%1$sI am innovating WordPress login page. I will help you to customize your boring login page into a stylish login landing page.%2$s%1$sLoginPress (Free) is essential for Pro version. %3$sJust click the install button and enjoy the Pro Features.%2$s%4$sCreated by %5$sWPBrigade%6$s%2$s', 'loginpress-pro' ), '<p>', '</p>', '<br />', '<p class="loginpress-copyright">', '<a href="http://wpbrigade.com">', '</a>' ); ?>
				<button type="submit"  class="loginpress-btn"><?php esc_html_e( 'Install', 'loginpress-pro' ); ?></button>
			</div>
			<!-- .loginpress-install -->
			<?php
			return;
		}

		if ( ! class_exists( 'LoginPress' ) ) {

			add_action( 'admin_notices', 'lp_activate_free_plugin' );
			?>
			<div id="loginpressActiveFree" class="loginpress-install active">
				<img src="<?php echo esc_url( plugins_url( '../assets/img/loginpress-logo.png', __FILE__ ) ); ?>" alt="LoginPress">
				<p><?php echo sprintf( /* Translators: Pro Essential */ esc_html__( 'LoginPress (Free) is essential for Pro version. %1$sJust click the Activate button and enjoy the Pro Features.', 'loginpress-pro' ), '<br />' ); ?></p>
				<button type="submit" href="#" class="loginpress-btn"><?php esc_html_e( 'Activate', 'loginpress-pro' ); ?></button>
			</div>
			<!-- .loginpress-install active-->
			<?php
			return;
		}
		?>
	</form>
</div>
