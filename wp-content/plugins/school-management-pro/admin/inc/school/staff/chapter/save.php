<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Transport.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Lecture.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Helper.php';

$page_url = WLSM_M_Staff_Transport::get_chapter_page_url();

$school_id = $current_school['id'];

$chapter = null;

$nonce_action = 'add-chapter';

$title       = '';
$description = '';
$class_id    = '';
$title       = '';
$description = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id      = absint( $_GET['id'] );
	$chapter = WLSM_M_Staff_Lecture::fetch_chapter( $id );
	if ( $chapter ) {
		$nonce_action = 'edit-chapter-' . $chapter->ID;

		$title       = $chapter->title;
		$class_id    = $chapter->class_id;
		$subject_id  = $chapter->subject_id;
	}
}

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );
$subjects = WLSM_M_Staff_Class::fetch_subject_query_by_class_id( $school_id, $class_id );
?>

<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $chapter ) {
						printf(
							wp_kses(
								/* translators: %s: chapter name */
								__( 'Edit Chapter: %s', 'school-management' ),
								array(
									'span' => array( 'class' => array() ),
								)
							),
							esc_html( $title )
						);
					} else {
						esc_html_e( 'Add New Chapter', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-home"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-chapter-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-chapter">

			<?php if ( $chapter ) { ?>
				<input type="hidden" name="chapter_id" value="<?php echo esc_attr( $chapter->ID ); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-3">
						<label for="wlsm_label" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Title', 'school-management' ); ?>:
						</label>
						<input type="text" name="title" class="form-control" id="wlsm_title" placeholder="<?php esc_attr_e( 'Enter title', 'school-management' ); ?>" value="<?php echo esc_attr( stripslashes( $title ) ); ?>">
					</div>

					<div class="form-group col-md-3">
						<label for="wlsm_class" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="classes" class="form-control selectpicker wlsm_class_subjects" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" data-nonce-subjects="<?php echo esc_attr( wp_create_nonce( 'get-class-subjects' ) ); ?>" id="wlsm_class" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
								<option <?php selected( $class->ID, $class_id, true ); ?> value="<?php echo esc_attr( $class->ID ); ?>" <?php selected( $class->ID, $class_id, true ); ?>>
									<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group col-md-3">
						<label for="wlsm_subject" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Subject', 'school-management' ); ?>:
						</label>

						<select name="subject" class="form-control selectpicker" id="wlsm_subject" data-live-search="true" title="<?php esc_attr_e( 'Select subject', 'school-management' ); ?>" data-actions-box="true">
							<?php if ( $subject_id ) : ?>
								<?php foreach ( $subjects as $subject ) { ?>
									<option value="<?php echo esc_attr( $subject->ID ); ?>" <?php selected( $subject->ID, $subject_id ); ?> >
										<?php echo esc_html( WLSM_M_Staff_Class::get_subject_label_text( $subject->subject_name ) ); ?>
									</option>
								<?php } ?>
							<?php elseif ( ! $subject_id ) : ?>
								<?php foreach ( $subjects as $subject ) { ?>
									<option value="<?php echo esc_attr( $subject->ID ); ?>" <?php echo 'selected'; ?>>
										<?php echo esc_html( WLSM_M_Staff_Class::get_subject_label_text( $subject->label ) ); ?>
									</option>
								<?php } ?>
							<?php endif ?>

						</select>
					</div>
				</div>

				<div class="row mt-2">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-chapter-btn">
							<?php
							if ( $chapter ) {
								?>
								<i class="fas fa-save"></i>&nbsp;
								<?php
								esc_html_e( 'Update Chapter', 'school-management' );
							} else {
								?>
								<i class="fas fa-plus-square"></i>&nbsp;
								<?php
								esc_html_e( 'Add New Chapter', 'school-management' );
							}
							?>
						</button>
					</div>
				</div>

		</form>
	</div>
</div>
