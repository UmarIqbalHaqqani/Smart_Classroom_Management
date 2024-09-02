<?php
defined( 'ABSPATH' ) || die();
?>
<div class="row mt-1 wlsm-stats-blocks">
	<?php if ( WLSM_M_Role::check_permission( array( 'manage_classes' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-layer-group wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_classes_count ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Total Classes', 'school-management' ); ?></div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-layer-group wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_sections_count ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Total Sections', 'school-management' ); ?></div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'manage_students' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-users wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_students_count ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Total Students <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-users wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $active_students_count ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Students Active <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-users wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $inactive_students_count ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Inactive Students <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-users wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $promoted_students_count ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Promoted Students <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'manage_inquiries' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-envelope wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_inquiries_count ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Total Inquiries', 'school-management' ); ?></div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-envelope wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $active_inquiries_count ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Inquiries Active', 'school-management' ); ?></div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'manage_transfer_student' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-users wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $students_transferred_to_count ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Transferred to Other School <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-users wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $students_transferred_from_count ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Transferred to this School <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'manage_invoices' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-file-invoice wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_invoices_count ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Total Invoices <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-file-invoice wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $invoices_paid_count ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Paid Invoices <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-file-invoice wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $invoices_unpaid_count ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Unpaid Invoices <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-file-invoice wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $invoices_partially_paid_count ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Partially Paid Invoices <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'stats_payments' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-file-invoice wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_payments_count ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Total Payments <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-dollar-sign wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( WLSM_Config::get_money_text( $total_payment_received,  $school_id ) ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Payment Received <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'stats_amount_fees_structure' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-dollar-sign wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( WLSM_Config::get_money_text( $invoices_pending_amount,  $school_id  ) ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Amount Pending<br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'stats_expense' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-dollar-sign wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( WLSM_Config::get_money_text( $total_expenses_sum, $school_id  ) ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Expense <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'stats_income' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-dollar-sign wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( WLSM_Config::get_money_text( $total_income_sum, $school_id  ) ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Income <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'manage_exams' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-calendar-alt wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_exams_with_published_timetables ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Exams with Published Timetables', 'school-management' ); ?></div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-id-card wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_exams_with_published_admit_cards ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Exams with Published Admit Cards', 'school-management' ); ?></div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-table wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_exams_with_published_results ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Exams with Published Results', 'school-management' ); ?></div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'manage_admins' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-user-shield wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_admins_count ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Total Admins', 'school-management' ); ?></div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'manage_roles' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-user-tag wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_roles_count ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Total Roles', 'school-management' ); ?></div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'manage_employees' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-user-shield wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_staff_count ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Total Staff', 'school-management' ); ?></div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-user-shield wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $active_staff_count ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Staff Active', 'school-management' ); ?></div>
		</div>
	</div>
	<?php } ?>

	<?php if ( WLSM_M_Role::check_permission( array( 'manage_library' ), $current_school['permissions'] ) ) { ?>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-book wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_books ); ?></div>
			<div class="wlsm-stats-label"><?php esc_html_e( 'Total Books', 'school-management' ); ?></div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-id-card wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_library_cards ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Total Library Cards <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-book wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_books_issued ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Total Books Issued <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>

	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-book wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_books_return_pending ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Total Books Return Pending <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-book wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_girls ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Total Girls <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-lg-3">
		<div class="wlsm-stats-block">
			<i class="fas fa-book wlsm-stats-icon"></i>
			<div class="wlsm-stats-counter"><?php echo esc_html( $total_boys ); ?></div>
			<div class="wlsm-stats-label">
				<?php
				printf(
					wp_kses(
						/* translators: %s: session label */
						__( 'Total Boys <br><small class="text-secondary"> - Session: %s</small>', 'school-management' ),
						array( 'small' => array( 'class' => array() ), 'br' => array() )
					),
					esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
				);
				?>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
