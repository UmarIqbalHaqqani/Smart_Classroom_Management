<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_Class::get_attendance_page_url();

$school_id = $current_school['id'];

$nonce_action = 'take-attendance';

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-2 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-clock"></i>
				<?php esc_html_e( 'Bulk Upload', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-calendar-alt"></i>&nbsp;
					<?php echo esc_html( 'View Attendance', 'school-management' ); ?>
				</a>
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-bulk-import-attendance-form" enctype="multipart/form-data">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="<?php echo esc_attr( 'wlsm-upload-attendance' ); ?>">

			<div class="wlsm-form-section">

			
				<div class="row">
					<div class="col-md-6">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Attendance By', 'school-management' ); ?>
							<br>
							<small class="text-dark">
								<em><?php esc_html_e( 'Select class, section, year, month and By Subject.', 'school-management' ); ?></em>
							</small>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="wlsm-font-bold">
								<?php esc_html_e( 'Export Attendance CSV File', 'school-management' ); ?>
							</label>
							<br>
							<button type="button" class="btn btn-sm btn-outline-info" id="wlsm-attendance-csv-export-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'attendance-csv-export' ) ); ?>">
								<i class="fas fa-file-export"></i>
								<?php esc_html_e( 'Export Attendance CSV', 'school-management' ); ?>
							</button>
						</div>
					</div>	
				</div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<div class="form-check form-check-inline">
							<input checked class="form-check-input" type="radio" name="attendance_by" id="attendance_by_all" value="all">
							<label class="ml-1 form-check-label wlsm-font-bold" for="attendance_by_all">
								<?php esc_html_e( 'Attendance By Month', 'school-management' ); ?>
							</label>
						</div>
						<?php if ( ! $restrict_to_section ) { ?>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="attendance_by" id="attendance_by_subject" data-el_id="wlsm_subject" value="subject">
							<label class="ml-1 form-check-label wlsm-font-bold" for="attendance_by_subject">
								<?php esc_html_e( 'Attendance By Subject', 'school-management' ); ?>
							</label>
						</div>
						<?php } ?>
					</div>
				</div>

				<div class="form-row mt-2">
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<?php
						if ( $restrict_to_section ) {
						?>
						<div class="ml-2">
							<?php echo esc_html( WLSM_M_Class::get_label_text( $restrict_to_section_detail->class_label ) ); ?>
						</div>
						<input type="hidden" name="class_id" id="wlsm_class" value="<?php echo esc_attr( $restrict_to_section_detail->class_id ); ?>">
						<?php
						} else {
						?>
						<select name="class_id" class="form-control selectpicker" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_class" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
							<option value="<?php echo esc_attr( $class->ID ); ?>">
								<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
							</option>
							<?php } ?>
						</select>
						<?php
						}
						?>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_section" class="wlsm-font-bold">
							<?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<?php
						if ( $restrict_to_section ) {
						?>
						<div class="ml-2">
							<?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $restrict_to_section_detail->section_label ) ); ?>
						</div>
						<input type="hidden" name="section_id" id="wlsm_section" value="<?php echo esc_attr( $restrict_to_section ); ?>">
						<?php
						} else {
						?>
						<select name="section_id" class="form-control selectpicker" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'All Sections', 'school-management' ); ?>" data-all-sections="1">
						</select>
						<?php
						}
						?>
					</div>
					<div class="form-group col-md-4 form-subject-select" style="display: none;">
						<label for="wlsm_subject" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Subject', 'school-management' ); ?>:
						</label>
						<select name="subject_id" class="form-control selectpicker" id="wlsm_subject" data-live-search="true" title="<?php esc_attr_e( 'All Subjects', 'school-management' ); ?>" data-all-subjects="1" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-subjects' ) ); ?>">
						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_attendance_csv" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'CSV File', 'school-management' ); ?>:
						</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="wlsm_attendance_csv" name="csv">
							<label class="custom-file-label" for="wlsm_attendance_csv">
								<?php esc_html_e( 'Choose CSV File', 'school-management' ); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-sm btn-primary" id="wlsm-bulk-import-attendance-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'upload-attendance' ) ); ?>">
						<?php esc_html_e( 'Upload attendance', 'school-management' ); ?>
					</button>
				</div>
			</div>

			<div class="wlsm-students-attendance mt-2"></div>

		</form>
	</div>
</div>
