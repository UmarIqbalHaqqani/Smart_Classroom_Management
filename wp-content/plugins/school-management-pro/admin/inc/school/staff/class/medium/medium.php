<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

global $wpdb;

// $page_url = WLSM_M_Staff_Class::get_sections_page_url();

// $school_id = $current_school['id'];

// $class = NULL;

// if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
// 	$class_id = absint( $_GET['id'] );
// 	$class    = WLSM_M_Staff_Class::fetch_class( $school_id, $class_id );
// }

// if ( ! $class ) {
// 	die();
// }

$medium = NULL;

$nonce_action = 'add-medium';

$medium_id = NULL;

$label = '';


if ( isset( $_GET['medium_id'] ) && ! empty( $_GET['medium_id'] ) ) {
	$medium_id = absint( $_GET['medium_id'] );
	$medium    = WLSM_M_Staff_Class::fetch_medium( $school_id, $medium_id, $class->ID );

	if ( $medium ) {
		$nonce_action = 'edit-medium-' . $medium->ID;

		$medium_id = $medium->ID;

		$label = $medium->label;
	}
}
?>
<div class="row justify-content-md-center ">
	<div class="col-md-12">
		<div class="card col">
				<div class="row<?php if ( $medium ) { echo ' justify-content-md-center'; } ?>">
					<?php if ( ! $medium ) { ?>
					<div class="col-md-7">
						<h2 class="h4 border-bottom pb-2">
							<i class="fas fa-layer-group text-primary"></i>
							<?php esc_html_e( 'Mediums', 'school-management' ); ?>
						</h2>
						<table class="table table-hover table-bordered" id="wlsm-class-medium-table">
							<thead>
								<tr class="text-white bg-primary">
									<th scope="col"><?php esc_html_e( '#', 'school-management' ); ?></th>
									<th scope="col"><?php esc_html_e( 'Medium', 'school-management' ); ?></th>
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
								<?php esc_html_e( 'Add New Medium', 'school-management' ); ?>
							</h2>
						</div>
						<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-medium-form">

							<?php $nonce = wp_create_nonce( $nonce_action ); ?>
							<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

							<input type="hidden" name="action" value="wlsm-save-medium">

							<?php if ( $medium ) { ?>
							<input type="hidden" name="medium_id" value="<?php echo esc_attr( $medium->ID ); ?>">
							<?php } ?>

							<div class="form-group">
								<label for="wlsm_medium_label" class="font-weight-bold"><?php esc_html_e( 'Medium', 'school-management' ); ?>:</label>
								<input type="text" name="label" class="form-control" id="wlsm_medium_label" placeholder="<?php esc_attr_e( 'Enter medium', 'school-management' ); ?>" value="<?php echo esc_attr( $label ); ?>">
							</div>

							<div>
								<span class="float-md-right">
									<button type="submit" class="btn btn-sm btn-primary" id="wlsm-save-medium-btn">
										<?php
										if ( $medium ) {
											?>
											<i class="fas fa-save"></i>&nbsp;
											<?php
											esc_html_e( 'Update Medium', 'school-management' );
										} else {
											?>
											<i class="fas fa-plus-square"></i>&nbsp;
											<?php
											esc_html_e( 'Add New Medium', 'school-management' );
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
