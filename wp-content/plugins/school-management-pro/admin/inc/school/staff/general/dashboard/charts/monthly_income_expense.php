<?php
defined( 'ABSPATH' ) || die();

// Monthly Income Expense.
$monthly_income_expense = array(
	'id'            => 'wlsm-chart-monthly-income-expense',
	'action'        => 'wlsm-fetch-monthly-income-expense',
	'nonce'         => esc_attr( wp_create_nonce('monthly-income-expense') ),
	'title'         => esc_html__( 'Monthly Income and Expense', 'school-management' ),
	'title_1'       => esc_html__( 'Month', 'school-management' ),
	'title_2'       => esc_html__( 'Amount', 'school-management' ),
	'income_label'  => esc_html__( 'Income', 'school-management' ),
	'expense_label' => esc_html__( 'Expense', 'school-management' ),
);

$currency_symbol = html_entity_decode( WLSM_Config::currency_symbol() );
?>

<div class="wlsm-chart-container">
	<canvas class="wlsm-chart" id="<?php echo esc_attr( $monthly_income_expense['id'] ); ?>" width="800" height="250"></canvas>
</div>

<?php
$js = <<<EOT
(function($) {
	'use strict';
	$(document).ready(function() {

		// Monthly Income Expense.
		function wlsmMonthlyIncomeExpense(postData) {
			$.post('$ajax_url', postData, function(data) {
				var data = JSON.parse(data);
				var incomeData = data.income;
				var expenseData = data.expense;

				if(incomeData.length > 0 || expenseData.length > 0) {
					var incomeLabels = [];
					var incomeDatasets = [];

					var expenseLabels = [];
					var expenseDatasets = [];

					for (var i = 0; i < incomeData.length; i++) {
						incomeLabels.push(incomeData[i].x);
						incomeDatasets.push(incomeData[i].y);
					}

					for (var i = 0; i < expenseData.length; i++) {
						expenseLabels.push(expenseData[i].x);
						expenseDatasets.push(expenseData[i].y);
					}

					var monthlyIncomeExpenseCtx = $('#{$monthly_income_expense['id']}')

					var monthlyIncomeExpenseChart = new Chart(monthlyIncomeExpenseCtx, {
						type: '{$settings_chart_types['monthly_income_expense']}',
						data: {
							labels: incomeLabels,
							datasets: [{
								label: '{$monthly_income_expense['income_label']}',
								backgroundColor: 'rgba(50, 205, 50, 0.3)',
								borderColor: 'rgba(50, 205, 50, 0.7)',
								data: incomeDatasets
							},
							{
								label: '{$monthly_income_expense['expense_label']}',
								backgroundColor: 'rgba(255, 99, 132, 0.3)',
								borderColor: 'rgba(255, 99, 132, 0.7)',
								data: expenseDatasets
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
					$('#{$monthly_income_expense['id']}').parent().parent().remove();
				}
			})
		}

		wlsmMonthlyIncomeExpense({
			'action': '{$monthly_income_expense['action']}',
			'nonce': '{$monthly_income_expense['nonce']}'
		});

	});
})(jQuery);
EOT;
wp_add_inline_script( 'wlsm-admin', $js );
