<?php
defined('ABSPATH') || die();


require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';
$settings_dashboard                     = WLSM_M_Setting::get_settings_dashboard($school_id);
$school_enrollment_number = $settings_dashboard['school_enrollment_number'];
$school_admission_number  = $settings_dashboard['school_admission_number'];

$student_name = WLSM_M_Staff_Class::get_name_text($student->student_name);

$notices = WLSM_M_Staff_Class::get_school_notices($school_id, 7, $student->ID);

$section = WLSM_M_Staff_Class::get_school_section($school_id, $student->section_id);

$class_label   = $section->class_label;
$section_label = $section->label;

$attendance = WLSM_M_Staff_General::get_student_attendance_stats($student->ID);
$invoices = WLSM_M_Staff_Accountant::get_student_pending_invoices($student->ID);
global $wpdb;
$vehicle_id = $student->route_vehicle_id;
if ($vehicle_id) {
	$query = 'SELECT  ro.name, ro.fare, v.vehicle_number, v.driver_name, v.driver_phone FROM ' . WLSM_ROUTE_VEHICLE . ' as rov
				JOIN ' . WLSM_ROUTES . ' as ro ON ro.ID = rov.route_id
				JOIN ' . WLSM_VEHICLES . ' as v ON v.ID = rov.vehicle_id
				WHERE rov.ID = ' . "$vehicle_id". '';

	$transportation_details = $wpdb->get_results(($query));
}
$invoices = WLSM_M_Staff_Accountant::get_student_pending_invoices_paid($student->ID, 1);
$check_dashboard_display = WLSM_M_Setting::get_dash($invoices);

// School Information 
$schools = $wpdb->get_results('SELECT s.ID, s.label, s.phone, s.email, s.address, s.is_active, s.is_active FROM ' . WLSM_SCHOOLS . ' as s WHERE s.ID = ' . $school_id . '');
?>
<?php 
	if(	$schools[0]->{'is_active'} === '0'){
		echo '<span style="color: red;"> This School is not active </span>';
		die;
	}
?>
<?php if ($invoices and 'paid' !== $check_dashboard_display) {
	require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/pending_fee_invoices.php';
} else {
	require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php'; ?>
	<div class="wlsm-content-area wlsm-section-dashboard wlsm-student-dashboard">

		<span class="wlsm-pl-2">
			<?php
			/* translators: %s: student name */
			printf(
				wp_kses(
					'Student Name: <span class="wlsm-font-bold">%s</span>',
					array('span' => array('class' => array()))
				),
				esc_html($student_name)
			);
			?>
		</span>
		<div class="wlsm-fex">
		<!-- School name information -->
		<?php foreach ($schools as $school) : ?>
			<span class=" wlsm-pl-2">
				<?php esc_html_e('School Name:', 'school-management') ?>
				<strong> <?php esc_html_e($school->label, 'school-management') ?></strong>
			</span>
			<br>
			<span class="wlsm-pl-2">
				<?php esc_html_e('School Email:', 'school-management') ?>
				<strong> <?php esc_html_e($school->email, 'school-management') ?></strong>
			</span>
			<span class=" wlsm-pl-2">
				<?php esc_html_e('School Phone:', 'school-management') ?>
				<strong> <?php esc_html_e($school->phone, 'school-management') ?></strong>
			</span>
			<br>
			<span class="wlsm-pl-2">
				<?php esc_html_e('School Address:', 'school-management') ?>
				<strong> <?php esc_html_e($school->address, 'school-management') ?></strong>
			</span>
		<?php endforeach ?>
		<!-- School name information -->
		
		<a style="float: right;" class="" href="<?php echo esc_url(add_query_arg(array('action' => 'settings'), $current_page_url)); ?>"><?php esc_html_e('Account Settings', 'school-management'); ?></a>
		<br>

		<div class="wlsm-flex-between">
			<div class="wlsm-flex-item wlsm-l-w-50 wlsm-mt-3">
				<div class="wlsm-st-details-heading wlsm-mt-3">
					<span><?php esc_html_e('Noticeboard', 'school-management'); ?></span>
				</div>
				<div class="wlsm-st-recent-notices-section">
					<?php
					if (count($notices)) {
						$today = new DateTime();
						$today->setTime(0, 0, 0);
					?>
						<ul class="wlsm-st-recent-notices">
							<?php
							foreach ($notices as $key => $notice) {
								$link_to = $notice->link_to;
								$link    = '#';

								if ('url' === $link_to) {
									if (!empty($notice->url)) {
										$link = $notice->url;
									}
								} else if ('attachment' === $link_to) {
									if (!empty($notice->attachment)) {
										$attachment = $notice->attachment;
										$link       = wp_get_attachment_url($attachment);
									}
								} else {
									$link = '#';
								}

								$notice_date = DateTime::createFromFormat('Y-m-d H:i:s', $notice->created_at);
								$notice_date->setTime(0, 0, 0);

								$interval = $today->diff($notice_date);
							?>
								<li>
									<span>
										<a target="_blank" href="<?php echo esc_url($link); ?>"><?php echo esc_html(stripslashes($notice->title)); ?> <span class="wlsm-st-notice-date wlsm-font-bold"><?php echo esc_html(WLSM_Config::get_date_text($notice->created_at)); ?></span></a>
										<?php if ($interval->days < 7) { ?>
											<img class="wlsm-st-notice-new" src="<?php echo esc_url(WLSM_PLUGIN_URL . 'assets/images/newicon.gif'); ?>">
										<?php } ?>
									</span>
								</li>
							<?php
							}
							?>
						</ul>
					<?php
					} else {
					?>
						<div>
							<span class="wlsm-font-medium wlsm-font-bold">
								<?php esc_html_e('There is no notice.', 'school-management'); ?>
							</span>
						</div>
					<?php
					}
					?>
				</div>
			</div>
			<div class="wlsm-flex-item wlsm-l-w-48 wlsm-mt-2">
				

				<div class="wlsm-st-details">
					<div class="wlsm-st-details-heading">
						<span><?php esc_html_e('Your Details', 'school-management'); ?></span>
					</div>
					<!-- student photo -->
					<?php if ($student->photo_id): ?>
						
						<img src="<?php echo esc_url(wp_get_attachment_url($student->photo_id)); ?>" alt="student picture" class="wlsm-img-thumbnail">
					<?php endif ?>
					<ul class="wlsm-st-details-list">
						<li>
							<span class="wlsm-st-details-list-key"><?php esc_html_e('Name'); ?>:</span>
							<span class="wlsm-st-details-list-value"><?php echo esc_html($student_name); ?></span>
						</li>
						<?php if ($school_enrollment_number): ?>
							<li>
								<span class="wlsm-st-details-list-key"><?php esc_html_e('Enrollment Number', 'school-management'); ?>:</span>
								<span class="wlsm-st-details-list-value"><?php echo esc_html($student->enrollment_number); ?></span>
							</li>
						<?php endif ?>

						<?php if ($school_admission_number): ?>
							<li>
								<span class="wlsm-st-details-list-key"><?php esc_html_e('Admission Number', 'school-management'); ?>:</span>
								<span class="wlsm-st-details-list-value"><?php echo esc_html($student->admission_number); ?></span>
							</li>
						<?php endif ?>
						
						<li>
							<span class="wlsm-st-details-list-key"><?php esc_html_e('Session', 'school-management'); ?>:</span>
							<span class="wlsm-st-details-list-value"><?php echo esc_html(WLSM_M_Session::get_label_text($student->session_label)); ?></span>
						</li>
						<li>
							<span class="wlsm-st-details-list-key"><?php esc_html_e('Class', 'school-management'); ?>:</span>
							<span class="wlsm-st-details-list-value"><?php echo esc_html(WLSM_M_Class::get_label_text($student->class_label)); ?></span>
						</li>
						<li>
							<span class="wlsm-st-details-list-key"><?php esc_html_e('Section', 'school-management'); ?>:</span>
							<span class="wlsm-st-details-list-value"><?php echo esc_html(WLSM_M_Class::get_label_text($student->section_label)); ?></span>
						</li>
						<li>
							<span class="wlsm-st-details-list-key"><?php esc_html_e('Roll Number', 'school-management'); ?>:</span>
							<span class="wlsm-st-details-list-value"><?php echo esc_html(WLSM_M_Staff_Class::get_roll_no_text($student->roll_number)); ?></span>
						</li>
						<li>
							<span class="wlsm-st-details-list-key"><?php esc_html_e('Father\'s Name', 'school-management'); ?>:</span>
							<span class="wlsm-st-details-list-value"><?php echo esc_html(WLSM_M_Staff_Class::get_name_text($student->father_name)); ?></span>
						</li>
						<li>
							<span class="wlsm-st-details-list-key"><?php esc_html_e('ID Card', 'school-management'); ?>:</span>
							<span class="wlsm-st-details-list-value">
								<a class="wlsm-st-print-id-card" data-id-card="<?php echo esc_attr($user_id); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('st-print-id-card-' . $user_id)); ?>" href="#" data-message-title="<?php echo esc_attr__('Print ID Card', 'school-management'); ?>">
									<?php esc_html_e('Print', 'school-management'); ?>
								</a>
							</span>
						</li>
					</ul>
					
					<div class="wlsm-st-details-heading">
						<span><?php esc_html_e('Your Attendance', 'school-management'); ?></span>
					</div>
					<span class="wlsm-st-details-list wlsm-st-attendance-section">
						<ul class="wlsm-st-attendance-stats">
							<li><?php echo esc_html($attendance['percentage_text']); ?></li>
						</ul>
					</span>
				
					<br>
					<?php if ($vehicle_id) : ?>
						<div class="wlsm-st-details-heading">
							<span><?php esc_html_e('Transportation Details', 'school-management'); ?></span>
						</div>
						<ul class="wlsm-st-details-list">
							<li>
								<span class="wlsm-st-details-list-key"><?php esc_html_e('Route Name', 'school-management'); ?>:</span>
								<span class="wlsm-st-details-list-value"><?php foreach ($transportation_details as $transport) {
																				if ($transport->name) {
																					echo esc_html($transport->name);
																				} else {
																					echo '-';
																				}
																			} ?></span>
							</li>
							<li>
								<span class="wlsm-st-details-list-key"><?php esc_html_e('Vehicle Number', 'school-management'); ?>:</span>
								<span class="wlsm-st-details-list-value">
									<?php foreach ($transportation_details as $transport) {
										if ($transport->vehicle_number) {
											echo esc_html($transport->vehicle_number);
										} else {
											echo '-';
										}
									} ?></span>
							</li>
							<li>
								<span class="wlsm-st-details-list-key"><?php esc_html_e('Fare', 'school-management'); ?>:</span>
								<span class="wlsm-st-details-list-value"><?php foreach ($transportation_details as $transport) {
																				if ($transport->fare) {
																					echo esc_html($transport->fare);
																				} else {
																					echo '-';
																				}
																			} ?></span>
							</li>
							<li>
								<span class="wlsm-st-details-list-key"><?php esc_html_e("Driver's Name", 'school-management'); ?>:</span>
								<span class="wlsm-st-details-list-value"><?php foreach ($transportation_details as $transport) {
																				if ($transport->driver_name) {
																					echo esc_html($transport->driver_name);
																				} else {
																					echo '-';
																				}
																			} ?></span>
							</li>
							<li>
								<span class="wlsm-st-details-list-key"><?php esc_html_e("Driver's Mobile", 'school-management'); ?>:</span>
								<span class="wlsm-st-details-list-value"><?php foreach ($transportation_details as $transport) {
																				if ($transport->driver_phone) {
																					echo esc_html($transport->driver_phone);
																				} else {
																					echo '-';
																				}
																			} ?></span>
							</li>
						</ul>
					<?php endif ?>

					<br>

					<?php 
					$student_id = $student->ID;
					$student = WLSM_M_Staff_General::fetch_student($school_id, $session_id, $student_id);
					$fee_structure = WLSM_M_Staff_Accountant::fetch_student_assigned_fees($school_id, $student_id);

					$fees     = WLSM_M_Staff_Accountant::fetch_student_fees($school_id, $student_id);
					$invoices = WLSM_M_Staff_Accountant::get_student_invoices($student_id);
					$payments = WLSM_M_Staff_Accountant::get_student_payments($student_id);

					$class_label = WLSM_M_Class::get_label_text( $student->class_label );
					$start_date = $student->start_date;
					$end_date = $student->end_date;

					// get start_date and end_date difference in months
					$start_date = new DateTime($start_date);
					$end_date = new DateTime($end_date);
					$interval = $start_date->diff($end_date);
					$months_in_session = $interval->format('%m');

					$session_onetime_total     = 0;
					$session_quarterly_total   = 0;
					$session_half_yearly_total = 0;
					$session_monthly_total     = 0;

					// calculate fee type for current session total fee.
					foreach ( $fees as $key => $fee ) {

						if ($fee->period == "monthly") {
							$session_monthly_total = intval($fee->amount)*$months_in_session;
							
						} elseif ($fee->period == 'one-time'){
							$session_onetime_total += intval($fee->amount);
							
						} elseif ($fee->period == 'quarterly'){
						$session_quarterly_total += intval($fee->amount * $months_in_session / 3);
							
						} elseif ($fee->period == 'half-yearly'){
							$session_half_yearly_total += intval($fee->amount * $months_in_session / 6);
							
						}    
					}                                
						?>
					<div class="wlsm-st-details-heading">
						<span><?php esc_html_e('Class Fee Type Total', 'school-management'); ?></span><br>
					</div>
					<span><strong> <?php esc_html_e('Payable : ', 'school-management') ?> </strong></span>
					<?php echo esc_html( WLSM_Config::get_money_text( $session_monthly_total + $session_onetime_total+ $session_quarterly_total +$session_half_yearly_total, $school_id ) ); ?>
					<br>

					<?php 
					$payments_query = WLSM_M::payments_query();

					$payments_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$payments_query}) AS combined_table", $student->ID ) );
					
					$payments = $wpdb->get_results( $wpdb->prepare( $payments_query . ' ORDER BY p.ID DESC', $student->ID ) );

					$total_paid = 0;
					foreach ( $payments as $row ) {
						$total_paid += $row->amount;
					}
					?>
					<span><strong><?php esc_html_e('Total Paid : ', 'school-management') ?>  </strong> <?php echo esc_html( WLSM_Config::get_money_text($total_paid, $school_id)); ?></span> 
					<hr>
					<p> <strong><?php  esc_html_e('Note:', 'school-management') ?></strong> <?php esc_html_e('Total paid shows the all payments made by student. Payable will only show current session fee type amount estimate.', 'school-management') ?></p>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
