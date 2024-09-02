<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_Accountant::get_invoices_page_url();

WLSM_Helper::enqueue_datatable_assets();

global $wpdb;

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$classes       = WLSM_M_Staff_Class::fetch_classes( $school_id );

?>
<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-file-invoice"></i>
				<?php esc_html_e( 'Student Fee Report', 'school-management' ); ?>
			</span>
		</div>
		<div class="wlsm-table-block">
			<div class="row">
				<div class="col-md-12">
					<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-get-invoices-report-form" class="mb-3">
						<?php
						$nonce_action = 'get-invoices-report';
						?>
						<?php $nonce = wp_create_nonce( $nonce_action ); ?>
						<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

						<input type="hidden" name="action" value="wlsm-get-invoices-report">

						<div class="form-row">
							<div class="form-group col-md-3">
								<label for="wlsm_class" class="wlsm-font-bold">
									<?php esc_html_e( 'Class', 'school-management' ); ?>:
								</label>
								<select name="class_id" class="form-control selectpicker" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_class" data-live-search="true">
									<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
									<?php foreach ( $classes as $class ) { ?>
										<option value="<?php echo esc_attr( $class->ID ); ?>">
											<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
										</option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="wlsm_section" class="wlsm-font-bold">
									<?php esc_html_e( 'Section', 'school-management' ); ?>:
								</label>
								<select name="section_id" class="form-control selectpicker" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'All Sections', 'school-management' ); ?>" data-all-sections="1">
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="wlsm_status" class="wlsm-font-bold">
									<?php esc_html_e( 'Status', 'school-management' ); ?>:
								</label>
								<select name="status" class="form-control selectpicker" id="wlsm_status" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-fee-total' ) ); ?>" data-live-search="true" title="<?php esc_attr_e( 'All Status', 'school-management' ); ?>">
									<option value="paid"><?php esc_html_e( 'Paid', 'school-management' ); ?></option>
									<option value="unpaid"><?php esc_html_e( 'UnPaid', 'school-management' ); ?></option>
									<option value="partially_paid"><?php esc_html_e( 'Partially Paid', 'school-management' ); ?></option>
								</select>
							</div>
							<div class="col-md-3">
								<ul>
									<li> <label for="student_total_pending" >Total Pending : </label>
									<span id="fees_report_total_pending"></span> </li>
									<li><label for="student_total_paid" >Total Paid : </label>
									<span id="fees_report_total_paid"></span></li>
								</ul>
							</div>
						</div>

						<div class="form-row">
							<div class="col-md-12">
								<button type="button" class="btn btn-sm btn-outline-primary wlsm-get-invoice_total" id="wlsm-get-invoices-report-btn">
									<i class="fas fa-file-invoice"></i>&nbsp;
									<?php esc_html_e( 'Fetch Report', 'school-management' ); ?>
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<table class="table table-hover table-bordered" id="wlsm-staff-invoices-report-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Admission Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Payable', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Paid', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Due', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Phone', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Section', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
