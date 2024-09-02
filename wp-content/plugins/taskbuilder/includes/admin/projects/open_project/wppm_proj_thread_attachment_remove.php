<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wppmfunction, $wpdb;
if ( check_ajax_referer( 'wppm_remove_proj_thread_attachment', '_ajax_nonce', false ) != 1 ) {
	wp_send_json_error( 'Unauthorised request!', 401 );
  }
$attachment     = isset($_POST['attachment']) ? intval(sanitize_text_field($_POST['attachment'])) : '0' ;
$comment_id = isset($_POST['comment_id']) ? intval(sanitize_text_field($_POST['comment_id'])) : '0' ;
$proj_id = isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : '0' ;
$proj_comment = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wppm_project_comment where id = $comment_id AND proj_id=$proj_id");
if(!(((!empty($proj_comment)) && ($proj_comment->created_by == $current_user->ID)) || ($current_user->has_cap('manage_options')) || ($wppmfunction->has_proj_comment_permission('edit_proj_comment',$proj_id,$comment_id)))){
  exit;
}
$sql="SELECT * FROM {$wpdb->prefix}wppm_attachments WHERE id =".$attachment;
$result=$wpdb->get_row($sql);
$sql_query="SELECT file_path FROM {$wpdb->prefix}wppm_attachments WHERE file_path ='".$result->file_path."'";
$attach_result=$wpdb->get_results($sql_query);
$result_count = count($attach_result);
if(file_exists($result->file_path) && $result_count < 2)
{
	unlink($result->file_path);
}
$success = $wpdb->delete( 
	$wpdb->prefix . 'wppm_attachments', 
	array( 'id' => $attachment ) 
);
if( !$success ) return false;
$proj_attachment = $wpdb->get_var("SELECT attachment_ids FROM {$wpdb->prefix}wppm_project_comment WHERE id=$comment_id ");
$proj_attachment = explode(",",$proj_attachment);
if(!empty($proj_attachment)){
	foreach($proj_attachment as $key=>$val){
		if($val == $attachment){
			unset($proj_attachment[$key]);
		}
	}
}
$pattachment = implode(',',$proj_attachment);
$values=array(
	'attachment_ids'=>$pattachment
);
$wpdb->update($wpdb->prefix.'wppm_project_comment', $values, array('id'=>$comment_id));
$attachment = apply_filters('wppm_after_delete_proj_comment_attachment',$attachment,$comment_id,$proj_id);