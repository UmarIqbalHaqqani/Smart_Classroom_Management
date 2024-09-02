<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wppmfunction,$wpdb;
$current_user_data = get_userdata($current_user->ID);
$project_id = intval(sanitize_text_field($project_id));
$wppm_project_data = $wppmfunction->get_project($project_id);
$wppm_proj_comment = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_comment where proj_id='".$project_id."' ORDER BY id DESC LIMIT 1;");
$attachments = array();
$auth_id  = $wppmfunction->get_project_meta($project_id,'project_auth_code',true);
if(!empty($wppm_proj_comment)){
	$attachments = explode(',',$wppm_proj_comment->attachment_ids);
}
preg_match_all("/{[^}]*}/" ,$str,$matches);
$matches = array_unique($matches[0]);
$flag =false;
foreach($matches as $match){
	switch($match){
		//Current User Name
		case '{user_name}':
			$str = preg_replace('/{user_name}/', $current_user_data->display_name, $str);
			break;
		// Project ID
		case '{project_id}':
			$str = preg_replace('/{project_id}/', $project_id, $str);
			break;
		// Old project Status
		case '{old_project_status}':
			$str = preg_replace('/{old_project_status}/', $this->get_old_project_status_name($project_id), $str);
			break;
		// Project Status
		case '{new_project_status}':
			$str = preg_replace('/{new_project_status}/', $this->get_new_project_status_name(sanitize_text_field($wppm_project_data['status'])), $str);
			break;
		// Project Status
		case '{project_status}':
			$str = preg_replace('/{project_status}/', $this->get_new_project_status_name(sanitize_text_field($wppm_project_data['status'])), $str);
			break;
		// Project Category
		case '{project_category}':
			if(!empty($wppm_project_data['cat_id'])){
				$str = preg_replace('/{project_category}/', $this->get_project_category_name(sanitize_text_field($wppm_project_data['cat_id'])), $str);
			}else{
				$str = "";
			}

			break;
		// Project Name
		case '{project_name}':
			$str = preg_replace('/{project_name}/', sanitize_text_field($wppm_project_data['project_name']), $str);
			break;
		// Project Start Date
		case '{project_start_date}':
			$str = preg_replace('/{project_start_date}/', sanitize_text_field($wppm_project_data['start_date']), $str);
			break;
		// Project End Date
		case '{project_end_date}':
			$str = preg_replace('/{project_end_date}/', sanitize_text_field($wppm_project_data['end_date']), $str);
			break;
		// Assigned Users
		case '{project_assigned_users}':
			$assigned_users = $this->get_project_assigned_users_names($project_id);
			$str = preg_replace('/{project_assigned_users}/', sanitize_text_field($assigned_users), $str);
			break;
		// Previously Assigned users
		case '{previously_assigned_project_users}':
			$previously_assigned_users = $this->get_project_previously_assigned_users_names($project_id);
			$str = preg_replace('/{previously_assigned_project_users}/', sanitize_text_field($previously_assigned_users), $str);
			break;
		// Date created
		case '{date_created}':
			$str = preg_replace('/{date_created}/', get_date_from_gmt(sanitize_text_field($wppm_project_data['date_created'] )), $str);
			break;
			//Last comment user name
		case '{last_proj_comment_user_name}':
			$str = preg_replace('/{last_proj_comment_user_name}/', $this->get_last_comment_proj_user_name($project_id), $str);
		break;
		//Last comment body
		case '{proj_comment_body}':
			$flag= true;
			$str = preg_replace('/{proj_comment_body}/', $this->get_proj_comment_body($project_id), $str);
		break;

		// Project Description
		case '{project_description}':
		$str = preg_replace('/{project_description}/', sanitize_text_field($wppm_project_data['description']), $str);	
	}
}
if($flag == true){
	if(!empty($attachments)){
		foreach($attachments as $attach_id){
			$upload_dir   = wp_upload_dir();
			$attachment = $wpdb->get_row("select * from {$wpdb->prefix}wppm_attachments where id='".$attach_id."'");
			if(!empty($attachment)){
				$updated_time = sanitize_text_field($attachment->date_created);
				$time  = strtotime(sanitize_text_field($updated_time));
				$month = date("m",$time);
				$year  = date("Y",$time);
				$findStr = ".txt";
				$attachment_name = preg_replace('/' . $findStr . '/', "", sanitize_file_name($attachment->name), 1);
				$file_url = $upload_dir['basedir'] . '/wppm/'.'/'.$year.'/'.$month.'/'. $attachment_name;
				$download_url = home_url('/').'?wppm_attachment='.sanitize_text_field($attachment->id).'&pid='.sanitize_text_field($project_id).'&pac='.sanitize_text_field($auth_id);
				$attach_url[] = '<a style="text-decoration:none;" href="'.$download_url.'" target="_blank">'.sanitize_file_name($attachment->file_name).'</a>';
			}else{
				$attach_url= array();
			}
		}
		$str = $str.implode("<br>",$attach_url);
	}
}
$str = apply_filters('wppm_replace_macro',$str,$project_id);

