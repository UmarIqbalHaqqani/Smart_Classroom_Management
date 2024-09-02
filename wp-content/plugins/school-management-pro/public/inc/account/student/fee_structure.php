<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Accountant.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

// Checks if student exists.
$student_id = $student->ID;
$student = WLSM_M_Staff_General::fetch_student($school_id, $session_id, $student_id);

if (!$student) {
    throw new Exception(esc_html__('Student not found.', 'school-management'));
}

$fee_structure = WLSM_M_Staff_Accountant::fetch_student_assigned_fees($school_id, $student_id);


$fees     = WLSM_M_Staff_Accountant::fetch_student_fees($school_id, $student_id);
$invoices = WLSM_M_Staff_Accountant::get_student_invoices($student_id);
$payments = WLSM_M_Staff_Accountant::get_student_payments($student_id);

$class_label = WLSM_M_Class::get_label_text( $student->class_label );
$start_date = $student->start_date;
$end_date = $student->end_date;

// get start_date and end_date difference in months
$start_date = new DateTime($start_date);
$end_date = new DateTime($end_date);
$interval = $start_date->diff($end_date);
$months_in_session = $interval->format('%m');
?>


<div class="wlsm-content-area wlsm-section-fee-invoices wlsm-student-fee-invoices">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Fee Structure', 'school-management' ); ?>
		</span>
	</div>

    <!-- Print fee structure section. -->
        <div class="wlsm-container wlsm wlsm-form-section" id="wlsm-print-fee-structure">
            <div class="wlsm-print-fee-structure-container">
                <div class="table-responsive w-100">
                    <table class="table table-bordered wlsm-view-fee-structure">
                        <thead>
                            <tr>
                                <th class="text-nowrap"><?php esc_html_e( 'Fee Type', 'school-management' ); ?></th>
                                <th class="text-nowrap"><?php esc_html_e( 'Amount', 'school-management' ); ?></th>
                                <th class="text-nowrap"><?php esc_html_e( 'Period', 'school-management' ); ?></th>
                                <th class="text-nowrap"><?php esc_html_e( 'Session Total', 'school-management' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $session_onetime_total     = 0;
                            $session_quarterly_total   = 0;
                            $session_half_yearly_total = 0;
                            $session_monthly_total     = 0;
                            $session_yearly_total      = 0;
                            foreach ( $fees as $key => $fee ) {
                            ?>
                            <tr>
                                <td><?php echo esc_html( WLSM_M_Staff_Accountant::get_label_text( $fee->label ) ); ?></td>
                                <td><?php echo esc_html( WLSM_Config::get_money_text( $fee->amount, $school_id ) ); ?></td>
                                <td><?php echo esc_html( WLSM_M_Staff_Accountant::get_fee_period_text( $fee->period ) ); ?></td>
                                <td>
                                    <?php
                                    if ($fee->period == "monthly") {
                                        $session_monthly_total += intval($fee->amount)*$months_in_session;
                                        echo esc_html( WLSM_Config::get_money_text( intval($fee->amount)* $months_in_session, $school_id )  ). esc_html( ' Monthly', 'school-management' );
                                    } elseif ($fee->period == 'one-time'){
                                        $session_onetime_total += intval($fee->amount);
                                        echo esc_html( WLSM_Config::get_money_text( $fee->amount, $school_id )  ) ;
                                    } elseif ($fee->period == 'quarterly'){
                                    $session_quarterly_total += intval($fee->amount * $months_in_session / 3);
                                        echo esc_html( WLSM_Config::get_money_text( ($fee->amount)* ($months_in_session/3), $school_id )  ) . esc_html( ' Quarterly', 'school-management' );
                                    } elseif ($fee->period == 'half-yearly'){
                                        $session_half_yearly_total += intval($fee->amount * $months_in_session / 6);
                                        echo esc_html( WLSM_Config::get_money_text( ($fee->amount)* ($months_in_session/6), $school_id )  ). esc_html( ' Half Yearly', 'school-management' );
                                     } elseif ($fee->period == 'annually'){
                                        $session_yearly_total += intval($fee->amount * $months_in_session);
                                        echo esc_html( WLSM_Config::get_money_text( ($session_yearly_total), $school_id )  ). esc_html( ' Yearly', 'school-management' );
                                     }
                                    ?>
                                </td>
                            </tr>

                            <?php
                            }
                            ?>
                             <tr>
                                <td></td>
                                <td></td>
                                <td><strong><?php esc_html_e( 'Fee Type Total:', 'school-management' ); ?></strong></td>
                                <td> <strong> <?php
                                echo esc_html( WLSM_Config::get_money_text( $session_monthly_total + $session_onetime_total+ $session_quarterly_total + $session_half_yearly_total+$session_yearly_total, $school_id ) ); ?> </strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

	</div>
</div>
