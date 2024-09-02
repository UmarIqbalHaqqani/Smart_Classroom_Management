<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

if (isset($from_front)) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}
$sum = 0;
$due = $invoice->payable - $invoice->paid;

$school           = WLSM_M_School::fetch_school($school_id);
$settings_general = WLSM_M_Setting::get_settings_general($school_id);
$school_logo      = $settings_general['school_logo'];
$school_signature = $settings_general['school_signature'];
?>
<style>
	body {
		font-family: sans-serif;
		font-size: 10pt;
	}

	p {
		margin: 0pt;
	}

	table.items {
		border: 0.1mm solid #000000;
	}

	td {
		vertical-align: top;
	}

	.items td {
		border-left: 0.1mm solid #000000;
		border-right: 0.1mm solid #000000;
	}

	table thead td {
		background-color: #EEEEEE;
		text-align: center;
		border: 0.1mm solid #000000;
		font-variant: small-caps;
	}

	.items td.blanktotal {
		background-color: #EEEEEE;
		border: 0.1mm solid #000000;
		background-color: #FFFFFF;
		border: 0mm none #000000;
		border-top: 0.1mm solid #000000;
		border-right: 0.1mm solid #000000;
	}

	.items td.totals {
		text-align: right;
		border: 0.1mm solid #000000;
	}

	.items td.cost {
		text-align: "."center;
	}
</style>

<div style="font-size: 9pt; text-align: center; padding-top: 3mm; ">
	<?php echo WLSM_M_Staff_Accountant::get_invoice_title_text($invoice->invoice_title); ?>

</div>
<div style="text-align: right"> <?php esc_html_e('Invoice Number:', 'school-management'); ?><?php echo esc_html($invoice->invoice_number); ?></div>

<table width="100%" style="font-family: serif;" cellpadding="10">
	<tr>
		<td width="45%" style="border: 0.1mm solid #888888; "><span style="font-size: 7pt; color: #555555; font-family: sans;"></span><br />
			<br />
			<?php esc_html_e('Student Name: ', 'school-management'); ?><?php echo esc_html(WLSM_M_Staff_Class::get_name_text($invoice->student_name)); ?><br>
			<?php esc_html_e('Enrollment Number: ', 'school-management'); ?><?php echo esc_html($invoice->enrollment_number); ?><br>
			<?php esc_html_e('Phone: ', 'school-management'); ?><?php echo esc_html(WLSM_M_Staff_Class::get_phone_text($invoice->phone)); ?><br>
			<?php esc_html_e('Email: ', 'school-management'); ?><?php echo esc_html(WLSM_M_Staff_Class::get_name_text($invoice->email)); ?><br>
			<?php esc_html_e('Class: ', 'school-management'); ?><?php echo esc_html(WLSM_M_Class::get_label_text($invoice->class_label)); ?><br>
			<?php esc_html_e('Section:', 'school-management'); ?><?php echo esc_html(WLSM_M_Class::get_label_text($invoice->section_label)); ?><br>
			<?php esc_html_e('Roll Number:', 'school-management'); ?><?php echo esc_html(WLSM_M_Staff_Class::get_roll_no_text($invoice->roll_number)); ?><br>
			<?php esc_html_e('Parent Name:', 'school-management'); ?><?php echo esc_html(WLSM_M_Staff_Class::get_name_text($invoice->father_name)); ?><br>

		</td>
		<td width="10%">&nbsp;</td>
		<td width="45%" style="border: 0.1mm solid #888888;"><span style="font-size: 7pt; color: #555555; font-family: sans;"></span><br />
			<br> <?php esc_html_e('School Name: ', 'school-management'); ?><?php echo esc_html(WLSM_M_School::get_label_text($school->label)); ?>
			<br> <?php esc_html_e('Phone: ', 'school-management'); ?><?php echo esc_html(WLSM_M_School::get_label_text($school->phone)); ?>
			<br><?php esc_html_e('Email: ', 'school-management'); ?><?php echo esc_html(WLSM_M_School::get_phone_text($school->email)); ?>
			<br> <?php esc_html_e('Address: ', 'school-management'); ?><?php echo esc_html(WLSM_M_School::get_email_text($school->address)); ?>

			<?php esc_html_e('Date: ', 'school-management'); ?><span><?php echo esc_html(WLSM_Config::get_date_text($invoice->date_issued)); ?></span><br>
			<?php esc_html_e('Due Date: ', 'school-management'); ?><?php echo esc_html(WLSM_Config::get_date_text($invoice->due_date)); ?><br>
			<?php esc_html_e('Due Date Penalty: ', 'school-management'); ?><?php echo esc_html(WLSM_Config::get_money_text($invoice->due_date_amount, $school_id)); ?><br>
		</td>
	</tr>
</table>
<br />

<?php $fee_list = unserialize($invoice->fee_list); ?>
<?php if ($fee_list) : ?>

	<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
		<thead>
			<tr>
				<td><?php esc_html_e('Fee Type', 'school-management'); ?></td>
				<td><?php esc_html_e('Amount', 'school-management'); ?></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($fee_list as $fee) { ?>
				<tr>
					<td><?php echo esc_html(($fee['label'])); ?></td>
					<td><?php echo esc_html(($fee['amount'])); ?></td>
					<?php $sum +=  $fee['amount']; ?>
				</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<td><strong><?php echo esc_html('Total'); ?></strong></td>
				<td><strong><?php echo esc_html($sum); ?></strong></td>
			</tr>
		</tfoot>
	</table>
<?php endif ?>

<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
	<thead>
		<tr>
			<?php if (!empty($invoice->invoice_amount_total)) : ?>
				<td class="text-center"><?php esc_html_e('Total', 'school-management'); ?></td>
			<?php endif ?>

			<td class="text-center"><?php esc_html_e('Amount', 'school-management'); ?></td>
			<td class="text-center"><?php esc_html_e('Discount', 'school-management'); ?></td>
			<td class="text-center"><?php esc_html_e('Due', 'school-management'); ?></td>
			<td class="text-center"><?php esc_html_e('After Due Date', 'school-management'); ?></td>
			<td class="text-center"><?php esc_html_e('Status', 'school-management'); ?></td>

		</tr>
	</thead>
	<tbody>
		<!-- ITEMS HERE -->


		<tr>
			<?php if (!empty($invoice->invoice_amount_total)) : ?>
				<td class="text-center"><?php echo esc_html(WLSM_Config::get_money_text($invoice->invoice_amount_total, $school_id)); ?></td>
			<?php endif ?>

			<td class="text-center"><?php echo esc_html(WLSM_Config::get_money_text($invoice->payable, $school_id)); ?></td>
			<td class="text-center"><?php echo esc_html(($invoice->discount)); ?>%</td>
			<td class="text-center"><?php
									if ($due > 0) {
										echo esc_html(WLSM_Config::get_money_text($due, $school_id));
									} else {
										echo esc_html(0);
									}
									?></td>
			<td class="text-center"><?php echo esc_html(WLSM_Config::get_money_text(($invoice->payable) + ($invoice->due_date_amount), $school_id)); ?></td>
			<td class="text-center">
				<?php
				echo wp_kses(
					WLSM_M_Invoice::get_status_text($invoice->status),
					array('span' => array('class' => array()))
				);
				?>
			</td>
		</tr>
		<!-- END ITEMS HERE -->
		<tr>
			<td class="blanktotal" colspan="4" rowspan="6"></td>
			<td class="totals"><?php esc_html_e('TOTAL PAID:', 'school-management'); ?></td>
			<td class="totals cost"><strong><?php echo esc_html(WLSM_Config::get_money_text($invoice->paid, $school_id)); ?></strong></td>
		</tr>

	</tbody>
</table>
</body>
