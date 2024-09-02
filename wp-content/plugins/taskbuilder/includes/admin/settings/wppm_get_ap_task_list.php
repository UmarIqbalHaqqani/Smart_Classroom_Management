<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;
$settings = get_option("wppm-ap-task-list");
$wppm_ap_settings = get_option("wppm-ap-settings");
?>
<form action="#" onsubmit="return false;" class="wppm-frm-ap-tl">
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'List header button', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Hover color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="list-header-button-background-color" value="<?php echo esc_attr( $settings['list-header-button-background-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="list-header-button-text-color" value="<?php echo esc_attr( $settings['list-header-button-text-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="list-header-button-hover-color" value="<?php echo esc_attr( $settings['list-header-button-hover-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'List header', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="list-header-background-color" value="<?php echo esc_attr( $settings['list-header-background-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="list-header-text-color" value="<?php echo esc_attr( $settings['list-header-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'List item (odd)', 'taskbuilder' ); ?></label>
        </div>
        <table class="wpsc-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="list-item-odd-background-color" value="<?php echo esc_attr( $settings['list-item-odd-background-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="list-item-odd-text-color" value="<?php echo esc_attr( $settings['list-item-odd-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'List item (even)', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="list-item-even-background-color" value="<?php echo esc_attr( $settings['list-item-even-background-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="list-item-even-text-color" value="<?php echo esc_attr( $settings['list-item-even-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'List item (hover)', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="list-item-hover-background-color" value="<?php echo esc_attr( $settings['list-item-hover-background-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="list-item-hover-text-color" value="<?php echo esc_attr( $settings['list-item-hover-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <input type="hidden" name="action" value="wppm_set_ap_task_list">
    <input type="hidden" name="_ajax_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_set_ap_task_list' ) ); ?>">
    <script>jQuery('.wppm-color-picker').wpColorPicker();</script>
    <style>
        .wppm-ap-table td {padding: 5px;}
        .wppm-ap-table table, .wppm-ap-table td {border: 1px solid #c3c3c3;}
    </style>
</form>
<div class="setting-footer-actions">
    <button 
        class="wppm-submit-btn"
        onclick="wppm_set_ap_task_list();" style="background-color:<?php echo esc_attr($wppm_ap_settings['save-changes-button-bg-color'])?>!important;color:<?php echo esc_attr($wppm_ap_settings['save-changes-button-text-color'])?>!important;">
        <?php esc_attr_e( 'Save Changes', 'taskbuilder' ); ?></button>
    <button 
        class="wppm_reset_btn" id="wppm_reset_ap_task_list_btn"
        onclick="wppm_reset_ap_task_list(this);">
    <?php esc_attr_e( 'Reset default', 'taskbuilder' ); ?></button>
    <input type="hidden" name="wppm_reset_ap_task_list_ajax_nonce" id="wppm_reset_ap_task_list_ajax_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_reset_ap_task_list' ) ); ?>">
    <span class="wppm_submit_wait" style="display:none;"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/loading_buffer.svg'); ?>" alt="edit"></span>  
</div>
<?php
wp_die();