<?php
defined( 'ABSPATH' ) || die();

global $wpdb;

$page_url = WLSM_M_Staff_Class::get_study_materials_page_url();

$school_id = $current_school['id'];

$study_material = NULL;

$nonce_action = 'add-study-material';

$title       = '';
$description = '';
$link = '';
$downloadable = 0;

$attachments = array();

$study_material_classes = array();

$class_id = '';
$subject_label = '';
$section_label = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id      = absint( $_GET['id'] );
	$study_material = WLSM_M_Staff_Class::fetch_study_material( $school_id, $id );

	if ( $study_material ) {
		$nonce_action = 'edit-study-material-' . $study_material->ID;

		$title         = $study_material->title;
		$description   = $study_material->description;
		$downloadable   = $study_material->downloadable;
		$link          = $study_material->url;
		$class_id      = $study_material->class_id;
		$subject_label = $study_material->subject_label;
		$subject_id    = $study_material->subject_id;
		$section_label = $study_material->section_label;
		$section_id    = $study_material->study_material_section_id;

		$attachments = $study_material->attachments;
		if ( is_serialized( $attachments ) ) {
			$attachments = unserialize( $attachments );
		} else {
			if ( ! is_array( $attachments ) ) {
				$attachments = array();
			}
		}

		$study_material_classes = WLSM_M_Staff_Class::fetch_study_material_classes( $school_id, $id );
	}
}

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $study_material ) {
						printf(
							wp_kses(
								/* translators: %s: study_material title */
								__( 'Edit Study Material: %s', 'school-management' ),
								array(
									'span' => array( 'class' => array() )
								)
							),
							esc_html( stripslashes( $title ) )
						);
					} else {
						esc_html_e( 'Add New Study Material', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-book"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-study-material-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-study-material">

			<?php if ( $study_material ) { ?>
			<input type="hidden" name="study_material_id" value="<?php echo esc_attr( $study_material->ID ); ?>">
			<?php } ?>
			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="classes[]" class="form-control selectpicker" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_class" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
							<option value="<?php echo esc_attr( $class->ID ); ?>" <?php if($class_id == $class->ID ){ echo 'selected';} ?>>
								<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
							</option>
							<?php } ?>
							
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_section" class="wlsm-font-bold">
							<?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="section" class="form-control selectpicker wlsm_section" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'All Sections', 'school-management' ); ?>" data-all-sections="1" data-fetch-students="1" data-skip-transferred="0" data-only-active="0" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-section-students' ) ); ?>">
						<?php if ($section_label): ?>
							<option value="<?php echo $section_id; ?>" selected><?php esc_html_e(  $section_label); ?></option>
						<?php endif ?>
						</select>
					</div>
					<div class="form-group col-md-4 wlsm-subjects-select-block">
						<label for="wlsm_subjects" class="wlsm-font-bold">
							<?php esc_html_e( 'subject', 'school-management' ); ?>:
						</label>
						<select name="subject" class="form-control selectpicker" id="wlsm_subjects" data-live-search="true" data-actions-box="true" data-none-selected-text="<?php esc_attr_e( 'Select subject', 'school-management' ); ?>">
						<?php if ($subject_label): ?>
							<option value="<?php echo $subject_id; ?>" selected><?php esc_html_e(  $subject_label); ?></option>
						<?php endif ?>
						</select>
					</div>
				</div>
			</div>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="wlsm_label" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Title', 'school-management' ); ?>:
						</label>
						<input type="text" name="label" class="form-control" id="wlsm_label" placeholder="<?php esc_attr_e( 'Enter title', 'school-management' ); ?>" value="<?php echo esc_attr( stripslashes( $title ) ); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="wlsm_description" class="wlsm-font-bold">
							<?php esc_html_e( 'Description', 'school-management' ); ?>:
						</label>
						<textarea name="description" class="form-control" id="wlsm_description" placeholder="<?php esc_attr_e( 'Enter description', 'school-management' ); ?>" cols="30" rows="5"><?php echo esc_html( stripslashes( $description ) ); ?></textarea>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="wlsm_link" class="wlsm-font-bold">
							<?php esc_html_e( ' URL', 'school-management' ); ?>:
						</label>
						<input name="link" class="form-control" id="wlsm_link" placeholder="<?php esc_attr_e( 'Enter URL', 'school-management' ); ?>" cols="30" rows="5" value="<?php echo esc_url( ( $link ) ); ?>" >
					</div>
				</div>

				<div class="form-group col-md-6">
						<input <?php checked($downloadable, 1, true); ?> class="form-check-input mt-1" type="checkbox" name="downloadable" id="wlsm_downloadable" value="1">
						<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_downloadable">
							<?php esc_html_e('Make study material downloadable in application.', 'school-management'); ?>
						</label>
					</div>

				<div class="form-row">
					<div class="form-group col-md-6">
						<div class="wlsm-attachment-box">
							<div class="wlsm-attachment-section">
								<label for="wlsm_attachments" class="wlsm-font-bold">
									<?php esc_html_e( 'Study Materials', 'school-management' ); ?>:
								</label>
								<?php
								if ( count( $attachments ) ) {
								?>
								<ul class="list-group list-group-flush">
								<?php
								foreach ( $attachments as $attachment ) {
									if ( ! empty ( $attachment ) ) {
										$file_name = basename ( get_attached_file( $attachment ) );
									?>
									<li class="list-group-item pl-0 ml-0">
										<a target="_blank" href="<?php echo esc_url( wp_get_attachment_url( $attachment ) ); ?>">
											<?php echo esc_html( $file_name ); ?>
										</a>
										<input type="hidden" name="saved_attachment[]" value="<?php echo esc_attr( $attachment ); ?>">
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

					
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-study-material-btn">
						<?php
						if ( $study_material ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Study Material', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Study Material', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
