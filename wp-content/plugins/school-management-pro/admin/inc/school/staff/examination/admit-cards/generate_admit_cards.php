<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

$page_url             = WLSM_M_Staff_Examination::get_exams_page_url();
$admit_cards_page_url = WLSM_M_Staff_Examination::get_exams_admit_cards_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$exam = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );

if ( ! $exam ) {
	die;
}

$nonce_action = 'generate-admit-cards-' . $exam->ID;

$exam_title = $exam->exam_title;
$start_date = $exam->start_date;
$end_date   = $exam->end_date;

$exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes( $school_id, $exam_id );

$admit_cards = WLSM_M_Staff_Examination::get_exam_admit_cards( $school_id, $exam_id );

$students = WLSM_M_Staff_Class::fetch_active_students_of_classes( $school_id, $session_id, $exam_classes );

$admit_card_student_ids = array_map( function( $admit_card ) {
	return $admit_card->student_id;
}, $admit_cards );

// Classes students without admit cards.
$students = array_filter( $students, function( $student ) use ( $admit_card_student_ids ) {
	return ( ! in_array( $student->ID, $admit_card_student_ids ) );
} );
?>

<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					printf(
						wp_kses(
							/* translators: 1: exam title, 2: start date, 3: end date */
							__( 'Exam: %1$s (%2$s - %3$s)', 'school-management' ),
							array(
								'span' => array( 'class' => array() )
							)
						),
						esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
						esc_html( WLSM_Config::get_date_text( $start_date ) ),
						esc_html( WLSM_Config::get_date_text( $end_date ) )
					);
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . '&action=save&id=' . $exam_id ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-edit"></i>&nbsp;
					<?php esc_html_e( 'Edit Exam', 'school-management' ); ?>
				</a>&nbsp;
				<a href="<?php echo esc_url( $admit_cards_page_url . '&action=admit_cards&exam_id=' . $exam_id ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-id-card"></i>&nbsp;
					<?php esc_html_e( 'View Admit Cards', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-generate-admit-cards-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-generate-admit-cards">

			<?php if ( $exam ) { ?>
			<input type="hidden" name="exam_id" value="<?php echo esc_attr( $exam->ID ); ?>">
			<?php } ?>

			<!-- Exam roll number series. -->
			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-12 mb-0">
						<label for="wlsm_roll_numbers" class="wlsm-font-bold">
							<?php esc_html_e( 'Starting Roll Number & Prefix', 'school-management' ); ?>:
							<br>
							<small>
								<?php esc_html_e( 'For example, enter "CS" and "10001" to create roll numbers - CS10001, CS10002, CS10003 and so on.', 'school-management' ); ?>
							</small>
						</label>
					</div>
					<div class="col-sm-12 col-md-7">
						<div class="row">
							<div class="col-sm-4 col-md-3 mb-1 pr-0 pl-1">
								<input type="text" name="roll_number_prefix" class="ml-1 mr-1 form-control" id="wlsm_roll_number_prefix" placeholder="<?php esc_attr_e( 'Enter prefix', 'school-management' ); ?>">
							</div>
							<div class="col-sm-8 col-md-9 mb-1 pr-0 pl-1">
								<input type="number" name="staring_roll_number" class="ml-1 mr-1 form-control" id="wlsm_staring_roll_number" placeholder="<?php esc_attr_e( 'Enter starting exam roll number', 'school-management' ); ?>">
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="wlsm-students-without-admit-cards-box">
				<div class="wlsm-students-without-admit-cards">
					<?php if ( count( $students ) ) { ?>
					<!-- Class students with no admit cards. -->
					<div class="wlsm-form-section">
						<div class="wlsm-students-without-admit-cards">
							<div class="wlsm-font-bold">
								<?php esc_html_e( 'Generate Admit Cards for Students', 'school-management' ); ?>:
							</div>
							<div class="table-responsive w-100">
								<table class="table table-bordered wlsm-students-without-admit-cards-table">
									<thead>
										<tr class="bg-primary text-white">
											<th><input type="checkbox" name="select_all" id="wlsm-select-all" value="1"></th>
											<th><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Class', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Section', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Phone', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Email', 'school-management' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ( $students as $row ) {
										?>
										<tr>
											<td>
												<input type="checkbox" class="wlsm-select-single" name="student[<?php echo esc_attr( $row->ID ); ?>]" value="<?php echo esc_attr( $row->ID ); ?>">
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Class::get_label_text( $row->class_label ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ); ?>
											</td>
											<td>
												<?php echo esc_html( $row->enrollment_number ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $row->phone ) ); ?>
											</td>
											<td>
												<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->email ) ); ?>
											</td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="row mt-2">
						<div class="col-md-12 text-center">
							<button type="submit" class="btn btn-primary" id="wlsm-generate-admit-cards-btn" data-message-title="<?php esc_attr_e( 'Generate Admit Cards!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'Are you sure to generate admit cards for selected students?', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Generate', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>">
								<i class="fas fa-id-card"></i>&nbsp;
								<?php esc_html_e( 'Generate Admit Cards', 'school-management' ); ?>
							</button>
						</div>
					</div>
					<?php } else { ?>
					<div class="alert alert-info mt-2 wlsm-font-bold text-center">
						<span><?php esc_html_e( 'No student without admit card.', 'school-management' ); ?></span>
					</div>
					<?php } ?>
				</div>
			</div>

		</form>
	</div>
</div>
