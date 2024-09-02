<?php
defined( 'ABSPATH' ) || die();

if ( count( $invoices ) ) {
?>
<!-- Student pending invoices. -->
<div class="wlsm-table-section">
	<div class="wlsm-table-caption wlsm-font-bold">
		<?php
		printf(
			wp_kses(
				/* translators: %s: number of pending invoices */
				_n( '%d Pending fee invoice found.', '%d Pending fee invoices found.', count( $invoices ), 'school-management' ),
				array( 'span' => array( 'class' => array() ) )
			),
			count( $invoices )
		);
		?>
	</div>

	<div class="table-responsive w-100 wlsm-w-100">
		<table class="table table-bordered wlsm-student-pending-invoices-table wlsm-w-100">
			<thead>
				<tr class="bg-primary text-white">
					<th><input type="checkbox" name="select_all" id="wlsm-select-all"></th>
					<th><?php esc_html_e( 'Invoice Number', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Invoice Title', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Payable', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Paid', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Due', 'school-management' ); ?></th>
					<th class="text-nowrap"><?php esc_html_e( 'Status', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Date Issued', 'school-management' ); ?></th>
					<th><?php esc_html_e( 'Due Date', 'school-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$total = 0;
				foreach ( $invoices as $row ) {
					$due = $row->payable - $row->paid;
					$total += $due;
				?>
				<tr>
					<td><input type="checkbox" name="invoice_ids[]" class="wlsm-select-single" value="<?php echo esc_attr($row->ID);?>"></td>
					<td>
						<?php echo esc_html( $row->invoice_number ); ?>
					</td>
					<td>
						<?php echo esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $row->invoice_title ) ); ?>
					</td>
					<td>
						<?php echo esc_html( WLSM_Config::get_money_text( $row->payable, $school_id  ) ); ?>
					</td>
					<td>
						<?php echo esc_html( WLSM_Config::get_money_text( $row->paid, $school_id  ) ); ?>
					</td>
					<td>
						<span class="wlsm-font-bold">
							<?php echo esc_html( WLSM_Config::get_money_text( $due,  $school_id  ) ); ?>
						</span>
					</td>
					<td class="text-nowrap">
						<?php
						echo wp_kses(
								WLSM_M_Invoice::get_status_text( $row->status ),
								array( 'span' => array( 'class' => array() ) )
							);
						if ( WLSM_M_Invoice::get_paid_key() !== $row->status ) {
							echo '<br><a href="#" class="wlsm-view-student-pending-invoice" data-invoice="' . $row->ID . '" data-nonce="' . esc_attr( wp_create_nonce( 'view-student-invoice-' . $row->ID ) ) . '">' . esc_html__( 'Pay Now', 'school-management' ) . '</a>';
						}
						?>
					</td>
					<td>
						<?php echo esc_html( WLSM_Config::get_date_text( $row->date_issued ) ); ?>
					</td>
					<td>
						<?php echo esc_html( WLSM_Config::get_date_text( $row->due_date ) ); ?>
					</td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
		 if ( WLSM_M_Invoice::get_paid_key() !== $row->status ) {
			echo '<a href="#" class="wlsm-view-student-pending-invoice-bulk" data-nonce="' . esc_attr( wp_create_nonce( 'view-student-invoices-bulk' ) ) . '">' . esc_html__( 'Pay Selected', 'school-management' ) . '</a>';
		}
		?>

	<div class="wlsm-student-pending-invoice">
	
	</div>
</div>

<?php
} else {
?>
<div class="wlsm-alert wlsm-alert-warning wlsm-font-bold">
	<span class="wlsm-icon wlsm-icon-red">&#33;</span>
	<?php esc_html_e( 'There is no pending fee.', 'school-management' ); ?>
</div>
<?php
}
