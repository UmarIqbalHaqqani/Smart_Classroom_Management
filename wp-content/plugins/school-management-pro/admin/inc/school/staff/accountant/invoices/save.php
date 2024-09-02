<?php
defined('ABSPATH') || die();

global $wpdb;

$page_url = WLSM_M_Staff_Accountant::get_invoices_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$invoice = NULL;

$nonce_action = 'add-invoice';
$student                 = 0;
$student_id              = NULL;
$student_name            = '';
$admission_number        = '';
$phone                   = '';
$email                   = '';
$father_name             = '';
$father_phone            = '';
$class_label             = '';
$section_label           = '';
$invoice_title           = '';
$invoice_number          = '';
$invoice_description     = '';
$invoice_amount          = '';
$invoice_amount_total    = '';
$invoice_discount        = '';
$invoice_date_issued     = '';
$invoice_due_date        = '';
$invoice_partial_payment = 0;
$invoice_status          = '';
$due_date_amount          = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
	$id      = absint($_GET['id']);
	$invoice = WLSM_M_Staff_Accountant::fetch_invoice($school_id, $session_id, $id);
	if ($invoice) {
		$nonce_action = 'edit-invoice-' . $invoice->ID;

		$student_id              = $invoice->student_id;
		$student_name            = $invoice->student_name;
		$admission_number        = $invoice->admission_number;
		$phone                   = $invoice->phone;
		$email                   = $invoice->email;
		$father_name             = $invoice->father_name;
		$father_phone            = $invoice->father_phone;
		$class_label             = $invoice->class_label;
		$section_label           = $invoice->section_label;
		$invoice_title           = $invoice->invoice_title;
		$invoice_number          = $invoice->invoice_number;
		$invoice_description     = $invoice->invoice_description;
		$invoice_amount          = $invoice->amount;
		$invoice_amount_total    = $invoice->invoice_amount_total;
		$invoice_discount        = $invoice->discount;
		$invoice_date_issued     = $invoice->date_issued;
		$invoice_due_date        = $invoice->due_date;
		$invoice_partial_payment = $invoice->partial_payment;
		$invoice_status          = $invoice->status;
		$due_date_amount          = $invoice->due_date_amount;
	}
}


if (!$invoice) {
	$classes = WLSM_M_Staff_Class::fetch_classes($school_id);
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ($invoice) {
						printf(
							wp_kses(
								/* translators: 1: invoice title, 2: invoice number */
								__('Edit Invoice: %1$s (%2$s)', 'school-management'),
								array(
									'span' => array('class' => array())
								)
							),
							esc_html(WLSM_M_Staff_Accountant::get_invoice_title_text($invoice_title)),
							esc_html($invoice_number)
						);
					} else {
						esc_html_e('Add New Fee Invoice', 'school-management');
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url($page_url); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-file-invoice"></i>&nbsp;
					<?php esc_html_e('View All', 'school-management'); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-invoice-form">

			<?php $nonce = wp_create_nonce($nonce_action); ?>
			<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

			<input type="hidden" name="action" value="wlsm-save-invoice">

			<?php if ($invoice) { ?>
				<input type="hidden" name="invoice_id" value="<?php echo esc_attr($invoice->ID); ?>">
			<?php } ?>

			<?php if (!$invoice) { ?>
				<div class="wlsm-form-section">
					<!-- Single Invoice or Mass Invoice -->
					<div class="form-row mt-3">
						<div class="form-group col-md-12">
							<div class="form-check form-check-inline  btn btn-primary">
								<input checked class="form-check-input" type="radio" name="invoice_type" id="wlsm_single_invoice" value="single_invoice">
								<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_single_invoice">
									<?php esc_html_e('Create Single Invoice', 'school-management'); ?>
								</label>
							</div>
							<div class="form-check form-check-inline  btn btn-primary">
								<input class="form-check-input" type="radio" name="invoice_type" id="wlsm_bulk_invoice" value="bulk_invoice">
								<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_bulk_invoice">
									<?php esc_html_e('Create Bulk Invoice', 'school-management'); ?>
								</label>
							</div>
							<div class="form-check form-check-inline  btn btn-primary">

								<input class="form-check-input" type="radio" name="invoice_type" id="wlsm_single_invoice_fee_type" value="single_invoice_fee_type">

								<label class="ml-1 form-check-label wlsm-font-bold" for="wlsm_single_invoice_fee_type">
									<?php esc_html_e('Create Single Invoice Accroding Fee Type', 'school-management'); ?>
								</label>

							</div>
						</div>
					</div>

					<div class="form-row">
						<div class="form-group col-md-4">
							<label for="wlsm_class" class="wlsm-font-bold">
								<?php esc_html_e('Class', 'school-management'); ?>:
							</label>
							<select name="class_id" class="form-control selectpicker" data-nonce="<?php echo esc_attr(wp_create_nonce('get-class-sections')); ?>" id="wlsm_class" data-live-search="true">
								<option value=""><?php esc_html_e('Select Class', 'school-management'); ?></option>
								<?php foreach ($classes as $class) { ?>
									<option value="<?php echo esc_attr($class->ID); ?>">
										<?php echo esc_html(WLSM_M_Class::get_label_text($class->label)); ?>
									</option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group col-md-4">
							<label for="wlsm_section" class="wlsm-font-bold">
								<?php esc_html_e('Section', 'school-management'); ?>:
							</label>
							<select name="section_id" class="form-control selectpicker wlsm_section" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e('All Sections', 'school-management'); ?>" data-all-sections="1" data-fetch-students="1" data-nonce="<?php echo esc_attr(wp_create_nonce('get-section-students')); ?>">
							</select>
						</div>
						<div class="form-group col-md-4 wlsm-student-select-block">
							<label for="wlsm_student" class="wlsm-font-bold" data-bulk-label="<?php esc_attr_e('Students', 'school-management'); ?>" data-single-label="<?php esc_attr_e('Student', 'school-management'); ?>">
								<?php esc_html_e('Student', 'school-management'); ?>:
							</label>
							<select name="student" class="form-control selectpicker" id="wlsm_student" data-fee-structure="1" data-live-search="true" data-actions-box="true" data-none-selected-text="<?php esc_attr_e('Select', 'school-management'); ?>">
							</select>
							<div class="mt-2 wlsm-print-fee-structure-box">
								<button type="button" class="btn btn-sm btn-outline-primary wlsm-print-invoice-fee-structure" data-nonce="<?php echo esc_attr(wp_create_nonce('print-invoice-fee-structure')); ?>" data-only-one-student="<?php esc_attr_e('Please select only one student.', 'school-management'); ?>" data-message-title="<?php echo esc_attr__('Print Student Fee Structure', 'school-management'); ?>" data-close="<?php echo esc_attr__('Close', 'school-management'); ?>"><i class="fas fa-print"></i> <?php esc_html_e('Fee Structure', 'school-management'); ?></button>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>

			<?php
			require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Helper.php';
			$fees             = WLSM_M_Staff_Accountant::fetch_fees($school_id);
			$fee_periods      = WLSM_Helper::fee_period_list();
			$due_date_periods = WLSM_Helper::due_date_period();
			?>
			<!-- Fees Structure -->
			<div class="wlsm-form-section" id="fee-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold pb-0">
							<?php esc_html_e('Student Fee Structure', 'school-management'); ?>
						</div>
					</div>
				</div>

				<div id="fees-box_list" class="wlsm-fees-box" data-fees-type-list="<?php echo !empty($fees) ? esc_attr(json_encode((array)$fees)) : ''; ?>" data-fee-type="<?php esc_attr_e('Fee Type', 'school-management'); ?>" data-fee-type-placeholder="<?php esc_attr_e('Enter fee type', 'school-management'); ?>" data-fee-period="<?php esc_attr_e('Period', 'school-management'); ?>" data-fee-amount="<?php esc_attr_e('Amount', 'school-management'); ?>" data-fee-amount-placeholder="<?php esc_attr_e('Enter amount', 'school-management'); ?>" data-fee-periods="<?php echo esc_attr(json_encode($fee_periods)); ?>">

					<?php
					if (count($fees)) {
						foreach ($fees as $key => $fee) {
							$index = $key + 1;
					?>
							<div class="wlsm-fee-box card col" data-fee="<?php echo esc_attr($index); ?>">
								<button type="button" class="btn btn-sm btn-danger wlsm-remove-fee-btn"><i class="fas fa-times"></i></button>

								<input type="hidden" name="fee_id[]" value="<?php echo esc_attr($fee->ID); ?>">

								<div class="form-row">
									<div class="form-group col-md-4">
										<label for="wlsm_fee_label_<?php echo esc_attr($index); ?>" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e('Fee Type', 'school-management'); ?>:
										</label>
										<input type="text" name="fee_label[]" class="form-control" ID="wlsm_fee_label_<?php echo esc_attr($index); ?>" placeholder="<?php esc_attr_e('Enter fee type', 'school-management'); ?>" value="<?php echo esc_attr(stripcslashes($fee->label)); ?>">
									</div>
									<div class="form-group col-md-4">
										<label for="wlsm_fee_period_<?php echo esc_attr($index); ?>" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e('Period', 'school-management'); ?>:
										</label>
										<select name="fee_period[]" class="form-control selectpicker wlsm_fee_period_selectpicker" id="wlsm_fee_period_<?php echo esc_attr($index); ?>" data-live-search="true">
											<?php foreach ($fee_periods as $key => $value) { ?>
												<option value="<?php echo esc_attr($key); ?>" <?php selected($key, $fee->period, true); ?>>
													<?php echo esc_html($value); ?>
												</option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label for="wlsm_fee_amount_<?php echo esc_attr($index); ?>" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e('Amount', 'school-management'); ?>:
										</label>
										<input type="number" step="any" min="0" name="fee_amount[]" class="form-control" id="wlsm_fee_amount_<?php echo esc_attr($index); ?>" placeholder="<?php esc_attr_e('Enter amount', 'school-management'); ?>" value="<?php echo esc_attr($fee->amount ? WLSM_Config::sanitize_money($fee->amount) : ''); ?>">
									</div>
								</div>
							</div>
						<?php
						}
					} else {
						if (!$student) {
						?>
							<div class="wlsm-fee-box card col" data-fee="1">
								<button type="button" class="btn btn-sm btn-danger wlsm-remove-fee-btn"><i class="fas fa-times"></i></button>

								<div class="form-row">
									<div class="form-group col-md-4">
										<label for="wlsm_fee_label" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e('Fee Type', 'school-management'); ?>:
										</label>
										<input type="text" name="fee_label[]" class="form-control" placeholder="<?php esc_attr_e('Enter fee type', 'school-management'); ?>">
									</div>
									<div class="form-group col-md-4">
										<label for="wlsm_fee_period" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e('Period', 'school-management'); ?>:
										</label>
										<select name="fee_period[]" class="form-control selectpicker wlsm_fee_period_selectpicker" data-live-search="true">
											<?php foreach ($fee_periods as $key => $value) { ?>
												<option value="<?php echo esc_attr($key); ?>">
													<?php echo esc_html($value); ?>
												</option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-4">
										<label for="wlsm_fee_amount" class="wlsm-font-bold">
											<span class="wlsm-important">*</span> <?php esc_html_e('Amount', 'school-management'); ?>:
										</label>
										<input type="number" step="any" min="0" name="fee_amount[]" class="form-control" placeholder="<?php esc_attr_e('Enter amount', 'school-management'); ?>">
									</div>
								</div>
							</div>
					<?php
						}
					}
					?>
				</div>

				<div class="form-row mt-3">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-sm btn-outline-primary wlsm-add-fee-btn">
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php esc_html_e('Add Fee', 'school-management'); ?>
						</button>
					</div>
				</div>
			</div>

			<!-- Invoice Detail -->
			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-4">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e('Invoice Detail', 'school-management'); ?>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="wlsm_invoice_label" class="wlsm-font-bold">
								<?php esc_html_e('Invoice Title', 'school-management'); ?>:
							</label>
							<input type="text" name="invoice_label" class="form-control" id="wlsm_invoice_label" placeholder="<?php esc_attr_e('Enter invoice title', 'school-management'); ?>" value="<?php echo esc_attr($invoice_title); ?>">
						</div>
						<div class="form-group">
							<input <?php checked($invoice_partial_payment, true, true); ?> class="form-check-input mt-1" type="checkbox" name="partial_payment" id="wlsm_invoice_partial_payment" value="1">
							<label class="ml-4 mb-1 form-check-label wlsm-font-bold" for="wlsm_invoice_partial_payment">
								<?php esc_html_e('Allow Partial Payments?', 'school-management'); ?>
							</label>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_invoice_description" class="wlsm-font-bold">
							<?php esc_html_e('Description', 'school-management'); ?>:
						</label>
						<textarea name="invoice_description" class="form-control" id="wlsm_invoice_description" cols="30" rows="2" placeholder="<?php esc_attr_e('Enter description', 'school-management'); ?>"><?php echo esc_html(stripslashes($invoice_description)); ?></textarea>
					</div>
				</div>

				<div class="form-row">

					<div class="form-group col-md-3" id="wlsm_invoice_fee_type_amount">
						<label for="wlsm_invoice_fee_type_amount" class="wlsm-font-bold">
							<?php esc_html_e('Total Fee Type Amount', 'school-management'); ?>:
						</label>
						<input id="fee-amount" type="number" step="any" min="0" name="fee_type_amount" class="form-control" placeholder="<?php esc_attr_e('Enter amount', 'school-management'); ?>" value="" readonly>
					</div>
					<div class="form-group col-md-4" id="invoice_amount_total">
						<label for="wlsm_invoice_amount" class="wlsm-font-bold">
							<?php esc_html_e('Total Amount', 'school-management'); ?>:
						</label>
						<input type="number" step="any" min="0" name="invoice_amount_total" class="form-control" id="wlsm_invoice_amount" placeholder="<?php esc_attr_e('Enter invoice amount', 'school-management'); ?>" value="<?php echo esc_attr($invoice_amount_total ? WLSM_Config::sanitize_money($invoice_amount_total) : ''); ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_invoice_amount" class="wlsm-font-bold">
							<?php esc_html_e('Payable Amount', 'school-management'); ?>:
						</label>
						<input type="number" step="any" min="0" name="invoice_amount" class="form-control" id="wlsm_invoice_amount_2" placeholder="<?php esc_attr_e('Enter invoice amount', 'school-management'); ?>" value="<?php echo esc_attr($invoice_amount ? WLSM_Config::sanitize_money($invoice_amount) : ''); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_invoice_discount" class="wlsm-font-bold">
							<?php esc_html_e('Discount Percentage', 'school-management'); ?>:
						</label>
						<span id="fee_type_note" class="text-danger"><?php esc_html_e('Enter the percent of Amount for example : 5%', 'school-management'); ?></span>
						<input type="number" step="any" min="0" name="invoice_discount" class="form-control" id="wlsm_invoice_discount" placeholder="<?php esc_attr_e('Enter discount in percent', 'school-management'); ?>" value="<?php echo esc_attr($invoice_discount ? WLSM_Config::sanitize_money($invoice_discount) : ''); ?>">
					</div>
					
				</div>
				<div class="from-row">
				<div class="form-group col-md-3">
						<a id="get-invoices-total_amount" class="btn btn-success text-white mt-2"><?php esc_html_e('Re Calculate Total Fee Types ', 'school-management'); ?></a>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_invoice_date_issued" class="wlsm-font-bold">
							<?php esc_html_e('Date Issued', 'school-management'); ?>:
						</label>
						<input type="text" name="invoice_date_issued" class="form-control wlsm-date" id="wlsm_invoice_date_issued" placeholder="<?php esc_attr_e('Enter date issued', 'school-management'); ?>" value="<?php echo esc_attr(WLSM_Config::get_date_text($invoice_date_issued)); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_invoice_due_date" class="wlsm-font-bold">
							<?php esc_html_e('Due Date', 'school-management'); ?>:
						</label>
						<input type="text" name="invoice_due_date" class="form-control wlsm-date" id="wlsm_invoice_due_date" placeholder="<?php esc_attr_e('Enter due date', 'school-management'); ?>" value="<?php echo esc_attr(WLSM_Config::get_date_text($invoice_due_date)); ?>">
					</div>

					<div class="form-group col-md-4">
						<label for="wlsm_due_date_amount" class="wlsm-font-bold">
							<?php esc_html_e('Due Date Amount', 'school-management'); ?>: 
						</label><span class="text-muted">  <?php esc_html_e(' ( This will be added to amount )', 'school-management'); ?></span>
						<input type="text" name="due_date_amount" class="form-control wlsm-date" id="wlsm_due_date_amount" placeholder="<?php esc_attr_e('Enter due date Amount', 'school-management'); ?>" value="<?php echo esc_attr(($due_date_amount)); ?>">
					</div>
				</div>
			</div>

			<?php
			if ($invoice) {
			?>
				<div class="row mt-2 mb-2">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-invoice-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e('Update Fee Invoice', 'school-management'); ?>
						</button>
					</div>
				</div>
			<?php
			}
			?>

			<?php
			if ($invoice) {
				$show_collect_payment_link = true;
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/accountant/invoices/invoice_status.php';
			}
			?>

			<!-- Payment -->
			<?php
			if (!$invoice) {
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/accountant/invoices/new_payment.php';
			} else {
				require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/accountant/invoices/invoice_payment_history.php';
			}

			if (!$invoice) {
			?>
				<div class="row mt-2">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-invoice-btn">
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php esc_html_e('Add New Fee Invoice', 'school-management'); ?>
						</button>
					</div>
				</div>
			<?php
			}
			?>
		</form>
	</div>
</div>
