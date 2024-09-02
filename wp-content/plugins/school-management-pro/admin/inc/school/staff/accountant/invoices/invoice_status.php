<?php
defined( 'ABSPATH' ) || die();

$due = $invoice->payable - $invoice->paid;
?>

<!-- Student Detail -->
<div class="wlsm-form-section">
	<div class="row">
		<div class="col-md-4">
			<div class="wlsm-form-sub-heading wlsm-font-bold">
				<?php esc_html_e( 'Student Detail', 'school-management' ); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<ul class="list-group list-group-flush">
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e( 'Student Name', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $student_name ) ); ?></span>
				</li>
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e( 'Admission Number', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Staff_Class::get_admission_no_text( $admission_number ) ); ?></span>
				</li>
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e( 'Class', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Class::get_label_text( $class_label ) ); ?></span>
				</li>
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e( 'Section', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $section_label ) ); ?></span>
				</li>					
			</ul>
		</div>
		<div class="col-md-6">
			<ul class="list-group list-group-flush">
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e( 'Phone', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $phone ) ); ?></span>
				</li>
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e( 'Email', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $email ) ); ?></span>
				</li>
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e( 'Father\'s Name', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $father_name ) ); ?></span>
				</li>
				<li class="list-group-item">
					<span class="wlsm-font-bold"><?php esc_html_e( 'Father\'s Phone', 'school-management' ); ?>:</span>
					<span><?php echo esc_html( WLSM_M_Staff_Class::get_phone_text( $father_phone ) ); ?></span>
				</li>
			</ul>
		</div>
	</div>
</div>

<!-- Invoice Status -->
<div class="wlsm-fee-invoice-status-box">
	<div class="wlsm-form-section wlsm-fee-invoice-status" id="wlsm-fee-invoice-status">
		<div class="row">
			<div class="col-md-4">
				<div class="wlsm-form-sub-heading wlsm-font-bold">
					<?php esc_html_e( 'Invoice Status', 'school-management' ); ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<ul class="list-group list-group-flush">
					<li class="list-group-item">
						<span class="wlsm-font-bold"><?php esc_html_e( 'Payable', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_Config::get_money_text( $invoice->payable, $school_id ) ); 
						?></span>
					</li>
					<li class="list-group-item">
						<span class="wlsm-font-bold"><?php esc_html_e( 'Paid', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_Config::get_money_text( $invoice->paid, $school_id ) ); ?></span>
					</li>
				</ul>
			</div>
			<div class="col-md-6">
				<ul class="list-group list-group-flush">
					<li class="list-group-item">
						<span class="wlsm-font-bold"><?php esc_html_e( 'Due', 'school-management' ); ?>:</span>
						<span class="wlsm-font-bold"><?php 
						if($due > 0 ){
							echo esc_html( WLSM_Config::get_money_text( $due, $school_id) ); 
						}
						?></span>
					</li>
					<li class="list-group-item">
						<span class="wlsm-font-bold"><?php esc_html_e( 'Status', 'school-management' ); ?>:</span>
						<span>
							<?php
							echo wp_kses(
									WLSM_M_Invoice::get_status_text( $invoice->status ),
									array( 'span' => array( 'class' => array() ) )
								);
							if ( WLSM_M_Invoice::get_paid_key() !== $invoice->status ) {
							?>
							<?php if ( isset( $show_collect_payment_link ) ) { ?>
							<div class="mt-1">
								<a href="<?php echo esc_url( $page_url . '&action=collect_payment&id=' . $invoice->ID . '#wlsm-fee-invoice-status' ); ?>" class="btn btn-sm btn-success">
									<?php esc_html_e( 'Collect Payment', 'school-management' ); ?>
								</a>
							</div>
							<?php } ?>
							<?php
							}
							?>
						</span>
					</li>
				</ul>
			</div>
		</div>
		<?php 
		if ($invoice->fee_list) {
			$fee_list = unserialize($invoice->fee_list);
		} else {
			$fee_list = array();
		}
		
		?>
		<?php if ($fee_list): ?>
		<div class="table-responsive w-100">
			<table class="table table-bordered wlsm-view-fee-structure">
				<thead>
					<tr>
						<th class="text-nowrap"><?php esc_html_e( 'Fee Type', 'school-management' ); ?></th>
						<th class="text-nowrap"><?php esc_html_e( 'Period', 'school-management' ); ?></th>
						<th class="text-nowrap"><?php esc_html_e( 'Amount', 'school-management' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $fee_list as $fee ) { ?>
					<tr>
						<td><?php echo esc_html( ( $fee['label'] ) ); ?></td>
						<td><?php echo esc_html( ( $fee['period'] ) ); ?></td>
						<td><?php echo esc_html( ( $fee['amount']) ); ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php endif ?>
	</div>
</div>
