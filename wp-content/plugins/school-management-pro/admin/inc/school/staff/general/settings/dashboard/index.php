<?php
defined('ABSPATH') || die();

// dashboard settings.
$settings_dashboard                     = WLSM_M_Setting::get_settings_dashboard($school_id);
$school_invoice           = $settings_dashboard['school_invoice'];
$school_payment_history   = $settings_dashboard['school_payment_history'];
$school_study_material    = $settings_dashboard['school_study_material'];
$school_home_work         = $settings_dashboard['school_home_work'];
$school_noticeboard       = $settings_dashboard['school_noticeboard'];
$school_events            = $settings_dashboard['school_events'];
$school_class_time_table  = $settings_dashboard['school_class_time_table'];
$school_live_classes      = $settings_dashboard['school_live_classes'];
$school_books_issues      = $settings_dashboard['school_books_issues'];
$school_exam_time_table   = $settings_dashboard['school_exam_time_table'];
$school_admit_card        = $settings_dashboard['school_admit_card'];
$school_exam_result       = $settings_dashboard['school_exam_result'];
$school_certificate       = $settings_dashboard['school_certificate'];
$school_attendance        = $settings_dashboard['school_attendance'];
$school_leave_request     = $settings_dashboard['school_leave_request'];
$school_enrollment_number = $settings_dashboard['school_enrollment_number'];
$school_admission_number  = $settings_dashboard['school_admission_number'];


$school_parent_id_card          = $settings_dashboard['parent_id_card'];
$school_parent_fee_invoice      = $settings_dashboard['parent_fee_invoice'];
$school_parent_payement_history = $settings_dashboard['parent_payement_history'];
$school_parent_noticeboard      = $settings_dashboard['parent_noticeboard'];
$school_parent_class_time_table = $settings_dashboard['parent_class_time_table'];
$school_parent_exam_results     = $settings_dashboard['parent_exam_results'];
$school_parent_attendance       = $settings_dashboard['parent_attendance'];

?>
<div class="tab-pane fade" id="wlsm-school-dashboard" role="tabpanel" aria-labelledby="wlsm-school-dashboard-tab">

	<div class="row">
		<div class="col-md-9">
			<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-school-dashboard-settings-form">
				<?php
				$nonce_action = 'save-school-dashboard-settings';
				$nonce        = wp_create_nonce($nonce_action);
				?>
				<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-dashboard-settings">

				<!-- Addmission form options -->
				<div class="text-center my-5"><h2><strong><?php esc_html_e('Student Dashboard Settings', 'school-management'); ?> </strong></h2></div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_invoice" class="wlsm-font-bold"><?php esc_html_e('Invoice', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_invoice, true, true); ?> class="form-check-input mt-1" type="checkbox" name="invoice" id="wlsm_invoice" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_invoice">
								<?php esc_html_e("  Show Invoice", 'school-management'); ?>
							</label>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_payment_history" class="wlsm-font-bold"><?php esc_html_e('Payment History', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_payment_history, true, true); ?> class="form-check-input mt-1" type="checkbox" name="payment_history" id="wlsm_payment_history" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_payment_history">
								<?php esc_html_e("  Show Payment History", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_study_material" class="wlsm-font-bold"><?php esc_html_e('Study Material', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_study_material, true, true); ?> class="form-check-input mt-1" type="checkbox" name="study_material" id="wlsm_study_material" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_study_material">
								<?php esc_html_e("  Show study material", 'school-management'); ?>
							</label>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_home_work" class="wlsm-font-bold"><?php esc_html_e('Home Work', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_home_work, true, true); ?> class="form-check-input mt-1" type="checkbox" name="home_work" id="wlsm_home_work" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_home_work">
								<?php esc_html_e("  Home Work", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_noticeboard" class="wlsm-font-bold"><?php esc_html_e('Noticeboard', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_noticeboard, true, true); ?> class="form-check-input mt-1" type="checkbox" name="noticeboard" id="wlsm_noticeboard" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_noticeboard">
								<?php esc_html_e("  Show noticeboard", 'school-management'); ?>
							</label>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_events" class="wlsm-font-bold"><?php esc_html_e('Events', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_events, true, true); ?> class="form-check-input mt-1" type="checkbox" name="events" id="wlsm_events" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_events">
								<?php esc_html_e("  Show Events", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_class_time_table" class="wlsm-font-bold"><?php esc_html_e('class time table', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_class_time_table, true, true); ?> class="form-check-input mt-1" type="checkbox" name="class_time_table" id="wlsm_class_time_table" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_class_time_table">
								<?php esc_html_e("  Show class time table", 'school-management'); ?>
							</label>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_live_classes" class="wlsm-font-bold"><?php esc_html_e('Live Classes', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_live_classes, true, true); ?> class="form-check-input mt-1" type="checkbox" name="live_classes" id="wlsm_live_classes" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_live_classes">
								<?php esc_html_e("  Show Live classes", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_books_issues" class="wlsm-font-bold"><?php esc_html_e('books issues', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_books_issues, true, true); ?> class="form-check-input mt-1" type="checkbox" name="books_issues" id="wlsm_books_issues" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_books_issues">
								<?php esc_html_e("  Show books issues", 'school-management'); ?>
							</label>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_exam_time_table" class="wlsm-font-bold"><?php esc_html_e('Exam Time Table', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_exam_time_table, true, true); ?> class="form-check-input mt-1" type="checkbox" name="exam_time_table" id="wlsm_exam_time_table" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_exam_time_table">
								<?php esc_html_e("  Show Exam Time Table", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_admit_card" class="wlsm-font-bold"><?php esc_html_e('admit card', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_admit_card, true, true); ?> class="form-check-input mt-1" type="checkbox" name="admit_card" id="wlsm_admit_card" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_admit_card">
								<?php esc_html_e("  Show admit card", 'school-management'); ?>
							</label>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_exam_result" class="wlsm-font-bold"><?php esc_html_e('Exam Result', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_exam_result, true, true); ?> class="form-check-input mt-1" type="checkbox" name="exam_result" id="wlsm_exam_result" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_exam_result">
								<?php esc_html_e("  Show Exam Result", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_certificate" class="wlsm-font-bold"><?php esc_html_e('certificate', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_certificate, true, true); ?> class="form-check-input mt-1" type="checkbox" name="certificate" id="wlsm_certificate" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_certificate">
								<?php esc_html_e("  Show certificate", 'school-management'); ?>
							</label>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_attendance" class="wlsm-font-bold"><?php esc_html_e('Attendance', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_attendance, true, true); ?> class="form-check-input mt-1" type="checkbox" name="attendance" id="wlsm_attendance" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_attendance">
								<?php esc_html_e("  Show attendance", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_leave_request" class="wlsm-font-bold"><?php esc_html_e('leave request', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_leave_request, true, true); ?> class="form-check-input mt-1" type="checkbox" name="leave_request" id="wlsm_leave_request" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_leave_request">
								<?php esc_html_e("  Show leave request", 'school-management'); ?>
							</label>
						</div>
					</div>

				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_enrollment_number" class="wlsm-font-bold"><?php esc_html_e('Enrollment Number', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_enrollment_number, true, true); ?> class="form-check-input mt-1" type="checkbox" name="enrollment_number" id="wlsm_enrollment_number" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_enrollment_number">
								<?php esc_html_e("  Show Enrollment Number", 'school-management'); ?>
							</label>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_admission_number" class="wlsm-font-bold"><?php esc_html_e('Admission Number', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_admission_number, true, true); ?> class="form-check-input mt-1" type="checkbox" name="admission_number" id="wlsm_admission_number" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_admission_number">
								<?php esc_html_e("  Show Admission Number", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>
				<!-- Parent dashboard Settings  -->
				<div class="text-center my-5"><h2><strong><?php esc_html_e('Parent Dashboard Settings', 'school-management'); ?> </strong></h2></div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_parent_id_card" class="wlsm-font-bold"><?php esc_html_e('Id Card', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_parent_id_card, true, true); ?> class="form-check-input mt-1" type="checkbox" name="parent_id_card" id="wlsm_parent_id_card" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_parent_id_card">
								<?php esc_html_e("  Show parent id card", 'school-management'); ?>
							</label>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_parent_fee_invoice" class="wlsm-font-bold"><?php esc_html_e('Fee Invoice', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_parent_fee_invoice, true, true); ?> class="form-check-input mt-1" type="checkbox" name="parent_fee_invoice" id="wlsm_parent_fee_invoice" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_parent_fee_invoice">
								<?php esc_html_e("  Show parent fee invoice", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_parent_payement_history" class="wlsm-font-bold"><?php esc_html_e('Payement history', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_parent_payement_history, true, true); ?> class="form-check-input mt-1" type="checkbox" name="parent_payement_history" id="wlsm_parent_payement_history" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_parent_payement_history">
								<?php esc_html_e("  Show parent payement history", 'school-management'); ?>
							</label>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_parent_noticeboard" class="wlsm-font-bold"><?php esc_html_e('Noticeboard', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_parent_noticeboard, true, true); ?> class="form-check-input mt-1" type="checkbox" name="parent_noticeboard" id="wlsm_parent_noticeboard" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_parent_noticeboard">
								<?php esc_html_e("  Show parent noticeboard", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_parent_class_time_table" class="wlsm-font-bold"><?php esc_html_e('Class Time Table', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_parent_class_time_table, true, true); ?> class="form-check-input mt-1" type="checkbox" name="parent_class_time_table" id="wlsm_parent_class_time_table" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_parent_class_time_table">
								<?php esc_html_e("  Show Class Time Table", 'school-management'); ?>
							</label>
						</div>
					</div>

					<div class="col-md-3">
						<label for="wlsm_parent_exam_results" class="wlsm-font-bold"><?php esc_html_e('Exam Results', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_parent_exam_results, true, true); ?> class="form-check-input mt-1" type="checkbox" name="parent_exam_results" id="wlsm_parent_exam_results" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_parent_exam_results">
								<?php esc_html_e("  Show parent noticeboardExam Results", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label for="wlsm_parent_attendance" class="wlsm-font-bold"><?php esc_html_e('Class Attendance Report', 'school-management'); ?>:</label>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input <?php checked($school_parent_attendance, true, true); ?> class="form-check-input mt-1" type="checkbox" name="parent_attendance" id="wlsm_parent_attendance" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_parent_attendance">
								<?php esc_html_e("  Show Attendance Report", 'school-management'); ?>
							</label>
						</div>
					</div>
				</div>

				<!-- Parent dashboard Settings  -->

				<div class="row">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-dashboard-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e('Save', 'school-management'); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
