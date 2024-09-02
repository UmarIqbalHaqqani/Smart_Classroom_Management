<?php 


?>

<div class="wlsm-grid">
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
				<div class="heading"><?php echo wp_kses_post($lesson->description); ?>
					<div class="author">
						<strong><?php esc_html_e('Subject: ', 'school-management') ?></strong> <span class="name"><?php echo esc_html($lesson->subject); ?></span>
						&#8209;
						<strong><?php esc_html_e('Chapter: ', 'school-management') ?></strong> <span class="name"><?php echo esc_html($lesson->chapter); ?></span>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>