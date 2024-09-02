<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}

$exam_title = $exam->exam_title;
$start_date = $exam->start_date;
$end_date   = $exam->end_date;

$class_names = array();
foreach ( $exam_classes as $exam_class ) {
	array_push( $class_names, WLSM_M_Class::get_label_text( $exam_class->label ) );
}

$class_names = implode( ', ', $class_names );

$photo_id = $admit_card->photo_id;

$settings_dashboard                     = WLSM_M_Setting::get_settings_dashboard($school_id);
$school_enrollment_number = $settings_dashboard['school_enrollment_number'];
$school_admission_number  = $settings_dashboard['school_admission_number'];

$settings_url          = WLSM_M_Setting::get_settings_certificate_qcode_url( $school_id );
$school_admit_card_url = $settings_url['admin_card_url'];
?>

<!-- Print exam admit card. -->
<div class="wlsm-container wlsm d-flex mt-2 mb-2">
	<div class="col-md-12 wlsm-text-center">
		<?php
		printf(
			wp_kses(
				/* translators: 1: exam title, 2: start date, 3: end date, 4: exam classes */
				__( '<span class="wlsm-font-bold">Admit Card:</span> <span class="text-dark">%1$s (%2$s - %3$s)<br><span class="wlsm-font-bold">Class:</span> %4$s</span>', 'school-management' ),
				array( 'span' => array( 'class' => array() ), 'br' => array() )
			),
			esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
			esc_html( WLSM_Config::get_date_text( $start_date ) ),
			esc_html( WLSM_Config::get_date_text( $end_date ) ),
			esc_html( $class_names )
		);
		?>
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?> mt-2" id="wlsm-print-exam-admit-card-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-exam-admit-card.css' ); ?>"]' data-title="<?php
			printf(
				/* translators: 1: exam title, 2: start date, 3: end date, 4: exam classes */
				esc_attr__( 'Admit Card: %1$s (%2$s - %3$s), Class: %4$s', 'school-management' ),
				esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
				esc_html( WLSM_Config::get_date_text( $start_date ) ),
				esc_html( WLSM_Config::get_date_text( $end_date ) ),
				esc_html( $class_names )
			);
			?>"><?php esc_html_e( 'Print Admit Card', 'school-management' ); ?>
		</button>
	</div>
</div>

<!-- Print exam admit card section. -->
<div class="wlsm-container wlsm wlsm-form-section" id="wlsm-print-exam-admit-card">
	<div class="wlsm-print-exam-admit-card-container">

		<?php // school header section. ---------------------------------------
		$school           = WLSM_M_School::fetch_school( $school_id );
		$settings_general = WLSM_M_Setting::get_settings_general( $school_id );
		$school_logo      = $settings_general['school_logo'];
		$school_signature = $settings_general['school_signature'];
		?>

		<!-- School header -->
		<div class="container-fluid">
			<div class="row wlsm-school-header justify-content-center">
				<div class="col-3 text-right">
					<?php if ( ! empty ( $school_logo ) ) { ?>
					<img src="<?php echo esc_url( wp_get_attachment_url( $school_logo ) ); ?>" class="wlsm-print-school-logo">
					<?php } ?>
				</div>
				<div class="col-9">
					<div class="wlsm-print-school-label">
						<?php echo esc_html( WLSM_M_School::get_label_text( $school->label ) ); ?>
					</div>
					<div class="wlsm-print-school-contact">
						<?php if ( $school->phone ) { ?>
						<span class="wlsm-print-school-phone">
							<span class="wlsm-font-bold">
								<?php esc_html_e( 'Phone:', 'school-management' ); ?>
							</span>
							<span><?php echo esc_html( WLSM_M_School::get_label_text( $school->phone ) ); ?></span>
						</span>
						<?php } ?>
						<?php if ( $school->email ) { ?>
						<span class="wlsm-print-school-email">
							<span class="wlsm-font-bold">
								| <?php esc_html_e( 'Email:', 'school-management' ); ?>
							</span>
							<span><?php echo esc_html( WLSM_M_School::get_phone_text( $school->email ) ); ?></span>
						</span>
						<br>
						<?php } ?>
						<?php if ( $school->address ) { ?>
						<span class="wlsm-print-school-address">
							<span class="wlsm-font-bold">
								<?php esc_html_e( 'Address:', 'school-management' ); ?>
							</span>
							<span><?php echo esc_html( WLSM_M_School::get_email_text( $school->address ) ); ?></span>
						</span>
						<?php } ?>
					</div>
				</div>
				<div class="col-3 text-right">
					<?php if ( ! empty ( $school_admit_card_url ) ) { ?>
						<?php
							$qr_code_url           = $school_admit_card_url.'?exam_roll_number='.WLSM_M_Staff_Class::get_roll_no_text( $admit_card->roll_number ).'&id='.$admit_card->exam_id;
							$field_output          = esc_url('https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($qr_code_url) . '&choe=UTF-8');
							?>
							<?php if ($school_admit_card_url): ?>
								<img src="<?php echo esc_url( $field_output); ?>" width="120px">
							<?php endif ?>
					<?php } ?>
				</div>
			</div>
		</div>

		<div class="wlsm-heading wlsm-admit-card-heading h5 wlsm-text-center">
			<span><?php esc_html_e( 'STUDENT ADMIT CARD', 'school-management' ); ?></span>
		</div>

		<div class="row wlsm-student-details">
			<div class="col-9 wlsm-student-details-right">
				<ul class="wlsm-list-group">
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Student Name', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $admit_card->name ) ); ?></span>
					</li>

					<?php if ($school_enrollment_number): ?>
						<li>
							<span class="wlsm-font-bold"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( $admit_card->enrollment_number ); ?></span>
						</li>
						<?php endif ?>
					<?php if ($school_admission_number): ?>
						<li>
							<span class="wlsm-font-bold"><?php esc_html_e( 'Admission Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( $admit_card->admission_number ); ?></span>
						</li>
					<?php endif ?>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Session', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Session::get_label_text( $admit_card->session_label ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-pr-3 pr-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Class', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Class::get_label_text( $admit_card->class_label ) ); ?></span>
						</span>
						<span class="wlsm-pl-3 pl-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Section', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Class::get_label_text( $admit_card->section_label ) ); ?></span>
						</span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Exam Roll Number', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $admit_card->roll_number ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Phone', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $admit_card->phone ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Email', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $admit_card->email ) ); ?></span>
					</li>
				</ul>
			</div>

			<div class="col-3 wlsm-student-details-left">
				<div class="wlsm-student-photo-box">
				<?php if ( ! empty ( $photo_id ) ) { ?>
					<img src="<?php echo esc_url( wp_get_attachment_url( $photo_id ) ); ?>" class="wlsm-student-photo">
				<?php } ?>
				</div>
			</div>


			<div class="col-9 mx-auto">
			</div>
				<div class="col-3 wlsm-student-detail-left text-right">
				<span class="wlsm-font-bold text-right"><?php esc_html_e('Authorized By', 'school-management'); ?>:</span>
					<div class="wlsm-student-detail-photo-box float-right" >
						<?php if (!empty($school_signature)) { ?>
							<img width="50%" src="<?php echo esc_url(wp_get_attachment_url($school_signature)); ?>" class="wlsm-student-detail-photo">
						<?php } ?>
					</div>
				</div>

				</div>
			</div>

		<div class="row">
			<div class="col-12">
				<span>
				<?php
				printf(
					wp_kses(
						/* translators: 1: exam title, 2: start date, 3: end date */
						__( '<span class="wlsm-font-bold">Exam:</span> <span class="text-dark">%1$s (%2$s - %3$s)</span>', 'school-management' ),
						array( 'span' => array( 'class' => array() ) )
					),
					esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
					esc_html( WLSM_Config::get_date_text( $start_date ) ),
					esc_html( WLSM_Config::get_date_text( $end_date ) )
				);
				?>
				</span>
				<span class="float-md-right">
				<?php
				printf(
					wp_kses(
						/* translators: %s: exam classes */
						__( '<span class="wlsm-font-bold">Class:</span> %s</span>', 'school-management' ),
						array( 'span' => array( 'class' => array() ) )
					),
					esc_html( $class_names )
				);
				?>
				</span>
			</div>
		</div>
		<div class="table-responsive w-100">
			<table class="table table-bordered wlsm-view-exam-time-table">
				<?php require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/exam_time_table.php'; ?>
			</table>
		</div>

	</div>
</div>
