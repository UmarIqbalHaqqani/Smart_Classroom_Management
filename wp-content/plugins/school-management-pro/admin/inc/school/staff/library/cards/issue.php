<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Library.php';

$library_cards_page_url = WLSM_M_Staff_Library::get_library_cards_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$nonce_action = 'issue-library-cards';

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-2 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-id-card"></i>
				<?php esc_html_e( 'Issue Library Cards', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $library_cards_page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-id-card"></i>&nbsp;
					<?php echo esc_html( 'View Library Cards', 'school-management' ); ?>
				</a>
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-issue-library-cards-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="<?php echo esc_attr( 'wlsm-issue-library-cards' ); ?>">

			<!-- Library card number series. -->
			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-12 mb-0">
						<label for="wlsm_roll_numbers" class="wlsm-font-bold">
							<?php esc_html_e( 'Starting Library Card Number & Prefix', 'school-management' ); ?>:
							<br>
							<small>
								<?php esc_html_e( 'For example, enter "LC" and "10001" to create library card numbers - LC10001, LC10002, LC10003 and so on.', 'school-management' ); ?>
							</small>
						</label>
					</div>
					<div class="col-sm-12 col-md-7">
						<div class="row">
							<div class="col-sm-4 col-md-3 mb-1 pr-0 pl-1">
								<input type="text" name="card_number_prefix" class="ml-1 mr-1 form-control" id="wlsm_card_number_prefix" placeholder="<?php esc_attr_e( 'Enter prefix', 'school-management' ); ?>">
							</div>
							<div class="col-sm-8 col-md-9 mb-1 pr-0 pl-1">
								<input type="number" name="staring_card_number" class="ml-1 mr-1 form-control" id="wlsm_staring_card_number" placeholder="<?php esc_attr_e( 'Enter starting library card number', 'school-management' ); ?>">
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="wlsm-form-section">
				<div class="form-row mt-2">
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="class_id" class="form-control selectpicker" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_class" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
							<option value="<?php echo esc_attr( $class->ID ); ?>">
								<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_section" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="section_id" class="form-control selectpicker" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'All Sections', 'school-management' ); ?>" data-all-sections="1">
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_date_issued" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Date Issued', 'school-management' ); ?>:
						</label>
						<input type="text" name="date_issued" class="form-control" id="wlsm_date_issued" placeholder="<?php esc_attr_e( 'Date Issued', 'school-management' ); ?>">
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-sm btn-primary" id="wlsm-manage-library-cards-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'manage-library-cards' ) ); ?>">
						<?php esc_html_e( 'Manage Library Cards', 'school-management' ); ?>
					</button>
				</div>
			</div>

			<div class="wlsm-students-library-cards mt-2"></div>

		</form>
	</div>
</div>
