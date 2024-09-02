<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
if ( check_ajax_referer( 'wppm_submit_proj_comment', '_ajax_nonce', false ) != 1 ) {
  wp_send_json_error( 'Unauthorised request!', 401 );
}
$proj_id  = isset($_POST['proj_id']) ? sanitize_text_field($_POST['proj_id']) : '' ;
$comment  = isset($_POST['comment_body']) ? ($_POST['comment_body']) : '' ;
$allowed_tags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(), 'rel'=> array(), 'target'=> array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));
$comment_body = wp_kses(htmlspecialchars_decode($comment, ENT_QUOTES),$allowed_tags);
if($comment_body=="") exit;
$attachments = !empty($_POST['wppm_proj_comment_attachment']) ? $wppmfunction->sanitize_array($_POST['wppm_proj_comment_attachment']) : [];
$attachment_ids = implode(",",$attachments);
if(!empty($attachments)){
  $project_auth_code = $wppmfunction->get_project_meta($proj_id,'project_auth_code',true);
  if(empty($project_auth_code)){
    $auth_code = $wppmfunction->getRandomString(10);
    $wppmfunction->add_project_meta($proj_id,'project_auth_code',$auth_code);
  }
}
$vals = array('is_active' => 1);
foreach($attachments as $attach){
  $wpdb->update($wpdb->prefix.'wppm_attachments', $vals, array('id'=>$attach));
}
$args=array(
  'proj_id'=> $proj_id,
  'body'=>$comment_body,
  'attachment_ids'=>$attachment_ids,
  'create_time'=>current_time('mysql', 1),
  'created_by'=>$current_user->ID
);
$args = apply_filters( 'wppm_submit_proj_comment_args', $args );
$comment_id = $wppmfunction->wppm_submit_proj_comment($args);

do_action('wppm_after_submit_proj_comment', $proj_id,$comment_id);

