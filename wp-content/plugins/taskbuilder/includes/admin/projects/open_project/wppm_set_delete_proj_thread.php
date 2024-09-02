<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wppmfunction ,$wpdb;
if ( check_ajax_referer( 'wppm_set_delete_proj_thread', '_ajax_nonce', false ) != 1 ) {
    wp_send_json_error( 'Unauthorised request!', 401 );
}
$proj_id  = isset($_POST['proj_id']) ? sanitize_text_field($_POST['proj_id']) : 0 ;
$thread_id  = isset($_POST['comment_id']) ? sanitize_text_field($_POST['comment_id']) : 0 ;
$project_comment = $wppmfunction->get_proj_comment($thread_id);
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_proj_comment_permission('delete_proj_thread',$proj_id,$thread_id))) {exit;}
$sql="SELECT attachment_ids FROM {$wpdb->prefix}wppm_project_comment WHERE id =".$thread_id;
$thread_attachment_ids = $wpdb->get_results( $sql );
if(!empty($thread_attachment_ids) ){
	foreach ($thread_attachment_ids as $thread_attachment_id){
		$attachment_ids_temp=array();
		if($thread_attachment_id->attachment_ids){
			$attachment_ids_temp =  explode(',', $thread_attachment_id->attachment_ids);
		}
		$attachment_ids=$attachment_ids_temp;
		if(!empty($attachment_ids_temp)){
			foreach ($attachment_ids_temp as $attachment_id){
				$sql="SELECT * FROM {$wpdb->prefix}wppm_attachments WHERE id =".$attachment_id;
				$result=$wpdb->get_row($sql);
				if(!empty($result)){
					$sql_query="SELECT file_path FROM {$wpdb->prefix}wppm_attachments WHERE file_path ='".$result->file_path."'";
					$attach_result=$wpdb->get_results($sql_query);
					$result_count = count($attach_result);
					if(file_exists($result->file_path) && $result_count < 2)
					{
						unlink($result->file_path);
					}
					$wpdb->delete($wpdb->prefix.'wppm_attachments', array( 'id' => $attachment_id));
				}
			}
		}
	}
}
$wpdb->delete($wpdb->prefix.'wppm_project_comment',array('id'=>$thread_id));
do_action('wppm_after_delete_project_thread',$thread_id,$proj_id);

?>