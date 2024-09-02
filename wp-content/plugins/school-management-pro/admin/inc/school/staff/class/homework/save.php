<?php
defined('ABSPATH') || die();

global $wpdb;

$page_url = WLSM_M_Staff_Class::get_homeworks_page_url();

$school_id = $current_school['id'];

$session_id = $current_session['ID'];

$homework = NULL;

$nonce_action = 'add-homework';

$class_id      = NULL;
$title         = '';
$description   = '';
$downloadable   = '';
$homework_date = '';
$link = '';
$subject = '';

$attachments = array();

$sections          = array();
$homework_sections = array();

if (isset($_GET['id']) && !empty($_GET['id'])) {
	$id       = absint($_GET['id']);
	$homework = WLSM_M_Staff_Class::fetch_homework($school_id, $session_id, $id);

	if ($homework) {
		$nonce_action = 'edit-homework-' . $homework->ID;

		$class_id      = $homework->class_id;
		$title         = $homework->title;
		$subject_id    = $homework->subject;
		$description   = $homework->description;
		$downloadable   = $homework->downloadable;
		$homework_date = $homework->homework_date;

		$attachments = $homework->attachments;
		if (is_serialized($attachments)) {
			$attachments = unserialize($attachments);
		} else {
			if (!is_array($attachments)) {
				$attachments = array();
			}
		}

		$class_school_id = $homework->class_school_id;

		$sections = WLSM_M_Staff_General::fetch_class_sections($class_school_id);

		$homework_sections = WLSM_M_Staff_Class::fetch_homework_sections($school_id, $session_id, $id);

		$subjects = WLSM_M_Staff_Class::get_class_subjects($school_id, $class_id);
	}
}

$classes = WLSM_M_Staff_Class::fetch_classes($school_id);
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ($homework) {
						printf(
							wp_kses(
								/* translators: %s: homework title */
								__('Edit Homework: %s', 'school-management'),
								array(
									'span' => array('class' => array())
								)
							),
							esc_html(stripslashes($title))
						);
					} else {
						esc_html_e('Assign Homework', 'school-management');
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url($page_url); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-book"></i>&nbsp;
					<?php esc_html_e('View All', 'school-management'); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-homework-form">

			<?php $nonce = wp_create_nonce($nonce_action); ?>
			<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

			<input type="hidden" name="action" value="wlsm-save-homework">

			<?php if ($homework) { ?>
				<input type="hidden" name="homework_id" value="<?php echo esc_attr($homework->ID); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_label" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Title', 'school-management'); ?>:
						</label>
						<input type="text" name="title" class="form-control" id="wlsm_title" placeholder="<?php esc_attr_e('Enter title', 'school-management'); ?>" value="<?php echo esc_attr(stripslashes($title)); ?>">
					</div>

					<div class="form-group col-md-3">
						<label for="wlsm_class" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Class', 'school-management'); ?>:
						</label>
						<select name="class_id" class="form-control selectpicker wlsm_class_subjects" data-nonce="<?php echo esc_attr(wp_create_nonce('get-class-sections')); ?>" data-nonce-subjects="<?php echo esc_attr(wp_create_nonce('get-class-subjects')); ?>" id="wlsm_class" data-live-search="true">
							<option value=""><?php esc_html_e('Select Class', 'school-management'); ?></option>
							<?php foreach ($classes as $class) { ?>
								<option <?php selected($class->ID, $class_id, true); ?> value="<?php echo esc_attr($class->ID); ?>" <?php selected($class->ID, $class_id, true); ?>>
									<?php echo esc_html(WLSM_M_Class::get_label_text($class->label)); ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="wlsm_section" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Section', 'school-management'); ?>:
						</label>
						<select name="sections[]" class="form-control selectpicker" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e('Select Section', 'school-management'); ?>" data-actions-box="true" multiple>
							<?php foreach ($sections as $section) { ?>
								<option value="<?php echo esc_attr($section->ID); ?>" <?php selected(in_array($section->ID, $homework_sections), true, true); ?>>
									<?php echo esc_html(WLSM_M_Staff_Class::get_section_label_text($section->label)); ?>
								</option>
							<?php } ?>
						</select>
					</div>



					<div class="form-group col-md-2">
						<label for="wlsm_homework_date" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Date', 'school-management'); ?>:
						</label>
						<input type="text" name="homework_date" class="form-control" id="wlsm_homework_date" placeholder="<?php esc_attr_e('Date', 'school-management'); ?>" value="<?php echo esc_attr(WLSM_Config::get_date_text($homework_date)); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-3">
						<label for="wlsm_subject" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Subject', 'school-management'); ?>:
						</label>

						<select name="subjects" class="form-control selectpicker" id="wlsm_subject" data-live-search="true" title="<?php esc_attr_e('Select subject', 'school-management'); ?>" data-actions-box="true">
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
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="wlsm_description" class="wlsm-font-bold">
							<?php esc_html_e('Description', 'school-management'); ?>:
						</label>
						<textarea name="description" class="form-control" id="wlsm_description" placeholder="<?php esc_attr_e('Enter description', 'school-management'); ?>" cols="30" rows="5"><?php echo esc_html(stripslashes($description)); ?></textarea>
					</div>
				</div>

				<div class="form-group col-md-6">
					<div class="wlsm-attachment-box">
						<div class="wlsm-attachment-section">
							<label for="wlsm_attachments" class="wlsm-font-bold">
								<?php esc_html_e('Home Work', 'school-management'); ?>:
							</label>
							<?php
							if (count($attachments)) {
							?>
								<ul class="list-group list-group-flush">
									<?php
									foreach ($attachments as $attachment) {
										if (!empty($attachment)) {
											$file_name = basename(get_attached_file($attachment));
									?>
											<li class="list-group-item pl-0 ml-0">
												<a target="_blank" href="<?php echo esc_url(wp_get_attachment_url($attachment)); ?>">
													<?php echo esc_html($file_name); ?>
												</a>
												<input type="hidden" name="saved_attachment[]" value="<?php echo esc_attr($attachment); ?>">
												<i class="float-md-right ml-1 pt-1 wlsm-remove-study-material-attachment text-danger fas fa-times"></i>
											</li>
									<?php
										}
									}
									?>
								</ul>
							<?php
							}
							?>
							<div class="mb-3">
								<input multiple type="file" id="wlsm_attachments" name="attachment[]">
							</div>
						</div>
					</div>
				</div>


				<div class="form-row">

				<div class="form-group col-md-12">
						<input <?php checked((bool) $downloadable, 1, true); ?> class="form-check-input mt-1" type="checkbox" name="downloadable" id="wlsm_downloadable" value="1">
						<label class="ml-4 mb-1 pl-1 form-check-label wlsm-font-bold text-dark" for="wlsm_downloadable">
							<?php esc_html_e('Make Homework downloadable in application', 'school-management'); ?>
						</label>
					</div>

					<div class="form-group col-md-12">
						<input <?php checked((bool) $homework, false, true); ?> class="form-check-input mt-1" type="checkbox" name="sms_to_students" id="wlsm_sms_to_students" value="1">
						<label class="ml-4 mb-1 pl-1 form-check-label wlsm-font-bold text-dark" for="wlsm_sms_to_students">
							<?php esc_html_e('Send SMS to Students', 'school-management'); ?>
						</label>
					</div>

					<div class="form-group col-md-12">
						<input class="form-check-input mt-1" type="checkbox" name="sms_to_parents" id="wlsm_sms_to_parents" value="1">
						<label class="ml-4 mb-1 pl-1 form-check-label wlsm-font-bold text-dark" for="wlsm_sms_to_parents">
							<?php esc_html_e('Send SMS to Parents', 'school-management'); ?>
						</label>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-homework-btn">
						<?php
						if ($homework) {
						?>
							<i class="fas fa-save"></i>&nbsp;
						<?php
							esc_html_e('Update Homework', 'school-management');
						} else {
						?>
							<i class="fas fa-plus-square"></i>&nbsp;
						<?php
							esc_html_e('Assign Homework', 'school-management');
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
