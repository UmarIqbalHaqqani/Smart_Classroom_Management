<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;
$settings = get_option("wppm-ap-settings");
$wppm_ap_settings = get_option("wppm-ap-settings");
?>
<form action="#" onsubmit="return false;" class="wppm-frm-ap-settings">
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Tab', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="tab-background-color" value="<?php echo esc_attr( $settings['tab-background-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="tab-text-color" value="<?php echo esc_attr( $settings['tab-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Add new button', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'text color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'hover color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="add-new-button-bg-color" value="<?php echo esc_attr( $settings['add-new-button-bg-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="add-new-button-text-color" value="<?php echo esc_attr( $settings['add-new-button-text-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="add-new-button-hover-color" value="<?php echo esc_attr( $settings['add-new-button-hover-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Save changes button', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="save-changes-button-bg-color" value="<?php echo esc_attr( $settings['save-changes-button-bg-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="save-changes-button-text-color" value="<?php echo esc_attr( $settings['save-changes-button-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <input type="hidden" name="action" value="wppm_set_ap_settings">
    <input type="hidden" name="_ajax_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_set_ap_settings' ) ); ?>">
    <script>jQuery('.wppm-color-picker').wpColorPicker();</script>
    <style>
        .wppm-ap-table td {padding: 5px;}
        .wppm-ap-table table, .wppm-ap-table td {border: 1px solid #c3c3c3;}
    </style>
</form>
<div class="setting-footer-actions">
    <button 
        class="wppm-submit-btn"
        onclick="wppm_set_ap_settings();" style="background-color:<?php echo esc_attr($wppm_ap_settings['save-changes-button-bg-color'])?>!important;color:<?php echo esc_attr($wppm_ap_settings['save-changes-button-text-color'])?>!important;">
        <?php esc_attr_e( 'Save Changes', 'taskbuilder' ); ?></button>
    <button 
        class="wppm_reset_btn" id="wppm_reset_ap_settings_btn"
        onclick="wppm_reset_ap_settings();">
    <?php esc_attr_e( 'Reset default', 'taskbuilder' ); ?></button>
    <input type="hidden" name="wppm_reset_ap_settings_nonce" id="wppm_reset_ap_settings_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_reset_ap_settings' ) ); ?>">
    <span class="wppm_submit_wait" style="display:none;"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/loading_buffer.svg'); ?>" alt="edit"></span>  
</div>
<?php
wp_die();