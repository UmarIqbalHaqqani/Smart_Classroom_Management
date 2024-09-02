<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Transport.php';

$school_id = null;

$gender = 'male';
$survey = 'google';
$medium = 'english';

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
$school_registration_medium			   = '';
$school_registration_fees 			   = '';
$school_registration_survey			   = '';


if (isset($attr['school_id'])) {
	$school_id = absint($attr['school_id']);

	$school = WLSM_M_School::get_active_school($school_id);
	if (!$school) {
		$invalid_message = esc_html__('School not found.', 'school-management');
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/partials/invalid.php';
	}

	$classes = WLSM_M_Staff_General::fetch_school_classes($school_id);

	$routes_vehicles = WLSM_M_Staff_Transport::fetch_routes_vehicles($school_id);

	$routes = array();
	foreach ($routes_vehicles as $route_vehicle) {
		if (array_key_exists($route_vehicle->route_id, $routes)) {
			array_push(
				$routes[$route_vehicle->route_id]['vehicles'],
				array(
					'vehicle_number' => $route_vehicle->vehicle_number,
					'ID'             => $route_vehicle->ID,
				)
			);
		} else {
			$routes[$route_vehicle->route_id] = array(
				'route_name' => $route_vehicle->route_name,
				'vehicles'   => array(
					array(
						'vehicle_number' => $route_vehicle->vehicle_number,
						'ID'             => $route_vehicle->ID,
					),
				),
			);
		}
	}

	// Registration settings.
	$settings_registration                 = WLSM_M_Setting::get_settings_registration($school_id);
	$school_registration_form_title        = $settings_registration['form_title'];
	$school_registration_dob               = $settings_registration['dob'];
	$school_registration_religion          = $settings_registration['religion'];
	$school_registration_caste             = $settings_registration['caste'];
	$school_registration_blood_group       = $settings_registration['blood_group'];
	$school_registration_phone             = $settings_registration['phone'];
	$school_registration_city              = $settings_registration['city'];
	$school_registration_state             = $settings_registration['state'];
	$school_registration_country           = $settings_registration['country'];
	$school_registration_transport         = $settings_registration['transport'];
	$school_registration_parent_detail     = $settings_registration['parent_detail'];
	$school_registration_parent_login      = $settings_registration['parent_login'];
	$school_registration_id_number         = $settings_registration['id_number'];
	$school_registration_survey            = $settings_registration['survey'];
	$school_registration_fees              = $settings_registration['fees'];
	$school_registration_medium            = $settings_registration['medium'];
	$school_registration_parent_occupation = $settings_registration['parent_occupation'];

	$settings_registration = true;
} else {
	$school  = null;
	$schools = WLSM_M_School::get_active_schools();

	// Registration settings.
	$settings_registration = false;
}

$gender_list 	  = WLSM_Helper::gender_list();
$survey_list      = WLSM_Helper::survey_list();
$blood_group_list = WLSM_Helper::blood_group_list();

$medium_list  = WLSM_Helper::medium_list();

$nonce_action = 'wlsm-submit-registration';
?>
<div class="wlsm wlsm-grid">
	<div id="wlsm-submit-registration-section">

		<?php
		if ($settings_registration && $school_registration_form_title) {
		?>
			<div class="wlsm-header-title wlsm-font-bold wlsm-mb-3">
				<span class="wlsm-border-bottom wlsm-pb-1">
					<?php echo esc_html($school_registration_form_title); ?>
				</span>
			</div>
		<?php
		} else {
		?>
			<div class="wlsm-header-title wlsm-font-bold wlsm-mb-3">
				<span class="wlsm-border-bottom wlsm-pb-1">
					<?php esc_html_e('Online Registration', 'school-management'); ?>
				</span>
			</div>
		<?php
		}
		?>
		<div class="wlsm-header-title wlsm-font-bold wlsm-mb-3">
			<span class="wlsm-border-bottom wlsm-pb-1">
			</span>
		</div>

		<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-submit-registration-form">

			<?php $nonce = wp_create_nonce($nonce_action); ?>
			<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

			<input type="hidden" name="action" value="wlsm-p-submit-registration">

			<?php if (!$school) { ?>
				<div class="wlsm-form-group wlsm-row">
					<div class="wlsm-col-12">
						<label for="wlsm_school" class="wlsm-form-label wlsm-font-bold">
							<?php esc_html_e('School', 'school-management'); ?>:
						</label>
					</div>
					<div class="wlsm-col-4 wlsm-px-0">
						<select name="school_id" class="wlsm-form-control wlsm_school" data-nonce="<?php echo esc_attr(wp_create_nonce('get-school-classes')); ?>" data-routes-vehicles-nonce="<?php echo esc_attr(wp_create_nonce('get-school-routes-vehicles')); ?>" id="wlsm_school" data-sections="1">
							<option value=""><?php esc_html_e('Select School', 'school-management'); ?></option>
							<?php foreach ($schools as $value) { ?>
								<option value="<?php echo esc_attr($value->ID); ?>">
									<?php echo esc_html(WLSM_M_School::get_label_text($value->label)); ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>
			<?php } else { ?>
				<input type="hidden" name="school_id" value="<?php echo esc_attr($school_id); ?>" id="wlsm_school">
				<div class="wlsm-form-group wlsm-row wlsm-mb-2">
					<div class="wlsm-col-12">
						<label class="wlsm-form-label wlsm-font-bold">
							<?php esc_html_e('School', 'school-management'); ?>:
						</label>
					</div>
					<div class="wlsm-col-12 wlsm-px-0">
						<span class="wlsm-font-normal">
							<?php echo esc_html(WLSM_M_School::get_label_text($school->label)); ?>
						</span>
					</div>
				</div>
			<?php } ?>

			<!-- Personal Detail -->
			<div class="wlsm-form-section">
				<div class="wlsm-row">
					<div class="wlsm-col-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e('Personal Detail', 'school-management'); ?>
						</div>
					</div>
				</div>

				<div class="wlsm-row">
					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_name" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Student Name', 'school-management'); ?>:
						</label>
						<input type="text" name="name" class="wlsm-form-control" id="wlsm_name" placeholder="<?php esc_attr_e('Enter student name', 'school-management'); ?>" value="">
					</div>

					<div class="wlsm-form-group wlsm-col-4">
						<label class="wlsm-font-bold wlsm-d-block">
							<span class="wlsm-important">*</span> <?php esc_html_e('Gender', 'school-management'); ?>:
						</label>
						<?php
						foreach ($gender_list as $key => $value) {
							reset($gender_list);
						?>
							<div class="wlsm-form-check wlsm-form-check-inline">
								<input class="wlsm-form-check-input" type="radio" name="gender" id="wlsm_gender_<?php echo esc_attr($value); ?>" value="<?php echo esc_attr($key); ?>" <?php checked($key, $gender, true); ?>>
								<label class="wlsm-ml-1 wlsm-form-check-label wlsm-font-bold" for="wlsm_gender_<?php echo esc_attr($value); ?>">
									<?php echo esc_html($value); ?>
								</label>
							</div>
						<?php
						}
						?>
					</div>
					<?php if ($school_registration_dob or empty($school_id)) : ?>
						<div class="wlsm-form-group wlsm-col-4" id="registration_dob">
							<label for="wlsm_date_of_birth" class="wlsm-font-bold">
								<?php esc_html_e('Date of Birth', 'school-management'); ?>:
							</label>
							<input type="text" name="dob" class="wlsm-form-control" id="wlsm_date_of_birth" placeholder="<?php esc_attr_e('Enter date of birth', 'school-management'); ?>" value="">
						</div>
					<?php endif ?>

				</div>

				<div class="wlsm-row">

					<?php if ($school_registration_religion or empty($school_id)) : ?>
						<div class="wlsm-form-group wlsm-col-4" id="registration_religion">
							<label for="wlsm_religion" class="wlsm-font-bold">
								<?php esc_html_e('Religion', 'school-management'); ?>:
							</label>
							<input type="text" name="religion" class="wlsm-form-control" id="wlsm_religion" placeholder="<?php esc_attr_e('Enter religion', 'school-management'); ?>" value="">
						</div>
					<?php endif ?>

					<?php if ($school_registration_caste or empty($school_id)) : ?>
						<div class="wlsm-form-group wlsm-col-4" id="registration_caste">
							<label for="wlsm_caste" class="wlsm-font-bold">
								<?php esc_html_e('Caste', 'school-management'); ?>:
							</label>
							<input type="text" name="caste" class="wlsm-form-control" id="wlsm_caste" placeholder="<?php esc_attr_e('Enter caste', 'school-management'); ?>" value="">
						</div>
					<?php endif ?>

					<?php if ($school_registration_blood_group or empty($school_id)) : ?>
						<div class="wlsm-form-group wlsm-col-4" id="registration_blood_group">
							<label for="wlsm_blood_group" class="wlsm-font-bold">
								<?php esc_html_e('Blood Group', 'school-management'); ?>:
							</label>
							<select name="blood_group" class="wlsm-form-control selectpicker" id="wlsm_blood_group" data-live-search="true">
								<option value=""><?php esc_html_e('Select Blood Group', 'school-management'); ?></option>
								<?php foreach ($blood_group_list as $key => $value) { ?>
									<option value="<?php echo esc_attr($key); ?>">
										<?php echo esc_html($value); ?>
									</option>
								<?php } ?>
							</select>
						</div>
					<?php endif ?>
				</div>

				<div class="wlsm-row">
					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_address" class="wlsm-font-bold">
							<?php esc_html_e('Address', 'school-management'); ?>:
						</label>
						<textarea name="address" class="wlsm-form-control" id="wlsm_address" cols="30" rows="3" placeholder="<?php esc_attr_e('Enter student address', 'school-management'); ?>"></textarea>
					</div>

					<?php if ($school_registration_phone or empty($school_id)) : ?>
						<div class="wlsm-form-group wlsm-col-4" id="registration_phone">
							<label for="wlsm_phone" class="wlsm-font-bold">
								<?php esc_html_e('Phone', 'school-management'); ?>:
							</label>
							<input type="text" name="phone" class="wlsm-form-control" id="wlsm_phone" placeholder="<?php esc_attr_e('Enter student phone number', 'school-management'); ?>" value="">
						</div>
					<?php endif ?>

					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_email" class="wlsm-font-bold">
							<?php esc_html_e('Email', 'school-management'); ?>:
						</label>
						<input type="email" name="email" class="wlsm-form-control" id="wlsm_email" placeholder="<?php esc_attr_e('Enter student email address', 'school-management'); ?>" value="">
					</div>
				</div>

				<div class="wlsm-row">
					<?php if ($school_registration_phone or empty($school_id)) : ?>
						<div class="wlsm-form-group wlsm-col-4" id="registration_city">
							<label for="wlsm_city" class="wlsm-font-bold">
								<?php esc_html_e('City', 'school-management'); ?>:
							</label>
							<input type="text" name="city" class="wlsm-form-control" id="wlsm_city" placeholder="<?php esc_attr_e('Enter city', 'school-management'); ?>" value="">
						</div>
					<?php endif ?>

					<?php if ($school_registration_state or empty($school_id)) : ?>
						<div class="wlsm-form-group wlsm-col-4" id="registration_state">
							<label for="wlsm_state" class="wlsm-font-bold">
								<?php esc_html_e('State', 'school-management'); ?>:
							</label>
							<input type="text" name="state" class="wlsm-form-control" id="wlsm_state" placeholder="<?php esc_attr_e('Enter state', 'school-management'); ?>" value="">
						</div>
					<?php endif ?>

					<?php if ($school_registration_country or empty($school_id)) : ?>
						<div class="wlsm-form-group wlsm-col-4" id="registration_country">
							<label for="wlsm_country" class="wlsm-font-bold">
								<?php esc_html_e('Country', 'school-management'); ?>:
							</label>
							<input type="text" name="country" class="wlsm-form-control" id="wlsm_country" placeholder="<?php esc_attr_e('Enter country', 'school-management'); ?>" value="">
						</div>
					<?php endif ?>
				</div>
				<div class="wlsm-row">
					<?php if ($school_registration_medium or empty($school_id)) : ?>
						<div class="wlsm-form-group wlsm-col-4" id="registration_medium">
							<label for="wlsm_medium" class="wlsm-font-bold">
								<?php esc_html_e('Medium', 'school-management'); ?>:
							</label>
							<select name="medium" class="wlsm-form-control selectpicker" id="wlsm_medium" data-live-search="true">
								<option value=""><?php esc_html_e('Select Medium', 'school-management'); ?></option>
								<?php foreach ($medium_list as $key => $value) { ?>
									<option value="<?php echo esc_attr($key); ?>">
										<?php echo esc_html($value); ?>
									</option>
								<?php } ?>
							</select>
						</div>
					<?php endif ?>

					<div class="wlsm-form-group wlsm-col-4">
						<div class="wlsm-photo-box wlsm-mt-2">
							<div class="wlsm-photo-section">
								<label for="wlsm_photo" class="wlsm-font-bold">
									<?php esc_html_e('Upload Photo', 'school-management'); ?>:
								</label>
								<div class="custom-file mb-3">
									<input type="file" class="custom-file-input" id="wlsm_photo" name="photo">
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if ($school_registration_id_number or empty($school_id)) : ?>
					<div class="wlsm-row" id="registration_id">
						<div class="wlsm-form-group wlsm-col-4">
							<label for="wlsm_id_number" class="wlsm-font-bold">
								<?php esc_html_e('ID Number', 'school-management'); ?>:
							</label>
							<input type="text" name="id_number" class="wlsm-form-control" id="wlsm_id_number" placeholder="<?php esc_attr_e('Enter ID number', 'school-management'); ?>" value="">
						</div>
						<div class="wlsm-form-group wlsm-col-4">
							<div class="wlsm-id-proof-box wlsm-mt-2">
								<div class="wlsm-id-proof-section">
									<label for="wlsm_id_proof" class="wlsm-font-bold">
										<?php esc_html_e('Upload ID Proof', 'school-management'); ?>:
									</label>
									<div class="custom-file mb-3">
										<input type="file" class="custom-file-input" id="wlsm_id_proof" name="id_proof">
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>

			</div>

			<!-- Admission Detail -->
			<div class="wlsm-form-section">
				<div class="wlsm-row">
					<div class="wlsm-col-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e('Admission Detail', 'school-management'); ?>
						</div>
					</div>
				</div>

				<div class="wlsm-row">
					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_school_class" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Class', 'school-management'); ?>:
						</label>
						<select name="class_id" class="wlsm-form-control wlsm_school_class wlsm_get_class_fees wlsm_school_class_subject wlsm_school_class_activity " data-nonce="<?php echo esc_attr(wp_create_nonce('get-class-sections')); ?>" id="">
							<option value=""><?php esc_html_e('Select Class', 'school-management'); ?></option>
							<?php
							if (isset($classes)) {
								foreach ($classes as $class) {
							?>
									<option value="<?php echo esc_attr($class->ID); ?>">
										<?php echo esc_html(WLSM_M_Class::get_label_text($class->label)); ?>
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
							<span class="wlsm-important">*</span> <?php esc_html_e('Section', 'school-management'); ?>:
						</label>
						<select name="section_id" class="wlsm-form-control" id="wlsm_section">
							<option value=""><?php esc_html_e('Select Section', 'school-management'); ?></option>
						</select>
					</div>

					<?php $student_type_list = WLSM_Helper::student_type($school_id); ?>
					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_student_type" class="wlsm-font-bold">
							<?php esc_html_e('Student type', 'school-management'); ?>:
						</label>
						<select name="student_type_id" class="wlsm-form-control" id="wlsm_student_type">
							<option value=""><?php esc_html_e('Select Student type', 'school-management'); ?></option>
							<?php
							foreach ( $student_type_list as $student ) {
								echo '<option value="'.esc_attr( $student->ID ).'">'.esc_html( $student->label ).'</option>';
							}
							?>
						</select>
					</div>
				</div>
				<div class="wlsm-row">

					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_subjects" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Subjects', 'school-management'); ?>:
						</label><br>
						<select name="subjects[]" class="wlsm-form-control-select " id="wlsm_subjects" multiple >
							<option value=""><?php esc_html_e('Select subjects', 'school-management'); ?></option>
						</select>
					</div>

					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_activity" class="wlsm-font-bold">
							<?php esc_html_e('Activity', 'school-management'); ?>:
						</label>
						<select name="activity[]" class="wlsm-form-control-select " id="wlsm_activity" multiple >
							<option value=""><?php esc_html_e('Select activity', 'school-management'); ?></option>
						</select>
					</div>
				</div>
			</div>

			<!-- Parent Detail -->
			<?php if ($school_registration_parent_detail or empty($school_id)) : ?>
				<div class="wlsm-form-section" id="registration_parent_detail">
					<div class="wlsm-row">
						<div class="wlsm-col-12">
							<div class="wlsm-form-sub-heading wlsm-font-bold">
								<?php esc_html_e('Parent Detail', 'school-management'); ?>
							</div>
						</div>
					</div>

					<div class="wlsm-row">
						<div class="wlsm-form-group wlsm-col-4">
							<label for="wlsm_father_name" class="wlsm-font-bold">
								<?php esc_html_e('Father\'s Name', 'school-management'); ?>:
							</label>
							<input type="text" name="father_name" class="wlsm-form-control" id="wlsm_father_name" placeholder="<?php esc_attr_e('Enter father name', 'school-management'); ?>" value="">
						</div>
						<div class="wlsm-form-group wlsm-col-4">
							<label for="wlsm_father_phone" class="wlsm-font-bold">
								<?php esc_html_e('Father\'s Phone', 'school-management'); ?>:
							</label>
							<input type="text" name="father_phone" class="wlsm-form-control" id="wlsm_father_phone" placeholder="<?php esc_attr_e('Enter father phone number', 'school-management'); ?>" value="">
						</div>
						<?php if ($school_registration_parent_occupation or empty($school_id)) : ?>
							<div class="wlsm-form-group wlsm-col-4">
								<label for="wlsm_father_occupation" class="wlsm-font-bold">
									<?php esc_html_e('Father\'s Occupation', 'school-management'); ?>:
								</label>
								<input type="text" name="father_occupation" class="wlsm-form-control" id="wlsm_father_occupation" placeholder="<?php esc_attr_e('Enter father occupation', 'school-management'); ?>" value="">
							</div>
						<?php endif ?>
					</div>

					<div class="wlsm-row">
						<div class="wlsm-form-group wlsm-col-4">
							<label for="wlsm_mother_name" class="wlsm-font-bold">
								<?php esc_html_e('Mother\'s Name', 'school-management'); ?>:
							</label>
							<input type="text" name="mother_name" class="wlsm-form-control" id="wlsm_mother_name" placeholder="<?php esc_attr_e('Enter mother name', 'school-management'); ?>" value="">
						</div>
						<div class="wlsm-form-group wlsm-col-4">
							<label for="wlsm_mother_phone" class="wlsm-font-bold">
								<?php esc_html_e('Mother\'s Phone', 'school-management'); ?>:
							</label>
							<input type="text" name="mother_phone" class="wlsm-form-control" id="wlsm_mother_phone" placeholder="<?php esc_attr_e('Enter mother phone number', 'school-management'); ?>" value="">
						</div>
						<?php if ($school_registration_parent_occupation or empty($school_id)) : ?>
							<div class="wlsm-form-group wlsm-col-4">
								<label for="wlsm_mother_occupation" class="wlsm-font-bold">
									<?php esc_html_e('Mother\'s Occupation', 'school-management'); ?>:
								</label>
								<input type="text" name="mother_occupation" class="wlsm-form-control" id="wlsm_mother_occupation" placeholder="<?php esc_attr_e('Enter mother occupation', 'school-management'); ?>" value="">
							</div>
						<?php endif ?>
					</div>

					<div class="wlsm-row">
						<div class="wlsm-form-group wlsm-col-4">
							<div class="wlsm-parent-id-proof-box">
								<div class="wlsm-parent-id-proof-section">
									<label for="wlsm_parent_id_proof" class="wlsm-font-bold">
										<?php esc_html_e('Upload Parent ID Proof', 'school-management'); ?>:
									</label>
									<div class="custom-file mb-3">
										<input type="file" class="custom-file-input" id="wlsm_parent_id_proof" name="parent_id_proof">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif ?>

			<!-- Student Login Detail -->
			<div class="wlsm-form-section">
				<div class="wlsm-row">
					<div class="wlsm-col-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e('Login Detail', 'school-management'); ?>
						</div>
					</div>
				</div>

				<div class="wlsm-row wlsm-student-new-user">
					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_username" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Username', 'school-management'); ?>:
						</label>
						<input type="text" name="username" class="wlsm-form-control" id="wlsm_username" placeholder="<?php esc_attr_e('Enter username', 'school-management'); ?>">
					</div>

					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_login_email" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Login Email', 'school-management'); ?>:
						</label>
						<input type="email" name="login_email" class="wlsm-form-control" id="wlsm_login_email" placeholder="<?php esc_attr_e('Enter login email', 'school-management'); ?>">
					</div>

					<div class="wlsm-form-group wlsm-col-4">
						<label for="wlsm_login_password" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Password', 'school-management'); ?>:
						</label>
						<input type="password" name="password" class="wlsm-form-control" id="wlsm_login_password" placeholder="<?php esc_attr_e('Enter password', 'school-management'); ?>">
					</div>
				</div>
			</div>

			<!-- Parent / Guardian Name and Login Detail -->
			<?php if ($school_registration_parent_login or empty($school_id)) : ?>
				<div class="wlsm-form-section" id="registration_parent_login">
					<div class="wlsm-row">
						<div class="wlsm-col-12">
							<div class="wlsm-form-sub-heading wlsm-font-bold">
								<?php esc_html_e('Parent / Guardian Login Detail', 'school-management'); ?>
							</div>
						</div>
					</div>

					<div class="wlsm-row">
						<div class="wlsm-col-12">
							<div class="wlsm-form-group wlsm-row wlsm-mb-2">
								<input type="checkbox" name="allow_parent_login" id="wlsm_allow_parent_login" class="wlsm-mt-1 wlsm-mr-1" value="1">
								<label class="wlsm-font-bold wlsm-d-i-block wlsm-ml-1" for="wlsm_allow_parent_login">
									<?php esc_html_e('Allow Parent Login?', 'school-management'); ?>
								</label>
							</div>
						</div>
					</div>

					<div class="wlsm-parent-new-user">
						<div class="wlsm-row">
							<div class="wlsm-form-group wlsm-col-4">
								<label for="wlsm_parent_username" class="wlsm-font-bold">
									<span class="wlsm-important">*</span> <?php esc_html_e('Username', 'school-management'); ?>:
								</label>
								<input type="text" name="parent_username" class="wlsm-form-control" id="wlsm_parent_username" placeholder="<?php esc_attr_e('Enter username', 'school-management'); ?>">
							</div>

							<div class="wlsm-form-group wlsm-col-4">
								<label for="wlsm_parent_login_email" class="wlsm-font-bold">
									<span class="wlsm-important">*</span> <?php esc_html_e('Login Email', 'school-management'); ?>:
								</label>
								<input type="email" name="parent_login_email" class="wlsm-form-control" id="wlsm_parent_login_email" placeholder="<?php esc_attr_e('Enter login email', 'school-management'); ?>">
							</div>

							<div class="wlsm-form-group wlsm-col-4">
								<label for="wlsm_parent_login_password" class="wlsm-font-bold">
									<span class="wlsm-important">*</span> <?php esc_html_e('Password', 'school-management'); ?>:
								</label>
								<input type="password" name="parent_password" class="wlsm-form-control" id="wlsm_parent_login_password" placeholder="<?php esc_attr_e('Enter password', 'school-management'); ?>">
							</div>
						</div>
					</div>
				</div>
			<?php endif ?>

			<!-- Transport Detail -->

			<?php if ($school_registration_transport or empty($school_id)) : ?>
				<div class="wlsm-form-section" id="registration_transport">
					<div class="wlsm-row">
						<div class="wlsm-col-12">
							<div class="wlsm-form-sub-heading wlsm-font-bold">
								<?php esc_html_e('Transport Detail', 'school-management'); ?>
							</div>
						</div>
					</div>

					<div class="wlsm-row">
						<div class="wlsm-form-group wlsm-col-4">
							<label for="wlsm_route_vehicle" class="wlsm-font-bold">
								<?php esc_html_e('Transport Route and Vehicle', 'school-management'); ?>:
							</label>
							<select name="route_vehicle_id" class="wlsm-form-control selectpicker" id="wlsm_route_vehicle" data-live-search="true" title="<?php esc_attr_e('Select', 'school-management'); ?>">
								<option value=""><?php esc_html_e('Select', 'school-management'); ?></option>
								<?php foreach ($routes as $key => $route) { ?>
									<optgroup label="<?php echo esc_attr($route['route_name']); ?>">
										<?php foreach ($route['vehicles'] as $route_vehicle) { ?>
											<option value="<?php echo esc_attr($route_vehicle['ID']); ?>">
												<?php echo esc_html($route_vehicle['vehicle_number']); ?>
											</option>
										<?php } ?>
									</optgroup>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			<?php endif ?>


			<?php if (($school_registration_survey === true) || empty($school_id)) : ?>
				<!-- Student survey Detail -->
				<div class="wlsm-form-section">
					<div class="wlsm-row">
						<div class="wlsm-col-12">
							<div class="wlsm-form-sub-heading wlsm-font-bold">
								<?php esc_html_e('How Did you Hear From Us?', 'school-management'); ?>
							</div>
						</div>
					</div>

					<?php
					foreach ($survey_list as $key => $value) {
						reset($survey_list);
					?>
						<div class="wlsm-form-check">
							<input class="wlsm-form-check-input" type="radio" name="survey" id="wlsm_survey_<?php echo esc_attr($value); ?>" value="<?php echo esc_attr($key); ?>" <?php checked($key, $survey, true); ?>>
							<label class="wlsm-ml-1 wlsm-form-check-label wlsm-font-bold" for="wlsm_survey_<?php echo esc_attr($value); ?>">
								<?php echo esc_html($value); ?>
							</label>
						</div>
					<?php
					}
					?>
				</div>
			<?php endif ?>

			<br>
			<?php if (($school_registration_fees === true) || empty($school_id)) : ?>
				<div class="wlsm-form-section">
					<div class="wlsm-row">
						<div class="wlsm-col-12">
							<div class="wlsm-form-sub-heading wlsm-font-bold">
								<?php esc_html_e('Student Fees', 'school-management'); ?>
							</div>
						</div>
					</div>

					<div class="wlsm-row">
						<div class="wlsm-col-12 " id="wlsm_fees">

						</div>
					</div>

				</div>
			<?php endif ?>

			<div class="wlsm-form-section wlsm-mt-2">
				<?php
				if (get_option('wlsm_gdpr_enable')) {
				?>
					<div class="wlsm-form-group wlsm-row wlsm-mb-2">
						<input type="checkbox" name="gdpr" id="wlsm_gdpr" class="wlsm-mt-1 wlsm-mr-1" value="1">
						<label class="wlsm-font-bold wlsm-d-i-block wlsm-ml-1" for="wlsm_gdpr">
							<?php echo wp_kses(WLSM_Config::gdpr_text_registration(), array('a' => array('href' => array()))); ?>
						</label>
					</div>
				<?php
				}
				?>
			</div>

			<div class="wlsm-border-top wlsm-pt-2 wlsm-mt-1">
				<button class="button wlsm-btn btn btn-primary" type="submit" id="wlsm-submit-registration-btn">
					<?php esc_html_e('Submit', 'school-management'); ?>
				</button>
			</div>

		</form>

	</div>
</div>
<?php
return ob_get_clean();
