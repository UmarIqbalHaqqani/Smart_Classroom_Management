<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

global $wpdb;

$page_url = WLSM_M_Staff_Class::get_student_type_page_url();


$student_type = NULL;

$nonce_action = 'add-student-type';

$student_type_id = NULL;

$label = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id = absint( $_GET['id'] );
	$student_type    = WLSM_M_Staff_Class::fetch_student_type( $student_type_id );

	if ( $student_type ) {
		$nonce_action = 'edit-student-type-' . $student_type->ID;

		$student_type_id = $student_type->ID;

		$label = $student_type->label;
	}
}
?>
<div class="row justify-content-md-center ">
	<div class="col-md-12">
		<div class="card col">
				<div class="row<?php if ( $student_type ) { echo ' justify-content-md-center'; } ?>">
					<?php if ( ! $student_type ) { ?>
					<div class="col-md-7">
						<h2 class="h4 border-bottom pb-2">
							<i class="fas fa-layer-group text-primary"></i>
							<?php esc_html_e( 'Student Types', 'school-management' ); ?>
						</h2>
						<table class="table table-hover table-bordered" id="wlsm-class-student-type-table">
							<thead>
								<tr class="text-white bg-primary">
									<th scope="col"><?php esc_html_e( '#', 'school-management' ); ?></th>
									<th scope="col"><?php esc_html_e( 'Student Type', 'school-management' ); ?></th>
									<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
								</tr>
							</thead>
						</table>
					</div>
					<?php } ?>
					<div class="col-md-5">
						<div class="wlsm-page-heading-box">
							<h2 class="h4 border-bottom pb-2 wlsm-page-heading">
								<i class="fas fa-plus-square text-primary"></i>
								<?php esc_html_e( 'Add New Student Type', 'school-management' ); ?>
							</h2>
						</div>
						<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-student-type-form">

							<?php $nonce = wp_create_nonce( $nonce_action ); ?>
							<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

							<input type="hidden" name="action" value="wlsm-save-student-type">

							<?php if ( $student_type ) { ?>
							<input type="hidden" name="student_type_id" value="<?php echo esc_attr( $student_type->ID ); ?>">
							<?php } ?>

							<div class="form-group">
								<label for="wlsm_student_type_label" class="font-weight-bold"><?php esc_html_e( 'Student Type', 'school-management' ); ?>:</label>
								<input type="text" name="label" class="form-control" id="wlsm_student_type_label" placeholder="<?php esc_attr_e( 'Enter student type', 'school-management' ); ?>" value="<?php echo esc_attr( $label ); ?>">
							</div>

							<div>
								<span class="float-md-right">
									<button type="submit" class="btn btn-sm btn-primary" id="wlsm-save-student-type-btn">
										<?php
										if ( $student_type ) {
											?>
											<i class="fas fa-save"></i>&nbsp;
											<?php
											esc_html_e( 'Update Student Type', 'school-management' );
										} else {
											?>
											<i class="fas fa-plus-square"></i>&nbsp;
											<?php
											esc_html_e( 'Add New Student Type', 'school-management' );
										}
										?>
									</button>
								</span>
							</div>

						</form>
					</div>
				</div>

		</div>
	</div>
</div>
