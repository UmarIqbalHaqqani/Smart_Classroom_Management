<?php
defined( 'ABSPATH' ) || die();

// Monthly Enrollments.
$monthly_admissions = array(
	'id'      => 'wlsm-chart-monthly-admissions',
	'action'  => 'wlsm-fetch-monthly-admissions',
	'nonce'   => esc_attr( wp_create_nonce('monthly-admissions') ),
	'title'   => sprintf(
		/* translators: %s: session label */
		__( 'Monthly Enrollments for Session: %s', 'school-management' ),
		esc_html( WLSM_M_Session::get_label_text( $current_session['label'] ) )
	),
	'title_1' => esc_html__( 'Month', 'school-management' ),
	'title_2' => esc_html__( 'Number of Enrollments', 'school-management' ),
);
?>

<div class="wlsm-chart-container">
	<canvas class="wlsm-chart" id="<?php echo esc_attr( $monthly_admissions['id'] ); ?>" width="400" height="250"></canvas>
</div>

<?php
$js = <<<EOT
(function($) {
	'use strict';
	$(document).ready(function() {

		// Monthly Enrollments.
		function wlsmMonthlyEnrollments(postData) {
			$.post('$ajax_url', postData, function(data) {
				var data = JSON.parse(data);
				if(data.length > 0) {

					var labels = [];
					var datasets = [];
					for (var i = 0; i < data.length; i++) {
						labels.push(data[i].x);
						datasets.push(data[i].y);
					}

					var monthlyEnrollmentsCtx = $('#{$monthly_admissions['id']}')

					var monthlyEnrollmentsChart = new Chart(monthlyEnrollmentsCtx, {
						type: '{$settings_chart_types['monthly_admissions']}',
						data: {
							labels: labels,
							datasets: [{
								label: '{$monthly_admissions['title']}',
								backgroundColor: 'rgba(153, 102, 255, 0.3)',
								borderColor: 'rgba(153, 102, 255, 0.7)',
								data: datasets
							}]
						},
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: true,
										callback: function(value) {if (value % 1 === 0) {return value;}}
									}
								}]
							}
						},
					});

				} else {
					$('#{$monthly_admissions['id']}').parent().parent().remove();
				}
			})
		}

		wlsmMonthlyEnrollments({
			'action': '{$monthly_admissions['action']}',
			'nonce': '{$monthly_admissions['nonce']}'
		});

	});
})(jQuery);
EOT;
wp_add_inline_script( 'wlsm-admin', $js );
