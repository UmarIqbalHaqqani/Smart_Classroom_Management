<?php
defined( 'ABSPATH' ) || die();
?>
<hr>
<a href="<?php echo esc_url( add_query_arg( array(), $current_page_url ) ); ?>" class="wlsm-mb-3 wlsm-font-bold">
	&#8592;&nbsp;<?php esc_html_e( 'Back', 'school-management' ); ?>
</a>
<?php require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/partials/student_detail.php'; ?>
<hr>
