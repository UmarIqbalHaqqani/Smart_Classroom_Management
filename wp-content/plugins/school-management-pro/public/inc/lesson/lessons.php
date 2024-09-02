<?php
defined('ABSPATH') || die();
global $wpdb;
$school = null;
$lesson_per_page = WLSM_M::lesson_per_page();
$lesson_query = WLSM_M::lesson_query();
$lesson_query = WLSM_M::lesson_query();

$lesson_total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM ({$lesson_query}) AS combined_table"));

$lesson_page = isset($_GET['lesson_page']) ? absint($_GET['lesson_page']) : 1;

$lesson_page_offset = ($lesson_page * $lesson_per_page) - $lesson_per_page;

$lessons = $wpdb->get_results($wpdb->prepare($lesson_query . ' ORDER BY l.ID DESC LIMIT %d, %d', $lesson_page_offset, $lesson_per_page));

if (isset($attr['school_id'])) {
	$school_id = absint($attr['school_id']);
	$classes = WLSM_M_Staff_General::fetch_school_classes($school_id);
}

if ($class_id) {
	$subjects = WLSM_M_Staff_Class::get_class_subjects($school_id, $class_id);
}

?>
<!-- jQuery Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<div class="wlsm wlsm-grid">
	<div id="wlsm-submit-registration-section">

		<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-get-student-lesson-form">
			<input type="hidden" name="<?php echo esc_attr("nonce"); ?>" value="<?php echo wp_create_nonce("lessons"); ?>">

			<input type="hidden" name="action" value="wlsm-p-submit-lessons">
			<input type="hidden" id="school_id" name="school_id" value="<?php echo $school_id; ?>">
			<?php if ($class_id): ?>
				<input type="hidden" id="class_id" name="class_id" value="<?php echo $class_id; ?>">

			<?php endif ?>

			<div class="wlsm-row">
				<?php if (!$class_id): ?>
					<div class="wlsm-form-group wlsm-col-4">
					<label for="wlsm_school_class" class="wlsm-font-bold">
						<?php esc_html_e('Class', 'school-management'); ?>:
					</label>
					<select name="class_id" class="wlsm-form-control wlsm_school_class_subject" data-nonce="<?php echo esc_attr(wp_create_nonce('get-class-subject')); ?>" id="wlsm_school_class_subject">
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
				<?php endif ?>


				<div class="wlsm-form-group wlsm-col-4">
					<label for="wlsm_subject" class="wlsm-font-bold">
						<?php esc_html_e('Subject', 'school-management'); ?>:
					</label>
					<select name="subject_id" class="wlsm-form-control" id="wlsm_class_subject" data-nonce="<?php echo esc_attr(wp_create_nonce('get-subject-chapter')); ?>">
						<option value=""><?php esc_html_e('Select Subject', 'school-management'); ?></option>
						<?php if ($subjects): ?>
							<?php foreach ($subjects as $subject): ?>
								<option value="<?php echo esc_attr($subject->ID); ?>"><?php echo esc_html($subject->label); ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>

				<div class="wlsm-form-group wlsm-col-4">
					<label for="wlsm_chapter" class="wlsm-font-bold">
						<?php esc_html_e('Chapter', 'school-management'); ?>:
					</label>
					<select name="chapter_id" class="wlsm-form-control" id="wlsm_chapter"  >
						<option value=""><?php esc_html_e('Select All', 'school-management'); ?></option>
					</select>
				</div>
				<br>


			</div>
			<div class="wlsm-form-group wlsm-col-2">
					<button class="button wlsm-btn btn btn-primary" id="wlsm-get-student-lesson-btn"><?php esc_html_e( 'Filter', 'school-management' ); ?></button>
				</div>
		</form>
	</div>
</div>

<hr>

<div class="wlsm-grid wlsm-student-lesson">
	<div class="wlsm wlsm-row ">
		<?php foreach ($lessons as $lesson) : ?>
			<?php
			if ($lesson->link_to = 'attachment') {
				$image = $lesson->attachment;
			} else {
				$image = '';
			}

			?>
			<div class="lessons-card">
				<div class="lessons-card-image">
					<iframe src="<?php echo esc_url(wp_get_attachment_url($image)); ?>" frameborder="0"></iframe>
				</div>
				<div class="category"> <a href="<?php echo esc_url($lesson->url); ?>"><?php echo esc_html($lesson->title) ?> </div></a>

				<div class="heading trigger"><?php echo wp_kses_post(wp_trim_words($lesson->description, 8)); ?>
					<a href="#<?php echo esc_html($lesson->title) ?>" rel="modal:open" >Read</a>
					<div class="author">
						<strong><?php esc_html_e('Subject: ', 'school-management') ?></strong> <span class="name trigger"><?php echo esc_html($lesson->subject); ?></span>
						&#8209;
						<strong><?php esc_html_e('Chapter: ', 'school-management') ?></strong> <span class="name trigger"><?php echo esc_html($lesson->chapter); ?></span>
					</div>
				</div>
			</div>
			<div id="<?php echo esc_html($lesson->title) ?>" class="modal">
				<p><?php echo wp_kses_post(($lesson->description)); ?></p>
				<a href="#" rel="modal:close">Close</a>
			</div>


		<?php endforeach ?>
	</div>
</div>

