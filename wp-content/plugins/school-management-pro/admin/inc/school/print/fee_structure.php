<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

if ( isset( $from_front ) ) {
	$print_button_classes = 'button btn-sm btn-success';
} else {
	$print_button_classes = 'btn btn-sm btn-success';
}

$class_label = WLSM_M_Class::get_label_text( $student->class_label );
?>

<!-- Print fee structure. -->
<div class="wlsm-container wlsm d-flex mt-2 mb-2">
	<div class="col-md-12 wlsm-text-center">
		<?php
		printf(
			wp_kses(
				/* translators: %s: class label */
				__( '<span class="wlsm-font-bold">Student Fee Structure</span><br><span class="wlsm-font-bold">Class:</span> %s</span>', 'school-management' ),
				array( 'span' => array( 'class' => array() ), 'br' => array() )
			),
			esc_html( $class_label )
		);
		?>
		<br>
		<button type="button" class="<?php echo esc_attr( $print_button_classes ); ?> mt-2" id="wlsm-print-fee-structure-btn" data-styles='["<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/bootstrap.min.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/wlsm-school-header.css' ); ?>","<?php echo esc_url( WLSM_PLUGIN_URL . 'assets/css/print/wlsm-fee-structure.css' ); ?>"]' data-title="<?php esc_attr_e( 'Fee Structure', 'school-management' ); ?>"><?php esc_html_e( 'Print Fee Structure', 'school-management' ); ?>
		</button>
	</div>
</div>

<!-- Print fee structure section. -->
<div class="wlsm-container wlsm wlsm-form-section" id="wlsm-print-fee-structure">
	<div class="wlsm-print-fee-structure-container">

		<?php require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/print/partials/school_header.php'; ?>

		<ul>
			<li>
				<span class="wlsm-font-bold"><?php esc_html_e( 'Student Name', 'school-management' ); ?>:</span>
				<span><?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $student->student_name ) ); ?></span>
			</li>
			<li>
				<span class="wlsm-font-bold"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?>:</span>
				<span><?php echo esc_html( $student->enrollment_number ); ?></span>
			</li>
			<li>
				<span class="wlsm-font-bold"><?php esc_html_e( 'Session', 'school-management' ); ?>:</span>
				<span><?php echo esc_html( WLSM_M_Session::get_label_text( $student->session_label ) ); ?></span>
			</li>
		</ul>

		<span class="wlsm-font-bold"><?php esc_html_e( 'Fee Structure', 'school-management' ); ?></span>
		<span class="wlsm-float-md-right float-md-right">
		<?php
		/* translators: %s: class label */
		printf(
			wp_kses(
				__( '<span class="wlsm-font-bold">Class:</span> %s</span>', 'school-management' ),
				array( 'span' => array( 'class' => array() ) )
			),
			esc_html( $class_label )
		);
		?>
		</span>

		<div class="table-responsive w-100">
			<table class="table table-bordered wlsm-view-fee-structure">
				<thead>
					<tr>
						<th class="text-nowrap"><?php esc_html_e( 'Fee Type', 'school-management' ); ?></th>
						<th class="text-nowrap"><?php esc_html_e( 'Amount', 'school-management' ); ?></th>
						<th class="text-nowrap"><?php esc_html_e( 'Period', 'school-management' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $fees as $key => $fee ) {
					?>
					<tr>
						<td><?php echo esc_html( WLSM_M_Staff_Accountant::get_label_text( $fee->label ) ); ?></td>
						<td><?php echo esc_html( WLSM_Config::get_money_text( $fee->amount, $school_id ) ); ?></td>
						<td><?php echo esc_html( WLSM_M_Staff_Accountant::get_fee_period_text( $fee->period ) ); ?></td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>

	</div>
</div>
