<?php
defined( 'ABSPATH' ) || die();

$school = NULL;
if ( isset( $attr['school_id'] ) ) {
	$school_id = absint( $attr['school_id'] );
	$school    = WLSM_M_School::get_active_school( $school_id );
}

if ( ! $school ) {
	$invalid_message = esc_html__( 'School not found.', 'school-management' );
	return require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/partials/invalid.php';
}

?>
<div class="wlsm-shortcode-entity">
<?php require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/noticeboard.php'; ?>
</div>
<?php
return ob_get_clean();
