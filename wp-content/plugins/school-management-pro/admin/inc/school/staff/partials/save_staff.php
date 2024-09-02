<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Role.php';

$gender_list = WLSM_Helper::gender_list();

$role_list = WLSM_M_Role::get_staff_roles( $school_id );

$permission_list = WLSM_M_Role::get_permissions();

$nonce_action = 'add-' . $role;
$action       = 'wlsm-save-' . $role;

$staff = NULL;

$name                = '';
$gender              = 'male';
$dob                 = '';
$phone               = '';
$email               = '';
$address             = '';
$salary              = '';
$designation         = '';
$qualification       = '';
$note                = '';
$joining_date        = '';
$role_id             = NULL;
$assigned_by_manager = 0;
$permissions         = array();
$username            = '';
$login_email         = '';
$is_active           = 1;

$api_key      = '';
$api_secret   = '';
$redirect_url = '';
$sdk_key      = '';
$sdk_secret   = '';

$sections   = array();
$class_id   = NULL;
$section_id = NULL;
$vehicle_id = NULL;

$staff_role_permissions = array();

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id    = absint( $_GET['id'] );
	$staff = WLSM_M_Staff_General::fetch_staff( $school_id, $role, $id );

	$admin = WLSM_M_Staff_General::get_staff($school_id, $role, $id);
	$user_id  = $admin->user_id;
	
	$api_key      = get_the_author_meta( 'api_key', $user_id ) ;
	$api_secret   = get_the_author_meta( 'api_secret', $user_id ) ;
	$redirect_url = get_the_author_meta( 'redirect_url', $user_id ) ;
	$sdk_key      = get_the_author_meta( 'sdk_key', $user_id ) ;
	$sdk_secret   = get_the_author_meta( 'sdk_secret', $user_id ) ;

	if ( $staff ) {
		$nonce_action = 'edit-' . $role .  '-' . $staff->ID;

		$name                = $staff->name;
		$gender              = $staff->gender;
		$dob                 = $staff->dob;
		$phone               = $staff->phone;
		$email               = $staff->email;
		$address             = $staff->address;
		$salary              = $staff->salary;
		$designation         = $staff->designation;
		$qualification       = $staff->qualification;
		$note                = $staff->note;
		$joining_date        = $staff->joining_date;
		$role_id             = $staff->role_id;
		$assigned_by_manager = $staff->assigned_by_manager;
		$permissions         = $staff->permissions;
		$username            = $staff->username;
		$login_email         = $staff->login_email;
		$is_active           = $staff->is_active;

		$class_id   = $staff->class_id;
		$section_id = $staff->section_id;
		$vehicle_id = $staff->vehicle_id;

		$sections = WLSM_M_Staff_Class::fetch_sections( $staff->class_school_id );

		if ( $role_id ) {
			$staff_role_exists = WLSM_M_Staff_General::fetch_role( $school_id, $role_id );
			if ( $staff_role_exists ) {
				$role_permissions = $staff_role_exists->permissions;
				if ( is_serialized( $role_permissions ) ) {
					$staff_role_permissions = unserialize( $role_permissions );
				}
			}
		}
	}
}

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );

$vehicles = WLSM_M_Staff_Transport::fetch_vehicles( $school_id );

$permissions = WLSM_M_Role::get_role_permissions( $role, $permissions );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $staff ) {
						/* translators: 1: role name, 2: staff name */
						printf( esc_html__( 'Edit %1$s: %2$s', 'school-management' ), esc_html( WLSM_M_Role::get_role_text( $role ) ), esc_html( $name ) );
					} else {
						/* translators: %s: role name */
						printf( esc_html__( 'Add New %s', 'school-management' ), esc_html( WLSM_M_Role::get_role_text( $role ) ) );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-user-shield"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-staff-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">

			<?php if ( $staff ) { ?>
			<input type="hidden" name="staff_id" value="<?php echo esc_attr( $staff->ID ); ?>">
			<?php } ?>

			<!-- Personal Detail -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Personal Detail', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_name" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Name', 'school-management' ); ?>:
						</label>
						<input type="text" name="name" class="form-control" id="wlsm_name" placeholder="<?php esc_attr_e( 'Enter name', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $name ) ); ?>">
					</div>

					<div class="form-group col-md-4">
						<label class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Gender', 'school-management' ); ?>:
						</label>
						<br>
						<?php
						foreach ( $gender_list as $key => $value ) {
							reset( $gender_list );
						?>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="gender" id="wlsm_gender_<?php echo esc_attr( $value ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php checked( $key, $gender, true ); ?>>
							<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_gender_<?php echo esc_attr( $value ); ?>">
								<?php echo esc_html( $value ); ?>
							</label>
						</div>
						<?php
						}
						?>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_date_of_birth" class="wlsm-font-bold">
							<?php esc_html_e( 'Date of Birth', 'school-management' ); ?>:
						</label>
						<input type="text" name="dob" class="form-control" id="wlsm_date_of_birth" placeholder="<?php esc_attr_e( 'Enter date of birth', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::get_date_text( $dob ) ); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_address" class="wlsm-font-bold">
							<?php esc_html_e( 'Address', 'school-management' ); ?>:
						</label>
						<textarea name="address" class="form-control" id="wlsm_address" cols="30" rows="3" placeholder="<?php esc_attr_e( 'Enter address', 'school-management' ); ?>"><?php echo esc_html( $address ); ?></textarea>
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
				</div>
			</div>

			<!-- Joining Detail -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Joining Detail', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="wlsm_joining_date" class="wlsm-font-bold">
								<?php esc_html_e( 'Joining Date', 'school-management' ); ?>:
							</label>
							<input type="text" name="joining_date" class="form-control" id="wlsm_joining_date" placeholder="<?php esc_attr_e( 'Enter joining date', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::get_date_text( $joining_date ) ); ?>">
						</div>

						<div class="form-group">
							<label for="wlsm_designation" class="wlsm-font-bold">
								<?php esc_html_e( 'Salary', 'school-management' ); ?>:
							</label>
							<input type="number" step="any" min="0" name="salary" class="form-control" id="wlsm_salary" placeholder="<?php esc_attr_e( 'Enter salary', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::sanitize_money( $salary ) ); ?>">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="wlsm_role" class="wlsm-font-bold">
								<?php esc_html_e( 'Role', 'school-management' ); ?>:
							</label>
						<?php if ( WLSM_M_Role::get_admin_key() === $role ) { ?>
							<br>
							<div><?php echo esc_html( WLSM_M_Role::get_admin_text() ); ?></div>
							<small class="text-dark"><em><?php esc_html_e( 'School administrators bypass all permissions.', 'school-management' ); ?></em></small>
						<?php } else { ?>
							<select name="role" class="form-control selectpicker" id="wlsm_role" data-live-search="true" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-role-permissions' ) ); ?>">
								<option value=""><?php esc_html_e( 'Select Role', 'school-management' ); ?></option>
								<?php foreach ( $role_list as $key => $value ) { ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $role_id, true ); ?>>
									<?php echo esc_html( $value->name ); ?>
								</option>
								<?php } ?>
							</select>
						<?php } ?>
						</div>

						<div class="form-group">
							<label for="wlsm_designation" class="wlsm-font-bold">
								<?php esc_html_e( 'Designation', 'school-management' ); ?>:
							</label>
							<input type="text" name="designation" class="form-control" id="wlsm_designation" placeholder="<?php esc_attr_e( 'Enter designation', 'school-management' ); ?>" value="<?php echo esc_attr( $designation ); ?>">
						</div>			
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_note" class="wlsm-font-bold">
							<?php esc_html_e( 'Note / Description', 'school-management' ); ?>:
						</label>
						<textarea name="note" class="form-control" id="wlsm_note" cols="30" rows="4" placeholder="<?php esc_attr_e( 'Enter note or description', 'school-management' ); ?>"><?php echo esc_html( $note ); ?></textarea>
					</div>
					<div class="form-group  col-md-4">
							<label for="wlsm_qualification" class="wlsm-font-bold">
								<?php esc_html_e( 'Qualification', 'school-management' ); ?>:
							</label>
							<input type="text" name="qualification" class="form-control" id="wlsm_qualification" placeholder="<?php esc_attr_e( 'Enter qualification', 'school-management' ); ?>" value="<?php echo esc_attr( $qualification ); ?>">
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

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="class_id" class="form-control selectpicker" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_class" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
							<option <?php selected( $class_id, $class->ID, true ); ?> value="<?php echo esc_attr( $class->ID ); ?>">
								<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_section" class="wlsm-font-bold">
							<?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="section_id" class="form-control selectpicker" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'Select Section', 'school-management' ); ?>">
							<?php foreach ( $sections as $section ) { ?>
							<option <?php selected( $section_id, $section->ID, true ); ?> value="<?php echo esc_attr( $section->ID ); ?>">
								<?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $section->label ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>

			<!-- Bus In-charge -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Bus In-charge', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_is_vehicle_incharge" class="wlsm-font-bold">
							<?php esc_html_e( 'Is Bus In-charge', 'school-management' ); ?>:
						</label>
						<br>
						<div class="form-check form-check-inline">
							<input <?php checked( (bool) $vehicle_id, true, true ); ?> class="form-check-input" type="radio" name="is_vehicle_incharge" id="is_vehicle_incharge_yes" value="1">
							<label class="ml-1 form-check-label text-primary font-weight-bold" for="is_vehicle_incharge_yes">
								<?php esc_html_e( 'Yes', 'school-management' ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked( (bool) $vehicle_id, false, true ); ?> class="form-check-input" type="radio" name="is_vehicle_incharge" id="is_vehicle_incharge_no" value="0">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="is_vehicle_incharge_no">
								<?php esc_html_e( 'No', 'school-management' ); ?>
							</label>
						</div>
					</div>

					<div class="form-group col-md-4 wlsm-vehicle-incharge">
						<label for="wlsm_vehicle" class="wlsm-font-bold">
							<?php esc_html_e( 'Bus / Vehicle Incharge', 'school-management' ); ?>:
						</label>
						<select name="vehicle_id" class="form-control selectpicker" id="wlsm_vehicle" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Vehicle', 'school-management' ); ?></option>
							<?php foreach ( $vehicles as $vehicle ) { ?>
							<option <?php selected( $vehicle_id, $vehicle->ID, true ); ?> value="<?php echo esc_attr( $vehicle->ID ); ?>">
								<?php echo esc_html( $vehicle->vehicle_number ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>

			<!-- Permissions -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Permissions', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<?php foreach ( $permission_list as $key => $value ) { ?>
					<div class="form-group col-md-4">
						<input <?php if ( ( WLSM_M_Role::get_admin_key() === $role ) || ( in_array( $key, $permissions ) && in_array( $key ,$staff_role_permissions ) ) ) { echo 'disabled'; } ?> <?php checked( in_array( $key, $permissions ), true, true ); ?> class="form-check-input mt-1" type="checkbox" name="permission[]" id="wlsm_staff_permission_<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>">
						&nbsp;
						<label class="ml-4 mb-1 form-check-label wlsm-font-bold" for="wlsm_staff_permission_<?php echo esc_attr( $key ); ?>">
							<?php echo esc_html( $value ); ?>
						</label>
					</div>
					<?php } ?>
				</div>
			</div>

			<!-- Login Detail -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Login Detail', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<?php if ( ! $assigned_by_manager ) { ?>
						<div class="form-check form-check-inline">
							<input <?php checked( false, (bool) $username, true ); ?> class="form-check-input" type="radio" name="staff_new_or_existing" id="wlsm_staff_disallow_login" value="">
							<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_staff_disallow_login">
								<?php esc_html_e( 'Disallow Login?', 'school-management' ); ?>
							</label>
						</div>
						<?php } ?>
						<div class="form-check form-check-inline">
							<input <?php checked( true, (bool) $username, true ); ?> class="form-check-input" type="radio" name="staff_new_or_existing" id="wlsm_staff_existing_user" value="existing_user">
							<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_staff_existing_user">
								<?php esc_html_e( 'Existing User?', 'school-management' ); ?>
							</label>
						</div>
						<?php if ( ! $assigned_by_manager ) { ?>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="staff_new_or_existing" id="wlsm_staff_new_user" value="new_user">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_staff_new_user">
								<?php esc_html_e( 'New User?', 'school-management' ); ?>
							</label>
						</div>
						<?php } ?>
					</div>
				</div>

				<div class="form-row wlsm-staff-existing-user">
					<div class="form-group col-md-4">
						<label for="wlsm_existing_username" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Existing Username', 'school-management' ); ?>:
							<?php if ( $username ) { ?>
								<small>
									<em class="text-secondary">
									<?php esc_html_e( 'Usernames cannot be changed.', 'school-management' ); ?>
									</em>
								</small>
							<?php } ?>
						</label>
						<input type="text" name="existing_username" class="form-control" id="wlsm_existing_username" placeholder="<?php esc_attr_e( 'Enter existing username', 'school-management' ); ?>" value="<?php echo esc_attr( $username ); ?>" <?php if ( $username ) { echo 'readonly'; } ?>>
					</div>

					<?php if ( $username ) { ?>
					<div class="form-group col-md-4">
						<label for="wlsm_new_login_email" class="wlsm-font-bold">
							<?php esc_html_e( 'Login Email', 'school-management' ); ?>:
						</label>
						<input type="email" name="new_login_email" class="form-control" id="wlsm_new_login_email" placeholder="<?php esc_attr_e( 'Enter login email', 'school-management' ); ?>" value="<?php echo esc_attr( $login_email ); ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_new_login_password" class="wlsm-font-bold">
							<?php esc_html_e( 'Password', 'school-management' ); ?>:
						</label>
						<input type="password" name="new_password" class="form-control" id="wlsm_new_login_password" placeholder="<?php esc_attr_e( 'Enter password', 'school-management' ); ?>">
					</div>
					<?php } ?>
				</div>

				<div class="form-row wlsm-staff-new-user">
					<div class="form-group col-md-4">
						<label for="wlsm_username" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Username', 'school-management' ); ?>:
						</label>
						<input type="text" name="username" class="form-control" id="wlsm_username" placeholder="<?php esc_attr_e( 'Enter username', 'school-management' ); ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_login_email" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Login Email', 'school-management' ); ?>:
						</label>
						<input type="email" name="login_email" class="form-control" id="wlsm_login_email" placeholder="<?php esc_attr_e( 'Enter login email', 'school-management' ); ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_login_password" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Password', 'school-management' ); ?>:
						</label>
						<input type="password" name="password" class="form-control" id="wlsm_login_password" placeholder="<?php esc_attr_e( 'Enter password', 'school-management' ); ?>">
					</div>
				</div>
			</div>

			<!-- Zoom Settings  -->
			<div class="wlsm-form-section">

			<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Zoom OAuth 2.0 For Live Classes ', 'school-management' ); ?>
							<p>
							<?php esc_html_e( 'To access the API Key and Secret, Create a oauth App on the Marketplace. After providing basic information about your app, locate your API Key and Secret in the App Credentials page.', 'school-management' ); ?>
							<a target="_blank" href="https://developers.zoom.us/docs/integrations/"><?php esc_html_e( 'Click here for more information', 'school-management' ); ?></a>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-4">
							<label for="wlsm_api_key" class="wlsm-font-bold">
							 <?php esc_html_e( 'Zoom Client ID', 'school-management' ); ?>:
							
							</label>
							<input type="text" name="api_key" class="form-control" id="wlsm_api_key" placeholder="<?php esc_attr_e( 'Enter API Key', 'school-management' ); ?>" value="<?php 
							if($api_key) { 
								echo $api_key;
							} 
							?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_api_secret" class="wlsm-font-bold">
						 <?php esc_html_e( 'Zoom Client Secret', 'school-management' ); ?>:
						</label>
						<input type="text" name="api_secret" class="form-control" id="wlsm_api_secret" placeholder="<?php esc_attr_e( 'Enter API Secret', 'school-management' ); ?>" value="<?php if ($api_secret) {
							echo $api_secret;
						} ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_redirect_url" class="wlsm-font-bold">
						 <?php esc_html_e( 'Redirect URL for OAuth', 'school-management' ); ?>:
						</label>
						<input type="text" name="redirect_url" class="form-control" id="wlsm_redirect_url" placeholder="<?php esc_attr_e( 'Enter URL', 'school-management' ); ?>" value="<?php if ($redirect_url) {
							echo $redirect_url;
						} ?>">
					</div>
				</div>


				<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Zoom API sdk ', 'school-management' ); ?>
							<p>
							<?php esc_html_e( 'Zoom api sdk key is used only if you have android application.', 'school-management' ); ?>
							<a target="_blank" href="https://marketplace.zoom.us/user/build"><?php esc_html_e( 'Click here for more information', 'school-management' ); ?></a>
							</p>
						</div>
					</div>
				<div class="row">
					<div class="form-group col-md-4">
							<label for="wlsm_sdk_key" class="wlsm-font-bold">
							 <?php esc_html_e( 'Zoom SDK Key', 'school-management' ); ?>:
							
							</label>
							<input type="text" name="sdk_key" class="form-control" id="wlsm_sdk_key" placeholder="<?php esc_attr_e( 'Enter SDK Key', 'school-management' ); ?>" value="<?php 
							if($sdk_key) { 
								echo $sdk_key;
							} 
							?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_sdk_secret" class="wlsm-font-bold">
						 <?php esc_html_e( 'Zoom SDK Secret', 'school-management' ); ?>:
						</label>
						<input type="text" name="sdk_secret" class="form-control" id="wlsm_sdk_secret" placeholder="<?php esc_attr_e( 'Enter SDK Secret', 'school-management' ); ?>" value="<?php if ($sdk_secret) {
							echo $sdk_secret;
						} ?>">
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
								<?php echo esc_html( WLSM_M_Staff_Class::get_active_text() ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked( 0, $is_active, true ); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_inactive" value="0">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_status_inactive">
								<?php echo esc_html( WLSM_M_Staff_Class::get_inactive_text() ); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-staff-btn">
						<?php
						if ( $staff ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							/* translators: %s: role name */
							printf( esc_html__( 'Update %s', 'school-management' ), esc_html( WLSM_M_Role::get_role_text( $role ) ) );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							/* translators: %s: role name */
							printf( esc_html__( 'Add New %s', 'school-management' ), esc_html( WLSM_M_Role::get_role_text( $role ) ) );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
