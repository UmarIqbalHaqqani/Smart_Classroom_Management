<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$proj_id  = isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : '' ;
$comment_id  = isset($_POST['comment_id']) ? sanitize_text_field($_POST['comment_id']) : 0 ;
$project_comment = $wppmfunction->get_proj_comment($comment_id);
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_proj_comment_permission('delete_proj_thread',$proj_id,$comment_id))) {exit;}
ob_start();
?>
<form id="frm_delete_proj_thread">
    <p><?php echo esc_html_e('Are you sure to delete this thread?','taskbuilder');?></p>
    <input type="hidden" name="action" value="wppm_set_delete_proj_thread" />
    <input type="hidden" name="_ajax_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_set_delete_proj_thread' ) ); ?>">
    <input type="hidden" name="proj_id" value="<?php echo htmlentities(esc_attr($proj_id))?>" />
    <input type="hidden" name="comment_id" value="<?php echo htmlentities(esc_attr($comment_id))?>" />  
</form>
<?php 
$body = ob_get_clean();
ob_start();
?>
<div class="row">
    <div class="col-md-12" style="text-align: right;">
			<button type="button" class="btn wppm_modal_close" onclick="wppm_modal_close()"><?php echo esc_html_e('Cancel','taskbuilder');?></button>
			<button type="button" class="btn wppm_popup_action"  onclick="wppm_set_delete_proj_thread(<?php echo esc_attr($proj_id); ?>);"><?php echo esc_html_e('Confirm','taskbuilder');?></button>
  </div>
</div>
<?php
$footer = ob_get_clean();
$output = array(
  'body'   => $body,
  'footer' => $footer
);
echo json_encode($output);
?>