<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Setting.php';

// Chart settings.
$settings_charts       = WLSM_M_Setting::get_settings_charts( $school_id );
$settings_chart_types  = $settings_charts['chart_types'];
$settings_chart_enable = $settings_charts['chart_enable'];

$charts      = WLSM_Helper::charts();
$chart_types = WLSM_Helper::chart_types();
?>
<div class="tab-pane fade" id="wlsm-school-charts" role="tabpanel" aria-labelledby="wlsm-school-charts-tab">

	<div class="row">
		<div class="col-md-12">
			<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-charts-settings-form">
				<?php
				$nonce_action = 'save-school-charts-settings';
				$nonce        = wp_create_nonce( $nonce_action );
				?>
				<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

				<input type="hidden" name="action" value="wlsm-save-school-charts-settings">

				<?php foreach ( $charts as $key => $value ) { ?>
				<div class="row">
					<div class="col-md-4">
						<label for="wlsm_chart_type_<?php echo esc_attr( $key ); ?>" class="wlsm-font-bold">
							<?php echo esc_html( $value ); ?>:
						</label>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<select name="chart_type_<?php echo esc_attr( $key ); ?>" id="wlsm_chart_type_<?php echo esc_attr( $key ); ?>" class="form-control">
								<?php foreach ( $chart_types as $chart_type_value ) { ?>
								<option <?php selected( $chart_type_value, $settings_chart_types[ $key ], true ); ?> value="<?php echo esc_attr( $chart_type_value ); ?>"><?php echo esc_attr( $chart_type_value ); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="wlsm-font-bold" for="wlsm_chart_enable_<?php echo esc_attr( $key ); ?>">
								<input <?php checked( $settings_chart_enable[ $key ], true, true ); ?> type="checkbox" name="chart_enable_<?php echo esc_attr( $key ); ?>" id="wlsm_chart_enable_<?php echo esc_attr( $key ); ?>">
								<?php esc_html_e( 'Enable', 'school-management' ); ?>
							</label>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="row">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-primary" id="wlsm-save-school-charts-settings-btn">
							<i class="fas fa-save"></i>&nbsp;
							<?php esc_html_e( 'Save', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
