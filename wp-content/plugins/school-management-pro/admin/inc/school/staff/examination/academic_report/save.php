<?php
defined( 'ABSPATH' ) || die();

global $wpdb;

$page_url = WLSM_M_Staff_Examination::get_academic_report_page_url();

$school_id = $current_school['id'];

$exam = null;

$nonce_action = 'add-academic-report';

$exam_title   = '';
$exam_group   = '';
$exam_classes = array();

$exam_groups = WLSM_M_Staff_Examination::fetch_exams_groups( $school_id );
if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id   = absint( $_GET['id'] );
	$academic_report = WLSM_M_Staff_Examination::get_academic_report( $school_id, $id );

	if ( $academic_report ) {
		$nonce_action = 'edit-academic-report' . $academic_report->ID;
		$exam_group   = array();
		$exam_title   = $academic_report->label;
		$exam_classes   = $academic_report->class_id;
		$exam_group    = $academic_report->exam_group;

		$exam_classes = WLSM_M_Staff_Examination::fetch_exam_classes( $school_id, $id );
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
					if ( $exam ) {
						printf(
							wp_kses(
								/* translators: 1: exam title, 2: start date, 3: end date */
								__( 'Edit Report: %1$s ', 'school-management' ),
								array(
									'span' => array( 'class' => array() ),
								)
							),
							esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_title ) ),
							esc_html( '' ),
							esc_html( '')
						);
					} else {
						esc_html_e( 'Add New Academic Report', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-clock"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-report-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-report">
			<input type="hidden" name="report_id" value="<?php echo esc_attr($id) ?>">

			<?php if ( $exam ) { ?>
				<input type="hidden" name="exam_id" value="<?php echo esc_attr( $exam->ID ); ?>">
			<?php } ?>

			<!-- Report Detail -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Report Detail', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_report_title" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Report Title', 'school-management' ); ?>:
						</label>
						<input type="text" name="label" class="form-control" id="wlsm_report_title" placeholder="<?php esc_attr_e( 'Enter report title', 'school-management' ); ?>" value="<?php echo esc_attr( stripslashes( $exam_title ) ); ?>">
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_exam_group" class="wlsm-font-bold">
							<?php esc_html_e( 'Group', 'school-management' ); ?>:
						</label>
						<select name="exam_group" class="form-control selectpicker" id="wlsm_exam_group" data-actions-box="true" data-none-selected-text="<?php esc_attr_e( 'Select', 'school-management' ); ?>">
							<option value=""><?php esc_html_e( 'Select', 'school-management' ); ?></option>
							<?php foreach ( $exam_groups as $group ) { ?>
								<option <?php selected(  $group->ID, $exam_group, true, true ); ?> value="<?php echo esc_attr( $group->ID ); ?>">
									<?php echo esc_html( WLSM_M_Class::get_label_text( $group->label ) ); ?>
								</option>
							<?php } ?>
						</select>
					</div>

				</div>

				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_classes" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="class_id" class="form-control selectpicker" id="wlsm_class_report" data-actions-box="true" data-none-selected-text="<?php esc_attr_e( 'Select', 'school-management' ); ?>">
							<option value=""><?php esc_attr_e( 'Select Class', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
								<option <?php selected( in_array( $class->ID, $exam_classes ), true, true ); ?> value="<?php echo esc_attr( $class->ID ); ?>">
									<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
								</option>
							<?php } ?>
						</select>
						<p><?php esc_html_e( 'You can assign create this exam for single class.', 'school-management' ); ?></p>
					</div>

					<div class="form-group col-md-6">
						<label for="wlsm_exams" class="wlsm-font-bold">
							<?php esc_html_e( 'Exams', 'school-management' ); ?>:
						</label>
						<select multiple name="exams[]" class="form-control selectpicker" id="wlsm_exams" data-actions-box="true" data-none-selected-text="<?php esc_attr_e( 'Select', 'school-management' ); ?>">
							<?php foreach ( $exams as $exam ) { ?>
								<option <?php selected( in_array( $exam->ID, $route_exams ), true, true ); ?> value="<?php echo esc_attr( $exam->ID ); ?>">
									<?php echo esc_html( $exam->exam_number ); ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-report-btn">
						<?php
						if ( $exam ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Report', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Report', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
