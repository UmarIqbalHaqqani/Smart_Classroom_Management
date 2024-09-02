<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$student_id = $student->ID;

$event_id = $id;

$event = WLSM_M_Staff_Class::fetch_active_event( $school_id, $event_id, $student_id );

if ( ! $event ) {
	die;
}

require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/event.php';
?>
<div class="wlsm-content-area wlsm-section-event">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Event Detail', 'school-management' ); ?>
		</span>
		<span class="wlsm-float-md-right wlsm-font-small">
			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'events' ), $current_page_url ) ); ?>">
				<?php esc_html_e( 'View all Events', 'school-management' ); ?>&nbsp;&rarr;
			</a>
		</span>
	</div>

	<div class="wlsm-st-event-section w-100 wlsm-w-100">
		<div class="wlsm-mb-1">
			<div class="wlsm-event-header">
				<span class="wlsm-event-title wlsm-font-extra-large">
					<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $event_title ) ); ?>
					<br>
					<span class="wlsm-event-date">
						<?php
						printf(
							wp_kses(
								/* translators: %s: event date */
								__( '<span class="wlsm-font-bold">Event Date:</span> %s', 'school-management' ),
								array( 'span' => array( 'class' => array() ) )
							),
							WLSM_Config::get_date_text( $event_date )
						);
						?>
					</span>
				</span>

				<span class="wlsm-join-unjoin-event-box">
					<span class="wlsm-join-unjoin-event">
						<?php if ( ! $student_joined ) { ?>
						<button data-confirm="<?php echo esc_attr( 'Confirm joining this event?'); ?>" data-event="<?php echo esc_attr( $event_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'st-join-event-' . $event_id ) ); ?>" type="button" class="btn btn-primary button wlsm-event-btn wlsm-join-event-btn">
							<?php esc_html_e( 'Join Event', 'school-management' ); ?>
						</button>
						<?php } else { ?>
						<span class="wlsm-joined-message"><?php esc_html_e( 'You have joined this event.', 'school-management' ); ?></span>
						<br>
						<button data-confirm="<?php echo esc_attr( 'Confirm leaving from this event?'); ?>" data-event="<?php echo esc_attr( $event_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'st-unjoin-event-' . $event_id ) ); ?>" type="button" class="btn btn-danger button wlsm-event-btn wlsm-unjoin-event-btn">
							<?php esc_html_e( 'Leave From Event', 'school-management' ); ?>
						</button>
						<?php } ?>
					</span>
				</span>
			</div>

			<?php if ( ! empty ( $image_url ) ) { ?>
			<div class="wlsm-event-image">
				<img src="<?php echo esc_url( $image_url ); ?>" class="img-responsive wlsm-image">
			</div>
			<?php } ?>

			<div class="wlsm-event-description wlsm-mt-1 wlsm-mb-1">
				<?php echo wp_kses_post( stripslashes( $description ) ); ?>
			</div>
		</div>
	</div>
</div>
