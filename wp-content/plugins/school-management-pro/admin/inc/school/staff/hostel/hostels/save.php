<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Transport.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Helper.php';

$page_url = WLSM_M_Staff_Transport::get_hostels_page_url();

$hostel_type_list = WLSM_Helper::hostel_type_list();

$school_id = $current_school['id'];

$hostel = NULL;

$nonce_action = 'add-hostel';

$hostel_name = '';
$hostel_type = '';
$address     = '';
$intake      = '';
$fees      = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
	$id      = absint($_GET['id']);
	$hostel = WLSM_M_Staff_Transport::fetch_hostel($school_id, $id);
	if ($hostel) {
		$nonce_action = 'edit-hostel-' . $hostel->ID;

		$hostel_name = $hostel->hostel_name;
		$hostel_type = $hostel->hostel_type;
		$address     = $hostel->hostel_address;
		$intake      = $hostel->hostel_intake;
		$fees        = $hostel->fees;
	
	}
}
?>

<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ($hostel) {
						printf(
							wp_kses(
								/* translators: %s: hostel name */
								__('Edit Hostel: %s', 'school-management'),
								array(
									'span' => array('class' => array())
								)
							),
							esc_html($hostel_name)
						);
					} else {
						esc_html_e('Add New Hostel', 'school-management');
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url($page_url); ?>" class="btn btn-sm btn-outline-light">
				<i class="fas fa-home"></i>&nbsp;
					<?php esc_html_e('View All', 'school-management'); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-save-hostel-form">

			<?php $nonce = wp_create_nonce($nonce_action); ?>
			<input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

			<input type="hidden" name="action" value="wlsm-save-hostel">

			<?php if ($hostel) { ?>
				<input type="hidden" name="hostel_id" value="<?php echo esc_attr($hostel->ID); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_hostel_name" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e('Hostel Name', 'school-management'); ?>:
						</label>
						<input type="text" name="hostel_name" class="form-control" id="wlsm_hostel_name" placeholder="<?php esc_attr_e('Enter hostel name', 'school-management'); ?>" value="<?php echo esc_attr($hostel_name); ?>">
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_hostel_model" class="wlsm-font-bold">
							<?php esc_html_e('Hostel Type', 'school-management'); ?>:
						</label>
						<select name="hostel_type" class="form-control selectpicker" id="wlsm_hostel_type" data-live-search="true">
							<option value=""><?php esc_html_e('Select Hostel Type', 'school-management'); ?></option>
							<?php foreach ($hostel_type_list as $key => $value) { ?>
								<option value="<?php echo esc_attr($key); ?>" <?php selected($key, $hostel_type, true); ?>>
									<?php echo esc_html($value); ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>
				
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_hostel_name" class="wlsm-font-bold">
						<?php esc_html_e('Hostel Address', 'school-management'); ?>:
						</label>
						<textarea name="address" class="form-control" id="wlsm_address" cols="30" rows="3" placeholder="<?php esc_attr_e('Enter address', 'school-management'); ?>"><?php echo esc_html($address); ?></textarea>
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_intake" class="wlsm-font-bold">
							<?php esc_html_e('Hostel Capacity', 'school-management'); ?>:
						</label>
						<input type="Number" name="intake" class="form-control" id="wlsm_intake" placeholder="<?php esc_attr_e('Enter', 'school-management'); ?>" value="<?php echo esc_attr($intake); ?>">
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_fees" class="wlsm-font-bold">
							<?php esc_html_e('Hostel fees', 'school-management'); ?>:
						</label>
						<input type="Number" name="fees" class="form-control" id="wlsm_fees" placeholder="<?php esc_attr_e('Enter Fees', 'school-management'); ?>" value="<?php echo esc_attr($fees); ?>">
					</div>
				</div>
				
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-hostel-btn">
						<?php
						if ($hostel) {
						?>
							<i class="fas fa-save"></i>&nbsp;
						<?php
							esc_html_e('Update Hostel', 'school-management');
						} else {
						?>
							<i class="fas fa-plus-square"></i>&nbsp;
						<?php
							esc_html_e('Add New Hostel', 'school-management');
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>