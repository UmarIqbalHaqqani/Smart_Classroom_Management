<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Role.php';

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$page_url = WLSM_M_Staff_General::get_employees_page_url();
$role     = WLSM_M_Role::get_employee_key();
$nonce_action = 'bulk-import-staff';
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-2 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-file-import"></i>
				<?php esc_html_e( 'Bulk Import staff', 'school-management' ); ?>
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-bulk-import-staff-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="<?php echo esc_attr( 'wlsm-bulk-import-staff' ); ?>">

			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Import staff From CSV File', 'school-management' ); ?>
							<br>
							<small class="text-dark">
								<em><?php esc_html_e( 'click "Export Sample CSV", fill staff details in the file, choose the CSV file with staff details and click "Bulk Import".', 'school-management' ); ?></em>
							</small>
						</div>
					</div>
				</div>

				<div class="form-row mt-2">
					<div class="form-group col-md-4 col-lg-4 ">
						<label for="wlsm_students_csv" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'CSV File', 'school-management' ); ?>:
						</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="wlsm_students_csv" name="csv">
							<label class="custom-file-label" for="wlsm_students_csv">
								<?php esc_html_e( 'Choose CSV File', 'school-management' ); ?>
							</label>
						</div>
					</div>

					<div class="form-group col-md-4">
						<label class="wlsm-font-bold">
							<?php esc_html_e( 'Export Sample CSV File', 'school-management' ); ?>
						</label>
						<br>
						<button type="button" class="btn btn-sm btn-outline-primary p-2" id="wlsm-staff-sample-csv-export-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'staff-sample-csv-export' ) ); ?>">
                            <i class="fas fa-cloud-download-alt pr-1"></i>
							<?php esc_html_e( 'Export Sample CSV', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-sm btn-primary" id="wlsm-bulk-import-staff-btn" data-message-title="<?php esc_attr_e( 'Confirm Import!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'Are you sure to import these students to selected class?', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Import', 'school-management' ); ?>">
						<i class="fas fa-file-import"></i>
						<?php esc_html_e( 'Bulk Import', 'school-management' ); ?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>