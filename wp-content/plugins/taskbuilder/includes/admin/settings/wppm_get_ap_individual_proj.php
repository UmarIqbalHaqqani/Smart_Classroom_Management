<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;
$settings = get_option("wppm-ap-individual-project");
$wppm_ap_settings = get_option("wppm-ap-settings");
?>
<form action="#" onsubmit="return false;" class="wppm-frm-ap-individual_pl">
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Menu buttons', 'taskbuilder' ); ?></label>
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
            <label for=""><?php esc_attr_e( 'Comment thread', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Primary color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Secondary color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="comment-primary-color" value="<?php echo esc_attr( $settings['comment-primary-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="comment-secondary-color" value="<?php echo esc_attr( $settings['comment-secondary-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Comment Date', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Hover color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="comment-date-color" value="<?php echo esc_attr( $settings['comment-date-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="comment-date-hover-color" value="<?php echo esc_attr( $settings['comment-date-hover-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Comment send button', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="comment-send-btn-bg-color" value="<?php echo esc_attr( $settings['comment-send-btn-bg-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="comment-send-btn-color" value="<?php echo esc_attr( $settings['comment-send-btn-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Widget Header', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="widget-header-bg-color" value="<?php echo esc_attr( $settings['widget-header-bg-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="widget-header-text-color" value="<?php echo esc_attr( $settings['widget-header-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="wppm-input-group">
        <div class="label-container">
            <label for=""><?php esc_attr_e( 'Widget Body', 'taskbuilder' ); ?></label>
        </div>
        <table class="wppm-ap-table">
            <tr>
                <td><?php esc_attr_e( 'Background color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Label color', 'taskbuilder' ); ?></td>
                <td><?php esc_attr_e( 'Text color', 'taskbuilder' ); ?></td>
            </tr>
            <tr>
                <td><input class="wppm-color-picker" type="text" name="widget-body-bg-color" value="<?php echo esc_attr( $settings['widget-body-bg-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="widget-body-label-color" value="<?php echo esc_attr( $settings['widget-body-label-color'] ); ?>"></td>
                <td><input class="wppm-color-picker" type="text" name="widget-body-text-color" value="<?php echo esc_attr( $settings['widget-body-text-color'] ); ?>"></td>
            </tr>
        </table>
    </div>
    <input type="hidden" name="action" value="wppm_set_ap_individual_proj">
    <input type="hidden" name="_ajax_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_set_ap_individual_proj' ) ); ?>">
    <script>jQuery('.wppm-color-picker').wpColorPicker();</script>
    <style>
        .wppm-ap-table td {padding: 5px;}
        .wppm-ap-table table, .wppm-ap-table td {border: 1px solid #c3c3c3;}
    </style>
</form>
<div class="setting-footer-actions">
    <button 
        class="wppm-submit-btn"
        onclick="wppm_set_ap_individual_proj();" style="background-color:<?php echo esc_attr($wppm_ap_settings['save-changes-button-bg-color'])?>!important;color:<?php echo esc_attr($wppm_ap_settings['save-changes-button-text-color'])?>!important;">
        <?php esc_attr_e( 'Save Changes', 'taskbuilder' ); ?></button>
    <button 
        class="wppm_reset_btn" id="wppm_reset_ap_individual_proj_btn"
        onclick="wppm_reset_ap_individual_proj(this);">
    <?php esc_attr_e( 'Reset default', 'taskbuilder' ); ?></button>
    <input type="hidden" name="wppm_reset_ap_individual_proj_ajax_nonce" id="wppm_reset_ap_individual_proj_ajax_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_reset_ap_individual_proj' ) ); ?>">
    <span class="wppm_submit_wait" style="display:none;"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/loading_buffer.svg'); ?>" alt="edit"></span>  
</div>
<?php
wp_die();