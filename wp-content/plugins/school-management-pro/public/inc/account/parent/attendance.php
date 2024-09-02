<?php
defined( 'ABSPATH' ) || die();

$student = WLSM_M_Parent::fetch_student( $student_id );
$school_id  = $student->school_id;
if ( ! $student ) {
	die;
}
?>

<div class="wlsm-parent-student-entity">
<?php
require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/parent/partials/student.php';
?>
</div>

<?php
require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/attendance.php';
