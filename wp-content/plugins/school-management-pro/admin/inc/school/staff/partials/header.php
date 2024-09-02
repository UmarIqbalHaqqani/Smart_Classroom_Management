<?php
defined( 'ABSPATH' ) || die();
$google_link_show       = get_option('wlsm_google_link_show');
?>
<div class="wlsm-main-header card col">
	<div class="">
		<h1 class="h3 text-center">
			<i class="fas fa-school text-primary"></i>
			<?php echo esc_html( WLSM_M_School::get_label_text( $current_school['name'] ) ); ?>
			<small class="wlsm_text_secondary"><?php echo esc_html( $current_session['label'] ); ?></small>
			<?php if ( $google_link_show ) : ?>
				<?php if ( current_user_can( WLSM_ADMIN_CAPABILITY ) ) { ?>
					<div class="float-right wlsm-android">
						<a target="_blank" href="https://play.google.com/store/apps/details?id=com.infigoschool"><img src="<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/images/android.png' ); ?>"></a>
					</div>
				<?php } ?>
			<?php endif ?>
		</h1>
		<?php
		if ( ! isset( $disallow_session_change ) ) {
			?>
			<div class="text-center wlsm_user_current_session_block">
				<?php if ( $current_session['ID'] ) { ?>
					<label for="wlsm_user_current_session" class="text-dark">
						<?php esc_html_e( 'Current Session: ', 'school-management' ); ?>
					</label>
					<select name="current_session" id="wlsm_user_current_session">
						<?php foreach ( $current_session['sessions'] as $session ) { ?>
							<option <?php selected( $session->ID, $current_session['ID'], true ); ?> value="<?php echo esc_attr( $session->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'set-session-' . $session->ID ) ); ?>">
								<?php echo esc_html( $session->label ); ?>
							</option>
						<?php } ?>
					</select>
				<?php } else { ?>
					<span class="text-danger">
						<?php esc_html_e( 'Default session is not set. Please contact the administrator.', 'school-management' ); ?>
					</span>
				<?php } ?>
			</div>
			<?php
		}
		if ( $restrict_to_section ) {
			?>
			<div class="text-center text-dark mt-2 wlsm-staff-section-detail">
				<?php
				printf(
					wp_kses(
						/* translators: 1: class label, 2: section label */
						__( 'You are teacher of <span class="wlsm-font-bold">Class:</span> %1$s, <span class="wlsm-font-bold">Section:</span> %2$s', 'school-management' ),
						array( 'span' => array( 'class' => array() ) )
					),
					esc_html( WLSM_M_Class::get_label_text( $restrict_to_section_detail->class_label ) ),
					esc_html( WLSM_M_Staff_Class::get_section_label_text( $restrict_to_section_detail->section_label ) )
				);
				?>
			</div>
			<?php
		}
		?>
	</div>
</div>
<?php
if ( ! $current_session['ID'] ) {
	die();
}
