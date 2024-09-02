<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_Accountant::get_invoices_page_url();

$can_delete_payments = WLSM_M_Role::check_permission( array( 'delete_payments' ), $current_school['permissions'] );
?>

<!-- Payment History -->
<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-file-invoice"></i>
				<?php esc_html_e( 'Payment History', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . '&action=pending_payments' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-file-invoice"></i>&nbsp;
					<?php echo esc_html( 'Pending Payments', 'school-management' ); ?>
				</a>&nbsp;
				<a href="<?php echo esc_url( $page_url . '&action=save' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-plus-square"></i>&nbsp;
					<?php echo esc_html( 'Add New Fee Invoice', 'school-management' ); ?>
				</a>&nbsp;
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-file-invoice"></i>&nbsp;
					<?php echo esc_html( 'View Invoices', 'school-management' ); ?>
				</a>
			</span>
		</div>

		
		<div class="wlsm-table-block">
			<div class="row">
			<div class="col-md-8">
					<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-fetch-payments-form" class="mb-3">
						<?php
						$nonce_action = 'get-payments';
						?>
						<?php $nonce = wp_create_nonce( $nonce_action ); ?>
						<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

						<input type="hidden" name="action" value="wlsm-fetch-payments">

						<div class="wlsm-search-students wlsm-search-keyword-students pt-2">
							<div class="row">
								<div class="col-md-8 mb-1">
									<div class="h6">
										<span class="text-secondary border-bottom">
										<?php esc_html_e( 'Select Date', 'school-management' ); ?>
										</span>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-4">
									<label for="wlsm_search_field" class="wlsm-font-bold">
										<?php esc_html_e( 'Start Date', 'school-management' ); ?>:
									</label>
									<input type="text" name="start_date" class="form-control wlsm_payment_date" id="wlsm_search_keyword" placeholder="<?php esc_attr_e( 'Enter Search Keyword', 'school-management' ); ?>">
								</div>
								<div class="form-group col-md-4">
									<label for="wlsm_search_keyword" class="wlsm-font-bold">
										<?php esc_html_e( 'End Date', 'school-management' ); ?>:
									</label>
									<input type="text" name="end_date" class="form-control wlsm_payment_date" id="wlsm_search_keyword" placeholder="<?php esc_attr_e( 'Enter Search Keyword', 'school-management' ); ?>">
								</div>
								<div class="form-group col-md-4">
									<label for="wlsm_search_keyword" class="wlsm-font-bold">
										<?php esc_html_e( 'Total', 'school-management' ); ?>:
									</label>
									<input type="text" name="total" class="form-control" id="wlsm_history_total" placeholder="<?php esc_attr_e( '', 'school-management' ); ?>" readonly>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-12">
								<button type="button" class="btn btn-sm btn-outline-primary" id="wlsm-fetch-payments-btn">
									<i class="fas fa-file-invoice"></i>&nbsp;
									<?php esc_html_e( 'Get Payment History', 'school-management' ); ?>
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<table class="table table-hover table-bordered" id="wlsm-payments-table">
				<thead>
					<tr class="text-white bg-primary">
						<th><?php esc_html_e( 'Receipt Number', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Amount', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Payment Method', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Transaction ID', 'school-management' ); ?></th>
						<th class="text-nowrap"><?php esc_html_e( 'Date', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Note', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Invoice', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Admission Number', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Section', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Phone', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'father Name', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'father Phone', 'school-management' ); ?></th>
						<th><?php esc_html_e( 'Print', 'school-management' ); ?></th>
						<?php if ( $can_delete_payments ) { ?>
						<th class="text-nowrap"><?php esc_html_e( 'Delete', 'school-management' ); ?></th>
						<?php } ?>
					</tr>
				</thead>
			</table>
			<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/partials/export.php'; ?>
		</div>
	</div>
</div>
