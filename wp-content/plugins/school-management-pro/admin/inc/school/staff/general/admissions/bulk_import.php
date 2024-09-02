<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_General::get_students_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$nonce_action = 'bulk-import-student';

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-2 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-file-import"></i>
				<?php esc_html_e( 'Bulk Admission', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-users"></i>&nbsp;
					<?php echo esc_html( 'View Students', 'school-management' ); ?>
				</a>
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-bulk-import-student-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="<?php echo esc_attr( 'wlsm-bulk-import-student' ); ?>">

			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Import Students From CSV File', 'school-management' ); ?>
							<br>
							<small class="text-dark">
								<em><?php esc_html_e( 'Select class and section, click "Export Sample CSV", fill student details in the file, choose the CSV file with student details and click "Bulk Import".', 'school-management' ); ?></em>
							</small>
						</div>
					</div>
				</div>

				<div class="form-row mt-2">
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
						<select name="section_id" class="form-control selectpicker" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'Default Section', 'school-management' ); ?>">
						</select>
					</div>

					<div class="form-group col-md-4">
						<label class="wlsm-font-bold">
							<?php esc_html_e( 'Export Sample CSV File', 'school-management' ); ?>
						</label>
						<br>
						<button type="button" class="btn btn-sm btn-outline-info" id="wlsm-student-sample-csv-export-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'student-sample-csv-export' ) ); ?>">
							<i class="fas fa-file-export"></i>
							<?php esc_html_e( 'Export Sample CSV', 'school-management' ); ?>
						</button>
					</div>
				</div>

				<div class="form-row mt-2 justify-content-md-center">
					<div class="form-group col-md-4">
						<label for="wlsm_students_csv" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'CSV File', 'school-management' ); ?>:
						</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="wlsm_students_csv" name="csv">
							<label class="custom-file-label" for="wlsm_students_csv">
								<?php esc_html_e( 'Choose CSV File', 'school-management' ); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-sm btn-primary" id="wlsm-bulk-import-student-btn" data-message-title="<?php esc_attr_e( 'Confirm Import!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'Are you sure to import these students to selected class?', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Import', 'school-management' ); ?>">
						<i class="fas fa-file-import"></i>
						<?php esc_html_e( 'Bulk Import', 'school-management' ); ?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
