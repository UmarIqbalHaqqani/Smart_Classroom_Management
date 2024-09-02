<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_Class::get_events_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$event = NULL;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id    = absint( $_GET['id'] );
	$event = WLSM_M_Staff_Class::fetch_event( $school_id, $id );
}

if ( ! $event ) {
	die;
}

$title = $event->title;
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					printf(
						wp_kses(
							/* translators: %s: event title */
							__( 'Participants: %s', 'school-management' ),
							array(
								'span' => array( 'class' => array() )
							)
						),
						esc_html( WLSM_M_Staff_Class::get_name_text( $title ) )
					);
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-calendar-alt"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-event-participants-table" data-event="<?php echo esc_attr( $event->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'event-' . $event->ID ) ); ?>">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Section', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Roll Number', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Phone', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
			<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/export.php'; ?>
		</div>
	</div>
</div>
