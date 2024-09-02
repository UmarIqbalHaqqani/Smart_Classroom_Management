<span class="wlsm-font-bold"> <strong><?php esc_html_e( 'Current Student Details', 'school-management' ); ?></strong></span><br>

<div class="row wlsm-student-details">

			<div class="col-md-12">
				<ul class="wlsm-list-group">
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Student Name', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $invoice_history[0]->student_name ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $invoice_history[0]->enrollment_number ) ); ?></span>
					</li>
					
					<li>
						<span class="wlsm-font-bold"><?php esc_html_e( 'Session', 'school-management' ); ?>:</span>
						<span><?php echo esc_html( WLSM_M_Session::get_label_text( $invoice_history[0]->session_label ) ); ?></span>
					</li>
					<li>
						<span class="wlsm-pr-3 pr-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Class', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Class::get_label_text( $invoice_history[0]->class_label ) ); ?></span>
						</span>
						<span class="wlsm-pl-3 pl-3">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Section', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( WLSM_M_Class::get_label_text( $invoice_history[0]->section_label ) ); ?></span>
						</span>
					</li>
				</ul>
			</div>
		</div>

<div class="table-responsive w-100 wlsm-w-100">
	<table class="table table-bordered wlsm-student-invoices-table wlsm-w-100">
		<thead>
			<tr class="bg-primary text-white">
				<th><?php esc_html_e( 'Invoice Number', 'school-management' ); ?></th>
				<th><?php esc_html_e( 'Invoice Title', 'school-management' ); ?></th>
				<th><?php esc_html_e( 'Payable', 'school-management' ); ?></th>
				<th><?php esc_html_e( 'Due', 'school-management' ); ?></th>
				<th class="text-nowrap"><?php esc_html_e( 'Status', 'school-management' ); ?></th>
				<th><?php esc_html_e( 'Date Issued', 'school-management' ); ?></th>
				<th><?php esc_html_e( 'Due Date', 'school-management' ); ?></th>
				<th><?php esc_html_e( 'Session', 'school-management' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $invoice_history as $row ) {
				$due = $row->payable - $row->paid;
				?>
				<tr>
					<td>
						<?php echo esc_html( $row->invoice_number ); ?>
					</td>
					<td>
						<?php echo esc_html( WLSM_M_Staff_Accountant::get_invoice_title_text( $row->invoice_title ) ); ?>
					</td>
					<td>
						<?php echo esc_html( WLSM_Config::get_money_text( $row->payable, $school_id ) ); ?>
					</td>
					
					<td>
						<span class="wlsm-font-bold">
							<?php echo esc_html( WLSM_Config::get_money_text( $due, $school_id ) ); ?>
						</span>
					</td>
					<td class="text-nowrap">
						<?php
						echo wp_kses(
							WLSM_M_Invoice::get_status_text( $row->status ),
							array( 'span' => array( 'class' => array() ) )
						);
						?>
					</td>
					<td>
						<?php echo esc_html( WLSM_Config::get_date_text( $row->date_issued ) ); ?>
					</td>
					<td>
						<?php echo esc_html( WLSM_Config::get_date_text( $row->due_date ) ); ?>
					</td>
					<td>
						<?php echo esc_html( $row->session_label ) ; ?>
					</td>
					
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>
