<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user,$wpdb,$wppmfunction;
$current_tab = isset( $_POST['current_tab'] ) ? sanitize_text_field( ( $_POST['current_tab'] ) ) : 'project-list'; // phpcs:ignore
  $tabs        = apply_filters(
    'wppm_appearance_tabs',
    array(
      'project-list'           => array(
        'slug'     => 'project_list',
        'label'    => esc_attr__( 'Project List', 'taskbuilder' ),
        'callback' => 'wppm_get_ap_proj_list',
      ),
      'task-list'       => array(
        'slug'     => 'task_list',
        'label'    => esc_attr__( 'Task List', 'taskbuilder' ),
        'callback' => 'wppm_get_ap_task_list',
      ),
      'individual-project' => array(
        'slug'     => 'individual_proj',
        'label'    => esc_attr__( 'Individual Project', 'taskbuilder' ),
        'callback' => 'wppm_get_ap_individual_proj',
      ),
      'individual-task' => array(
        'slug'     => 'individual_task',
        'label'    => esc_attr__( 'Individual Task', 'taskbuilder' ),
        'callback' => 'wppm_get_ap_individual_task',
      ),
      'modal-popup'       => array(
        'slug'     => 'modal_popup',
        'label'    => esc_attr__( 'Modal Popup', 'taskbuilder' ),
        'callback' => 'wppm_get_ap_modal_popup',
      ),
      'grid-view'   => array(
        'slug'     => 'grid_view',
        'label'    => esc_attr__( 'Grid View', 'taskbuilder' ),
        'callback' => 'wppm_get_ap_grid_view',
      ),
      'settings'   => array(
        'slug'     => 'settings',
        'label'    => esc_attr__( 'Settings', 'taskbuilder' ),
        'callback' => 'wppm_get_ap_settings',
      )
    )
  );
?>
<div class="wppm-setting-tab-container">
  <?php
  foreach ( $tabs as $key => $tab ) :
    $active = $current_tab === $key ? 'active' : ''
    ?>
    <button 
      class="<?php echo esc_attr( $key ) . ' ' . esc_attr( $active ); ?>"
      onclick="<?php echo esc_attr( $tab['callback'] ) . "('".$key."');"; ?>">
      <?php echo esc_attr( $tab['label'] ); ?>
      </button>
    <?php
  endforeach;
  ?>
</div>
<div class="row">
  <div class="col-sm-12 wppm-setting-section-body">
  </div>
</div>
<script>
  jQuery(document).ready(function(){
      wppm_get_ap_proj_list('project-list');
  });
</script>
<?php
wp_die();?>