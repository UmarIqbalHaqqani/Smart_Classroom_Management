<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
if ( check_ajax_referer( 'wppm_set_edit_proj_thread', '_ajax_nonce', false ) != 1 ) {
  wp_send_json_error( 'Unauthorised request!', 401 );
}
$proj_id  = isset($_POST['proj_id']) ? sanitize_text_field($_POST['proj_id']) : '' ;
$comment_id  = isset($_POST['proj_comment_id']) ? sanitize_text_field($_POST['proj_comment_id']) : '' ;
$proj_comment = $wppmfunction->get_proj_comment($comment_id);
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_proj_comment_permission('edit_proj_comment',$proj_id,$comment_id))) {exit;}
$proj_comment_data = $wppmfunction->get_proj_comment($comment_id);
$old_comment_body = $proj_comment_data['body'];
$allowed_tags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(),'target'=> array(), 'rel'=>array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));
$new_proj_comment = isset($_POST['wppm_edit_proj_thread']) ? wp_kses(htmlspecialchars_decode($_POST['wppm_edit_proj_thread'], ENT_QUOTES),$allowed_tags) : "" ;

if($new_proj_comment && $old_comment_body != $new_proj_comment ){
    $wppmfunction->change_proj_comment($comment_id, $new_proj_comment);
}