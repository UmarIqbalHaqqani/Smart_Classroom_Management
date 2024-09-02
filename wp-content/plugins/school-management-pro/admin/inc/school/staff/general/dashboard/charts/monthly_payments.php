<?php
defined( 'ABSPATH' ) || die();

// Monthly Payments.
$monthly_payments = array(
	'id'      => 'wlsm-chart-monthly-payments',
	'action'  => 'wlsm-fetch-monthly-payments',
	'nonce'   => esc_attr( wp_create_nonce('monthly-payments') ),
	'title'   => sprintf(
		/* translators: %s: session label */
		__( 'Monthly Payments for Session: %s', 'school-management' ),
		esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
	),
	'title_1' => esc_html__( 'Month', 'school-management' ),
	'title_2' => esc_html__( 'Payment Received', 'school-management' ),
);

$currency_symbol = html_entity_decode( WLSM_Config::currency_symbol() );
?>

<div class="wlsm-chart-container">
	<canvas class="wlsm-chart" id="<?php echo esc_attr( $monthly_payments['id'] ); ?>" width="400" height="250"></canvas>
</div>

<?php
$js = <<<EOT
(function($) {
	'use strict';
	$(document).ready(function() {

		// Monthly Payments.
		function wlsmMonthlyPayments(postData) {
			$.post('$ajax_url', postData, function(data) {
				var data = JSON.parse(data);
				if(data.length > 0) {

					var labels = [];
					var datasets = [];
					for (var i = 0; i < data.length; i++) {
						labels.push(data[i].x);
						datasets.push(data[i].y);
					}

					var monthlyPaymentsCtx = $('#{$monthly_payments['id']}')

					var monthlyPaymentsChart = new Chart(monthlyPaymentsCtx, {
						type: '{$settings_chart_types['monthly_payments']}',
						data: {
							labels: labels,
							datasets: [{
								label: '{$monthly_payments['title']}',
								backgroundColor: 'rgba(54, 162, 235, 0.3)',
								borderColor: 'rgba(54, 162, 235, 0.7)',
								data: datasets
							}]
						},
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: true
									}
								}]
							}
						},
					});

				} else {
					$('#{$monthly_payments['id']}').parent().parent().remove();
				}
			})
		}

		wlsmMonthlyPayments({
			'action': '{$monthly_payments['action']}',
			'nonce': '{$monthly_payments['nonce']}'
		});

	});
})(jQuery);
EOT;
wp_add_inline_script( 'wlsm-admin', $js );
