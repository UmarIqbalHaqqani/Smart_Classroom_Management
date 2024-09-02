<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $wpdb,$wppmfunction,$current_user;
$comment_id = isset($_POST['comment_id']) ? intval(sanitize_text_field($_POST['comment_id'])) : '' ;
$proj_id  = isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : '' ;
$project_data = $wppmfunction->get_project($proj_id);
$project_comment = $wppmfunction->get_proj_comment($comment_id);
$settings = get_option("wppm-ap-modal");
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_proj_comment_permission('edit_proj_comment',$proj_id,$comment_id))) {exit;}
ob_start();
?>
<form id="frm_edit_proj_thread" method="post">
	<div>
        <textarea id="wppm_edit_proj_thread_editor">
        <?php echo stripslashes(htmlspecialchars_decode(esc_textarea($project_comment['body']),ENT_QUOTES))?>
        </textarea>
	</div>
  <input type="hidden" name="action" value="wppm_set_edit_proj_thread" />
  <input type="hidden" name="_ajax_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wppm_set_edit_proj_thread' ) ); ?>">
  <input type="hidden" id="wppm_proj_id" name="proj_id" value="<?php echo htmlentities(esc_attr($proj_id)) ?>" />
  <input type="hidden" id="wppm_proj_comment_id" name="proj_comment_id" value="<?php echo htmlentities(esc_attr($comment_id)) ?>" />
</form>
<script>
jQuery( document ).ready( function( jQuery ) {
    tinymce.remove();
    tinymce.init({ 
        selector:'#wppm_edit_proj_thread_editor',
        body_id: 'wppm_edit_proj_thread_editor',
        directionality : '<?php //echo 'rtl'; ?>',
        menubar: false,
        statusbar: false,
        height : '200',
        width  : '100%', 
        plugins: [
            'lists link image directionality'
        ],
        image_advtab: true,
        toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
        branding: false,
        autoresize_bottom_margin: 20,
        browser_spellcheck : true,
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true,
        content_style: 
        `body {
              color:<?php echo esc_attr( $settings['body-text-color'])?>!important;
          }`,
        setup: function (editor) {
        }
    });
});
<?php
$body = ob_get_clean();

ob_start();
?>
<button type="button" class="btn wppm_popup_close" onclick="wppm_modal_close();"><?php echo esc_html_e('Close','taskbuilder');?></button>
<button type="button" class="btn wppm_popup_action" onclick="wppm_set_edit_proj_thread(<?php echo esc_attr($proj_id) ?>);"><?php echo esc_html_e('Save','taskbuilder');?></button>
<?php
$footer = ob_get_clean();

$output = array(
  'body'   => $body,
  'footer' => $footer
);

echo json_encode($output);