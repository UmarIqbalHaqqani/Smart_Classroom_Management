<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Class.php';

$page_url = WLSM_M_Class::get_page_url();
?>

<div class="wlsm container-fluid">
	<div class="row">
		<div class="wlsm-main-header card col">
			<div class="card-header">
				<h1 class="h3 text-center">
					<i class="fas fa-layer-group text-primary"></i>
					<?php esc_html_e( 'Classes', 'school-management' ); ?>
				</h1>
				<div class="float-right">
					<a href="<?php echo esc_url( $page_url . '&action=save' ); ?>" class="btn btn-sm btn-primary">
						<?php echo esc_html( 'Add Class', 'school-management' ); ?>
					</a>
				</div>
				
			</div>
			<div class="card-body">
				<table class="table table-hover table-bordered" id="wlsm-classes-table">
					<thead>
						<tr class="text-white bg-primary">
							<th scope="col"><?php esc_html_e( 'Class Name', 'school-management' ); ?></th>
							<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
