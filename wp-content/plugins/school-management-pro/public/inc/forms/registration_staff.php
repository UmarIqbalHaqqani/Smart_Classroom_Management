<?php
defined( 'ABSPATH' ) || die();

$school_id = null;

$gender = 'male';

$routes = array();

$school_registration_form_title        = '';
$school_registration_dob               = '';
$school_registration_religion          = '';
$school_registration_caste             = '';
$school_registration_blood_group       = '';
$school_registration_phone             = '';
$school_registration_city              = '';
$school_registration_state             = '';
$school_registration_country           = '';
$school_registration_transport         = '';
$school_registration_parent_detail     = '';
$school_registration_parent_occupation = '';
$school_registration_parent_login      = '';
$school_registration_id_number         = '';

if ( isset( $attr['school_id'] ) ) {
	$school_id = absint( $attr['school_id'] );

	$school = WLSM_M_School::get_active_school( $school_id );
	if ( ! $school ) {
		$invalid_message = esc_html__( 'School not found.', 'school-management' );
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/partials/invalid.php';
	}

	$classes = WLSM_M_Staff_General::fetch_school_classes( $school_id );
} else {
	$school  = null;
	$schools = WLSM_M_School::get_active_schools();

	// Registration settings.
	$settings_registration = false;
}

$gender_list = WLSM_Helper::gender_list();

$nonce_action = 'wlsm-submit-staff-registration';
?>
<div class="wlsm wlsm-grid">
	<div id="wlsm-submit-staff-registration-section">

		<div class="wlsm-header-title wlsm-font-bold wlsm-mb-3">
			<span class="wlsm-border-bottom wlsm-pb-1">
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-submit-staff-registration-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-p-submit-staff-registration">

			<?php if ( ! $school ) { ?>
				<div class="wlsm-form-group wlsm-row">
					<div class="wlsm-col-12">
						<label for="wlsm_school" class="wlsm-form-label wlsm-font-bold">
							<?php esc_html_e( 'School', 'school-management' ); ?>:
						</label>
					</div>
					<div class="wlsm-col-4 wlsm-px-0">
						<select name="school_id" class="wlsm-form-control wlsm_school" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-school-classes' ) ); ?>" data-routes-vehicles-nonce="<?php echo esc_attr( wp_create_nonce( 'get-school-routes-vehicles' ) ); ?>" id="wlsm_school" data-sections="1">
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
					<div class="wlsm-col-12">
						<label class="wlsm-form-label wlsm-font-bold">
							<?php esc_html_e( 'School', 'school-management' ); ?>:
						</label>
					</div>
					<div class="wlsm-col-12 wlsm-px-0">
						<span class="wlsm-font-normal">
							<?php echo esc_html( WLSM_M_School::get_label_text( $school->label ) ); ?>
						</span>
					</div>
				</div>
			<?php } ?>

			<!-- Personal Detail -->
			<div class="wlsm-form-section">
				<div class="wlsm-row">
					<div class="wlsm-col-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Personal Detail', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="wlsm-row">
					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_name" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Name', 'school-management' ); ?>:
						</label>
						<input type="text" name="name" class="wlsm-form-control" id="wlsm_name" placeholder="<?php esc_attr_e( 'Enter name', 'school-management' ); ?>" value="">
					</div>

					<div class="wlsm-form-group wlsm-col-4">
						<label class="wlsm-font-bold wlsm-d-block">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Gender', 'school-management' ); ?>:
						</label>
						<?php
						foreach ( $gender_list as $key => $value ) {
							reset( $gender_list );
							?>
							<div class="wlsm-form-check wlsm-form-check-inline">
								<input class="wlsm-form-check-input" type="radio" name="gender" id="wlsm_gender_<?php echo esc_attr( $value ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php checked( $key, $gender, true ); ?>>
								<label class="wlsm-ml-1 wlsm-form-check-label wlsm-font-bold" for="wlsm_gender_<?php echo esc_attr( $value ); ?>">
									<?php echo esc_html( $value ); ?>
								</label>
							</div>
							<?php
						}
						?>
					</div>

					<div class="wlsm-form-group wlsm-col-4" id="registration_dob">
						<label for="wlsm_date_of_birth" class="wlsm-font-bold">
							<?php esc_html_e( 'Date of Birth', 'school-management' ); ?>:
						</label>
						<input type="text" name="dob" class="wlsm-form-control" id="wlsm_date_of_birth" placeholder="<?php esc_attr_e( 'Enter date of birth', 'school-management' ); ?>" value="">
					</div>


				</div>

				<div class="wlsm-row">
					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_address" class="wlsm-font-bold">
							<?php esc_html_e( 'Address', 'school-management' ); ?>:
						</label>
						<textarea name="address" class="wlsm-form-control" id="wlsm_address" cols="30" rows="3" placeholder="<?php esc_attr_e( 'Enter address', 'school-management' ); ?>"></textarea>
					</div>


					<div class="wlsm-form-group wlsm-col-4" id="registration_phone">
						<label for="wlsm_phone" class="wlsm-font-bold">
							<?php esc_html_e( 'Phone', 'school-management' ); ?>:
						</label>
						<input type="text" name="phone" class="wlsm-form-control" id="wlsm_phone" placeholder="<?php esc_attr_e( 'Enter phone number', 'school-management' ); ?>" value="">
					</div>


					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_email" class="wlsm-font-bold">
							<?php esc_html_e( 'Email', 'school-management' ); ?>:
						</label>
						<input type="email" name="email" class="wlsm-form-control" id="wlsm_email" placeholder="<?php esc_attr_e( 'Enter email address', 'school-management' ); ?>" value="">
					</div>
				</div>
			
			</div>

			<!-- Joining Detail -->
			<div class="wlsm-form-section">
				<div class="wlsm-row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Joining Detail', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="wlsm-row">
					<div class="wlsm-form-group wlsm-col-4" id="registration_state">
						<label for="wlsm_state" class="wlsm-font-bold">
							<?php esc_html_e( 'Joining Date', 'school-management' ); ?>:
						</label>
						<input type="text" name="joining_date" class="wlsm-form-control" id="wlsm_joining_date" placeholder="<?php esc_attr_e( 'Enter joining date', 'school-management' ); ?>" value="">
					</div>



					<!-- <div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_role" class="wlsm-font-bold">
							<?php esc_html_e( 'Role', 'school-management' ); ?>:
						</label>
							<select name="role" class="wlsm-form-control selectpicker" id="wlsm_role" data-live-search="true" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-role-permissions' ) ); ?>">
								<option value=""><?php esc_html_e( 'Select Role', 'school-management' ); ?></option>
								<?php foreach ( $role_list as $key => $value ) { ?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $role_id, true ); ?>>
										<?php echo esc_html( $value->name ); ?>
									</option>
								<?php } ?>
							</select>
					</div> -->
				</div>

				<div class="wlsm-row">
					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_designation" class="wlsm-font-bold">
							<?php esc_html_e( 'Designation', 'school-management' ); ?>:
						</label>
						<input type="text" name="designation" class="wlsm-form-control" id="wlsm_designation" placeholder="<?php esc_attr_e( 'Enter Designation', 'school-management' ); ?>" value="">
					</div>

					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_note" class="wlsm-font-bold">
							<?php esc_html_e( 'Note', 'school-management' ); ?>:
						</label>
						<input type="text" name="note" class="wlsm-form-control" id="wlsm_note" placeholder="<?php esc_attr_e( 'Enter Note', 'school-management' ); ?>" value="">
					</div>
					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_salary" class="wlsm-font-bold">
							<?php esc_html_e( 'Salary', 'school-management' ); ?>:
						</label>
						<input type="text" name="salary" class="wlsm-form-control" id="wlsm_salary" placeholder="<?php esc_attr_e( 'Enter Salary', 'school-management' ); ?>" value="">
					</div>

				</div>
			</div>

			<!-- Class Teacher -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Class Teacher', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="wlsm-row">
					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="class_id" class="wlsm-form-control" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_school_class">
							<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
							<?php
							if ( isset( $classes ) ) {
								foreach ( $classes as $class ) {
									?>
									<option value="<?php echo esc_attr( $class->ID ); ?>">
										<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
									</option>
									<?php
								}
							}
							?>
							</option>
						</select>
					</div>

					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_section" class="wlsm-font-bold">
							<?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="section_id" class="wlsm-form-control" id="wlsm_section">
							<option value=""><?php esc_html_e( 'Select Section', 'school-management' ); ?></option>
						</select>
					</div>
				</div>
			</div>

			<!-- Login Detail -->
			<div class="wlsm-form-section">
				<div class="wlsm-row">
					<div class="wlsm-col-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Login Detail', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="wlsm-row wlsm-student-new-user">
					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_username" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Username', 'school-management' ); ?>:
						</label>
						<input type="text" name="username" class="wlsm-form-control" id="wlsm_username" placeholder="<?php esc_attr_e( 'Enter username', 'school-management' ); ?>">
					</div>

					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_login_email" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Login Email', 'school-management' ); ?>:
						</label>
						<input type="email" name="login_email" class="wlsm-form-control" id="wlsm_login_email" placeholder="<?php esc_attr_e( 'Enter login email', 'school-management' ); ?>">
					</div>

					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_login_password" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Password', 'school-management' ); ?>:
						</label>
						<input type="password" name="password" class="wlsm-form-control" id="wlsm_login_password" placeholder="<?php esc_attr_e( 'Enter password', 'school-management' ); ?>">
					</div>
				</div>
			</div>
	</div>
	<div class="wlsm-border-top wlsm-pt-2 wlsm-mt-1">
		<button class="button wlsm-btn btn btn-primary" type="submit" id="wlsm-submit-staff-registration-btn">
			<?php esc_html_e( 'Submit', 'school-management' ); ?>
		</button>
	</div>

	</form>

</div>


<?php
return ob_get_clean();
