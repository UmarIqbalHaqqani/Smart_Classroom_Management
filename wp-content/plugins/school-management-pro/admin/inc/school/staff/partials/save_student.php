<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Helper.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Transport.php';

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$page_url = WLSM_M_Staff_General::get_students_page_url();

$admissions_page_url = WLSM_M_Staff_General::get_admissions_page_url();

// Registration settings.
$settings_registration = WLSM_M_Setting::get_settings_registration( $school_id );
$auto_admission_number = $settings_registration['auto_admission_number'];
$auto_roll_number      = $settings_registration['auto_roll_number'];

$gender_list       = WLSM_Helper::gender_list();
$student_type_list = WLSM_Helper::student_type($school_id);

$medium_list = WLSM_Helper::medium_list();

$blood_group_list = WLSM_Helper::blood_group_list();

$student = null;

$nonce_action = 'add-admission';
$action       = 'wlsm-add-admission';

$name              = '';
$gender            = 'male';
$dob               = '';
$religion          = '';
$caste             = '';
$blood_group       = '';
$address           = '';
$city              = '';
$state             = '';
$country           = '';
$phone             = '';
$email             = '';
$id_number         = '';
$id_proof          = '';
$parent_id_proof   = '';
$note              = '';
$admission_date    = '';
$class_id          = '';
$class_label       = '';
$section_id        = '';
$admission_number  = '';
$roll_number       = '';
$photo_id          = '';
$father_name       = '';
$father_phone      = '';
$father_occupation = '';
$mother_name       = '';
$mother_phone      = '';
$mother_occupation = '';
$route_vehicle_id  = '';
$username          = '';
$login_email       = '';
$is_active         = 1;
$room_id           = '';
$student_type      = 'regular';
$subject_id        = '';
$medium            = 'english';
$activities	   	   = '';
$savedactivities   = '';

$sections = array();

$parent_username    = '';
$parent_login_email = '';

$fees = WLSM_M_Staff_Accountant::fetch_fees( $school_id );

// Registration settings.
$settings_registration             = WLSM_M_Setting::get_settings_registration( $school_id );

$school_registration_dob           = $settings_registration['dob'];
$school_registration_religion      = $settings_registration['religion'];
$school_registration_caste         = $settings_registration['caste'];
$school_registration_blood_group   = $settings_registration['blood_group'];
$school_registration_phone         = $settings_registration['phone'];
$school_registration_city          = $settings_registration['city'];
$school_registration_state         = $settings_registration['state'];
$school_registration_country       = $settings_registration['country'];
$school_registration_transport     = $settings_registration['transport'];
$school_registration_parent_detail = $settings_registration['parent_detail'];
$school_registration_parent_login  = $settings_registration['parent_login'];
$school_registration_id_number     = $settings_registration['id_number'];
// activities

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$current_user = WLSM_M_Role::can( 'manage_students' );

	$id       = absint( $_GET['id'] );
	$student  = WLSM_M_Staff_General::fetch_student( $school_id, $session_id, $id, $restrict_to_section );
	$subjects = WLSM_M_Staff_General::fetch_subjects( $id );
	if ( $student ) {
		$nonce_action = 'edit-student-' . $student->ID;
		$action       = 'wlsm-edit-student';

		$name              = $student->student_name;
		$gender            = $student->gender;
		$student_type      = $student->student_type;
		$dob               = $student->dob;
		$religion          = $student->religion;
		$caste             = $student->caste;
		$blood_group       = $student->blood_group;
		$address           = $student->address;
		$city              = $student->city;
		$state             = $student->state;
		$country           = $student->country;
		$phone             = $student->phone;
		$email             = $student->email;
		$id_number         = $student->id_number;
		$id_proof          = $student->id_proof;
		$parent_id_proof   = $student->parent_id_proof;
		$note              = $student->note;
		$admission_date    = $student->admission_date;
		$class_id          = $student->class_id;
		$class_label       = $student->class_label;
		$section_id        = $student->section_id;
		$admission_number  = $student->admission_number;
		$roll_number       = $student->roll_number;
		$photo_id          = $student->photo_id;
		$father_name       = $student->father_name;
		$father_phone      = $student->father_phone;
		$father_occupation = $student->father_occupation;
		$mother_name       = $student->mother_name;
		$mother_phone      = $student->mother_phone;
		$mother_occupation = $student->mother_occupation;
		$route_vehicle_id  = $student->route_vehicle_id;
		$username          = $student->username;
		$login_email       = $student->login_email;
		$is_active         = $student->is_active;
		$room_id           = $student->room_id;
		$medium            = $student->medium;
		$tempactivities	   = $student->activities;
		if( !empty($tempactivities) ) {
			$savedactivities   = unserialize($tempactivities);
		}

		$sections = WLSM_M_Staff_Class::fetch_sections( $student->class_school_id );

		$parent_user_id = $student->parent_user_id;
		$classes        = WLSM_M_Staff_Class::fetch_classes( $school_id );

		$parent_user = get_user_by( 'ID', $parent_user_id );
		if ( $parent_user ) {
			$parent_username    = $parent_user->user_login;
			$parent_login_email = $parent_user->user_email;
		}

		$fees = WLSM_M_Staff_Accountant::fetch_student_fees( $school_id, $id );

	}
} else {
	$current_user = WLSM_M_Role::can( 'manage_admissions' );

	$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );

	if ( isset( $_GET['inquiry_id'] ) && ! empty( $_GET['inquiry_id'] ) ) {
		$inquiry_id = absint( $_GET['inquiry_id'] );
		$inquiry    = WLSM_M_Staff_General::fetch_inquiry( $school_id, $inquiry_id );
		if ( $inquiry && $inquiry->is_active ) {
			$name     = $inquiry->name;
			$phone    = $inquiry->phone;
			$email    = $inquiry->email;
			$class_id = $inquiry->class_id;

			$class_school = WLSM_M_Staff_Class::get_class( $school_id, $class_id );
			if ( $class_school ) {
				$sections = WLSM_M_Staff_Class::fetch_sections( $class_school->ID );
			}
		}
	}
}

if ( ! $current_user ) {
	die();
}

$fee_periods = WLSM_Helper::fee_period_list();

$routes_vehicles = WLSM_M_Staff_Transport::fetch_routes_vehicles( $school_id );
$routes          = array();
$activities 	 = WLSM_M_Staff_General::fetch_class_activity($class_id);
// var_dump($activity);
foreach ( $routes_vehicles as $route_vehicle ) {
	if ( array_key_exists( $route_vehicle->route_id, $routes ) ) {
		array_push(
			$routes[ $route_vehicle->route_id ]['vehicles'],
			array(
				'vehicle_number' => $route_vehicle->vehicle_number,
				'ID'             => $route_vehicle->ID,
			)
		);
	} else {
		$routes[ $route_vehicle->route_id ] = array(
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
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $student ) {
						/* translators: 1: student name, 2: enrollment number */
						printf( esc_html__( 'Edit Student: %1$s (Enrollment Number - %2$s)', 'school-management' ), esc_html( WLSM_M_Staff_Class::get_name_text( $name ) ), esc_html( $student->enrollment_number ) );
					} else {
						/* translators: %s: session label */
						printf( esc_html__( 'New Admission For Session: %s', 'school-management' ), esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) ) );
					}
					?>
				</span>
			</span>
			<?php if ( $student ) { ?>
				<span class="float-md-right">
					<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
						<i class="fas fa-users"></i>&nbsp;
						<?php esc_html_e( 'View All', 'school-management' ); ?>
					</a>
				</span>
			<?php } elseif ( WLSM_M_Role::check_permission( array( 'manage_students' ), $current_school['permissions'] ) ) { ?>
				<span class="float-md-right">
					<a href="<?php echo esc_url( $admissions_page_url . '&action=bulk_import' ); ?>" class="btn btn-sm btn-outline-light">
						<i class="fas fa-file-import"></i>&nbsp;
						<?php esc_html_e( 'Bulk Admission', 'school-management' ); ?>
					</a>
					<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
						<i class="fas fa-users"></i>&nbsp;
						<?php esc_html_e( 'View Students', 'school-management' ); ?>
					</a>
				</span>
			<?php } ?>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="<?php echo esc_attr( $action ); ?>-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">

			<?php
			if ( $student ) {
				?>
				<input type="hidden" name="student_id" value="<?php echo esc_attr( $student->ID ); ?>">
				<?php
			} else {
				if ( isset( $inquiry ) && $inquiry ) {
					?>
					<input type="hidden" name="inquiry_id" value="<?php echo esc_attr( $inquiry->ID ); ?>">
					<?php
				}
			}
			?>

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
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Student Name', 'school-management' ); ?>:
						</label>
						<input type="text" name="name" class="form-control" id="wlsm_name" placeholder="<?php esc_attr_e( 'Enter student name', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $name ) ); ?>">
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

					<?php if ( $school_registration_dob ) : ?>
						<div class="form-group col-md-4">
						<label for="wlsm_date_of_birth" class="wlsm-font-bold">
							<?php esc_html_e( 'Date of Birth', 'school-management' ); ?>:
						</label>
						<input type="text" name="dob" class="form-control" id="wlsm_date_of_birth" placeholder="<?php esc_attr_e( 'Enter date of birth', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::get_date_text( $dob ) ); ?>">
					</div>
					<?php endif ?>

				</div>

				<div class="form-row">
					<?php if ( $school_registration_religion ) : ?>
						<div class="form-group col-md-4">
						<label for="wlsm_religion" class="wlsm-font-bold">
							<?php esc_html_e( 'Religion', 'school-management' ); ?>:
						</label>
						<input type="text" name="religion" class="form-control" id="wlsm_religion" placeholder="<?php esc_attr_e( 'Enter religion', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $religion ) ); ?>">
					</div>
					<?php endif ?>
					<?php if ( $school_registration_caste ) : ?>
						<div class="form-group col-md-4">
						<label for="wlsm_caste" class="wlsm-font-bold">
							<?php esc_html_e( 'Caste', 'school-management' ); ?>:
						</label>
						<input type="text" name="caste" class="form-control" id="wlsm_caste" placeholder="<?php esc_attr_e( 'Enter caste', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $caste ) ); ?>">
					</div>
					<?php endif ?>
					<?php if ( $school_registration_blood_group ) : ?>
						<div class="form-group col-md-4">
						<label for="wlsm_blood_group" class="wlsm-font-bold">
							<?php esc_html_e( 'Blood Group', 'school-management' ); ?>:
						</label>
						<select name="blood_group" class="form-control selectpicker" id="wlsm_blood_group" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Blood Group', 'school-management' ); ?></option>
							<?php foreach ( $blood_group_list as $key => $value ) { ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $blood_group, true ); ?>>
									<?php echo esc_html( $value ); ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<?php endif ?>

				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_address" class="wlsm-font-bold">
							<?php esc_html_e( 'Address', 'school-management' ); ?>:
						</label>
						<textarea name="address" class="form-control" id="wlsm_address" cols="30" rows="3" placeholder="<?php esc_attr_e( 'Enter student address', 'school-management' ); ?>"><?php echo esc_html( $address ); ?></textarea>
					</div>

					<?php if ( $school_registration_phone ) : ?>
						<div class="form-group col-md-4">
						<label for="wlsm_phone" class="wlsm-font-bold">
							<?php esc_html_e( 'Phone', 'school-management' ); ?>:
						</label>
						<input type="text" name="phone" class="form-control" id="wlsm_phone" placeholder="<?php esc_attr_e( 'Enter student phone number', 'school-management' ); ?>" value="<?php echo esc_attr( $phone ); ?>">
					</div>
					<?php endif ?>

					<div class="form-group col-md-4">
						<label for="wlsm_email" class="wlsm-font-bold">
							<span class="wlsm-important">* </span><?php esc_html_e( 'Email', 'school-management' ); ?>:
						</label>
						<input type="email" name="email" class="form-control" id="wlsm_email" placeholder="<?php esc_attr_e( 'Enter student email address', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $email ) ); ?>">
					</div>
				</div>

				<div class="form-row">
					<?php if ( $school_registration_city ) : ?>
						<div class="form-group col-md-4">
						<label for="wlsm_city" class="wlsm-font-bold">
							<?php esc_html_e( 'City', 'school-management' ); ?>:
						</label>
						<input type="text" name="city" class="form-control" id="wlsm_city" placeholder="<?php esc_attr_e( 'Enter city', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $city ) ); ?>">
					</div>
					<?php endif ?>
					<?php if ( $school_registration_state ) : ?>
						<div class="form-group col-md-4">
						<label for="wlsm_state" class="wlsm-font-bold">
							<?php esc_html_e( 'State', 'school-management' ); ?>:
						</label>
						<input type="text" name="state" class="form-control" id="wlsm_state" placeholder="<?php esc_attr_e( 'Enter state', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $state ) ); ?>">
					</div>
					<?php endif ?>
					<?php if ( $school_registration_country ) : ?>
						<div class="form-group col-md-4">
						<label for="wlsm_country" class="wlsm-font-bold">
							<?php esc_html_e( 'Country', 'school-management' ); ?>:
						</label>
						<input type="text" name="country" class="form-control" id="wlsm_country" placeholder="<?php esc_attr_e( 'Enter country', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $country ) ); ?>">
					</div>
					<?php endif ?>
				</div>

				<div class="form-row">
					<?php if ( $school_registration_id_number ) : ?>
						<div class="form-group col-md-4">
						<label for="wlsm_id_number" class="wlsm-font-bold">
							<?php esc_html_e( 'ID Number', 'school-management' ); ?>:
						</label>
						<input type="text" name="id_number" class="form-control" id="wlsm_id_number" placeholder="<?php esc_attr_e( 'Enter ID number', 'school-management' ); ?>" value="<?php echo esc_attr( $id_number ); ?>">
					</div>
					<?php endif ?>
					<div class="form-group col-md-4">
						<div class="wlsm-id-proof-box">
							<div class="wlsm-id-proof-section">
								<label for="wlsm_id_proof" class="wlsm-font-bold">
									<?php esc_html_e( 'Upload ID Proof', 'school-management' ); ?>:
								</label>
								<?php if ( ! empty( $id_proof ) ) { ?>
									<br>
									<a target="_blank" href="<?php echo esc_url( wp_get_attachment_url( $id_proof ) ); ?>" class="text-primary wlsm-font-bold wlsm-id-proof"><?php esc_html_e( 'Download ID Proof', 'school-management' ); ?></a>
								<?php } ?>
								<div class="custom-file mb-3">
									<input type="file" class="custom-file-input" id="wlsm_id_proof" name="id_proof">
									<label class="custom-file-label" for="wlsm_id_proof">
										<?php esc_html_e( 'Choose File', 'school-management' ); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_note" class="wlsm-font-bold">
							<?php esc_html_e( 'Extra Note / Detail', 'school-management' ); ?>:
						</label>
						<textarea name="note" class="form-control" id="wlsm_note" cols="30" rows="2" placeholder="<?php esc_attr_e( 'Enter extra detail.', 'school-management' ); ?>"><?php echo esc_html( $note ); ?></textarea>
					</div>
				</div>
			</div>

			<!-- Admission Detail -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Admission Detail', 'school-management' ); ?>
						</div>
						<?php if ( $student ) { ?>
							<h6 class="text-center text-danger"> <strong> <?php esc_html_e( 'Note: Make sure to manually generate invoice according to that class as well.', 'school-management' ); ?></strong></h6>
						<?php } ?>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_admission_date" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Admission Date', 'school-management' ); ?>:
						</label>
						<input type="text" name="admission_date" class="form-control" id="wlsm_admission_date" placeholder="<?php esc_attr_e( 'Enter admission date', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::get_date_text( $admission_date ) ); ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>

							<select name="class_id" class="form-control selectpicker wlsm_subjects wlsm_activity" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_class" data-live-search="true">
								<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
								<?php foreach ( $classes as $class ) { ?>
									<option value="<?php echo esc_attr( $class->ID ); ?>" <?php selected( $class->ID, $class_id, true ); ?>>
										<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
									</option>
								<?php } ?>
							</select>

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
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_admission_number" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Admission Number', 'school-management' ); ?>:
							<?php if ( $auto_admission_number ) { ?> <small class="text-dark"><?php esc_html_e( '(Auto Generated)', 'school-management' ); ?></small> <?php } ?>
						</label>
						<input <?php if ( $auto_admission_number ) { echo 'readonly'; } ?> type="text" name="admission_number" class="form-control" id="wlsm_admission_number" placeholder=" <?php if ( $auto_admission_number ) { esc_attr_e( '---- Auto Generated ----', 'school-management' ); } else { esc_attr_e( 'Enter admission number', 'school-management' ); } ?> " value="<?php echo esc_attr( $admission_number ); ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_roll_number" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Roll Number', 'school-management' ); ?>:
							<?php if ( $auto_roll_number ) { ?>
								<small class="text-dark"><?php esc_html_e( '(Auto Generated)', 'school-management' ); ?></small>
							<?php } ?>
						</label>
						<input
						<?php if ( $auto_roll_number ) { echo 'readonly'; } ?> type="text" name="roll_number" class="form-control" id="wlsm_roll_number" placeholder=" <?php if ( $auto_roll_number ) { esc_attr_e( '---- Auto Generated ----', 'school-management' ); } else { esc_attr_e( 'Enter roll number', 'school-management' ); } ?> " value="<?php echo esc_attr( $roll_number ); ?>">
					</div>

					<div class="form-group col-md-4">
						<div class="wlsm-photo-box">
							<div class="wlsm-photo-section">
								<label for="wlsm_photo" class="wlsm-font-bold">
									<?php esc_html_e( 'Upload Photo', 'school-management' ); ?>:
								</label>
								<?php if ( ! empty( $photo_id ) ) { ?>
									<img src="<?php echo esc_url( wp_get_attachment_url( $photo_id ) ); ?>" class="img-responsive wlsm-photo">
								<?php } ?>
								<div class="custom-file mb-3">
									<input type="file" class="custom-file-input" id="wlsm_photo" name="photo">
									<label class="custom-file-label" for="wlsm_photo">
										<?php esc_html_e( 'Choose File', 'school-management' ); ?>
									</label>
								</div>
							</div>
						</div>
					</div>


					<div class="form-group col-md-4">
						<label class="wlsm-font-bold">
							<?php esc_html_e( 'Student Type', 'school-management' ); ?>:
						</label>
						<br>
						<?php
						echo '<select name="student_type" class="form-control">';
						foreach ( $student_type_list as $student ) {
							$selected = ($student->ID == $student_type) ? 'selected' : '';
							echo '<option value="'.esc_attr( $student->ID ).'" '.$selected.'>'.esc_html( $student->label ).'</option>';
						}
						echo '</select>';
						?>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_medium" class="wlsm-font-bold">
							<?php esc_html_e( 'Medium', 'school-management' ); ?>:
						</label>
						<select name="medium" class="form-control selectpicker" id="wlsm_medium" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Medium', 'school-management' ); ?></option>
							<?php foreach ( $medium_list as $key => $value ) { ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $medium, true ); ?>>
									<?php echo esc_html( $value ); ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<!-- Student Subjects  -->
					<div class="form-group col-md-4">
						<label for="wlsm_subject" class="wlsm-font-bold">
							<?php esc_html_e('Subject', 'school-management'); ?>:
						</label>

						<select name="subjects[]" class="form-control selectpicker" id="wlsm_subjects" multiple title="<?php esc_attr_e('Select subject', 'school-management'); ?>" data-actions-box="true">

							<?php if ($subject_id) : ?>
								<?php foreach ($subjects as $subject) { ?>
									<option value="<?php echo esc_attr($subject->ID); ?>">
										<?php if ($subject_id == $subject->ID): ?>
										<?php echo esc_html(WLSM_M_Staff_Class::get_subject_label_text($subject->label)); ?>
										<?php endif ?>

									</option>
								<?php } ?>
							<?php elseif(!$subject_id) : ?>
								<?php foreach ($subjects as $subject) { ?>
								<option value="<?php echo esc_attr($subject->ID); ?>" <?php echo 'selected'; ?>>
									<?php echo esc_html(WLSM_M_Staff_Class::get_subject_label_text($subject->label)); ?>
								</option>
							<?php } ?>
								<?php endif ?>

						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_activity" class="wlsm-font-bold">
							<?php esc_html_e('Activity', 'school-management'); ?>:
						</label>
						<select name="activity[]" class="form-control selectpicker" id="wlsm_activity" multiple title="<?php esc_attr_e('Select activity', 'school-management'); ?>" data-actions-box="true">
						<!-- $activity -->
						<?php if ($activities) : ?>
							<?php
							// $savedactivities
							if( is_array( $savedactivities ) ) {
								foreach ($activities as $activity) {
									?>
										<option value="<?php echo esc_attr($activity->ID); ?>" <?php if(in_array( $activity->ID, $savedactivities )) { echo "selected"; } ?>>
											<?php echo esc_html(WLSM_M_Staff_Class::get_subject_label_text($activity->title)); ?>
										</option>
									<?php

								}
							} else {
								foreach ($activities as $activity) {
									?>
										<option value="<?php echo esc_attr($activity->ID); ?>" >
											<?php echo esc_html(WLSM_M_Staff_Class::get_subject_label_text($activity->title)); ?>
										</option>
									<?php

								}
							}

						endif;
							?>
						</select>
					</div>

				</div>
			</div>

			<!-- Parent Detail -->
			<?php if ( $school_registration_parent_detail ) : ?>
				<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Parent Detail', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_father_name" class="wlsm-font-bold">
							<?php esc_html_e( 'Father\'s Name', 'school-management' ); ?>:
						</label>
						<input type="text" name="father_name" class="form-control" id="wlsm_father_name" placeholder="<?php esc_attr_e( 'Enter father name', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $father_name ) ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_father_phone" class="wlsm-font-bold">
							<?php esc_html_e( 'Father\'s Phone', 'school-management' ); ?>:
						</label>
						<input type="text" name="father_phone" class="form-control" id="wlsm_father_phone" placeholder="<?php esc_attr_e( 'Enter father phone number', 'school-management' ); ?>" value="<?php echo esc_attr( $father_phone ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_father_occupation" class="wlsm-font-bold">
							<?php esc_html_e( 'Father\'s Occupation', 'school-management' ); ?>:
						</label>
						<input type="text" name="father_occupation" class="form-control" id="wlsm_father_occupation" placeholder="<?php esc_attr_e( 'Enter father occupation', 'school-management' ); ?>" value="<?php echo esc_attr( $father_occupation ); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_mother_name" class="wlsm-font-bold">
							<?php esc_html_e( 'Mother\'s Name', 'school-management' ); ?>:
						</label>
						<input type="text" name="mother_name" class="form-control" id="wlsm_mother_name" placeholder="<?php esc_attr_e( 'Enter mother name', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $mother_name ) ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_mother_phone" class="wlsm-font-bold">
							<?php esc_html_e( 'Mother\'s Phone', 'school-management' ); ?>:
						</label>
						<input type="text" name="mother_phone" class="form-control" id="wlsm_mother_phone" placeholder="<?php esc_attr_e( 'Enter mother phone number', 'school-management' ); ?>" value="<?php echo esc_attr( $mother_phone ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_mother_occupation" class="wlsm-font-bold">
							<?php esc_html_e( 'Mother\'s Occupation', 'school-management' ); ?>:
						</label>
						<input type="text" name="mother_occupation" class="form-control" id="wlsm_mother_occupation" placeholder="<?php esc_attr_e( 'Enter mother occupation', 'school-management' ); ?>" value="<?php echo esc_attr( $mother_occupation ); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<div class="wlsm-parent-id-proof-box">
							<div class="wlsm-parent-id-proof-section">
								<label for="wlsm_parent_id_proof" class="wlsm-font-bold">
									<?php esc_html_e( 'Upload Parent ID Proof', 'school-management' ); ?>:
								</label>
								<?php if ( ! empty( $parent_id_proof ) ) { ?>
									<br>
									<a target="_blank" href="<?php echo esc_url( wp_get_attachment_url( $parent_id_proof ) ); ?>" class="text-primary wlsm-font-bold wlsm-parent-id-proof"><?php esc_html_e( 'Download Parent ID Proof', 'school-management' ); ?></a>
								<?php } ?>
								<div class="custom-file mb-3">
									<input type="file" class="custom-file-input" id="wlsm_parent_id_proof" name="parent_id_proof">
									<label class="custom-file-label" for="wlsm_parent_id_proof">
										<?php esc_html_e( 'Choose File', 'school-management' ); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif ?>

				<!-- Transport Detail -->
				<?php if ( $school_registration_transport ) : ?>
				<div class="wlsm-form-section">

				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_route_vehicle" class="wlsm-font-bold">
							<?php esc_html_e( 'Transport Route and Vehicle', 'school-management' ); ?>:
						</label>
						<select name="route_vehicle_id" class="form-control selectpicker" id="wlsm_route_vehicle" data-live-search="true" title="<?php esc_attr_e( 'Select', 'school-management' ); ?>">
							<option value=""><?php esc_html_e( 'Select', 'school-management' ); ?></option>
							<?php foreach ( $routes as $key => $route ) { ?>
								<optgroup label="<?php echo esc_attr( $route['route_name'] ); ?>">
									<?php foreach ( $route['vehicles'] as $route_vehicle ) { ?>
										<option <?php selected( $route_vehicle_id, $route_vehicle['ID'], true ); ?> value="<?php echo esc_attr( $route_vehicle['ID'] ); ?>">
											<?php echo esc_html( $route_vehicle['vehicle_number'] ); ?>
										</option>
									<?php } ?>
								</optgroup>
							<?php } ?>
						</select>
					</div>

					<?php $rooms = WLSM_M_Staff_Transport::fetch_hostel_rooms(); ?>
					<div class="form-group col-md-6">
						<label for="wlsm_room" class="wlsm-font-bold">
							<?php esc_html_e( 'Hostel Room No.', 'school-management' ); ?>:
						</label>
						<select name="room_id" class="form-control selectpicker" id="wlsm_room" data-live-search="true" title="<?php esc_attr_e( 'Select', 'school-management' ); ?>">
							<option value=""><?php esc_html_e( 'Select', 'school-management' ); ?></option>
									<?php foreach ( $rooms as $room ) { ?>
										<option <?php selected( $room_id, $room->ID, true ); ?> value="<?php echo esc_attr( $room->ID ); ?>">
											<?php echo esc_html( $room->room_name." [".ucwords($room->hostel_type)."]"  ); ?>
										</option>
									<?php } ?>
						</select>
					</div>

				</div>
			</div>
			<?php endif ?>

			<!-- Fees Structure -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold pb-0">
							<?php esc_html_e( 'Student Fee Structure', 'school-management' ); ?>
							<?php if ( $student ) { ?>
								<span class="ml-1">
									<button type="button" class="btn btn-sm btn-outline-primary wlsm-print-fee-structure" data-nonce="<?php echo esc_attr( wp_create_nonce( 'print-fee-structure-' . $student->ID ) ); ?>" data-fee-structure="<?php echo esc_attr( $student->ID ); ?>" data-message-title="<?php echo esc_attr__( 'Print Student Fee Structure', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>"><i class="fas fa-print"></i></button>
								</span>
							<?php } ?>
						</div>
					</div>
				</div>

				<?php if ( ! $fees ) : ?>
				<h6 class="text-danger text-center"><strong><?php esc_html_e( 'No Fee Type Found!', 'school-management' ); ?></strong></h6>
				<?php endif ?>

				<div id="fees-box" class="wlsm-fees-box" data-fees-type-list="<?php echo ! empty( $fees ) ? esc_attr( json_encode( (array) $fees ) ) : ''; ?>" data-fee-type="<?php esc_attr_e( 'Fee Type', 'school-management' ); ?>" data-fee-type-placeholder="<?php esc_attr_e( 'Enter fee type', 'school-management' ); ?>" data-fee-period="<?php esc_attr_e( 'Period', 'school-management' ); ?>" data-fee-amount="<?php esc_attr_e( 'Amount', 'school-management' ); ?>" data-fee-amount-placeholder="<?php esc_attr_e( 'Enter amount', 'school-management' ); ?>" data-fee-periods="<?php echo esc_attr( json_encode( $fee_periods ) ); ?>">

					<?php
					if ( count( $fees ) ) {
						foreach ( $fees as $key => $fee ) {
							$index = $key + 1;
							?>
							<div class="wlsm-fee-box card col" data-fee="<?php echo esc_attr( $index ); ?>">
								<button type="button" class="btn btn-sm btn-danger wlsm-remove-fee-btn"><i class="fas fa-times"></i></button>
								<input type="hidden" name="fee_id[]" value="<?php echo esc_attr( $fee->ID ); ?>">
								<?php if (isset($fee->active_on_admission)): ?>
									<input type="hidden" name="active_on_admission[]" value="<?php echo esc_attr( $fee->active_on_admission ); ?>">
								<?php endif ?>
								<?php if (isset($fee->active_on_dashboard)): ?>
									<input type="hidden" name="active_on_dashboard[]" value="<?php echo esc_attr( $fee->active_on_dashboard ); ?>">
								<?php endif ?>
								<?php if (isset($fee->assign_on_addmission)): ?>
									<input type="hidden" name="assign_on_addmission[]" value="<?php echo esc_attr( $fee->assign_on_addmission ); ?>">
								<?php endif ?>

								<div class="form-row">
									<div class="form-group col-md-4">
										<label for="wlsm_fee_label_<?php echo esc_attr( $index ); ?>" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e( 'Fee Type', 'school-management' ); ?>:
										</label>
										<input type="text" name="fee_label[]" class="form-control" ID="wlsm_fee_label_<?php echo esc_attr( $index ); ?>" placeholder="<?php esc_attr_e( 'Enter fee type', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $fee->label ) ); ?>">
									</div>
									<div class="form-group col-md-4">
										<label for="wlsm_fee_period_<?php echo esc_attr( $index ); ?>" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e( 'Period', 'school-management' ); ?>:
										</label>
										<select name="fee_period[]" class="form-control selectpicker wlsm_fee_period_selectpicker" id="wlsm_fee_period_<?php echo esc_attr( $index ); ?>" data-live-search="true">
											<?php foreach ( $fee_periods as $key => $value ) { ?>
												<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $fee->period, true ); ?>>
													<?php echo esc_html( $value ); ?>
												</option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label for="wlsm_fee_amount_<?php echo esc_attr( $index ); ?>" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e( 'Amount', 'school-management' ); ?>:
										</label>
										<input type="number" step="any" min="0" name="fee_amount[]" class="form-control" id="wlsm_fee_amount_<?php echo esc_attr( $index ); ?>" placeholder="<?php esc_attr_e( 'Enter amount', 'school-management' ); ?>" value="<?php echo esc_attr( $fee->amount ? WLSM_Config::sanitize_money( $fee->amount ) : '' ); ?>">
									</div>
								</div>
							</div>
							<?php
						}
					} else {
						if ( ! $student ) {
							?>
							<div class="wlsm-fee-box card col" data-fee="1">
								<button type="button" class="btn btn-sm btn-danger wlsm-remove-fee-btn"><i class="fas fa-times"></i></button>

								<div class="form-row">
									<div class="form-group col-md-4">
										<label for="wlsm_fee_label" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e( 'Fee Type', 'school-management' ); ?>:
										</label>
										<input type="text" name="fee_label[]" class="form-control" placeholder="<?php esc_attr_e( 'Enter fee type', 'school-management' ); ?>">
									</div>
									<div class="form-group col-md-4">
										<label for="wlsm_fee_period" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e( 'Period', 'school-management' ); ?>:
										</label>
										<select name="fee_period[]" class="form-control selectpicker wlsm_fee_period_selectpicker" data-live-search="true">
											<?php foreach ( $fee_periods as $key => $value ) { ?>
												<option value="<?php echo esc_attr( $key ); ?>">
													<?php echo esc_html( $value ); ?>
												</option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label for="wlsm_fee_amount" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e( 'Amount', 'school-management' ); ?>:
										</label>
										<input type="number" step="any" min="0" name="fee_amount[]" class="form-control" placeholder="<?php esc_attr_e( 'Enter amount', 'school-management' ); ?>">
									</div>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>

				<div class="form-row mt-3">
					<!-- <div class="col-md-12 text-center">
						<button type="button" class="btn btn-sm btn-outline-primary wlsm-add-fee-btn">
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php esc_html_e( 'Add Fee', 'school-management' ); ?>
						</button>
					</div> -->
					<div class="form-group col-md-4">
						<label for="wlsm_discount" class="wlsm-font-bold">
							<?php esc_html_e( 'Discount', 'school-management' ); ?>:
						</label>
						<input type="number" step="any" min="0" name="invoice_discount" class="form-control" id="wlsm_invoice_discount" placeholder="<?php esc_attr_e( 'Enter discount in percent', 'school-management' ); ?>" >
					</div>
				</div>
			</div>

<!-- Student Login Detail -->
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
						<div class="form-check form-check-inline">
							<input <?php checked( false, (bool) $username, true ); ?> class="form-check-input" type="radio" name="student_new_or_existing" id="wlsm_student_disallow_login" value="">
							<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_student_disallow_login">
								<?php esc_html_e( 'Disallow Login?', 'school-management' ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked( true, (bool) $username, true ); ?> class="form-check-input" type="radio" name="student_new_or_existing" id="wlsm_student_existing_user" value="existing_user">
							<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_student_existing_user">
								<?php esc_html_e( 'Existing User?', 'school-management' ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="student_new_or_existing" id="wlsm_student_new_user" value="new_user">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_student_new_user">
								<?php esc_html_e( 'New User?', 'school-management' ); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="form-row wlsm-student-existing-user">
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
						<input type="text" name="existing_username" class="form-control" id="wlsm_existing_username" placeholder="<?php esc_attr_e( 'Enter existing username', 'school-management' ); ?>" value="<?php echo esc_attr( $username ); ?>" <?php if ( $username ) { echo 'readonly'; } ?> >
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

				<div class="form-row wlsm-student-new-user">
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

			<!-- Parent / Guardian Name and Login Detail -->
			<?php if ( $school_registration_parent_login ) : ?>
				<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Parent / Guardian Login Detail', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<div class="form-check form-check-inline">
							<input <?php checked( false, (bool) $parent_username, true ); ?> class="form-check-input" type="radio" name="parent_new_or_existing" id="wlsm_parent_disallow_login" value="">
							<label class="ml-1 form-check-label text-secondary font-weight-bold" for="wlsm_parent_disallow_login">
								<?php esc_html_e( 'Disallow Login?', 'school-management' ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked( true, (bool) $parent_username, true ); ?> class="form-check-input" type="radio" name="parent_new_or_existing" id="wlsm_parent_existing_user" value="existing_user">
							<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_parent_existing_user">
								<?php esc_html_e( 'Existing User?', 'school-management' ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="parent_new_or_existing" id="wlsm_parent_new_user" value="new_user">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_parent_new_user">
								<?php esc_html_e( 'New User?', 'school-management' ); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="form-row wlsm-parent-existing-user">
					<div class="form-group col-md-4">
						<label for="wlsm_parent_existing_username" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Existing Username', 'school-management' ); ?>:
							<?php if ( $parent_username ) { ?>
								<small>
									<em class="text-secondary">
										<?php esc_html_e( 'Usernames cannot be changed.', 'school-management' ); ?>
									</em>
								</small>
							<?php } ?>
						</label>
						<input type="text" name="parent_existing_username" class="form-control" id="wlsm_parent_existing_username" placeholder="<?php esc_attr_e( 'Enter existing username', 'school-management' ); ?>" value="<?php echo esc_attr( $parent_username ); ?>" <?php if ( $parent_username ) { echo 'readonly'; } ?> >
					</div>

					<?php if ( $parent_username ) { ?>
						<div class="form-group col-md-4">
							<label for="wlsm_parent_new_login_email" class="wlsm-font-bold">
								<?php esc_html_e( 'Login Email', 'school-management' ); ?>:
							</label>
							<input type="email" name="parent_new_login_email" class="form-control" id="wlsm_parent_new_login_email" placeholder="<?php esc_attr_e( 'Enter login email', 'school-management' ); ?>" value="<?php echo esc_attr( $parent_login_email ); ?>">
						</div>

						<div class="form-group col-md-4">
							<label for="wlsm_parent_new_login_password" class="wlsm-font-bold">
								<?php esc_html_e( 'Password', 'school-management' ); ?>:
							</label>
							<input type="password" name="parent_new_password" class="form-control" id="wlsm_parent_new_login_password" placeholder="<?php esc_attr_e( 'Enter password', 'school-management' ); ?>">
						</div>
					<?php } ?>
				</div>

				<div class="form-row wlsm-parent-new-user">
					<div class="form-group col-md-4">
						<label for="wlsm_parent_username" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Username', 'school-management' ); ?>:
						</label>
						<input type="text" name="parent_username" class="form-control" id="wlsm_parent_username" placeholder="<?php esc_attr_e( 'Enter username', 'school-management' ); ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_parent_login_email" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Login Email', 'school-management' ); ?>:
						</label>
						<input type="email" name="parent_login_email" class="form-control" id="wlsm_parent_login_email" placeholder="<?php esc_attr_e( 'Enter login email', 'school-management' ); ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_parent_login_password" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Password', 'school-management' ); ?>:
						</label>
						<input type="password" name="parent_password" class="form-control" id="wlsm_parent_login_password" placeholder="<?php esc_attr_e( 'Enter password', 'school-management' ); ?>">
					</div>
				</div>
			</div>
			<?php endif ?>

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
					<button type="submit" class="btn btn-primary" id="<?php echo esc_attr( $action ); ?>-btn">
						<?php
						if ( $student ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Student', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Submit', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
