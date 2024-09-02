<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_General.php';

$page_url = WLSM_M_Staff_General::get_certificates_page_url();
?>

<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-certificate"></i>
				<?php esc_html_e( 'Certificates', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . '&action=save' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-plus-square"></i>&nbsp;
					<?php echo esc_html( 'Add New Certificate', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<div class="wlsm-table-block wlsm-form-section">
			<table class="table table-hover table-bordered" id="wlsm-certificates-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Title', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Total Certificates Distributed', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Distribute Certificate', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
