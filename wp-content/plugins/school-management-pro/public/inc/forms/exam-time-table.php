<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

$school_id = NULL;
if ( isset( $attr['school_id'] ) ) {
	$school_id = absint( $attr['school_id'] );

	$school = WLSM_M_School::get_active_school( $school_id );
	if ( ! $school ) {
		$invalid_message = esc_html__( 'School not found.', 'school-management' );
		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/partials/invalid.php';
	}

	if ( isset( $attr['exam_id'] ) ) {
		$school_id = absint( $attr['school_id'] );
		$exam_id   = absint( $attr['exam_id'] );
		$exam      = WLSM_M_Staff_Examination::fetch_exam( $school_id, $exam_id );

		if ( ! $exam ) {
			$invalid_message = esc_html__( 'Exam not found.', 'school-management' );
			return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/partials/invalid.php';
		}

		return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/forms/partials/exam-time-table.php';
	}

} else {
	$school  = NULL;
	$schools = WLSM_M_School::get_active_schools();
}

$exams = WLSM_M_Staff_Examination::get_school_published_exams_time_table( $school_id );

$nonce_action = 'get-exam-time-table';
?>
<div class="wlsm">
	<div id="wlsm-get-exam-time-table-section">
		<div class="wlsm-header-title wlsm-font-bold wlsm-mb-3">
			<span class="wlsm-border-bottom wlsm-pb-1">
				<?php esc_html_e( 'Exam Time Table', 'school-management' ); ?>
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-get-exam-time-table-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-p-get-exam-time-table">
			<?php
			if ( ! $school ) {
			?>
			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label for="wlsm_school_exams_time_table" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'School', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<select name="school_id" class="wlsm-form-control wlsm_school_exams_time_table" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-school-exams' ) ); ?>" id="wlsm_school_exams_time_table" data-live-search="true">
						<option value=""><?php esc_html_e( 'Select School', 'school-management' ); ?></option>
						<?php foreach ( $schools as $value ) { ?>
						<option value="<?php echo esc_attr( $value->ID ); ?>">
							<?php echo esc_html( WLSM_M_School::get_label_text( $value->label ) ); ?>
						</option>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php
			} else {
			?>
			<input type="hidden" name="school_id" value="<?php echo esc_attr( $school_id ); ?>" id="wlsm_school_exams">
			<?php
			}
			?>
			<div class="wlsm-form-group wlsm-row wlsm-mb-2">
				<div class="wlsm-col-4">
					<label for="wlsm_school_exam" class="wlsm-form-label wlsm-font-bold">
						<span class="wlsm-text-danger">*</span> <?php esc_html_e( 'Exam', 'school-management' ); ?>:
					</label>
				</div>
				<div class="wlsm-col-8">
					<select name="exam_id" class="wlsm-form-control" id="wlsm_school_exam">
						<option value=""><?php esc_html_e( 'Select Exam', 'school-management' ); ?></option>
						<?php foreach ( $exams as $exam ) { ?>
						<option value="<?php echo esc_attr( $exam->ID ); ?>">
							<?php echo esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam->exam_title ) ); ?>
						</option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="wlsm-border-top wlsm-pt-2 wlsm-mt-1">
				<button class="button wlsm-btn btn btn-primary" type="submit" id="wlsm-get-exam-time-table-btn">
					<?php esc_html_e( 'Get Time Table', 'school-management' ); ?>
				</button>
			</div>

		</form>

		<div class="wlsm-shortcode-entity">
			<div class="wlsm-exam-time-table"></div>
		</div>

	</div>
</div>
<?php
return ob_get_clean();
