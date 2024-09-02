<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_General::get_transfer_student_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$nonce_action = 'transfer-student-' . $session_id;

$transfer_to_schools = WLSM_M_Staff_General::get_transfer_to_schools( $school_id );

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-2 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-sign-in-alt"></i>
				<?php esc_html_e( 'Transfer Student', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-users"></i>&nbsp;
					<?php echo esc_html( 'View Students Transferred', 'school-management' ); ?>
				</a>
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-transfer-student-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="<?php echo esc_attr( 'wlsm-transfer-student' ); ?>">

			<!-- Select Student -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Student', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<?php esc_html_e( 'Class', 'school-management' ); ?>:
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
							<?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="section_id" class="form-control selectpicker wlsm_section" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'All Sections', 'school-management' ); ?>" data-all-sections="1" data-only-active="0" data-fetch-students="1" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-section-students' ) ); ?>">
						</select>
					</div>
					<div class="form-group col-md-4 wlsm-student-select-block">
						<label for="wlsm_student" class="wlsm-font-bold" data-single-label="<?php esc_attr_e( 'Student', 'school-management' ); ?>">
							<?php esc_html_e( 'Student', 'school-management' ); ?>:
						</label>
						<select name="student" class="form-control selectpicker" id="wlsm_student" data-live-search="true" data-none-selected-text="<?php esc_attr_e( 'Select', 'school-management' ); ?>">
						</select>
					</div>
				</div>
			</div>

			<!-- New School -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Transfer to School', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_school" class="wlsm-font-bold">
							<?php esc_html_e( 'School', 'school-management' ); ?>:
						</label>
						<select name="transfer_to_school" class="form-control selectpicker wlsm_school" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-school-classes' ) ); ?>" id="wlsm_school" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select School', 'school-management' ); ?></option>
							<?php foreach ( $transfer_to_schools as $school ) { ?>
							<option value="<?php echo esc_attr( $school->ID ); ?>">
								<?php echo esc_html( WLSM_M_School::get_label_text( $school->label ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_school_class" class="wlsm-font-bold">
							<?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="transfer_to_class" class="form-control selectpicker wlsm_school_class" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_school_class" data-live-search="true" title="<?php esc_attr_e( 'Select Class', 'school-management' ); ?>">
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_school_class_section" class="wlsm-font-bold">
							<?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="transfer_to_section" class="form-control selectpicker wlsm_school_class_section" id="wlsm_school_class_section" data-live-search="true" title="<?php esc_attr_e( 'Select Section', 'school-management' ); ?>">
						</select>
					</div>
				</div>
			</div>

			<!-- Add Note -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Add Note', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="wlsm_note" class="wlsm-font-bold">
							<?php esc_html_e( 'Note', 'school-management' ); ?>:
						</label>
						<textarea name="note" class="form-control" id="wlsm_note" cols="30" rows="2" placeholder="<?php esc_attr_e( 'Enter note', 'school-management' ); ?>"></textarea>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-sm btn-primary" id="wlsm-transfer-student-btn" data-message-title="<?php esc_attr_e( 'Confirm Transfer!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'Are you sure to transfer this student?', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Transfer', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>">
						<?php esc_html_e( 'Transfer Student', 'school-management' ); ?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
