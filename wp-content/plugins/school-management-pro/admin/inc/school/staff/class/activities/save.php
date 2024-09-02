<?php
defined( 'ABSPATH' ) || die();

global $wpdb;

$page_url = WLSM_M_Staff_Class::get_student_activity_page_url();

$school_id = $current_school['id'];

$session_id = $current_session['ID'];

$student_activity = NULL;

$nonce_action = 'add-student-activity';

$title       = '';
$fees  		 = '';
$description = '';
$is_approved = 0;
$class_id	 = '';

$multiple_days = true;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id       = absint( $_GET['id'] );
	$student_activity = WLSM_M_Staff_Class::fetch_student_activity( $id );

	if ( $student_activity ) {
		$nonce_action = 'edit-student-activity-' . $student_activity->ID;

		$description = $student_activity->description;
		$title       = $student_activity->title;
		$fees        = $student_activity->fees;
		$class_id    = $student_activity->class_id;
		$is_approved = $student_activity->is_approved;

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
					if ( $student_activity ) {
						/* translators: 1: student name, 2: enrollment number */
						printf( esc_html__( 'Edit Student Activity', 'school-management' ));
					} else {
						esc_html_e( 'Add Student Activity', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-calendar-alt"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-student-activity-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-student-activity">

			<?php if ( $student_activity ) { ?>
			<input type="hidden" name="student_activity_id" value="<?php echo esc_attr( $student_activity->ID ); ?>">
			<!-- <input type="hidden" name="student" value="<?php echo esc_attr( $student_activity->student_id ); ?>"> -->
			<?php } ?>

			<div class="wlsm-form-section">
				<?php if ( ! $student_activity ) { ?>
				<!-- <div class="form-row">
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
							<?php esc_html_e( 'Student', 'school-management' ); ?>:
						</label>
						<select name="student" class="form-control selectpicker" id="wlsm_student" data-live-search="true" data-none-selected-text="<?php esc_attr_e( 'Select Student', 'school-management' ); ?>">
						</select>
					</div>
				</div> -->
				<?php } ?>


				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_title" class="wlsm-font-bold">
							<?php esc_html_e( 'Title', 'school-management' ); ?>:
						</label>
						<input name="title" type="text" class="form-control" placeholder="<?php esc_attr_e( 'Enter title', 'school-management' ); ?>" value="<?php echo esc_html( stripslashes( $title ) ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_fees" class="wlsm-font-bold">
							<?php esc_html_e( 'Fees', 'school-management' ); ?>:
						</label>
						<input name="fees" type="number" class="form-control" placeholder="<?php esc_attr_e( 'Enter fees', 'school-management' ); ?>" value="<?php echo esc_html( stripslashes( $fees ) ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="class_id[]" class="form-control selectpicker" multiple <?php if ($class_id) {
							echo "disabled";
						} ?>>
							<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
							<option value="<?php echo esc_attr( $class->ID ); ?>" <?php selected($class_id, $class->ID); ?>>
								<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_description" class="wlsm-font-bold">
							<?php esc_html_e( 'Description', 'school-management' ); ?>:
						</label>
						<textarea name="description" class="form-control" id="wlsm_description" placeholder="<?php esc_attr_e( 'Enter description', 'school-management' ); ?>" cols="30" rows="5"><?php echo esc_html( stripslashes( $description ) ); ?></textarea>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_is_approved" class="wlsm-font-bold mt-4">
							<?php esc_html_e( 'Status', 'school-management' ); ?>:
						</label>
						<br>
						<div class="form-check form-check-inline">
							<input <?php checked( 1, $is_approved, true ); ?> class="form-check-input" type="radio" name="is_approved" id="wlsm_is_approved_1" value="1">
							<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_is_approved_1">
								<?php echo esc_html( 'Active',  'school-management'  ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked( 0, $is_approved, true ); ?> class="form-check-input" type="radio" name="is_approved" id="wlsm_is_approved_0" value="0">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_is_approved_0">
							<?php echo esc_html( 'Inactive',  'school-management'  ); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-student-activity-btn">
						<?php
						if ( $student_activity ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Student Activity', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add Student Activity', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
