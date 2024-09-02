

<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Class.php';

if ( isset( $attr['session_id'] ) ) {
	$session_id = absint( $attr['session_id'] );
} else {
	$session_id = get_option( 'wlsm_current_session' );
}

$school_id = NULL;
if ( isset( $attr['school_id'] ) ) {
	$school_id = absint( $attr['school_id'] );

	$school = WLSM_M_School::get_active_school( $school_id );
	if ( ! $school ) {
		$invalid_message = esc_html__( 'School not found.', 'school-management' );
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/partials/invalid.php';
	}

	$classes = WLSM_M_Staff_General::fetch_school_classes( $school_id );

} else {
	$school  = NULL;
	$schools = WLSM_M_School::get_active_schools();
}

$sessions = WLSM_M_Session::fetch_sessions();
defined( 'ABSPATH' ) || die();


$nonce_action = 'get-invoice-history';
?>
<!-- <div class="wlsm">
	<div id="wlsm-get-invoice-history-section">
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-get-invoice-history-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-p-get-invoice_history">
			<?php
			if ( ! $school ) {
			?>
			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label for="wlsm_school_invoice_history" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'School', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<select name="school_id" class="wlsm-form-control wlsm_school_invoice_history" id="wlsm_school_invoice_history" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-school-certificates' ) ); ?>">
						<option value=""><?php esc_html_e( 'Select School', 'school-management' ); ?></option>
						<?php foreach ( $schools as $value ) { ?>
						<option value="<?php echo esc_attr( $value->ID ); ?>">
							<?php echo esc_html( WLSM_M_School::get_label_text( $value->label ) ); ?>
						</option>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php
			} else {
			?>
			<input type="hidden" name="school_id" value="<?php echo esc_attr( $school_id ); ?>" id="wlsm_school_invoice_history">
			<?php
			}
			?>
			<div class="wlsm-form-group wlsm-row">
				<div class="wlsm-col-4">
					<label for="wlsm_username" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'Student Name', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<input type="text" name="username" class="wlsm-form-control" id="wlsm_username" placeholder="<?php esc_attr_e( 'Enter Student name', 'school-management' ); ?>">
				</div>
			</div>

			<div class="wlsm-form-group wlsm-row">
				<div class="wlsm-col-4">
					<label for="wlsm_wlsm_admission_number" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'Student Admission Number', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<input type="text" name="admission_number" class="wlsm-form-control" id="wlsm_admission number" placeholder="<?php esc_attr_e( 'Admission Number', 'school-management' ); ?>">
				</div>
			</div>

			<div class="wlsm-border-top wlsm-pt-2 wlsm-mt-1">
				<button class="button wlsm-btn btn btn-primary" type="submit" id="wlsm-get-invoice-history-btn">
					<?php esc_html_e( 'Get History', 'school-management' ); ?>
				</button>
			</div>

		</form>

		<div class="wlsm-shortcode-entity">
			<div class="wlsm-invoice-history"></div>
		</div>

	</div>
</div> -->

<div class="wlsm">
	<div id="wlsm-get-pending-invoices-history-section">

		<div class="wlsm-header-title wlsm-font-bold wlsm-mb-3">
			<span class="wlsm-border-bottom wlsm-pb-1">
				<?php esc_html_e( 'Invoice History', 'school-management' ); ?>
			</span>
		</div>

		<?php $nonce = wp_create_nonce( $nonce_action ); ?>
		<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

		<input type="hidden" name="action" value="wlsm-p-get-pending-invoices-history">

		<?php if ( ! $school ) { ?>
		<div class="wlsm-form-group wlsm-row">
			<div class="wlsm-col-4">
				<label for="wlsm_school" class="wlsm-form-label wlsm-font-bold">
					<?php esc_html_e( 'School', 'school-management' ); ?>:
				</label>
			</div>
			<div class="wlsm-col-8">
				<select name="school_id" class="wlsm-form-control wlsm_school" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-school-classes' ) ); ?>" id="wlsm_school" data-live-search="true">
					<option value=""><?php esc_html_e( 'Select School', 'school-management' ); ?></option>
					<?php foreach ( $schools as $value ) { ?>
					<option value="<?php echo esc_attr( $value->ID ); ?>">
						<?php echo esc_html( WLSM_M_School::get_label_text( $value->label ) ); ?>
					</option>
					<?php } ?>
				</select>
			</div>
		</div>
		<?php } else { ?>
		<input type="hidden" name="school_id" value="<?php echo esc_attr( $school_id ); ?>" id="wlsm_school">
		<div class="wlsm-form-group wlsm-row wlsm-mb-2">
			<div class="wlsm-col-4">
				<label class="wlsm-form-label wlsm-font-bold">
					<?php esc_html_e( 'School', 'school-management' ); ?>:
				</label>
			</div>
			<div class="wlsm-col-8">
				<span class="wlsm-font-normal">
				<?php echo esc_html( WLSM_M_School::get_label_text( $school->label ) ); ?>
				</span>
			</div>
		</div>
		<?php } ?>

		<div class="wlsm-form-group wlsm-row">
			<div class="wlsm-col-4">
				<label for="wlsm_session" class="wlsm-form-label wlsm-font-bold">
					<?php esc_html_e( 'Session From', 'school-management' ); ?>:
				</label>
			</div>
			<div class="wlsm-col-8">
				<select name="session_id_from" class="wlsm-form-control" id="wlsm_session_from">
					<?php
					if ( isset( $sessions ) ) {
						foreach ( $sessions as $session ) {
						?>
						<option <?php selected( $session_id, $session->ID, true ); ?> value="<?php echo esc_attr( $session->ID ); ?>">
							<?php echo esc_html( WLSM_M_Session::get_label_text( $session->label ) ); ?>
						</option>
						<?php
						}
					}
					?>
				</select>
			</div>
		</div>

		<div class="wlsm-form-group wlsm-row">
			<div class="wlsm-col-4">
				<label for="wlsm_session" class="wlsm-form-label wlsm-font-bold">
					<?php esc_html_e( 'Session To', 'school-management' ); ?>:
				</label>
			</div>
			<div class="wlsm-col-8">
				<select name="session_id_to" class="wlsm-form-control" id="wlsm_session_to">
					<?php
					if ( isset( $sessions ) ) {
						foreach ( $sessions as $session ) {
						?>
						<option <?php selected( $session_id, $session->ID, true ); ?> value="<?php echo esc_attr( $session->ID ); ?>">
							<?php echo esc_html( WLSM_M_Session::get_label_text( $session->label ) ); ?>
						</option>
						<?php
						}
					}
					?>
				</select>
			</div>
		</div>

		<div class="wlsm-border-top wlsm-pt-2 wlsm-mt-1">
			<button class="button wlsm-btn btn btn-primary" type="button" id="wlsm-get-pending-invoices-history-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-pending-invoices-history' ) ); ?>">
				<?php esc_html_e( 'Search', 'school-management' ); ?>
			</button>
		</div>

		<div class="wlsm-students-with-pending-invoices wlsm-mt-2"></div>

	</div>
</div>
<?php
return ob_get_clean();
