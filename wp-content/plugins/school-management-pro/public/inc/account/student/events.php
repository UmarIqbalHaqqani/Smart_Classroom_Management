<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$events_per_page = WLSM_M::events_per_page();

$events_query = WLSM_M::events_query();

$events_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$events_query}) AS combined_table", $student->ID, $school_id ) );

$events_page = isset( $_GET['events_page'] ) ? absint( $_GET['events_page'] ) : 1;

$events_page_offset = ( $events_page * $events_per_page ) - $events_per_page;

$events = $wpdb->get_results( $wpdb->prepare( $events_query . ' ORDER BY ev.ID DESC LIMIT %d, %d', $student->ID, $school_id, $events_page_offset, $events_per_page ) );
?>
<div class="wlsm-content-area wlsm-section-events wlsm-student-events">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Events', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-events-section">
		<?php
		if ( count( $events ) ) {
		?>
		<ul class="wlst-st-list wlsm-st-events">
			<?php
			foreach ( $events as $key => $event ) {
			?>
			<li>
				<span>
					<span class="wlsm-font-bold">
						<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $event->title ) ); ?>
					</span>
					<span class="wlsm-st-event-date">
						<?php echo esc_html( WLSM_Config::get_date_text( $event->event_date ) ); ?>
					</span>
					&nbsp;
					<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'events', 'id' => $event->ID ), $current_page_url ) ); ?>" class="wlsm-font-bold">
						<?php
						if ( $event->student_joined ) {
							esc_html_e( 'View / Leave from event', 'school-management' );
						} else {
							esc_html_e( 'View / Join this event', 'school-management' );
						}
						?>
					</a>
				</span>
			</li>
			<?php
			}
		?>
		</ul>
		<div class="wlsm-text-right wlsm-font-medium wlsm-font-bold wlsm-mt-2">
		<?php
		echo paginate_links(
			array(
				'base'      => add_query_arg( 'events_page', '%#%' ),
				'format'    => '',
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'total'     => ceil( $events_total / $events_per_page ),
				'current'   => $events_page,
			)
		);
		?>
		</div>
		<?php
		} else {
		?>
		<div>
			<span class="wlsm-font-medium wlsm-font-bold">
				<?php esc_html_e( 'There is no event.', 'school-management' ); ?>
			</span>
		</div>
		<?php
		}
		?>
	</div>
</div>
