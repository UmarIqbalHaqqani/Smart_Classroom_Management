<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;
$settings = get_option("wppm-ap-modal");
$wppm_ap_settings = get_option("wppm-ap-settings");
?>
<form action="#" onsubmit="return false;" class="wppm-frm-ap-modal_popup">
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Popup header', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="header-bg-color" value="<?php echo esc_attr( $settings['header-bg-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="header-text-color" value="<?php echo esc_attr( $settings['header-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Popup body', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Label color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="body-bg-color" value="<?php echo esc_attr( $settings['body-bg-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="body-label-color" value="<?php echo esc_attr( $settings['body-label-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="body-text-color" value="<?php echo esc_attr( $settings['body-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Popup footer', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="footer-bg-color" value="<?php echo esc_attr( $settings['footer-bg-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Action button', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="action-btn-bg-color" value="<?php echo esc_attr( $settings['action-btn-bg-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="action-btn-text-color" value="<?php echo esc_attr( $settings['action-btn-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <input type="hidden" name="action" value="wppm_set_ap_modal_popup">
    <input type="hidden" name="_ajax_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_set_ap_modal_popup' ) ); ?>">
    <script>jQuery('.wppm-color-picker').wpColorPicker();</script>
    <style>
        .wppm-ap-table td {padding: 5px;}
        .wppm-ap-table table, .wppm-ap-table td {border: 1px solid #c3c3c3;}
    </style>
</form>
<div class="setting-footer-actions">
    <button 
        class="wppm-submit-btn"
        onclick="wppm_set_ap_modal_popup();" style="background-color:<?php echo esc_attr($wppm_ap_settings['save-changes-button-bg-color'])?>!important;color:<?php echo esc_attr($wppm_ap_settings['save-changes-button-text-color'])?>!important;">
        <?php esc_attr_e( 'Save Changes', 'taskbuilder' ); ?></button>
    <button 
        class="wppm_reset_btn" id="wppm_reset_ap_modal_popup_btn"
        onclick="wppm_reset_ap_modal_popup(this);">
    <?php esc_attr_e( 'Reset default', 'taskbuilder' ); ?></button>
    <input type="hidden" name="wppm_reset_ap_modal_popup_nonce" id="wppm_reset_ap_modal_popup_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_reset_ap_modal_popup' ) ); ?>">
    <span class="wppm_submit_wait" style="display:none;"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/loading_buffer.svg'); ?>" alt="edit"></span>  
</div>
<?php
wp_die();