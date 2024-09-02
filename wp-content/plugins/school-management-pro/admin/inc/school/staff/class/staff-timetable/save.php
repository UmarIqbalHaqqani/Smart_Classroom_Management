<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_Class::get_timetable_page_url();

$school_id = $current_school['id'];

$routine = NULL;

$nonce_action = 'add-routine';

$class_id    = NULL;
$section_id  = NULL;
$subject_id  = NULL;
$start_time  = NULL;
$end_time    = NULL;
$day         = NULL;
$room_number = NULL;

$sections = array();
$subjects = array();
$teachers = array();

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id      = absint( $_GET['id'] );
	$routine = WLSM_M_Staff_Class::fetch_routine( $school_id, $id );

	if ( $routine ) {
		$nonce_action = 'edit-routine-' . $routine->ID;

		$class_id    = $routine->class_id;
		$section_id  = $routine->section_id;
		$subject_id  = $routine->subject_id;
		$teacher_id  = $routine->admin_id;
		$start_time  = $routine->start_time;
		$end_time    = $routine->end_time;
		$day         = $routine->day;
		$room_number = $routine->room_number;

		$class_label   = $routine->class_label;
		$section_label = $routine->section_label;

		$class_school = WLSM_M_Staff_Class::get_class( $school_id, $class_id );

		if ( $class_school ) {
			$class_school_id = $class_school->ID;

			$sections = WLSM_M_Staff_General::fetch_class_sections( $class_school_id );
		}

		$subjects = WLSM_M_Staff_Class::get_class_subjects( $school_id, $class_id );

		$subject = WLSM_M_Staff_Class::get_class_subject( $school_id, $class_id, $subject_id );

		if ( $subject ) {
			$teachers = WLSM_M_Staff_Class::fetch_subject_admins( $school_id, $subject_id );
		}
	}
}

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );

$days = WLSM_Helper::days_list();
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $routine ) {
						printf(
							wp_kses(
								/* translators: 1: class label, 2: section */
								__( 'Edit Routine: %1$s - %2$s', 'school-management' ),
								array(
									'span' => array( 'class' => array() )
								)
							),
							esc_html( WLSM_M_Class::get_label_text( $class_label ) ),
							esc_html( WLSM_M_Staff_Class::get_section_label_text( $section_label ) )
						);
					} else {
						esc_html_e( 'Add New Class Routine', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<?php if ( $routine ) { ?>
				<a href="<?php echo esc_url( $page_url . '&action=timetable&id=' . $section_id ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-calendar-alt"></i>&nbsp;
					<?php esc_html_e( 'Class Timetable', 'school-management' ); ?>
				</a>
				<?php } ?>
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-calendar-alt"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-routine-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-routine">

			<?php if ( $routine ) { ?>
			<input type="hidden" name="routine_id" value="<?php echo esc_attr( $routine->ID ); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="class_id" class="form-control selectpicker wlsm_class_subjects" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" data-nonce-subjects="<?php echo esc_attr( wp_create_nonce( 'get-class-subjects' ) ); ?>" id="wlsm_class" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
							<option <?php selected( $class->ID, $class_id, true ); ?> value="<?php echo esc_attr( $class->ID ); ?>" <?php selected( $class->ID, $class_id, true ); ?>>
								<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_section" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="section_id" class="form-control selectpicker" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'Select Section', 'school-management' ); ?>">
							<?php foreach ( $sections as $section ) { ?>
							<option value="<?php echo esc_attr( $section->ID ); ?>" <?php selected( $section->ID, $section_id, true ); ?>>
								<?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $section->label ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_section" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Subject', 'school-management' ); ?>:
						</label>
						<select name="subject_id" class="form-control selectpicker wlsm_subject_teachers" id="wlsm_subject" data-nonce-teachers="<?php echo esc_attr( wp_create_nonce( 'get-subject-teachers' ) ); ?>" data-live-search="true" title="<?php esc_attr_e( 'Select Subject', 'school-management' ); ?>">
							<?php foreach ( $subjects as $subject ) { ?>
							<option value="<?php echo esc_attr( $subject->ID ); ?>" <?php selected( $subject->ID, $subject_id, true ); ?>>
								<?php
								printf(
									wp_kses(
										/* translators: 1: subject label, 2: subject code */
										_x( '%1$s (%2$s)', 'Subject', 'school-management' ),
										array( 'span' => array( 'class' => array() ) )
									),
									esc_html( WLSM_M_Staff_Class::get_subject_label_text( $subject->label ) ),
									esc_html( $subject->code )
								);
								?>
							</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-sm-6 col-md-4">
						<label for="wlsm_start_time" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Start Time', 'school-management' ); ?>:
						</label>
						<input type="text" name="start_time" class="form-control wlsm_time" id="wlsm_start_time" placeholder="<?php esc_attr_e( 'Enter start time', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::get_time_text( $start_time ) ); ?>">
					</div>
					<div class="form-group col-sm-6 col-md-4">
						<label for="wlsm_end_time" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'End Time', 'school-management' ); ?>:
						</label>
						<input type="text" name="end_time" class="form-control wlsm_time" id="wlsm_end_time" placeholder="<?php esc_attr_e( 'Enter end time', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::get_time_text( $end_time ) ); ?>">
					</div>
					<div class="form-group col-sm-6 col-md-4">
						<label for="wlsm_day" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Day', 'school-management' ); ?>:
						</label>
						<select name="day[]" class="form-control selectpicker" id="wlsm_day" title="<?php esc_attr_e( 'Select Day', 'school-management' ); ?>"  multiple data-live-search="true" data-actions-box="true">
							<?php foreach ( $days as $key => $value ) { ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $day, true ); ?>>
								<?php echo esc_html( $value ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-sm-6 col-md-4">
						<label for="wlsm_room_number" class="wlsm-font-bold">
							<?php esc_html_e( 'Room Number', 'school-management' ); ?>:
						</label>
						<input type="text" name="room_number" class="form-control" id="wlsm_room_number" placeholder="<?php esc_attr_e( 'Enter room number', 'school-management' ); ?>" value="<?php echo esc_attr( $room_number ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_teacher" class="wlsm-font-bold">
							<?php esc_html_e( 'Teacher', 'school-management' ); ?>:
						</label>
						<select name="admin_id" class="form-control selectpicker" id="wlsm_teacher" data-live-search="true" title="<?php esc_attr_e( 'Select Teacher', 'school-management' ); ?>">
							<option value=""></option>
							<?php foreach ( $teachers as $teacher ) { ?>
							<option value="<?php echo esc_attr( $teacher->ID ); ?>" <?php selected( $teacher->ID, $teacher_id, true ); ?>>
								<?php
								printf(
									wp_kses(
										/* translators: 1: Teacher name, 2: Teacher phone number */
										_x( '%1$s (%2$s)', 'Teacher', 'school-management' ),
										array( 'span' => array( 'class' => array() ) )
									),
									esc_html( WLSM_M_Staff_Class::get_name_text( $teacher->name ) ),
									esc_html( WLSM_M_Staff_Class::get_phone_text( $teacher->phone ) )
								);
								?>
							</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-routine-btn">
						<?php
						if ( $routine ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Routine', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Routine', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
