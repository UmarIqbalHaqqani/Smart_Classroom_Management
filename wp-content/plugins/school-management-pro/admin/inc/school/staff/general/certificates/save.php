<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';

global $wpdb;

$page_url = WLSM_M_Staff_General::get_certificates_page_url();

$school_id = $current_school['id'];

$certificate = NULL;

$nonce_action = 'add-certificate';

$label    = '';
$image_id = '';

$fields = WLSM_Helper::get_certificate_dynamic_fields();

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id          = absint( $_GET['id'] );
	$certificate = WLSM_M_Staff_General::fetch_certificate( $school_id, $id );

	if ( $certificate ) {
		$nonce_action = 'edit-certificate-' . $certificate->ID;

		$label    = $certificate->label;
		$image_id = $certificate->image_id;

		if ( $certificate->fields ) {
			$saved_fields = unserialize( $certificate->fields );

			if ( is_array( $saved_fields ) && count( $saved_fields ) ) {
				foreach ( $fields as $field_key => $field_value ) {
					if ( array_key_exists( $field_key, $saved_fields ) ) {
						$fields[ $field_key ] = $saved_fields[ $field_key ];
					}
				}
			}
		}
	}
}

if ( $certificate ) {
	$js = '(function($) { "use strict";';

	foreach ( $fields as $field_key => $field_value ) {
		foreach ( $field_value['props'] as $key => $prop ) {
			$js .= "$(document).on('keyup', '#ctf-" . esc_attr( $field_key . '-' . $key ) . "', function() {
						var pos = $(this).val();
						$('.ctf-data-" . esc_attr( $field_key ) . "').css({'" . esc_attr( $key ) . "': pos + '" . esc_attr( $prop['unit'] ) . "'});
					});";
		}

		$js .= "$(document).on('change', '#ctf-enable-" .  esc_attr( $field_key ) . "', function() {
					if($(this).is(':checked')) {
						$('.ctf-data-" . esc_attr( $field_key ) . "').css({'visibility': 'visible'});
					} else {
						$('.ctf-data-" . esc_attr( $field_key ) . "').css({'visibility': 'hidden'});
					}
				});";
	}

	$js .= '})(jQuery);';

	wp_register_script( 'wlsm-certificate', false );
	wp_enqueue_script( 'wlsm-certificate' );
	wp_add_inline_script( 'wlsm-certificate', $js );
}

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );
global $wpdb;
$exams = $wpdb->get_results(WLSM_M_Staff_Examination::fetch_exams_admit( $school_id ));
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $certificate ) {
						printf(
							wp_kses(
								/* translators: %s: certificate title */
								__( 'Edit Certificate: %s', 'school-management' ),
								array(
									'span' => array( 'class' => array() )
								)
							),
							esc_html( $label )
						);
					} else {
						esc_html_e( 'Add New Certificate', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-certificate"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-certificate-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-certificate">

			<?php if ( $certificate ) { ?>
			<input type="hidden" name="certificate_id" value="<?php echo esc_attr( $certificate->ID ); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row justify-content-md-center">
					<div class="form-group col-md-4">
						<label for="wlsm_label" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Certificate Title', 'school-management' ); ?>:
						</label>
						<input type="text" name="label" class="form-control" id="wlsm_label" placeholder="<?php esc_attr_e( 'Enter certificate title', 'school-management' ); ?>" value="<?php echo esc_attr( $label ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="class_id" class="form-control selectpicker" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_class" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
							<option value="<?php echo esc_attr( $class->ID ); ?>">
								<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_exam" class="wlsm-font-bold">
							<?php esc_html_e( 'Exams', 'school-management' ); ?>:
						</label>
						<select name="exam" class="form-control selectpicker" id="wlsm_exam">
						<option value=""><?php esc_html_e( 'Select exam', 'school-management' ); ?></option>
							<?php foreach ( $exams as $exam ) { ?>
							<option value="<?php echo esc_attr( $exam->ID ); ?>">
								<?php echo esc_html( WLSM_M_Class::get_label_text( $exam->exam_title ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>

					<?php if ( $certificate ) { ?>
					<div class="form-group col-md-4">
						<div class="wlsm-photo-box">
							<div class="wlsm-photo-section">
								<label for="wlsm_photo" class="wlsm-font-bold">
									<?php
										if ( ! empty ( $image_id ) ) {
											esc_html_e( 'Change Certificate Image', 'school-management' );
										} else {
											esc_html_e( 'Upload Certificate Image', 'school-management' );
										}
									?>:
								</label>
								<div class="custom-file mb-3">
									<input type="file" class="custom-file-input" id="wlsm_image" name="image">
									<label class="custom-file-label" for="wlsm_image">
										<?php esc_html_e( 'Choose Image', 'school-management' ); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>

				<?php if ( $certificate ) { ?>
				<div class="form-row wlsm-certificate-positions">
					<div class="wlsm-certificate-fields col-md-3 mt-3">
						<div class="h5 border-bottom">
							<?php esc_html_e( 'Set Positions', 'school-management' ); ?>
						</div>
						<?php
						foreach ( $fields as $field_key => $field_value ) {
						?>
						<div class="form-group">
							<label for="ctf-pos-<?php echo esc_attr( $field_key ); ?>" class="wlsm-font-bold">
								<?php echo esc_html( WLSM_Helper::get_certificate_field_label( $field_key ) ); ?>:
							</label>

							<div class="form-group">
								<input <?php checked( $field_value['enable'], 1, true ); ?> class="form-check-input mt-1" type="checkbox" name="enable-<?php echo esc_attr( $field_key ); ?>" id="ctf-enable-<?php echo esc_attr( $field_key ); ?>" value="1">
								<label class="ml-4 mb-1 pl-1 form-check-label wlsm-font-bold text-dark" for="ctf-enable-<?php echo esc_attr( $field_key ); ?>">
									<?php esc_html_e( 'Enable', 'school-management' ); ?>
								</label>
							</div>

							<?php foreach ( $field_value['props'] as $key => $prop ) { ?>
							<div class="input-group mb-1">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<?php echo esc_html( WLSM_Helper::get_certificate_property( $key ) ); ?>
									</span>
								</div>
								<input type="<?php echo esc_html( WLSM_Helper::get_certificate_field_type( $key ) ); ?>" name="<?php echo esc_attr( $field_key . '-' . $key ); ?>" class="form-control" id="ctf-<?php echo esc_attr( $field_key . '-' . $key ); ?>" value="<?php echo esc_attr( $prop['value'] ); ?>">
							</div>
							<?php } ?>
						</div>
						<?php
						}
						?>
					</div>
					<div class="col-md-9">
						<?php
						$image_url = wp_get_attachment_url( $image_id );
						require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/certificate.php';
						?>
					</div>
				</div>
				<?php } ?>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-certificate-btn">
						<?php
						if ( $certificate ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Certificate', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Certificate', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
