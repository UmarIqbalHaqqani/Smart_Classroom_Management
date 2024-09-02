<?php
defined( 'ABSPATH' ) || die();

$payment_amount = '';


$due_date_amount = 0;
$date_now        = new DateTime();
if ($invoice) {
	$due_date        = new DateTime(( $invoice->due_date ) );
}


if ( $invoice ) {
	if ( $date_now >= $due_date ) {
		$due_date_amount = $invoice->due_date_amount;
	}
}

$partial_payment_not_allowed = $invoice && ! $invoice_partial_payment;
if ( $partial_payment_not_allowed ) {
	$payment_amount = $due;
}
$collect_payment_methods = WLSM_M_Invoice::collect_payment_methods();
?>

<!-- Collect Payment -->
<div class="wlsm-form-section wlsm-invoice-payments" id="wlsm-collect-payment">
	<?php if ( ! $invoice ) { ?>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<input class="form-check-input mt-1" type="checkbox" name="collect_invoice_payment" id="wlsm_collect_invoice_payment" value="1">
				<label class="ml-4 mb-1 form-check-label wlsm-font-bold text-dark" for="wlsm_collect_invoice_payment">
					<?php esc_html_e( 'Collect Payment?', 'school-management' ); ?>
				</label>
			</div>
			<hr>
		</div>
	</div>
	<div class="wlsm-collect-invoice-payment">
	<?php } ?>
		<div class="row">
			<div class="col-md-12">
				<div class="wlsm-form-sub-heading wlsm-font-bold">
					<?php esc_html_e( 'Add New Payment', 'school-management' ); ?>
				</div>
			</div>
		</div>
		<div class="form-row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="wlsm_payment_amount" class="wlsm-font-bold">
						<?php esc_html_e( 'Amount', 'school-management' ); ?>:
					</label>
					<input 
					<?php
					if ( $partial_payment_not_allowed ) {
						echo 'readonly'; }
					?>
					 type="number" step="any" min="0" name="payment_amount" class="form-control" id="wlsm_payment_amount" placeholder="<?php esc_attr_e( 'Enter amount', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::sanitize_money( intval($payment_amount) + intval( $due_date_amount ) ) ); ?>">
				</div>
				<div class="form-group">
					<label for="wlsm_payment_method" class="wlsm-font-bold">
						<?php esc_html_e( 'Payment Method', 'school-management' ); ?>:
					</label>
					<select name="payment_method" class="form-control selectpicker" id="wlsm_payment_method">
						<?php foreach ( $collect_payment_methods as $key => $value ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>">
							<?php echo esc_html( $value ); ?>
						</option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label for="wlsm_payment_date" class="wlsm-font-bold">
						<?php esc_html_e( 'Payment Date', 'school-management' ); ?>:
					</label>
					<input type="text" name="payment_date" class="form-control" id="wlsm_payment_date" placeholder="<?php esc_attr_e( 'Enter payment date', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_Config::get_date_text( current_time( 'Y-m-d H:i:s' ) ) ); ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="wlsm_transaction_id" class="wlsm-font-bold">
						<?php esc_html_e( 'Transaction ID', 'school-management' ); ?>:
					</label>
					<input type="text" name="transaction_id" class="form-control" id="wlsm_transaction_id" placeholder="<?php esc_attr_e( 'Enter transaction ID', 'school-management' ); ?>">
				</div>
				<div class="form-group">
					<label for="wlsm_payment_note" class="wlsm-font-bold">
						<?php esc_html_e( 'Additional Note', 'school-management' ); ?>:
					</label>
					<textarea name="payment_note" class="form-control" id="wlsm_payment_note" cols="30" rows="3" placeholder="<?php esc_attr_e( 'Enter additional note', 'school-management' ); ?>"></textarea>
				</div>
			</div>
		</div>
	<?php if ( ! $invoice ) { ?>
	</div>
	<?php } ?>
</div>
