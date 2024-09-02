<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';

class WLSM_P_Invoice_History {
	public static function get_invoice_history() {
		if (!wp_verify_nonce($_POST['nonce'], 'get-pending-invoices-history')) {
			die();
		}

		try {
			ob_start();
			global $wpdb;

			$school_id  = isset($_POST['school_id']) ? absint($_POST['school_id']) : 0;
			$session_id_from = isset( $_POST['session_id_from'] ) ? sanitize_text_field( $_POST['session_id_from'] ) : '';
			$session_id_to   = isset( $_POST['session_id_to'] ) ? sanitize_text_field( $_POST['session_id_to'] ) : '';

			// $name = isset($_POST['student_name']) ? sanitize_text_field($_POST['student_name']) : '';

			// Start validation.
			$errors = array();

			// Check if session exists.
			// $session = WLSM_M_Session::get_session($session_id);

			if (!$session_id_from) {
				throw new Exception(esc_html__('Session not found.', 'school-management'));
			}
			if (!$session_id_to) {
				throw new Exception(esc_html__('Session not found.', 'school-management'));
			}

			if (empty($school_id)) {
				$errors['school_id'] = esc_html__('Please select a school.', 'school-management');
			} else {
				// Checks if school exists.
				$school = WLSM_M_School::get_active_school($school_id);

				if (!$school) {
					$errors['school_id'] = esc_html__('Please select a school.', 'school-management');
				}
			}

			if (count($errors) > 0) {
				wp_send_json_error($errors);
			}

			// Get class students in a session with the name provided.
			$students = $wpdb->get_results(
				$wpdb->prepare('SELECT sr.ID, sr.name, sr.enrollment_number, c.label as class_label, se.label as section_label, sr.roll_number FROM ' . WLSM_STUDENT_RECORDS . ' as sr
					JOIN ' . WLSM_INVOICES . ' as i ON i.student_record_id = sr.ID
					JOIN ' . WLSM_SECTIONS . ' as se ON se.ID = sr.section_id
					JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.ID = se.class_school_id
					JOIN ' . WLSM_CLASSES . ' as c ON c.ID = cs.class_id
					JOIN ' . WLSM_SESSIONS . ' as ss ON ss.ID = sr.session_id
					LEFT OUTER JOIN ' . WLSM_TRANSFERS . ' as tf ON tf.from_student_record = sr.ID
					WHERE i.status != %s AND sr.session_id BETWEEN %d AND %d AND tf.ID IS NULL GROUP BY sr.ID', 'paid', $session_id_from, $session_id_to )
			);

		} catch (Exception $exception) {
			$buffer = ob_get_clean();
			if (!empty($buffer)) {
				$response = esc_html__('An unexpected error occurred!', 'school-management');
			} else {
				$response = $exception->getMessage();
			}
			wp_send_json_error($response);
		}

		if (count($errors) < 1) {
			try {
				ob_start();

				if (count($students)) {
?>
					<!-- Students with pending invoices. -->
					<div class="wlsm-table-section">
						<div class="wlsm-table-caption wlsm-font-bold">
							<?php
							printf(
								wp_kses(
									/* translators: %s: number of students */
									_n('%d Student found.', '%d Students found.', count($students), 'school-management'),
									array('span' => array('class' => array()))
								),
								count($students)
							);
							?>
						</div>

						<div class="table-responsive w-100">
							<table class="table table-bordered wlsm-students-with-pending-invoices-table">
								<thead>
									<tr class="bg-primary text-white">
										<th><?php esc_html_e('Name', 'school-management'); ?></th>
										<th><?php esc_html_e('Enrollment Number', 'school-management'); ?></th>
										<th><?php esc_html_e('Class', 'school-management'); ?></th>
										<th><?php esc_html_e('Section', 'school-management'); ?></th>
										<th><?php esc_html_e('Roll Number', 'school-management'); ?></th>
										<th><?php esc_html_e('Pending Fee Invoices', 'school-management'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($students as $row) { ?>
										<tr>
											<td>
												<?php echo esc_html(WLSM_M_Staff_Class::get_name_text($row->name)); ?>
											</td>
											<td>
												<?php echo esc_html($row->enrollment_number); ?>
											</td>
											<td>
												<?php echo esc_html(WLSM_M_Class::get_label_text($row->class_label)); ?>
											</td>
											<td>
												<?php echo esc_html(WLSM_M_Staff_Class::get_section_label_text($row->section_label)); ?>
											</td>
											<td>
												<?php echo esc_html(WLSM_M_Staff_Class::get_roll_no_text($row->roll_number)); ?>
											</td>
											<td>
												<a class="wlsm-view-student-pending-invoices" data-student="<?php echo esc_attr($row->ID); ?>" data-nonce="<?php echo esc_attr(esc_attr(wp_create_nonce('view-student-invoices-' . $row->ID))); ?>" href="#">
													<?php esc_html_e('View', 'school-management'); ?>
												</a>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="wlsm-student-pending-invoices"></div>
				<?php
				} else {
				?>
					<div class="wlsm-alert wlsm-alert-warning wlsm-font-bold">
						<span class="wlsm-icon wlsm-icon-red">&#33;</span>
						<?php esc_html_e('There is no student with pending fees.', 'school-management'); ?>
					</div>
			<?php
				}
				$html = ob_get_clean();

				wp_send_json_success(array('html' => $html));
			} catch (Exception $exception) {
				$buffer = ob_get_clean();
				if (!empty($buffer)) {
					$response = esc_html__('An unexpected error occurred!', 'school-management');
				} else {
					$response = $exception->getMessage();
				}
				wp_send_json_error($response);
			}
		}
		wp_send_json_error($errors);
	}

	
}
