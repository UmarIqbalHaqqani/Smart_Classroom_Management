<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}
$sum                              = 0;
$due                              = $invoice->payable - $invoice->paid;
$settings_general                 = WLSM_M_Setting::get_settings_general( $school_id );
$school_generate_invoices_history = $settings_general['invoices_history'];
?>

<!-- Print invoice. -->
<div class="wlsm-container d-flex mb-2">
	<div class="col-md-12 wlsm-text-center">
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?>" id="wlsm-print-invoice-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-invoice.css' ); ?>"]' data-title="
												<?php
												printf(
													/* translators: 1: invoice title, 2: invoice number */
													esc_attr__( 'Fee Invoice - %1$s (%2$s)', 'school-management' ),
													esc_attr( WLSM_M_Staff_Accountant::get_invoice_title_text( $invoice->invoice_title ) ),
													esc_attr( $invoice->invoice_number )
												);
												?>
																																																																																																"><?php esc_html_e( 'Print Fee Invoice', 'school-management' ); ?>
		</button>
	</div>
</div>
<!-- Print invoice section. -->
<div class="wlsm-container wlsm" id="wlsm-print-invoice" >
	<div class="wlsm-print-invoice-container">
		<?php require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/school_header.php'; ?>

		<div class="row">
			<div class="col-md-12">
				<div class="wlsm-h5 wlsm-invoice-heading text-center">
					<?php
					printf(
						wp_kses(
							/* translators: %s: invoice title */
							__( '<span class="wlsm-font-bold">Fee Invoice:</span> %s', 'school-management' ),
							array(
								'span' => array( 'class' => array() ),
							)
						),
						esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $invoice->invoice_title ) )
					);
					?>
					<small class="float-md-right">
						<?php
						printf(
							wp_kses(
								/* translators: %s: invoice number */
								__( '<span class="wlsm-font-bold">Invoice No.</span> %s', 'school-management' ),
								array( 'span' => array( 'class' => array() ) )
							),
							esc_html( $invoice->invoice_number )
						);
						?>
					</small>
				</div>
			</div>
		</div>

<?php
$settings_background   = WLSM_M_Setting::get_settings_background($school_id);
$invoice_card_background = $settings_background['invoice_card_background'];
?>
		<div class="col-md-12">
			<div class="invoice border border-secondary wlsm-print-invoice-container"  style="background: no-repeat center/100% url(<?php echo ( wp_get_attachment_url($invoice_card_background) );  ?>) !important;  ">
				<!-- begin invoice-header -->
				<div class="invoice-header">
					<div class="invoice-from">
						<address class="m-t-5 m-b-5">
							<strong class="text-inverse"><?php esc_html_e( 'Student Name', 'school-management' ); ?> :</strong><br>
							<strong><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></strong> :<br>
							<strong><?php esc_html_e( 'Phone', 'school-management' ); ?> :</strong><br>
							<Strong><?php esc_html_e( 'Email', 'school-management' ); ?> :</Strong><br>
							<strong><?php esc_html_e( 'Class', 'school-management' ); ?> :</strong><br>
							<strong><?php esc_html_e( 'Section', 'school-management' ); ?> :</strong><br>
							<strong><?php esc_html_e( 'Roll Number', 'school-management' ); ?> :</strong><br>
							<strong><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?> :</strong>
						</address>
					</div>
					<div class="invoice-to">
						<address class="m-t-5 m-b-5">
							<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $invoice->student_name ) ); ?><br>
							<?php echo esc_html( $invoice->enrollment_number ); ?><br>
							<?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $invoice->phone ) ); ?><br>
							<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $invoice->email ) ); ?><br>
							<?php echo esc_html( WLSM_M_Class::get_label_text( $invoice->class_label ) ); ?><br>
							<?php echo esc_html( WLSM_M_Class::get_label_text( $invoice->section_label ) ); ?><br>
							<?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $invoice->roll_number ) ); ?><br>
							<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $invoice->father_name ) ); ?>
						</address>
					</div>
					<div class="invoice-date">
						<div class="date text-inverse m-t-5"><?php echo esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $invoice->invoice_title ) ); ?></div>
						<div class="date text-inverse m-t-5">Date - <?php echo esc_html( WLSM_Config::get_date_text( $invoice->date_issued ) ); ?></div>

						<div class="invoice-detail">
							<?php esc_html_e( 'Due Date:', 'school-management' ); ?> -
							<?php echo esc_html( WLSM_Config::get_date_text( $invoice->due_date ) ); ?>
						</div>
						<div class="invoice-detail">
							<strong>
								<?php esc_html_e( 'Due Date Penalty:', 'school-management' ); ?> </strong>
							<?php echo esc_html( WLSM_Config::get_money_text( $invoice->due_date_amount, $school_id ) ); ?>
						</div>
					</div>
				</div>
				<!-- end invoice-header -->
				<!-- begin invoice-content -->
				<div class="invoice-content">
					<!-- begin table-responsive -->

					<?php
					$fee_list = unserialize( $invoice->fee_list );

					?>
					<?php if ( $fee_list ) : ?>
						<div class="table-responsive w-100">
							<table class="table table-bordered wlsm-view-fee-structure" style="background-color: white !important;">
								<thead>
									<tr>
										<th class="text-nowrap"><?php esc_html_e( 'Fee Type', 'school-management' ); ?></th>
										<!-- <th class="text-nowrap"><?php esc_html_e( 'Period', 'school-management' ); ?></th> -->
										<th class="text-nowrap"><?php esc_html_e( 'Amount', 'school-management' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $fee_list as $fee ) { ?>
										<tr>
											<td><?php echo esc_html( ( $fee['label'] ) ); ?></td>
											<td><?php echo esc_html( ( $fee['amount'] ) ); ?></td>
											<?php $sum += $fee['amount']; ?>
										</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td><strong><?php echo esc_html( 'Total' ); ?></strong></td>
										<td><strong><?php echo esc_html( $sum ); ?></strong></td>
									</tr>
								</tfoot>
							</table>
						</div>
					<?php endif ?>
					<div class="table-responsive">
						<table class="table table-invoice table-bordered" style="background-color: white !important;">
							<thead>
								<tr>
									<?php if ( ! empty( $invoice->invoice_amount_total ) ) : ?>
										<th class="text-center"><?php esc_html_e( 'Total', 'school-management' ); ?></th>
									<?php endif ?>

									<th class="text-center"><?php esc_html_e( 'Amount', 'school-management' ); ?></th>
									<th class="text-center"><?php esc_html_e( 'Discount', 'school-management' ); ?></th>
									<th class="text-center"><?php esc_html_e( 'Due', 'school-management' ); ?></th>
									<th class="text-center"><?php esc_html_e( 'After Due Date', 'school-management' ); ?></th>
									<th class="text-center"><?php esc_html_e( 'Status', 'school-management' ); ?></th>

								</tr>
							</thead>
							<tbody>

								<tr>
									<?php if ( ! empty( $invoice->invoice_amount_total ) ) : ?>
										<td class="text-center"><?php echo esc_html( WLSM_Config::get_money_text( $invoice->invoice_amount_total, $school_id ) ); ?></td>
									<?php endif ?>

									<td class="text-center"><?php echo esc_html( ( WLSM_Config::get_money_text( $invoice->payable, $school_id ) ) ); ?></td>
									<td class="text-center"><?php echo esc_html( ( $invoice->discount ) ); ?>%</td>
									<td class="text-center">
										<?php
										if ( $due > 0 ) {
											echo esc_html( WLSM_Config::get_money_text( $due, $school_id ) );
										} else {
											echo esc_html( 0 );
										}
										?>
									</td>
									<td class="text-center"><?php echo esc_html( WLSM_Config::get_money_text( ( $invoice->payable ) + ( $invoice->due_date_amount ), $school_id ) ); ?></td>
									<td class="text-center">
										<?php
										echo wp_kses(
											WLSM_M_Invoice::get_status_text( $invoice->status ),
											array( 'span' => array( 'class' => array() ) )
										);
										?>
									</td>
								</tr>

							</tbody>
						</table>
					</div>
					<!-- end table-responsive -->
					<!-- begin invoice-price -->
					<div class="invoice-price" style="background-color: white !important;">
						<div class="invoice-price-left">
							<div class="invoice-price-row">
								<div class="sub-price">
									<span class="text-inverse"><?php esc_html_e( 'TOTAL PAID:', 'school-management' ); ?></span>
								</div>
							</div>
						</div>
						<div class="invoice-price-right ">
							<span class="text-inverse"><strong><?php echo esc_html( WLSM_Config::get_money_text( $invoice->paid, $school_id ) ); ?></strong></span>
						</div>
					</div>
				</div>
			</div>

			<?php if ( $school_generate_invoices_history ) : ?>
				<?php if ( count( $payments ) ) { ?>
					<div class="row mt-2">
						<div class="col-12">
							<div class="wlsm-h5 wlsm-font-bold wlsm-invoice-sub-heading">
								<?php esc_html_e( 'Payment History', 'school-management' ); ?>
							</div>
							<div class="table-responsive w-100">
								<table class="table table-bordered" style="background-color: white !important;">
									<thead>
										<tr>
											<th class="text-nowrap"><?php esc_html_e( 'Receipt Number', 'school-management' ); ?></th>
											<th class="text-nowrap"><?php esc_html_e( 'Amount', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Payment Method', 'school-management' ); ?></th>
											<th><?php esc_html_e( 'Transaction ID', 'school-management' ); ?></th>
											<th class="text-nowrap"><?php esc_html_e( 'Date', 'school-management' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ( $payments as $row ) {
											?>
											<tr>
												<td class="text-nowrap"><?php echo esc_html( WLSM_M_Invoice::get_receipt_number_text( $row->receipt_number ) ); ?></td>
												<td class="text-nowrap"><?php echo esc_html( WLSM_Config::get_money_text( $row->amount, $school_id ) ); ?></td>
												<td><?php echo esc_html( WLSM_M_Invoice::get_payment_method_text( $row->payment_method ) ); ?></td>
												<td><?php echo esc_html( WLSM_M_Invoice::get_transaction_id_text( $row->transaction_id ) ); ?></td>
												<td class="text-nowrap"><?php echo esc_html( WLSM_Config::get_date_text( $row->created_at ) ); ?></td>
											</tr>
											<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php } ?>
			<?php endif ?>
		</div>

	</div>

	<!-- Duplicate invoice if enabled -->
	<?php $active_invoice = WLSM_Config::invoice_copies( $school_id ); ?>
	<?php if ( $active_invoice ) : ?>
		<!-- page break -->
		<div class="page-break"></div>

		<div class="wlsm-print-invoice-container">
			<?php require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/school_header.php'; ?>

			<div class="row">
				<div class="col-md-12">
					<div class="wlsm-h5 wlsm-invoice-heading text-center">
						<?php
						printf(
							wp_kses(
								/* translators: %s: invoice title */
								__( '<span class="wlsm-font-bold">Fee Invoice:</span> %s', 'school-management' ),
								array(
									'span' => array( 'class' => array() ),
								)
							),
							esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $invoice->invoice_title ) )
						);
						?>
						<small class="float-md-right">
							<?php
							printf(
								wp_kses(
									/* translators: %s: invoice number */
									__( '<span class="wlsm-font-bold">Invoice No.</span> %s', 'school-management' ),
									array( 'span' => array( 'class' => array() ) )
								),
								esc_html( $invoice->invoice_number )
							);
							?>
						</small>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="invoice border border-secondary">
					<!-- begin invoice-header -->
					<div class="invoice-header">
						<div class="invoice-from">
							<address class="m-t-5 m-b-5">
								<strong class="text-inverse"><?php esc_html_e( 'Student Name', 'school-management' ); ?> :</strong><br>
								<strong><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></strong> :<br>
								<strong><?php esc_html_e( 'Phone', 'school-management' ); ?> :</strong><br>
								<Strong><?php esc_html_e( 'Email', 'school-management' ); ?> :</Strong><br>
								<strong><?php esc_html_e( 'Class', 'school-management' ); ?> :</strong><br>
								<strong><?php esc_html_e( 'Section', 'school-management' ); ?> :</strong><br>
								<strong><?php esc_html_e( 'Roll Number', 'school-management' ); ?> :</strong><br>
								<strong><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?> :</strong>
							</address>
						</div>
						<div class="invoice-to">
							<address class="m-t-5 m-b-5">
								<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $invoice->student_name ) ); ?><br>
								<?php echo esc_html( $invoice->enrollment_number ); ?><br>
								<?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $invoice->phone ) ); ?><br>
								<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $invoice->email ) ); ?><br>
								<?php echo esc_html( WLSM_M_Class::get_label_text( $invoice->class_label ) ); ?><br>
								<?php echo esc_html( WLSM_M_Class::get_label_text( $invoice->section_label ) ); ?><br>
								<?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $invoice->roll_number ) ); ?><br>
								<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $invoice->father_name ) ); ?>
							</address>
						</div>
						<div class="invoice-date">
							<div class="date text-inverse m-t-5"><?php echo esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $invoice->invoice_title ) ); ?></div>
							<div class="date text-inverse m-t-5">Date - <?php echo esc_html( WLSM_Config::get_date_text( $invoice->date_issued ) ); ?></div>

							<div class="invoice-detail">
								<?php esc_html_e( 'Due Date:', 'school-management' ); ?> -
								<?php echo esc_html( WLSM_Config::get_date_text( $invoice->due_date ) ); ?>
							</div>
							<div class="invoice-detail">
								<strong>
									<?php esc_html_e( 'Due Date Penalty:', 'school-management' ); ?> </strong>
								<?php echo esc_html( WLSM_Config::get_money_text( $invoice->due_date_amount, $school_id ) ); ?>
							</div>
						</div>
					</div>
					<!-- end invoice-header -->
					<!-- begin invoice-content -->
					<div class="invoice-content">
						<!-- begin table-responsive -->

						<?php
						$fee_list = unserialize( $invoice->fee_list );

						?>
						<?php if ( $fee_list ) : ?>
							<div class="table-responsive w-100">
								<table class="table table-bordered wlsm-view-fee-structure">
									<thead>
										<tr>
											<th class="text-nowrap"><?php esc_html_e( 'Fee Type', 'school-management' ); ?></th>
											<!-- <th class="text-nowrap"><?php esc_html_e( 'Period', 'school-management' ); ?></th> -->
											<th class="text-nowrap"><?php esc_html_e( 'Amount', 'school-management' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $fee_list as $fee ) { ?>
											<tr>
												<td><?php echo esc_html( ( $fee['label'] ) ); ?></td>
												<td><?php echo esc_html( ( $fee['amount'] ) ); ?></td>
												<?php $sum2 += $fee['amount']; ?>
											</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td><strong><?php echo esc_html( 'Total' ); ?></strong></td>
											<td><strong><?php echo esc_html( $sum2 ); ?></strong></td>
										</tr>
									</tfoot>
								</table>
							</div>
						<?php endif ?>
						<div class="table-responsive">
							<table class="table table-invoice table-bordered">
								<thead>
									<tr>
										<?php if ( ! empty( $invoice->invoice_amount_total ) ) : ?>
											<th class="text-center"><?php esc_html_e( 'Total', 'school-management' ); ?></th>
										<?php endif ?>

										<th class="text-center"><?php esc_html_e( 'Amount', 'school-management' ); ?></th>
										<th class="text-center"><?php esc_html_e( 'Discount', 'school-management' ); ?></th>
										<th class="text-center"><?php esc_html_e( 'Due', 'school-management' ); ?></th>
										<th class="text-center"><?php esc_html_e( 'After Due Date', 'school-management' ); ?></th>
										<th class="text-center"><?php esc_html_e( 'Status', 'school-management' ); ?></th>

									</tr>
								</thead>
								<tbody>

									<tr>
										<?php if ( ! empty( $invoice->invoice_amount_total ) ) : ?>
											<td class="text-center"><?php echo esc_html( WLSM_Config::get_money_text( $invoice->invoice_amount_total, $school_id ) ); ?></td>
										<?php endif ?>

										<td class="text-center"><?php echo esc_html( WLSM_Config::get_money_text( $invoice->payable, $school_id ) ); ?></td>
										<td class="text-center"><?php echo esc_html( ( $invoice->discount ) ); ?>%</td>
										<td class="text-center">
											<?php
											if ( $due > 0 ) {
												echo esc_html( WLSM_Config::get_money_text( $due, $school_id ) );
											} else {
												echo esc_html( 0 );
											}
											?>
										</td>
										<td class="text-center"><?php echo esc_html( WLSM_Config::get_money_text( ( $invoice->payable ) + ( $invoice->due_date_amount ), $school_id ) ); ?></td>
										<td class="text-center">
											<?php
											echo wp_kses(
												WLSM_M_Invoice::get_status_text( $invoice->status ),
												array( 'span' => array( 'class' => array() ) )
											);
											?>
										</td>
									</tr>

								</tbody>
							</table>
						</div>
						<!-- end table-responsive -->
						<!-- begin invoice-price -->
						<div class="invoice-price">
							<div class="invoice-price-left">
								<div class="invoice-price-row">
									<div class="sub-price">
										<span class="text-inverse"><?php esc_html_e( 'TOTAL PAID:', 'school-management' ); ?></span>
									</div>
								</div>
							</div>
							<div class="invoice-price-right ">
								<span class="text-inverse"><strong><?php echo esc_html( WLSM_Config::get_money_text( $invoice->paid, $school_id ) ); ?></strong></span>
							</div>
						</div>
						<!-- end invoice-price -->
					</div>
					<!-- end invoice-note -->
					<!-- begin invoice-footer -->
					<!-- <div class="invoice-footer">
		 </div> -->
					<!-- end invoice-footer -->
				</div>
			</div>


		</div>
	<?php endif ?>

</div>
