<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;
$settings = get_option("wppm-ap-grid-view");
$wppm_ap_settings = get_option("wppm-ap-settings");
?>
<form action="#" onsubmit="return false;" class="wppm-frm-ap-grid_view">
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Menu button', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Hover color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="menu-button-bg-color" value="<?php echo esc_attr( $settings['menu-button-bg-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="menu-button-hover-color" value="<?php echo esc_attr( $settings['menu-button-hover-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="menu-button-text-color" value="<?php echo esc_attr( $settings['menu-button-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Kanban card', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="grid-background-color" value="<?php echo esc_attr( $settings['grid-background-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="grid-header-text-color" value="<?php echo esc_attr( $settings['grid-header-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>

    <input type="hidden" name="action" value="wppm_set_ap_grid_view">
    <input type="hidden" name="_ajax_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_set_ap_grid_view' ) ); ?>">
    <div class="setting-footer-actions">
        <button 
            class="wppm-submit-btn"
            onclick="wppm_set_ap_grid_view();" style="background-color:<?php echo esc_attr($wppm_ap_settings['save-changes-button-bg-color'])?>!important;color:<?php echo esc_attr($wppm_ap_settings['save-changes-button-text-color'])?>!important;">
            <?php esc_attr_e( 'Save Changes', 'taskbuilder' ); ?></button>
        <button 
            class="wppm_reset_btn" id="wppm_reset_ap_grid_view_btn"
            onclick="wppm_reset_ap_grid_view(this);">
        <?php esc_attr_e( 'Reset default', 'taskbuilder' ); ?></button>
        <input type="hidden" name="wppm_reset_ap_grid_view_ajax_nonce" id="wppm_reset_ap_grid_view_ajax_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_reset_ap_grid_view' ) ); ?>">
        <span class="wppm_submit_wait" style="display:none;"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/loading_buffer.svg'); ?>" alt="edit"></span>  
    </div>
</form>
<script>jQuery('.wppm-color-picker').wpColorPicker();</script>
<style>
    .wppm-ap-table td {padding: 5px;}
    .wppm-ap-table table, .wppm-ap-table td {border: 1px solid #c3c3c3;}
</style>
<?php
wp_die();