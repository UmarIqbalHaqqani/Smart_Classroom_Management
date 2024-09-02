<?php
defined( 'ABSPATH' ) || die();

$student = WLSM_M_Parent::fetch_student( $student_id );

if ( ! $student ) {
	die;
}

$class_school_id = $student->class_school_id;

$school_id = $student->school_id;
?>

<div class="wlsm-parent-student-entity">
<?php
require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/partials/student.php';
?>
</div>

<?php
require_once WLSM_PLUGIN_DIR_PATH . 'includes/partials/noticeboard.php';
