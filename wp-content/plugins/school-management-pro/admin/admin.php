<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/constants.php';

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_Log.php';

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/WLSM_Menu.php';

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/WLSM_Bulk_Action.php';

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/WLSM_School.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/WLSM_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/WLSM_Category.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/WLSM_Session.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/manager/WLSM_Setting.php';

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/WLSM_Staff_School.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/WLSM_Staff_Export.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/WLSM_Staff_Import.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/WLSM_Staff_Chart.php';

require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/class/WLSM_Staff_Class.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/WLSM_Staff_General.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/accountant/WLSM_Staff_Accountant.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/examination/WLSM_Staff_Examination.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/library/WLSM_Staff_Library.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/transport/WLSM_Staff_Transport.php';
require_once WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/lectures/WLSM_Lectures.php';
add_filter('doing_it_wrong_trigger_error', function () {return false;}, 10, 0);
add_action( 'admin_menu', array( 'WLSM_Menu', 'create_menu' ) );

// Manager: Schools.
add_action( 'wp_ajax_wlsm-fetch-schools', array( 'WLSM_School', 'fetch_schools' ) );
add_action( 'wp_ajax_wlsm-save-school', array( 'WLSM_School', 'save' ) );
add_action( 'wp_ajax_wlsm-delete-school', array( 'WLSM_School', 'delete' ) );
add_action( 'wp_ajax_wlsm-fetch-school-classes', array( 'WLSM_School', 'fetch_school_classes' ) );
add_action( 'wp_ajax_wlsm-delete-school-class', array( 'WLSM_School', 'delete_school_class' ) );
add_action( 'wp_ajax_wlsm-get-keyword-classes', array( 'WLSM_School', 'get_keyword_classes' ) );
add_action( 'wp_ajax_wlsm-assign-classes', array( 'WLSM_School', 'assign_classes' ) );
add_action( 'wp_ajax_wlsm-fetch-school-admins', array( 'WLSM_School', 'fetch_school_admins' ) );
add_action( 'wp_ajax_wlsm-assign-admin', array( 'WLSM_School', 'assign_admin' ) );
add_action( 'wp_ajax_wlsm-delete-school-admin', array( 'WLSM_School', 'delete_school_admin' ) );
add_action( 'wp_ajax_wlsm-edit-school-admin', array( 'WLSM_School', 'save_school_admin' ) );
add_action( 'wp_ajax_wlsm-set-school', array( 'WLSM_School', 'set_school' ) );

// Manager: Classes.
add_action( 'wp_ajax_wlsm-fetch-classes', array( 'WLSM_Class', 'fetch_classes' ) );
add_action( 'wp_ajax_wlsm-save-class', array( 'WLSM_Class', 'save' ) );
add_action( 'wp_ajax_wlsm-delete-class', array( 'WLSM_Class', 'delete' ) );

// Manager: Category.
add_action( 'wp_ajax_wlsm-fetch-category', array( 'WLSM_Category', 'fetch_category' ) );
add_action( 'wp_ajax_wlsm-save-category', array( 'WLSM_Category', 'save' ) );
add_action( 'wp_ajax_wlsm-delete-category', array( 'WLSM_Category', 'delete' ) );

// Manager: Sessions.
add_action( 'wp_ajax_wlsm-fetch-sessions', array( 'WLSM_Session', 'fetch_sessions' ) );
add_action( 'wp_ajax_wlsm-save-session', array( 'WLSM_Session', 'save' ) );
add_action( 'wp_ajax_wlsm-delete-session', array( 'WLSM_Session', 'delete' ) );

// Manager: Settings.
add_action( 'wp_ajax_wlsm-save-general-settings', array( 'WLSM_Setting', 'save_general_settings' ) );
add_action( 'wp_ajax_wlsm-reset-plugin', array( 'WLSM_Setting', 'reset_plugin' ) );
add_action( 'wp_ajax_wlsm-save-uninstall-settings', array( 'WLSM_Setting', 'save_uninstall_settings' ) );

// Staff: Set school.
add_action( 'wp_ajax_wlsm-staff-set-school', array( 'WLSM_Staff_School', 'set_school' ) );

// Staff: Set session.
add_action( 'wp_ajax_wlsm-staff-set-session', array( 'WLSM_Staff_School', 'set_session' ) );

// Staff: Classes & Sections.
add_action( 'wp_ajax_wlsm-fetch-staff-classes', array( 'WLSM_Staff_Class', 'fetch_classes' ) );
add_action( 'wp_ajax_wlsm-fetch-class-sections', array( 'WLSM_Staff_Class', 'fetch_class_sections' ) );
add_action( 'wp_ajax_wlsm-save-section', array( 'WLSM_Staff_Class', 'save_section' ) );
add_action( 'wp_ajax_wlsm-delete-section', array( 'WLSM_Staff_Class', 'delete_section' ) );

add_action( 'wp_ajax_wlsm-fetch-staff-classes', array( 'WLSM_Staff_Class', 'fetch_classes' ) );
add_action( 'wp_ajax_wlsm-fetch-class-medium', array( 'WLSM_Staff_Class', 'fetch_class_medium' ) );
add_action( 'wp_ajax_wlsm-save-medium', array( 'WLSM_Staff_Class', 'save_medium' ) );
add_action( 'wp_ajax_wlsm-delete-medium', array( 'WLSM_Staff_Class', 'delete_medium' ) );

add_action( 'wp_ajax_wlsm-fetch-class-student-type', array( 'WLSM_Staff_Class', 'fetch_class_student_type' ) );
add_action( 'wp_ajax_wlsm-save-student-type', array( 'WLSM_Staff_Class', 'save_student_type' ) );
add_action( 'wp_ajax_wlsm-delete-student-type', array( 'WLSM_Staff_Class', 'delete_student_type' ) );

add_action( 'wp_ajax_wlsm-save-subject-type', array( 'WLSM_Staff_Class', 'save_subject_type' ) );
add_action( 'wp_ajax_wlsm-fetch-subject-type', array( 'WLSM_Staff_Class', 'fetch_subject_type' ) );
add_action( 'wp_ajax_wlsm-delete-subject-type', array( 'WLSM_Staff_Class', 'delete_subject_type' ) );

// Staff: Admissions.
add_action( 'wp_ajax_wlsm-add-admission', array( 'WLSM_Staff_General', 'add_admission' ) );

// Staff: Students.
add_action( 'wp_ajax_wlsm-edit-student', array( 'WLSM_Staff_General', 'edit_student' ) );
add_action( 'wp_ajax_wlsm-get-students', array( 'WLSM_Staff_General', 'get_students' ) );
add_action( 'wp_ajax_wlsm-delete-student', array( 'WLSM_Staff_General', 'delete_student' ) );
add_action( 'wp_ajax_wlsm-view-session-records', array( 'WLSM_Staff_General', 'view_session_records' ) );
add_action( 'wp_ajax_wlsm-print-id-card', array( 'WLSM_Staff_General', 'print_id_card' ) );
add_action( 'wp_ajax_wlsm-student-id', array( 'WLSM_Staff_General', 'print_student_proof_id' ) );
add_action( 'wp_ajax_wlsm-print-bulk-id-cards', array( 'WLSM_Staff_General', 'print_id_cards' ) );
add_action( 'wp_ajax_wlsm-print-fee-structure', array( 'WLSM_Staff_General', 'print_fee_structure' ) );
add_action( 'wp_ajax_wlsm-view-attendance-report', array( 'WLSM_Staff_General', 'view_attendance_report' ) );
add_action('wp_ajax_wlsm-view-student-detail', array('WLSM_Staff_General', 'view_student_detail'));

add_action( 'wp_ajax_wlsm-fetch-student-birthdays', array( 'WLSM_Staff_Accountant', 'fetch_student_birthdays' ) );

// Staff: Bulk Import Students.
add_action( 'wp_ajax_wlsm-student-sample-csv-export', array( 'WLSM_Staff_Export', 'student_sample_csv_export' ) );
add_action( 'wp_ajax_wlsm-bulk-import-student', array( 'WLSM_Staff_Import', 'bulk_import_student' ) );

// Staff: Bulk Import books.
add_action( 'wp_ajax_wlsm-books-sample-csv-export', array( 'WLSM_Staff_Export', 'books_sample_csv_export' ) );
add_action( 'wp_ajax_wlsm-bulk-import-books', array( 'WLSM_Staff_Import', 'bulk_import_books' ) );

// Staff: Bulk Import staff.
add_action( 'wp_ajax_wlsm-staff-sample-csv-export', array( 'WLSM_Staff_Export', 'staff_sample_csv_export' ) );
add_action( 'wp_ajax_wlsm-bulk-import-staff', array( 'WLSM_Staff_Import', 'bulk_import_staff' ) );

// Staff: Student Attendance.
add_action( 'wp_ajax_wlsm-manage-attendance', array( 'WLSM_Staff_Class', 'manage_attendance' ) );
add_action( 'wp_ajax_wlsm-take-attendance', array( 'WLSM_Staff_Class', 'take_attendance' ) );
add_action( 'wp_ajax_wlsm-view-attendance', array( 'WLSM_Staff_Class', 'view_attendance' ) );

// Staff: Employee Attendance.
add_action( 'wp_ajax_wlsm-manage-staff-attendance', array( 'WLSM_Staff_General', 'manage_staff_attendance' ) );
add_action( 'wp_ajax_wlsm-take-staff-attendance', array( 'WLSM_Staff_General', 'take_staff_attendance' ) );
add_action( 'wp_ajax_wlsm-view-staff-attendance', array( 'WLSM_Staff_General', 'view_staff_attendance' ) );

// Staff: Promote.
add_action( 'wp_ajax_wlsm-manage-promotion', array( 'WLSM_Staff_General', 'manage_promotion' ) );
add_action( 'wp_ajax_wlsm-promote-student', array( 'WLSM_Staff_General', 'promote_student' ) );

// Staff: Transfer student.
add_action( 'wp_ajax_wlsm-transfer-student', array( 'WLSM_Staff_General', 'transfer_student' ) );

// Staff: Transferred to Other School.
add_action( 'wp_ajax_wlsm-fetch-transferred-to-school', array( 'WLSM_Staff_General', 'fetch_transferred_to_school' ) );
add_action( 'wp_ajax_wlsm-view-transferred-to-note', array( 'WLSM_Staff_General', 'view_transferred_to_note' ) );
add_action( 'wp_ajax_wlsm-delete-transferred-to', array( 'WLSM_Staff_General', 'delete_transferred_to' ) );

// Staff: Transferred to this School.
add_action( 'wp_ajax_wlsm-fetch-transferred-from-school', array( 'WLSM_Staff_General', 'fetch_transferred_from_school' ) );
add_action( 'wp_ajax_wlsm-view-transferred-from-note', array( 'WLSM_Staff_General', 'view_transferred_from_note' ) );
add_action( 'wp_ajax_wlsm-delete-transferred-from', array( 'WLSM_Staff_General', 'delete_transferred_from' ) );

// Staff: Admins.
add_action( 'wp_ajax_wlsm-fetch-staff-admin', array( 'WLSM_Staff_General', 'fetch_admins' ) );
add_action( 'wp_ajax_wlsm-save-admin', array( 'WLSM_Staff_General', 'save_admin' ) );
add_action( 'wp_ajax_wlsm-delete-admin', array( 'WLSM_Staff_General', 'delete_admin' ) );

// Staff: Roles.
add_action( 'wp_ajax_wlsm-fetch-roles', array( 'WLSM_Staff_General', 'fetch_roles' ) );
add_action( 'wp_ajax_wlsm-save-role', array( 'WLSM_Staff_General', 'save_role' ) );
add_action( 'wp_ajax_wlsm-delete-role', array( 'WLSM_Staff_General', 'delete_role' ) );
add_action( 'wp_ajax_wlsm-get-role-permissions', array( 'WLSM_Staff_General', 'get_role_permissions' ) );

// Staff: Certificates.
add_action( 'wp_ajax_wlsm-fetch-certificates', array( 'WLSM_Staff_General', 'fetch_certificates' ) );
add_action( 'wp_ajax_wlsm-save-certificate', array( 'WLSM_Staff_General', 'save_certificate' ) );
add_action( 'wp_ajax_wlsm-delete-certificate', array( 'WLSM_Staff_General', 'delete_certificate' ) );
add_action( 'wp_ajax_wlsm-distribute-certificate', array( 'WLSM_Staff_General', 'distribute_certificate' ) );
add_action( 'wp_ajax_wlsm-fetch-certificates-distributed', array( 'WLSM_Staff_General', 'fetch_certificates_distributed' ) );
add_action( 'wp_ajax_wlsm-delete-certificate-distributed', array( 'WLSM_Staff_General', 'delete_certificate_distributed' ) );

// Staff: Notifications.
add_action( 'wp_ajax_wlsm-send-notification', array( 'WLSM_Staff_General', 'send_notification' ) );

// Staff: Inquiries.
add_action( 'wp_ajax_wlsm-fetch-inquiries', array( 'WLSM_Staff_General', 'fetch_inquiries' ) );
add_action( 'wp_ajax_wlsm-save-inquiry', array( 'WLSM_Staff_General', 'save_inquiry' ) );
add_action( 'wp_ajax_wlsm-delete-inquiry', array( 'WLSM_Staff_General', 'delete_inquiry' ) );
add_action( 'wp_ajax_wlsm-view-inquiry-message', array( 'WLSM_Staff_General', 'view_inquiry_message' ) );

// Staff: Notices.
add_action( 'wp_ajax_wlsm-fetch-notices', array( 'WLSM_Staff_Class', 'fetch_notices' ) );
add_action( 'wp_ajax_wlsm-save-notice', array( 'WLSM_Staff_Class', 'save_notice' ) );
add_action( 'wp_ajax_wlsm-delete-notice', array( 'WLSM_Staff_Class', 'delete_notice' ) );

// Staff: Events.
add_action( 'wp_ajax_wlsm-fetch-events', array( 'WLSM_Staff_Class', 'fetch_events' ) );
add_action( 'wp_ajax_wlsm-save-event', array( 'WLSM_Staff_Class', 'save_event' ) );
add_action( 'wp_ajax_wlsm-delete-event', array( 'WLSM_Staff_Class', 'delete_event' ) );
add_action( 'wp_ajax_wlsm-fetch-event-participants', array( 'WLSM_Staff_Class', 'fetch_event_participants' ) );
add_action( 'wp_ajax_wlsm-delete-event-participant', array( 'WLSM_Staff_Class', 'delete_event_participant' ) );

// Staff: Subjects.

add_action( 'wp_ajax_wlsm-fetch-class-subjects', array( 'WLSM_Staff_Class', 'fetch_subjects_by_class' ) );
add_action( 'wp_ajax_wlsm-fetch-subjects', array( 'WLSM_Staff_Class', 'fetch_subjects' ) );
add_action( 'wp_ajax_wlsm-save-subject', array( 'WLSM_Staff_Class', 'save_subject' ) );
add_action( 'wp_ajax_wlsm-delete-subject', array( 'WLSM_Staff_Class', 'delete_subject' ) );
add_action( 'wp_ajax_wlsm-fetch-subject-admins', array( 'WLSM_Staff_Class', 'fetch_subject_admins' ) );
add_action( 'wp_ajax_wlsm-delete-subject-admin', array( 'WLSM_Staff_Class', 'delete_subject_admin' ) );
add_action( 'wp_ajax_wlsm-get-keyword-admins', array( 'WLSM_Staff_Class', 'get_keyword_admins' ) );
add_action( 'wp_ajax_wlsm-assign-subject-admins', array( 'WLSM_Staff_Class', 'assign_subject_admins' ) );

// Staff: Timetable.
add_action( 'wp_ajax_wlsm-fetch-timetable', array( 'WLSM_Staff_Class', 'fetch_timetable' ) );
add_action( 'wp_ajax_wlsm-delete-timetable', array( 'WLSM_Staff_Class', 'delete_timetable' ) );
add_action( 'wp_ajax_wlsm-save-routine', array( 'WLSM_Staff_Class', 'save_routine' ) );
add_action( 'wp_ajax_wlsm-delete-routine', array( 'WLSM_Staff_Class', 'delete_routine' ) );

// staff member timetable
add_action( 'wp_ajax_wlsm-fetch-staff-timetable', array( 'WLSM_Staff_Class', 'fetch_staff_timetable' ) );

// Staff: Meetings.
add_action( 'wp_ajax_wlsm-fetch-meetings', array( 'WLSM_Staff_Class', 'fetch_meetings' ) );
add_action( 'wp_ajax_wlsm-save-meeting', array( 'WLSM_Staff_Class', 'save_meeting' ) );
add_action( 'wp_ajax_wlsm-delete-meeting', array( 'WLSM_Staff_Class', 'delete_meeting' ) );
add_action( 'wp_ajax_wlsm-fetch-staff-meetings', array( 'WLSM_Staff_Class', 'fetch_staff_meetings' ) );

add_action( 'wp_ajax_wlsm-fetch-ratting', array( 'WLSM_Staff_Class', 'fetch_ratting' ) );

// Staff: Exams.
add_action( 'wp_ajax_wlsm-fetch-exams', array( 'WLSM_Staff_Examination', 'fetch_exams' ) );
add_action( 'wp_ajax_wlsm-save-exam', array( 'WLSM_Staff_Examination', 'save_exam' ) );
add_action( 'wp_ajax_wlsm-save-exam-group', array( 'WLSM_Staff_Examination', 'save_exam_group' ) );
add_action( 'wp_ajax_wlsm-fetch-exams-group', array( 'WLSM_Staff_Examination', 'fetch_exams_group' ) );
add_action( 'wp_ajax_wlsm-delete-exam-group', array( 'WLSM_Staff_Examination', 'delete_exams_group' ) );
add_action( 'wp_ajax_wlsm-delete-exam', array( 'WLSM_Staff_Examination', 'delete_exam' ) );
add_action( 'wp_ajax_wlsm-view-exam-time-table', array( 'WLSM_Staff_Examination', 'view_exam_time_table' ) );
add_action( 'wp_ajax_wlsm-fetch-exams-admit-cards', array( 'WLSM_Staff_Examination', 'fetch_exams_admit_cards' ) );
add_action( 'wp_ajax_wlsm-fetch-exams-results', array( 'WLSM_Staff_Examination', 'fetch_exams_results' ) );
add_action( 'wp_ajax_wlsm-save-report', array( 'WLSM_Staff_Examination', 'save_academic_report' ) );
add_action( 'wp_ajax_wlsm-fetch-academic-report', array( 'WLSM_Staff_Examination', 'fetch_academic_report' ) );
add_action( 'wp_ajax_wlsm-delete-academic-report', array( 'WLSM_Staff_Examination', 'delete_academic_report' ) );

// Staff: Exam Admit Cards.
add_action( 'wp_ajax_wlsm-fetch-exam-admit-cards', array( 'WLSM_Staff_Examination', 'fetch_exam_admit_cards' ) );
add_action( 'wp_ajax_wlsm-print-exam-admit-card', array( 'WLSM_Staff_Examination', 'print_exam_admit_card' ) );
add_action( 'wp_ajax_wlsm-print-bulk-admit-cards', array( 'WLSM_Staff_General', 'print_admit_cards' ) );
add_action( 'wp_ajax_wlsm-generate-admit-cards', array( 'WLSM_Staff_Examination', 'generate_admit_cards' ) );
add_action( 'wp_ajax_wlsm-delete-exam-admit-card', array( 'WLSM_Staff_Examination', 'delete_admit_card' ) );
add_action( 'wp_ajax_wlsm-save-exam-admit-card', array( 'WLSM_Staff_Examination', 'save_admit_card' ) );

// Staff: Exam Results.
add_action( 'wp_ajax_wlsm-fetch-exam-results', array( 'WLSM_Staff_Examination', 'fetch_exam_results' ) );
add_action( 'wp_ajax_wlsm-save-exam-results', array( 'WLSM_Staff_Examination', 'save_exam_results' ) );
add_action( 'wp_ajax_wlsm-delete-exam-results', array( 'WLSM_Staff_Examination', 'delete_exam_results' ) );
add_action( 'wp_ajax_wlsm-print-exam-results', array( 'WLSM_Staff_Examination', 'print_exam_results' ) );
add_action( 'wp_ajax_wlsm-print-bulk-result', array( 'WLSM_Staff_Examination', 'bulk_print_result' ) );

// Staff: Bulk Import Exam Results.
add_action( 'wp_ajax_wlsm-exam-results-csv-export', array( 'WLSM_Staff_Export', 'exam_results_csv_export' ) );
add_action( 'wp_ajax_wlsm-bulk-import-exam-results', array( 'WLSM_Staff_Import', 'bulk_import_exam_results' ) );

// Staff: Bulk Import Attendance.
add_action( 'wp_ajax_wlsm-attendance-csv-export', array( 'WLSM_Staff_Export', 'attendance_csv_export' ) );
add_action( 'wp_ajax_wlsm-upload-attendance', array( 'WLSM_Staff_Import', 'bulk_import_attendance' ) );

// Staff: Exam Results Assessment.
add_action( 'wp_ajax_wlsm-get-results-assessment', array( 'WLSM_Staff_Examination', 'get_results_assessment' ) );
add_action( 'wp_ajax_wlsm-get-result-assessment', array( 'WLSM_Staff_Examination', 'get_result_assessment' ) );
add_action( 'wp_ajax_wlsm-get-result-subject-wise', array( 'WLSM_Staff_Examination', 'get_result_subject_wise' ) );
add_action( 'wp_ajax_wlsm-get-result-bulk', array( 'WLSM_Staff_Examination', 'get_result_bulk' ) );

add_action( 'wp_ajax_wlsm-get-academic-report', array( 'WLSM_Staff_Examination', 'get_academic_report' ) );

add_action( 'wp_ajax_wlsm-get-result-subject-wise-front', array( 'WLSM_Staff_Examination', 'result_subject_wise' ) );

// Staff: Study Materials.
add_action( 'wp_ajax_wlsm-fetch-study-materials', array( 'WLSM_Staff_Class', 'fetch_study_materials' ) );
add_action( 'wp_ajax_wlsm-save-study-material', array( 'WLSM_Staff_Class', 'save_study_material' ) );
add_action( 'wp_ajax_wlsm-delete-study-material', array( 'WLSM_Staff_Class', 'delete_study_material' ) );

// Staff: Homework.
add_action( 'wp_ajax_wlsm-fetch-homeworks', array( 'WLSM_Staff_Class', 'fetch_homeworks' ) );
add_action('wp_ajax_wlsm-fetch-student-homeworks', array( 'WLSM_Staff_Class', 'fetch_homeworks_submission' ) );
add_action( 'wp_ajax_wlsm-save-homework', array( 'WLSM_Staff_Class', 'save_homework' ) );
add_action( 'wp_ajax_wlsm-delete-homework', array( 'WLSM_Staff_Class', 'delete_homework' ) );

// Staff: Employees.
add_action( 'wp_ajax_wlsm-fetch-staff-employee', array( 'WLSM_Staff_General', 'fetch_employees' ) );
add_action( 'wp_ajax_wlsm-save-employee', array( 'WLSM_Staff_General', 'save_employee' ) );
add_action( 'wp_ajax_wlsm-delete-employee', array( 'WLSM_Staff_General', 'delete_employee' ) );
add_action( 'wp_ajax_wlsm-view-staff-attendance-report', array( 'WLSM_Staff_General', 'view_staff_attendance_report' ) );

// Staff: Settings.
add_action( 'wp_ajax_wlsm-save-school-general-settings', array( 'WLSM_Staff_General', 'save_school_general_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-email-carrier-settings', array( 'WLSM_Staff_General', 'save_school_email_carrier_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-email-templates-settings', array( 'WLSM_Staff_General', 'save_school_email_templates_settings' ) );
add_action( 'wp_ajax_wlsm-send-test-email', array( 'WLSM_Staff_General', 'send_test_email' ) );
add_action( 'wp_ajax_wlsm-save-school-sms-carrier-settings', array( 'WLSM_Staff_General', 'save_school_sms_carrier_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-sms-templates-settings', array( 'WLSM_Staff_General', 'save_school_sms_templates_settings' ) );
add_action( 'wp_ajax_wlsm-send-test-sms', array( 'WLSM_Staff_General', 'send_test_sms' ) );
add_action( 'wp_ajax_wlsm-save-school-payment-method-settings', array( 'WLSM_Staff_General', 'save_school_payment_method_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-inquiry-settings', array( 'WLSM_Staff_General', 'save_school_inquiry_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-registration-settings', array( 'WLSM_Staff_General', 'save_school_registration_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-dashboard-settings', array( 'WLSM_Staff_General', 'save_school_dashboard_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-charts-settings', array( 'WLSM_Staff_General', 'save_school_charts_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-zoom-settings', array( 'WLSM_Staff_General', 'save_school_zoom_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-url-settings', array( 'WLSM_Staff_General', 'save_school_url_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-logs-settings', array( 'WLSM_Staff_General', 'save_school_logs_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-lessons-settings', array( 'WLSM_Staff_General', 'save_school_lessons_settings' ) );
add_action( 'wp_ajax_wlsm-save-school-card-backgrounds-settings', array( 'WLSM_Staff_General', 'save_school_backgrounds_settings' ) );

// staff : invoice report
add_action( 'wp_ajax_wlsm-get-invoices-report', array( 'WLSM_Staff_Accountant', 'get_invoices_report' ) );
add_action( 'wp_ajax_wlsm-get-fees-total', array( 'WLSM_Staff_Accountant', 'get_invoices_report_total' ) );

// Staff: Invoices.
add_action( 'wp_ajax_wlsm-get-invoices', array( 'WLSM_Staff_Accountant', 'get_invoices' ) );
add_action( 'wp_ajax_wlsm-save-invoice', array( 'WLSM_Staff_Accountant', 'save_invoice' ) );
add_action( 'wp_ajax_wlsm-delete-invoice', array( 'WLSM_Staff_Accountant', 'delete_invoice' ) );
add_action( 'wp_ajax_wlsm-print-invoice', array( 'WLSM_Staff_Accountant', 'print_invoice' ) );
add_action( 'wp_ajax_wlsm-print-bulk-invoices', array( 'WLSM_Staff_Accountant', 'print_bulk_invoices' ) );
add_action( 'wp_ajax_wlsm-print-invoice-fee-structure', array( 'WLSM_Staff_Accountant', 'print_invoice_fee_structure' ) );
add_action( 'wp_ajax_wlsm-invoice-auto-generate', array( 'WLSM_Staff_Accountant', 'invoice_fee_auto_generate' ) );

// Staff: Invoice Payments.
add_action( 'wp_ajax_wlsm-fetch-invoice-payments', array( 'WLSM_Staff_Accountant', 'fetch_invoice_payments' ) );
add_action( 'wp_ajax_wlsm-collect-invoice-payment', array( 'WLSM_Staff_Accountant', 'collect_invoice_payment' ) );
add_action( 'wp_ajax_wlsm-delete-invoice-payment', array( 'WLSM_Staff_Accountant', 'delete_invoice_payment' ) );
add_action( 'wp_ajax_wlsm-print-invoice-payment', array( 'WLSM_Staff_Accountant', 'print_payment' ) );

// Staff: Payments.
add_action( 'wp_ajax_wlsm-fetch-pending-payments', array( 'WLSM_Staff_Accountant', 'fetch_pending_payments' ) );
add_action( 'wp_ajax_wlsm-delete-pending-payment', array( 'WLSM_Staff_Accountant', 'delete_pending_payment' ) );
add_action( 'wp_ajax_wlsm-approve-pending-payment', array( 'WLSM_Staff_Accountant', 'approve_pending_payment' ) );
add_action( 'wp_ajax_wlsm-fetch-payments', array( 'WLSM_Staff_Accountant', 'fetch_payments' ) );
add_action( 'wp_ajax_wlsm-delete-payment', array( 'WLSM_Staff_Accountant', 'delete_payment' ) );
add_action( 'wp_ajax_wlsm-view-payment-note', array( 'WLSM_Staff_Accountant', 'view_payment_note' ) );

// Staff: Expenses.
add_action( 'wp_ajax_wlsm-fetch-expense-categories', array( 'WLSM_Staff_Accountant', 'fetch_expense_categories' ) );
add_action( 'wp_ajax_wlsm-save-expense-category', array( 'WLSM_Staff_Accountant', 'save_expense_category' ) );
add_action( 'wp_ajax_wlsm-delete-expense-category', array( 'WLSM_Staff_Accountant', 'delete_expense_category' ) );
add_action( 'wp_ajax_wlsm-fetch-expenses', array( 'WLSM_Staff_Accountant', 'fetch_expenses' ) );
add_action( 'wp_ajax_wlsm-save-expense', array( 'WLSM_Staff_Accountant', 'save_expense' ) );
add_action( 'wp_ajax_wlsm-delete-expense', array( 'WLSM_Staff_Accountant', 'delete_expense' ) );
add_action( 'wp_ajax_wlsm-view-expense-note', array( 'WLSM_Staff_Accountant', 'view_expense_note' ) );

// Staff: Income.
add_action( 'wp_ajax_wlsm-fetch-income-categories', array( 'WLSM_Staff_Accountant', 'fetch_income_categories' ) );
add_action( 'wp_ajax_wlsm-save-income-category', array( 'WLSM_Staff_Accountant', 'save_income_category' ) );
add_action( 'wp_ajax_wlsm-delete-income-category', array( 'WLSM_Staff_Accountant', 'delete_income_category' ) );
add_action( 'wp_ajax_wlsm-fetch-income', array( 'WLSM_Staff_Accountant', 'fetch_income' ) );
add_action( 'wp_ajax_wlsm-save-income', array( 'WLSM_Staff_Accountant', 'save_income' ) );
add_action( 'wp_ajax_wlsm-delete-income', array( 'WLSM_Staff_Accountant', 'delete_income' ) );
add_action( 'wp_ajax_wlsm-view-income-note', array( 'WLSM_Staff_Accountant', 'view_income_note' ) );

// Staff: Fee Types.
add_action( 'wp_ajax_wlsm-fetch-fees', array( 'WLSM_Staff_Accountant', 'fetch_fees' ) );
add_action( 'wp_ajax_wlsm-save-fee', array( 'WLSM_Staff_Accountant', 'save_fee' ) );
add_action( 'wp_ajax_wlsm-delete-fee', array( 'WLSM_Staff_Accountant', 'delete_fee' ) );

// Staff: Library.
add_action( 'wp_ajax_wlsm-fetch-books', array( 'WLSM_Staff_Library', 'fetch_books' ) );
add_action( 'wp_ajax_wlsm-save-book', array( 'WLSM_Staff_Library', 'save_book' ) );
add_action( 'wp_ajax_wlsm-delete-book', array( 'WLSM_Staff_Library', 'delete_book' ) );
add_action( 'wp_ajax_wlsm-issue-book', array( 'WLSM_Staff_Library', 'issue_book' ) );
add_action( 'wp_ajax_wlsm-fetch-books-issued', array( 'WLSM_Staff_Library', 'fetch_books_issued' ) );
add_action( 'wp_ajax_wlsm-delete-book-issued', array( 'WLSM_Staff_Library', 'delete_book_issued' ) );
add_action( 'wp_ajax_wlsm-mark-book-as-returned', array( 'WLSM_Staff_Library', 'mark_book_as_returned' ) );
add_action( 'wp_ajax_wlsm-fetch-library-cards', array( 'WLSM_Staff_Library', 'fetch_library_cards' ) );
add_action( 'wp_ajax_wlsm-manage-library-cards', array( 'WLSM_Staff_Library', 'manage_library_cards' ) );
add_action( 'wp_ajax_wlsm-issue-library-cards', array( 'WLSM_Staff_Library', 'issue_library_cards' ) );
add_action( 'wp_ajax_wlsm-delete-library-card', array( 'WLSM_Staff_Library', 'delete_library_card' ) );
add_action( 'wp_ajax_wlsm-print-library-card', array( 'WLSM_Staff_Library', 'print_library_card' ) );
add_action( 'wp_ajax_wlsm-view-library-card', array( 'WLSM_Staff_Library', 'view_library_card' ) );

// Staff: Transport.
add_action( 'wp_ajax_wlsm-fetch-vehicles', array( 'WLSM_Staff_Transport', 'fetch_vehicles' ) );
add_action( 'wp_ajax_wlsm-save-vehicle', array( 'WLSM_Staff_Transport', 'save_vehicle' ) );
add_action( 'wp_ajax_wlsm-delete-vehicle', array( 'WLSM_Staff_Transport', 'delete_vehicle' ) );
add_action( 'wp_ajax_wlsm-fetch-routes', array( 'WLSM_Staff_Transport', 'fetch_routes' ) );
add_action( 'wp_ajax_wlsm-save-route', array( 'WLSM_Staff_Transport', 'save_route' ) );
add_action( 'wp_ajax_wlsm-delete-route', array( 'WLSM_Staff_Transport', 'delete_route' ) );
add_action( 'wp_ajax_wlsm-get-transport-report', array( 'WLSM_Staff_Transport', 'get_transport_report' ) );

// Staff: Logs.
add_action( 'wp_ajax_wlsm-fetch-logs', array( 'WLSM_Staff_General', 'fetch_logs' ) );

// Staff: Leave Request.
add_action( 'wp_ajax_wlsm-fetch-staff-leave-requests', array( 'WLSM_Staff_General', 'fetch_staff_leave_requests' ) );
add_action( 'wp_ajax_wlsm-submit-staff-leave-request', array( 'WLSM_Staff_General', 'submit_staff_leave_request' ) );

// Staff: Student Leaves.
add_action( 'wp_ajax_wlsm-fetch-student-leaves', array( 'WLSM_Staff_Class', 'fetch_student_leaves' ) );
add_action( 'wp_ajax_wlsm-save-student-leave', array( 'WLSM_Staff_Class', 'save_student_leave' ) );
add_action( 'wp_ajax_wlsm-delete-student-leave', array( 'WLSM_Staff_Class', 'delete_student_leave' ) );

// Staff: Student Activity.
add_action( 'wp_ajax_wlsm-fetch-student-activity', array( 'WLSM_Staff_Class', 'fetch_student_activity' ) );
add_action( 'wp_ajax_wlsm-save-student-activity', array( 'WLSM_Staff_Class', 'save_student_activity' ) );
add_action( 'wp_ajax_wlsm-delete-student-activity', array( 'WLSM_Staff_Class', 'delete_student_activity' ) );

// Staff: Employees Leaves.
add_action( 'wp_ajax_wlsm-fetch-staff-leaves', array( 'WLSM_Staff_General', 'fetch_staff_leaves' ) );
add_action( 'wp_ajax_wlsm-save-staff-leave', array( 'WLSM_Staff_General', 'save_staff_leave' ) );
add_action( 'wp_ajax_wlsm-delete-staff-leave', array( 'WLSM_Staff_General', 'delete_staff_leave' ) );

// Staff: Dashboard.
add_action( 'wp_ajax_wlsm-fetch-stats-payments', array( 'WLSM_Staff_General', 'fetch_stats_payments' ) );

// Staff: Dashboard - Charts.
add_action( 'wp_ajax_wlsm-fetch-monthly-admissions', array( 'WLSM_Staff_Chart', 'fetch_monthly_admissions' ) );
add_action( 'wp_ajax_wlsm-fetch-monthly-payments', array( 'WLSM_Staff_Chart', 'fetch_monthly_payments' ) );
add_action( 'wp_ajax_wlsm-fetch-monthly-income-expense', array( 'WLSM_Staff_Chart', 'fetch_monthly_income_expense' ) );

// Staff: Export.
add_action( 'wp_ajax_wlsm-export-staff-students-table', array( 'WLSM_Staff_Export', 'export_staff_students_table' ) );
add_action( 'wp_ajax_wlsm-export-staff-inquiries-table', array( 'WLSM_Staff_Export', 'export_staff_inquiries_table' ) );
add_action( 'wp_ajax_wlsm-export-staff-invoices-table', array( 'WLSM_Staff_Export', 'export_staff_invoices_table' ) );
add_action( 'wp_ajax_wlsm-export-staff-expenses-table', array( 'WLSM_Staff_Export', 'export_staff_expenses_table' ) );
add_action( 'wp_ajax_wlsm-export-staff-income-table', array( 'WLSM_Staff_Export', 'export_staff_income_table' ) );
add_action( 'wp_ajax_wlsm-export-staff-event-participants-table', array( 'WLSM_Staff_Export', 'export_staff_event_participants_table' ) );
add_action( 'wp_ajax_wlsm-export-staff-payments-table', array( 'WLSM_Staff_Export', 'export_staff_payments_table' ) );


// Staff: General Actions.
add_action( 'wp_ajax_wlsm-get-class-sections', array( 'WLSM_Staff_General', 'get_class_sections' ) );
add_action( 'wp_ajax_wlsm-get-section-students', array( 'WLSM_Staff_General', 'get_section_students' ) );
add_action( 'wp_ajax_wlsm-get-school-classes', array( 'WLSM_Staff_General', 'get_school_classes' ) );
add_action( 'wp_ajax_wlsm-get-school-class-sections', array( 'WLSM_Staff_General', 'get_school_class_sections' ) );
add_action( 'wp_ajax_wlsm-get-class-subjects', array( 'WLSM_Staff_General', 'get_class_subjects' ) );
add_action( 'wp_ajax_wlsm-get-class-chapter', array( 'WLSM_Staff_General', 'get_class_chapter' ) );
add_action( 'wp_ajax_wlsm-get-subject-teachers', array( 'WLSM_Staff_General', 'get_subject_teachers' ) );

add_action( 'wp_ajax_wlsm-get-students-subjects', array( 'WLSM_Staff_General', 'get_students_subjects' ) );

// Fee type get for students_page_url
add_action( 'wp_ajax_wlsm-get-fee-type', array( 'WLSM_Staff_General', 'get_fee_type' ) );


// Staff: Hostel.
add_action( 'wp_ajax_wlsm-fetch-hostels', array( 'WLSM_Staff_Transport', 'fetch_hostels' ) );
add_action( 'wp_ajax_wlsm-save-hostel', array( 'WLSM_Staff_Transport', 'save_hostel' ) );
add_action( 'wp_ajax_wlsm-delete-hostel', array( 'WLSM_Staff_Transport', 'delete_hostel' ) );

// staff: room
add_action( 'wp_ajax_wlsm-fetch-rooms', array( 'WLSM_Staff_Transport', 'fetch_rooms' ) );
add_action( 'wp_ajax_wlsm-save-room', array( 'WLSM_Staff_Transport', 'save_room' ) );
add_action( 'wp_ajax_wlsm-delete-room', array( 'WLSM_Staff_Transport', 'delete_room' ) );

// Lecture.
add_action( 'wp_ajax_wlsm-fetch-lecture', array( 'WLSM_Lecture', 'fetch_lecture' ) );
add_action( 'wp_ajax_wlsm-save-lecture', array( 'WLSM_Lecture', 'save_lecture' ) );
add_action( 'wp_ajax_wlsm-delete-lecture', array( 'WLSM_Lecture', 'delete_lecture' ) );

add_action( 'wp_ajax_wlsm-fetch-chapter', array( 'WLSM_Lecture', 'fetch_chapter' ) );
add_action( 'wp_ajax_wlsm-save-chapter', array( 'WLSM_Lecture', 'save_chapter' ) );
add_action( 'wp_ajax_wlsm-delete-chapter', array( 'WLSM_Lecture', 'delete_chapter' ) );

// Bulk Action.
add_action( 'wp_ajax_wlsm-bulk-action', array( 'WLSM_Bulk_Action', 'bulk_action' ) );

// Examination class subjects fetch Action.
add_action( 'wp_ajax_wlsm-get-class-exam-subjects', array( 'WLSM_Staff_General', 'get_class_subjects_exam' ) );

add_action( 'wp_ajax_wlsm-get-class-exams', array( 'WLSM_Staff_General', 'get_class_exams' ) );

// zoom api settings

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
    <h3><?php _e("Zoom API Settings", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="redirect_url"><?php _e("Redirect URI"); ?></label></th>
        <td>
            <input type="text" name="redirect_url" id="redirect_url" value="<?php echo esc_attr( get_the_author_meta( 'redirect_url', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your API KEY."); ?></span>
        </td>
    </tr>
    <tr>
        <th><label for="api_key"><?php _e("API KEY"); ?></label></th>
        <td>
            <input type="text" name="api_key" id="api_key" value="<?php echo esc_attr( get_the_author_meta( 'api_key', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your API KEY."); ?></span>
        </td>
    </tr>
    <tr>
        <th><label for="api_secret"><?php _e("API SECRET"); ?></label></th>
        <td>
            <input type="text" name="api_secret" id="api_secret" value="<?php echo esc_attr( get_the_author_meta( 'api_secret', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your api secret."); ?></span>
        </td>
        <h5><?php esc_html_e( 'Accessing your Zoom API Key & Secret', 'school-management' ); ?></h5>
        <p>
        <?php esc_html_e( 'To access the API Key and Secret, Create a oauth App on the Marketplace. After providing basic information about your app, locate your API Key and Secret in the App Credentials page.', 'school-management' ); ?>
        <a target="_blank" href="https://developers.zoom.us/docs/integrations/"><?php esc_html_e( 'Click here for more information', 'school-management' ); ?></a>
        </p>
    </tr>
    </table>
<?php }


add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    update_user_meta( $user_id, 'api_key', $_POST['api_key'] );
    update_user_meta( $user_id, 'api_secret', $_POST['api_secret'] );
    update_user_meta( $user_id, 'redirect_url', $_POST['redirect_url'] );
}

function wp_notice_add_notice() {
    $slug = isset($_GET['page']) ? $_GET['page'] : '';
    if ($slug == 'sm-staff-examination') {
        $message = '(Updating From v10.2.4 and lower To  v10.2.5) Please update the exam subjects in exams';
        echo '<div class="notice notice-error is-dismissible"><p>' . $message . '</p></div>';
    }
}

add_action( 'admin_notices', 'wp_notice_add_notice' );