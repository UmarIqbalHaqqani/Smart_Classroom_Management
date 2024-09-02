<?php
defined( 'ABSPATH' ) || die();

// Registration settings.
$settings_registration                     = WLSM_M_Setting::get_settings_registration( $school_id );
$school_registration_form_title            = $settings_registration['form_title'];
$school_registration_login_user            = $settings_registration['login_user'];
$school_registration_redirect_url          = $settings_registration['redirect_url'];
$school_registration_create_invoice        = $settings_registration['create_invoice'];
$school_registration_auto_admission_number = $settings_registration['auto_admission_number'];
$school_registration_auto_roll_number      = $settings_registration['auto_roll_number'];
$school_registration_admin_phone           = $settings_registration['admin_phone'];
$school_registration_admin_email           = $settings_registration['admin_email'];
$school_registration_success_message       = $settings_registration['success_message'];
$school_student_aprove                     = $settings_registration['student_aprove'];


$school_registration_dob               = $settings_registration['dob'];
$school_gender                         = $settings_registration['gender'];
$school_registration_religion          = $settings_registration['religion'];
$school_registration_caste             = $settings_registration['caste'];
$school_registration_blood_group       = $settings_registration['blood_group'];
$school_registration_phone             = $settings_registration['phone'];
$school_registration_city              = $settings_registration['city'];
$school_registration_state             = $settings_registration['state'];
$school_registration_country           = $settings_registration['country'];
$school_registration_transport         = $settings_registration['transport'];
$school_registration_parent_detail     = $settings_registration['parent_detail'];
$school_registration_parent_occupation = $settings_registration['parent_occupation'];
$school_registration_parent_login      = $settings_registration['parent_login'];
$school_registration_id_number         = $settings_registration['id_number'];
$school_registration_survey            = $settings_registration['survey'];
$school_registration_fees              = $settings_registration['fees'];
$school_registration_medium            = $settings_registration['medium'];

$school_registration_success_placeholders = WLSM_Helper::registration_success_message_placeholders();
?>
<div class="tab-pane fade" id="wlsm-school-registration" role="tabpanel" aria-labelledby="wlsm-school-registration-tab">

	<div class="row">
		<div class="col-md-9">
			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-registration-settings-form">
				<?php
				$nonce_action = 'save-school-registration-settings';
				$nonce        = wp_create_nonce( $nonce_action );
				?>
				<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-registration-settings">

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_form_title" class="wlsm-font-bold"><?php esc_html_e( 'Registration Form Title', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input name="registration_form_title" type="text" id="wlsm_registration_form_title" value="<?php echo esc_attr( $school_registration_form_title ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Registration form title', 'school-management' ); ?>">
							<p class="description">
								<?php esc_html_e( 'Works only when school_id is specified in the registration shortcode.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_login_user" class="wlsm-font-bold"><?php esc_html_e( 'Login after Registration', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input <?php checked( $school_registration_login_user, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_login_user" id="wlsm_registration_login_user" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_login_user">
								<?php esc_html_e( 'Login after Registration', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'This will login the student after registration.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_redirect_url" class="wlsm-font-bold"><?php esc_html_e( 'Redirect URL', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input name="redirect_url" type="text" id="wlsm_redirect_url" value="<?php echo esc_attr( $school_registration_redirect_url ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Redirect URL', 'school-management' ); ?>">
							<p class="description">
								<?php esc_html_e( 'Enter URL where to redirect the student after registration.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_create_invoice" class="wlsm-font-bold"><?php esc_html_e( 'Create Invoice from Fee Type', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input <?php checked( $school_registration_create_invoice, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_create_invoice" id="wlsm_registration_create_invoice" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_create_invoice">
								<?php esc_html_e( 'Create Invoice from Fee Type?', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'For every fee type, an invoice will be created. This is valid only for registrations from front registration form.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_auto_admission_number" class="wlsm-font-bold"><?php esc_html_e( 'Auto Generate Admission Number', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input <?php checked( $school_registration_auto_admission_number, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_auto_admission_number" id="wlsm_registration_auto_admission_number" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_auto_admission_number">
								<?php esc_html_e( 'Auto Generate Admission Number for Back-end Form?', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission number is auto-generated in front-end form. With this, you can auto-generate admission number in back-end form also.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_auto_roll_number" class="wlsm-font-bold"><?php esc_html_e( 'Auto Generate Roll Number', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input <?php checked( $school_registration_auto_roll_number, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_auto_roll_number" id="wlsm_registration_auto_roll_number" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_auto_roll_number">
								<?php esc_html_e( 'Auto Generate roll Number for Back-end Form?', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Roll number is auto-generated.', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_admin_phone" class="wlsm-font-bold"><?php esc_html_e( 'Admin Phone Number', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input name="registration_admin_phone" type="text" id="wlsm_registration_admin_phone" value="<?php echo esc_attr( $school_registration_admin_phone ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Admin phone number to receive registration notification', 'school-management' ); ?>">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_admin_email" class="wlsm-font-bold"><?php esc_html_e( 'Admin Email Address', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input name="registration_admin_email" type="email" id="wlsm_registration_admin_email" value="<?php echo esc_attr( $school_registration_admin_email ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Admin email address to receive registration notification', 'school-management' ); ?>">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_success_message" class="wlsm-font-bold"><?php esc_html_e( 'Success Message', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="mb-1">
							<span class="wlsm-font-bold text-dark"><?php esc_html_e( 'You can use the following variables:', 'school-management' ); ?></span>
							<div class="d-flex">
								<?php foreach ( $school_registration_success_placeholders as $key => $value ) { ?>
									<div class="col-sm-6 col-md-3 pb-1 pt-1 border">
										<span class="wlsm-font-bold text-secondary"><?php echo esc_html( $value ); ?></span>
										<br>
										<span><?php echo esc_html( $key ); ?></span>
									</div>
								<?php } ?>
							</div>
						</div>

						<div class="form-group">
							<textarea name="registration_success_message" id="wlsm_registration_success_message" class="form-control" rows="6" placeholder="<?php esc_attr_e( 'Success Message', 'school-management' ); ?>"><?php echo esc_html( $school_registration_success_message ); ?></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_student_aprove" class="wlsm-font-bold"><?php esc_html_e( 'Student Approval', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<input <?php checked( $school_student_aprove, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="student_aprove" id="wlsm_student_aprove" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_student_aprove">
								<?php esc_html_e( 'If Checked Student Will Be Inactive After Registration From Front End.  ( It Will Require Admin or Staff approval )', 'school-management' ); ?>
							</label>
						</div>
					</div>
				</div>

				<!-- Addmission form options -->
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_dob" class="wlsm-font-bold"><?php esc_html_e( 'Date Of Birth', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_dob, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_dob" id="wlsm_registration_dob" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_dob">
								<?php esc_html_e( '  Show Date Of Birth', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable date of birth field', 'school-management' ); ?>
							</p>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_registration_religion" class="wlsm-font-bold"><?php esc_html_e( 'Religion', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_religion, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_religion" id="wlsm_registration_religion" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_religion">
								<?php esc_html_e( '  Show Religion', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable Religion', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- <div class="col-md-3">
						<label for="wlsm_gender" class="wlsm-font-bold"><?php esc_html_e( 'Gender', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_gender, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="gender" id="wlsm_gender" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_gender">
								<?php esc_html_e( '  Show Gender', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Gender form will disable date of birth field', 'school-management' ); ?>
							</p>
						</div>
					</div> -->

				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_caste" class="wlsm-font-bold"><?php esc_html_e( 'Caste', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_caste, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_caste" id="wlsm_registration_caste" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_caste">
								<?php esc_html_e( '  Show caste', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable caste', 'school-management' ); ?>
							</p>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_registration_blood_group" class="wlsm-font-bold"><?php esc_html_e( 'Blood Group', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_blood_group, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_blood_group" id="wlsm_registration_blood_group" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_blood_group">
								<?php esc_html_e( '  Show blood_group', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable blood_group', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_phone" class="wlsm-font-bold"><?php esc_html_e( 'Phone', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_phone, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_phone" id="wlsm_registration_phone" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_phone">
								<?php esc_html_e( '  Show phone', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable phone', 'school-management' ); ?>
							</p>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_registration_city" class="wlsm-font-bold"><?php esc_html_e( 'City', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_city, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_city" id="wlsm_registration_city" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_city">
								<?php esc_html_e( '  Show city', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable city', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_state" class="wlsm-font-bold"><?php esc_html_e( 'State', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_state, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_state" id="wlsm_registration_state" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_state">
								<?php esc_html_e( '  Show state', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable state', 'school-management' ); ?>
							</p>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_registration_country" class="wlsm-font-bold"><?php esc_html_e( 'Country', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_country, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_country" id="wlsm_registration_country" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_country">
								<?php esc_html_e( '  Show Country', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable Country field', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_id_number" class="wlsm-font-bold"><?php esc_html_e( 'Id Number', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_id_number, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_id_number" id="wlsm_registration_id_number" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_id_number">
								<?php esc_html_e( '  Show Id Number', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable id Number field field', 'school-management' ); ?>
							</p>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_registration_transport" class="wlsm-font-bold"><?php esc_html_e( 'Transport Detail', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_transport, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_transport" id="wlsm_registration_transport" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_transport">
								<?php esc_html_e( '  Show Transport Detail', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable Transport panel', 'school-management' ); ?>
							</p>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_registration_survey" class="wlsm-font-bold"><?php esc_html_e( 'Survey Detail', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_survey, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_survey" id="wlsm_registration_survey" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_survey">
								<?php esc_html_e( '  Show Survey Detail', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable Survey panel', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_parent_detail" class="wlsm-font-bold"><?php esc_html_e( 'Parents Detail Panel', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_parent_detail, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_parent_detail" id="wlsm_registration_parent_detail" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_parent_detail">
								<?php esc_html_e( '  Show Parents Detail Panel', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Registration form will not show entire parent panel', 'school-management' ); ?>
							</p>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_registration_parent_login" class="wlsm-font-bold"><?php esc_html_e( 'Parents login Panel', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_parent_login, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_parent_login" id="wlsm_registration_parent_login" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_parent_login">
								<?php esc_html_e( '  Show Parents Login Panel', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will not show parents signup/login detail', 'school-management' ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_registration_parent_occupation" class="wlsm-font-bold"><?php esc_html_e( 'Occupation', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_parent_occupation, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_parent_occupation" id="wlsm_registration_parent_occupation" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_parent_occupation">
								<?php esc_html_e( '  Show Occupation', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Registration form will not show entire parent occupation', 'school-management' ); ?>
							</p>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_registration_fees" class="wlsm-font-bold"><?php esc_html_e( 'Fees Detail', 'school-management' ); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked( $school_registration_fees, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_fees" id="wlsm_registration_fees" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_fees">
								<?php esc_html_e( '  Show Fees Detail', 'school-management' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Admission Registration form will disable Fees panel', 'school-management' ); ?>
							</p>
						</div>
					</div>
					
				</div>

				<div class="row">
					<div class="col-md-3">
							<label for="wlsm_registration_medium" class="wlsm-font-bold"><?php esc_html_e( 'Medium Detail', 'school-management' ); ?>:</label>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<input <?php checked( $school_registration_medium, true, true ); ?> class="form-check-input mt-1" type="checkbox" name="registration_medium" id="wlsm_registration_medium" value="1">
								<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_registration_medium">
									<?php esc_html_e( '  Show Medium', 'school-management' ); ?>
								</label>
								<p class="description">
									<?php esc_html_e( 'Admission Registration form will disable Fees panel', 'school-management' ); ?>
								</p>
							</div>
						</div>
				</div>

				<div class="row">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-registration-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e( 'Save', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
