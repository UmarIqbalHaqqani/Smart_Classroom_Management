<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Role.php';

global $wpdb;

$page_url = WLSM_M_Staff_General::get_inquiries_page_url();

$school_id = $current_school['id'];

$inquiry = null;

$nonce_action = 'add-inquiry';

$name           = '';
$phone          = '';
$email          = '';
$message        = '';
$note           = '';
$next_follow_up = '';
$is_active      = 1;
$section_id     = 1;
$student        = '';
$reference      = '';

$class_id = null;
$sections = array();
if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id      = absint( $_GET['id'] );
	$inquiry = WLSM_M_Staff_General::fetch_inquiry( $school_id, $id );

	if ( $inquiry ) {
		$nonce_action = 'edit-inquiry-' . $inquiry->ID;

		$name           = $inquiry->name;
		$phone          = $inquiry->phone;
		$email          = $inquiry->email;
		$message        = $inquiry->message;
		$note           = $inquiry->note;
		$next_follow_up = $inquiry->next_follow_up;
		$is_active      = $inquiry->is_active;

		$class_id   = $inquiry->class_id;
		$section_id = $inquiry->section_id;
		$reference  = $inquiry->reference;
		$sections   = WLSM_M_Staff_Class::fetch_sections( $inquiry->class_school_id );
	}
}

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $inquiry ) {
						esc_html_e( 'Edit Inquiry', 'school-management' );
					} else {
						esc_html_e( 'Add New Inquiry', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-envelope"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-inquiry-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-inquiry">

			<?php if ( $inquiry ) { ?>
				<input type="hidden" name="inquiry_id" value="<?php echo esc_attr( $inquiry->ID ); ?>">
			<?php } ?>

			<!-- Inquiry -->
			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_name" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Name', 'school-management' ); ?>:
						</label>
						<input type="text" name="name" class="form-control" id="wlsm_name" placeholder="<?php esc_attr_e( 'Enter name', 'school-management' ); ?>" value="<?php echo esc_attr( stripslashes( $name ) ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<?php if ( $student ) { ?>
							<div class="ml-2"><?php echo esc_html( WLSM_M_Class::get_label_text( $class_label ) ); ?></div>
						<?php } else { ?>
							<select name="class_id" class="form-control selectpicker" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_class" data-live-search="true">
								<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
								<?php foreach ( $classes as $class ) { ?>
									<option value="<?php echo esc_attr( $class->ID ); ?>" <?php selected( $class->ID, $class_id, true ); ?>>
										<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
									</option>
								<?php } ?>
							</select>
						<?php } ?>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_section" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="section_id" class="form-control selectpicker" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'Select Section', 'school-management' ); ?>">
							<?php foreach ( $sections as $section ) { ?>
								<option value="<?php echo esc_attr( $section->ID ); ?>" <?php selected( $section->ID, $section_id, true ); ?>>
									<?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $section->label ) ); ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_phone" class="wlsm-font-bold">
							<?php esc_html_e( 'Phone', 'school-management' ); ?>:
						</label>
						<input type="text" name="phone" class="form-control" id="wlsm_phone" placeholder="<?php esc_attr_e( 'Enter phone number', 'school-management' ); ?>" value="<?php echo esc_attr( $phone ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_email" class="wlsm-font-bold">
							<?php esc_html_e( 'Email', 'school-management' ); ?>:
						</label>
						<input type="email" name="email" class="form-control" id="wlsm_email" placeholder="<?php esc_attr_e( 'Enter email address', 'school-management' ); ?>" value="<?php echo esc_attr( $email ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_reference" class="wlsm-font-bold">
							<?php esc_html_e( 'Reference', 'school-management' ); ?>:
						</label>
						<input type="text" name="reference" class="form-control" id="wlsm_reference" placeholder="<?php esc_attr_e( 'Enter Reference number', 'school-management' ); ?>" value="<?php echo esc_attr( $reference ); ?>">
					</div>
					<div class="form-group col-md-12">
						<label for="wlsm_message" class="wlsm-font-bold">
							<?php esc_html_e( 'Message', 'school-management' ); ?>:
						</label>
						<textarea name="message" class="form-control" id="wlsm_message" cols="30" rows="4" placeholder="<?php esc_attr_e( 'Enter message', 'school-management' ); ?>"><?php echo esc_html( stripslashes( $message ) ); ?></textarea>
					</div>

					<div class="form-group col-md-6">
						<label for="wlsm_inquiry_next_follow_up" class="wlsm-font-bold">
							<?php esc_html_e( 'Next Follow Up Date', 'school-management' ); ?>:
						</label>
						<input type="text" name="next_follow_up" class="form-control" id="wlsm_inquiry_next_follow_up" placeholder="<?php esc_attr_e( 'Enter next date to follow up', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::get_date_text( $next_follow_up ) ); ?>">
					</div>

					<div class="form-group col-md-6">
						<label for="wlsm_note" class="wlsm-font-bold">
							<?php esc_html_e( 'Note', 'school-management' ); ?>:
						</label>
						<textarea name="note" class="form-control" id="wlsm_note" cols="30" rows="4" placeholder="<?php esc_attr_e( 'Enter note', 'school-management' ); ?>"><?php echo esc_html( ( $note ) ); ?></textarea>
					</div>

				</div>

			</div>

			<!-- Status -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Status', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<div class="form-check form-check-inline">
							<input <?php checked( 1, $is_active, true ); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_active" value="1">
							<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_status_active">
								<?php echo esc_html( WLSM_M_Staff_General::get_inquiry_active_text() ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked( 0, $is_active, true ); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_inactive" value="0">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_status_inactive">
								<?php echo esc_html( WLSM_M_Staff_General::get_inquiry_inactive_text() ); ?>
							</label>
						</div>
					</div>

				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-inquiry-btn">
						<?php
						if ( $inquiry ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Inquiry', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Inquiry', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
