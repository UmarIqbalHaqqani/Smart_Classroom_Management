<?php
defined( 'ABSPATH' ) || die();

global $wpdb;

$page_url             = WLSM_M_Staff_Examination::get_exams_group_url();

$school_id = $current_school['id'];

$exam = NULL;
$exam_group_title = NULL;
$exam_group_center = NULL; 
$is_active = 1; 

$nonce_action = 'add-exam-group';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id   = absint( $_GET['id'] );
	$exam = WLSM_M_Staff_Examination::fetch_exams_group( $school_id, $id );

	if ( $exam ) {
		$nonce_action = 'edit-exam-group' . $exam->ID;
		$exam_group_title  = $exam->label;
	}
}

?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
							echo esc_html( WLSM_M_Staff_Examination::get_exam_label_text( $exam_group_title ) );
					
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
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-exam-group-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-exam-group">

			<?php if ( $exam ) { ?>
			<input type="hidden" name="exam_id" value="<?php echo esc_attr( $exam->ID ); ?>">
			<?php } ?>

			<!-- Exam Detail -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Exam Group', 'school-management' ); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_exam_title" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Exam Group Title', 'school-management' ); ?>:
						</label>
						<input type="text" name="label" class="form-control" id="wlsm_exam_title" placeholder="<?php esc_attr_e( 'Enter exam title', 'school-management' ); ?>" value="<?php echo esc_attr( stripslashes( $exam_group_title ) ); ?>">
					</div>
					<!--  -->
				</div>
                <div class="form-row">
					<div class="form-group col-md-12">
						<div class="form-check form-check-inline">
							<input <?php checked( 1, $is_active, true ); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_active" value="1">
							<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_status_active">
								<?php echo esc_html( WLSM_M_Staff_Examination::get_active_text() ); ?>
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input <?php checked( 0, $is_active, true ); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_inactive" value="0">
							<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_status_inactive">
								<?php echo esc_html( WLSM_M_Staff_Examination::get_inactive_text() ); ?>
							</label>
						</div>
					</div>
				</div>

                <div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-exam-group-btn">
						<?php
						if ( $exam ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Exam Group', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Exam Group', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>
                
			</div>
		</form>
	</div>
</div>
