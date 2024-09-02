<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb, $wppmfunction;
$popup_settings = get_option("wppm-ap-modal");
$wppm_ap_settings = get_option("wppm-ap-settings");
?>
<div class="wppm_bootstrap">
  <span class="wppm-heading-inline wppm_email_notification_header">
    <?php echo esc_html_e('Settings','taskbuilder');?>
  </span>
  <div class="wppm_padding_space"></div>
  <div class="row">
    <div class="col-sm-4 wppm_setting_col1">
      <ul class="nav nav-pills nav-stacked wppm_setting_pills">
        <li id="wppm_settings_general" role="presentation" class="active"><a href="javascript:wppm_get_general_settings();"><?php echo esc_html_e('General','taskbuilder');?></a></li>
        <li id="wppm_settings_category" role="presentation"><a href="javascript:wppm_get_category_settings();"><?php echo esc_html_e('Project Categories','taskbuilder');?></a></li>
        <li id="wppm_settings_proj_status" role="presentation"><a href="javascript:wppm_get_proj_status_settings();"><?php echo esc_html_e('Project Statuses','taskbuilder');?></a></li>
        <li id="wppm_settings_task_priorities" role="presentation"><a href="javascript:wppm_get_task_prioriy_settings();"><?php echo esc_html_e('Tasks Priorities','taskbuilder');?></a></li>
        <li id="wppm_settings_task_status" role="presentation"><a href="javascript:wppm_get_task_status_settings();"><?php echo esc_html_e('Task Statuses','taskbuilder');?></a></li>
        <li id="wppm_settings_miscellaneous" role="presentation"><a href="javascript:wppm_get_miscellaneous_settings();"><?php echo esc_html_e('Miscellaneous','taskbuilder');?></a></li>
        <li id="wppm_settings_appearance" role="presentation"><a href="javascript:wppm_get_appearance_settings();"><?php echo esc_html_e('Appearance','taskbuilder');?></a></li>
        <?php do_action('wppm_after_setting_pills');?>
      </ul>
    </div>
    <div class="col-sm-8 wppm_setting_col2"></div>
    <div id="wppm_alert_success" class="alert alert-success wppm_alert" style="display:none;" role="alert">
      <img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/success.svg'); ?>" alt="success">  
      <span class="wppm_alert_text"></span>
      </div>
      <div id="wppm_alert_error" class="alert alert-danger wppm_alert" style="display:none;" role="alert">
      <img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/warning-triangle.svg'); ?>" alt="warning-triangle"><span class="wppm_alert_text"></span>
    </div>
  </div>
  
</div>
<!-- Pop-up snippet start -->
<div id="wppm_popup_background" style="display:none;"></div>
<div id="wppm_popup_container" style="display:none;">
  <div class="wppm_bootstrap">
    <div class="row">
      <div id="wppm_popup" class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div id="wppm_popup_title" class="row"><h3><?php echo esc_html_e('Modal Title','taskbuilder');?></h3></div>
        <div id="wppm_popup_body" class="row"><?php echo esc_html_e('I am body!','taskbuilder');?></div>
        <div id="wppm_popup_footer" class="row">
          <button type="button" class="btn wppm_popup_close"><?php echo esc_html_e('Close','taskbuilder');?></button>
          <button type="button" class="btn wppm_popup_action"><?php echo esc_html_e('Save Changes','taskbuilder');?></button>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  #wppm_popup_title{
  background-color: <?php echo esc_attr($popup_settings['header-bg-color'])?>;
  }
  #wppm_popup_title h3{
    color:<?php echo esc_attr( $popup_settings['header-text-color'])?>;
  }
  #wppm_popup_body{
    background-color: <?php echo esc_attr($popup_settings['body-bg-color'])?>!important;
  }
  #wppm_popup_body label{
    color:<?php echo esc_attr( $popup_settings['body-label-color'])?>;
  }
  #wppm_popup_body input{
    color:<?php echo esc_attr( $popup_settings['body-text-color'])?>;
  }
  #wppm_popup_body select{
    color:<?php echo esc_attr( $popup_settings['body-text-color'])?>;
  }
  #wppm_popup_body span {
    color:<?php echo esc_attr( $popup_settings['body-text-color'])?>;
  }
  #wppm_popup_body .select2-results__options {
    color:<?php echo esc_attr( $popup_settings['body-text-color'])?>;
  }
  #wppm_popup_footer{
    background-color: <?php echo esc_attr($popup_settings['footer-bg-color'])?>!important;
  }
  .wppm_popup_action{
    background-color: <?php echo esc_attr($popup_settings['action-btn-bg-color'])?>!important;
    color:<?php echo esc_attr( $popup_settings['action-btn-text-color'])?>!important;
  }
  .wppm_bootstrap .nav-pills > li.active > a,
  .wppm_bootstrap .nav-pills > li.active > a:hover,
  .wppm_bootstrap .nav-pills > li.active > a:focus {
    color: <?php echo esc_attr( $wppm_ap_settings['tab-text-color'])?>!important;
    background-color:<?php echo esc_attr($wppm_ap_settings['tab-background-color'])?>!important;
  }
</style>
<!-- Pop-up snippet end -->
<script>
    jQuery(document).ready(function(){
      wppm_get_general_settings();
    });
</script>