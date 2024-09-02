<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_General::get_certificates_page_url();

$school_id = $current_school['id'];

$certificate = NULL;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id          = absint( $_GET['id'] );
	$certificate = WLSM_M_Staff_General::fetch_certificate( $school_id, $id );
}

if ( ! $certificate ) {
	die;
}

$nonce_action = 'distribute-certificate-' . $certificate->ID;

$label = $certificate->label;

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					printf(
						wp_kses(
							/* translators: %s: certificate title */
							__( 'Distribute Certificate: %s', 'school-management' ),
							array(
								'span' => array( 'class' => array() )
							)
						),
						esc_html( $certificate->label )
					);
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . "&action=students&id=" . $certificate->ID ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-certificate"></i>&nbsp;
					<?php esc_html_e( 'Total Certificates Distributed', 'school-management' ); ?>
				</a>
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-certificate"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-distribute-certificate-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-distribute-certificate">

			<input type="hidden" name="certificate_id" value="<?php echo esc_attr( $certificate->ID ); ?>">

			<div class="wlsm-form-section">
				<div class="form-row">
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
						<label for="wlsm_section" class="wlsm-font-bold">
							<?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="section_id" class="form-control selectpicker wlsm_section" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'All Sections', 'school-management' ); ?>" data-all-sections="1" data-fetch-students="1" data-skip-transferred="0" data-only-active="0" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-section-students' ) ); ?>">
						</select>
					</div>
					<div class="form-group col-md-4 wlsm-student-select-block">
						<label for="wlsm_student" class="wlsm-font-bold">
							<?php esc_html_e( 'Students', 'school-management' ); ?>:
						</label>
						<select name="student[]" multiple class="form-control selectpicker" id="wlsm_student" data-live-search="true" data-actions-box="true" data-none-selected-text="<?php esc_attr_e( 'Select Students', 'school-management' ); ?>">
						</select>
					</div>
				</div>
			</div>

			<div class="wlsm-form-section">
				<div class="form-row justify-content-md-center">
					<div class="form-group col-md-4">
						<label for="wlsm_date_issued" class="font-weight-bold">
							<?php esc_html_e( 'Date Issued', 'school-management' ); ?>:
						</label>
						<input type="text" name="date_issued" class="form-control" id="wlsm_date_issued" placeholder="<?php esc_attr_e( 'Enter date issued', 'school-management' ); ?>">
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-distribute-certificate-btn">
						<i class="fas fa-plus-square"></i>&nbsp;
						<?php esc_html_e( 'Distribute Certificate', 'school-management' ); ?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
