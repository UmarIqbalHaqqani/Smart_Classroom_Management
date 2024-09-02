<?php
defined( 'ABSPATH' ) || die();

global $wpdb;

/* Table names */
define( 'WLSM_USERS', $wpdb->base_prefix . 'users' );
define( 'WLSM_POSTS', $wpdb->prefix . 'posts' );
define( 'WLSM_SCHOOLS', $wpdb->prefix . 'wlsm_schools' );
define( 'WLSM_SETTINGS', $wpdb->prefix . 'wlsm_settings' );
define( 'WLSM_CLASSES', $wpdb->prefix . 'wlsm_classes' );
define( 'WLSM_CATEGORY', $wpdb->prefix . 'wlsm_category' );
define( 'WLSM_CLASS_SCHOOL', $wpdb->prefix . 'wlsm_class_school' );
define( 'WLSM_SESSIONS', $wpdb->prefix . 'wlsm_sessions' );
define( 'WLSM_INQUIRIES', $wpdb->prefix . 'wlsm_inquiries' );
define( 'WLSM_ROLES', $wpdb->prefix . 'wlsm_roles' );
define( 'WLSM_STAFF', $wpdb->prefix . 'wlsm_staff' );
define( 'WLSM_ADMINS', $wpdb->prefix . 'wlsm_admins' );
define( 'WLSM_SECTIONS', $wpdb->prefix . 'wlsm_sections' );
define( 'WLSM_SUBJECT_TYPES', $wpdb->prefix . 'wlsm_subject_types' );
define( 'WLSM_STUDENT_RECORDS', $wpdb->prefix . 'wlsm_student_records' );
define( 'WLSM_PROMOTIONS', $wpdb->prefix . 'wlsm_promotions' );
define( 'WLSM_TRANSFERS', $wpdb->prefix . 'wlsm_transfers' );
define( 'WLSM_CERTIFICATES', $wpdb->prefix . 'wlsm_certificates' );
define( 'WLSM_CERTIFICATE_STUDENT', $wpdb->prefix . 'wlsm_certificate_student' );
define( 'WLSM_INVOICES', $wpdb->prefix . 'wlsm_invoices' );
define( 'WLSM_PAYMENTS', $wpdb->prefix . 'wlsm_payments' );
define( 'WLSM_PENDING_PAYMENTS', $wpdb->prefix . 'wlsm_pending_payments' );
define( 'WLSM_EXPENSE_CATEGORIES', $wpdb->prefix . 'wlsm_expense_categories' );
define( 'WLSM_EXPENSES', $wpdb->prefix . 'wlsm_expenses' );
define( 'WLSM_INCOME_CATEGORIES', $wpdb->prefix . 'wlsm_income_categories' );
define( 'WLSM_INCOME', $wpdb->prefix . 'wlsm_income' );
define( 'WLSM_ATTENDANCE', $wpdb->prefix . 'wlsm_attendance' );
define( 'WLSM_STAFF_ATTENDANCE', $wpdb->prefix . 'wlsm_staff_attendance' );
define( 'WLSM_SUBJECTS', $wpdb->prefix . 'wlsm_subjects' );
define( 'WLSM_STUDENTS_SUBJECTS', $wpdb->prefix . 'wlsm_students_subjects' );
define( 'WLSM_EXAMS', $wpdb->prefix . 'wlsm_exams' );
define( 'WLSM_EXAMS_GROUP', $wpdb->prefix . 'wlsm_exams_group' );
define( 'WLSM_CLASS_SCHOOL_EXAM', $wpdb->prefix . 'wlsm_class_school_exam' );
define( 'WLSM_EXAM_PAPERS', $wpdb->prefix . 'wlsm_exam_papers' );
define( 'WLSM_ADMIT_CARDS', $wpdb->prefix . 'wlsm_admit_cards' );
define( 'WLSM_EXAM_RESULTS', $wpdb->prefix . 'wlsm_exam_results' );
define( 'WLSM_NOTICES', $wpdb->prefix . 'wlsm_notices' );
define( 'WLSM_CLASS_SCHOOL_NOTICE', $wpdb->prefix . 'wlsm_class_school_notice' );
define( 'WLSM_STUDY_MATERIALS', $wpdb->prefix . 'wlsm_study_materials' );
define( 'WLSM_CLASS_SCHOOL_STUDY_MATERIAL', $wpdb->prefix . 'wlsm_class_school_study_material' );
define( 'WLSM_HOMEWORK', $wpdb->prefix . 'wlsm_homework' );
define( 'WLSM_HOMEWORK_SUBMISSION', $wpdb->prefix . 'wlsm_homework_submission' );
define( 'WLSM_HOMEWORK_SECTION', $wpdb->prefix . 'wlsm_homework_section' );
define( 'WLSM_ADMIN_SUBJECT', $wpdb->prefix . 'wlsm_admin_subject' );
define( 'WLSM_FEES', $wpdb->prefix . 'wlsm_fees' );
define( 'WLSM_STUDENT_FEES', $wpdb->prefix . 'wlsm_student_fees' );
define( 'WLSM_STUDENT_ASSIGNED_FEES', $wpdb->prefix . 'wlsm_student_assigned_fees' );
define( 'WLSM_ROUTINES', $wpdb->prefix . 'wlsm_routines' );
define( 'WLSM_BOOKS', $wpdb->prefix . 'wlsm_books' );
define( 'WLSM_BOOKS_ISSUED', $wpdb->prefix . 'wlsm_books_issued' );
define( 'WLSM_LIBRARY_CARDS', $wpdb->prefix . 'wlsm_library_cards' );
define( 'WLSM_VEHICLES', $wpdb->prefix . 'wlsm_vehicles' );
define( 'WLSM_ROUTES', $wpdb->prefix . 'wlsm_routes' );
define( 'WLSM_ROUTE_VEHICLE', $wpdb->prefix . 'wlsm_route_vehicle' );
define( 'WLSM_LOGS', $wpdb->prefix . 'wlsm_logs' );
define( 'WLSM_LEAVES', $wpdb->prefix . 'wlsm_leaves' );
define( 'WLSM_EVENTS', $wpdb->prefix . 'wlsm_events' );
define( 'WLSM_EVENT_RESPONSES', $wpdb->prefix . 'wlsm_event_responses' );
define( 'WLSM_MEETINGS', $wpdb->prefix . 'wlsm_meetings' );

define( 'WLSM_REMINDER', $wpdb->prefix . 'wlsm_reminder');
define( 'WLSM_RATTING', $wpdb->prefix . 'wlsm_ratting');
define( 'WLSM_LECTURE', $wpdb->prefix . 'wlsm_lecture');
define( 'WLSM_CHAPTER', $wpdb->prefix . 'wlsm_chapter');

define( 'WLSM_ACTIVITIES', $wpdb->prefix . 'wlsm_activities');
define( 'WLSM_MEDIUM', $wpdb->prefix . 'wlsm_medium' );
define( 'WLSM_STUDENT_TYPE', $wpdb->prefix . 'wlsm_student_type' );


define( 'WLSM_HOSTELS', $wpdb->prefix . 'wlsm_hostels' );
define( 'WLSM_ROOMS', $wpdb->prefix . 'wlsm_rooms' );
define( 'WLSM_ACADEMIC_REPORTS', $wpdb->prefix . 'wlsm_academic_reports' );
/* Multi-School admin capability */
define( 'WLSM_ADMIN_CAPABILITY', 'manage_options' );

/* Demo mode */
define( 'WLSM_DEMO_MODE', false );

/* Menu page slugs for manager */
define( 'WLSM_MENU_SM', 'school-management' );
define( 'WLSM_MENU_SCHOOLS', 'sm-schools' );
define( 'WLSM_MENU_CLASSES', 'sm-classes' );
define( 'WLSM_MENU_CATEGORY', 'sm-category' );
define( 'WLSM_MENU_SESSIONS', 'sm-sessions' );
define( 'WLSM_MENU_SETTINGS', 'sm-settings' );

/* Menu page slugs for schools assigned */
define( 'WLSM_MENU_SCHOOLS_ASSIGNED', 'sm-schools-assigned' );

/* Menu page slugs for school staff */

/* School */
define( 'WLSM_MENU_STAFF_SCHOOL', 'sm-staff-dashboard' );
define( 'WLSM_MENU_STAFF_INQUIRIES', 'sm-staff-inquiries' );
define( 'WLSM_MENU_STAFF_ADMISSIONS', 'sm-staff-admissions' );
define( 'WLSM_MENU_STAFF_STUDENTS', 'sm-staff-students' );
define( 'WLSM_MENU_STAFF_ID_CARDS', 'sm-staff-id-cards' );
define( 'WLSM_MENU_STAFF_ADMINS', 'sm-staff-admins' );
define( 'WLSM_MENU_STAFF_ROLES', 'sm-staff-roles' );
define( 'WLSM_MENU_STAFF_EMPLOYEES', 'sm-staff-employees' );
define( 'WLSM_MENU_STAFF_EMPLOYEES_ATTENDANCE', 'sm-staff-employees-attendance' );
define( 'WLSM_MENU_STAFF_EMPLOYEE_LEAVES', 'sm-staff-employee-leaves' );
define( 'WLSM_MENU_STAFF_PROMOTE', 'sm-staff-promote' );
define( 'WLSM_MENU_STAFF_TRANSFER_STUDENT', 'sm-staff-transfer-student' );
define( 'WLSM_MENU_STAFF_CERTIFICATES', 'sm-staff-certificates' );
define( 'WLSM_MENU_STAFF_NOTIFICATIONS', 'sm-staff-notifications' );
define( 'WLSM_MENU_STAFF_STUDENT_LEAVES', 'sm-staff-student-leaves' );
define( 'WLSM_MENU_STAFF_SETTINGS', 'sm-staff-settings' );
define( 'WLSM_MENU_STAFF_LOGS', 'sm-staff-logs' );
define( 'WLSM_MENU_STAFF_LEAVE_REQUEST', 'sm-staff-leave-request' );
define( 'WLSM_MENU_STAFF_ASSIGNED_MEETINGS', 'sm-staff-assigned-live-classes' );

/* Class */
define( 'WLSM_MENU_STAFF_CLASSES', 'sm-staff-classes' );
define( 'WLSM_MENU_STAFF_SUBJECTS', 'sm-staff-subjects' );
define( 'WLSM_MENU_STAFF_TIMETABLE', 'sm-staff-timetable' );
define( 'WLSM_MENU_STAFF_MEMBER_TIMETABLE', 'sm-staff-member-timetable' );
define( 'WLSM_MENU_STAFF_ATTENDANCE', 'sm-staff-attendance' );
define( 'WLSM_MENU_STAFF_STUDY_MATERIALS', 'sm-staff-study-materials' );
define( 'WLSM_MENU_STAFF_HOMEWORK', 'sm-staff-study-homework' );
define( 'WLSM_MENU_SUBMIT_HOMEWORK', 'submit-homework' );
define( 'WLSM_MENU_SUBMIT_HOMEWORK_EDIT', 'submit-homework-edit' );
define( 'WLSM_MENU_STAFF_HOMEWORK_SUBMISSION', 'homework' );
define( 'WLSM_MENU_STAFF_NOTICES', 'sm-staff-notices' );
define( 'WLSM_MENU_STAFF_EVENTS', 'sm-staff-events' );
define( 'WLSM_MENU_STAFF_MEETINGS', 'sm-staff-live-classes' );
define( 'WLSM_MENU_STAFF_RATTING', 'sm-staff-ratting' );
define( 'WLSM_MENU_STUDENT_BIRTHDAYS', 'sm-student-birthdays' );

define( 'WLSM_MENU_STAFF_MEDIUM', 'sm-staff-medium' );
define( 'WLSM_MENU_STAFF_STUDENT_TYPE', 'sm-staff-student-type' );

/* Examination */
define( 'WLSM_MENU_STAFF_EXAMS', 'sm-staff-exams' );
define( 'WLSM_MENU_EXAMS_GROUP', 'sm-exams-group' );
define( 'WLSM_MENU_STAFF_EXAM_ADMIT_CARDS', 'sm-staff-exam-admit-cards-print' );
define( 'WLSM_MENU_STAFF_EXAM_ADMIT_CARDS_BULK_PRINT', 'admit-cards-bulk-print' );
define( 'WLSM_MENU_STAFF_EXAM_RESULTS', 'sm-staff-exam-results' );
define( 'WLSM_MENU_STAFF_EXAM_RESULTS_BULK_PRINT', 'sm-staff-exam-results-bulk-print' );
define( 'WLSM_MENU_STAFF_EXAM_ASSESSMENT', 'sm-staff-exam-assessment' );

/* Accountant */
define( 'WLSM_MENU_STAFF_EXPENSES', 'sm-staff-expenses' );
define( 'WLSM_MENU_STAFF_INCOME', 'sm-staff-income' );
define( 'WLSM_MENU_STAFF_INVOICES', 'sm-staff-invoices' );
define( 'WLSM_MENU_STAFF_INVOICES_PRINT', 'sm-staff-invoices-print' );
define( 'WLSM_MENU_STAFF_INVOICES_REPORT_PRINT', 'sm-staff-invoices-report-print' );
define( 'WLSM_MENU_STAFF_FEES', 'sm-staff-fees' );

/* Library */
define( 'WLSM_MENU_STAFF_BOOKS', 'sm-staff-books' );
define( 'WLSM_MENU_STAFF_BOOKS_ISSUED', 'sm-staff-books-issued' );
define( 'WLSM_MENU_STAFF_LIBRARY_CARDS', 'sm-staff-library-cards' );

/* Transport */
define( 'WLSM_MENU_STAFF_VEHICLES', 'sm-staff-vehicles' );
define( 'WLSM_MENU_STAFF_ROUTES', 'sm-staff-routes' );
define( 'WLSM_MENU_STAFF_TRANSPORT_REPORT', 'sm-staff-transport-report' );

// Hostel
define( 'WLSM_MENU_STAFF_HOSTEL', 'sm-staff-hostel' );
define( 'WLSM_MENU_STAFF_HOSTELS', 'sm-staff-hostel-dash' );
define( 'WLSM_MENU_STAFF_ROOMS', 'sm-staff-rooms' );

/* Groups */
define( 'WLSM_MENU_STAFF_ACADEMIC', 'sm-staff-academic' );
define( 'WLSM_MENU_STAFF_STUDENT', 'sm-staff-student' );
define( 'WLSM_MENU_STAFF_ADMINISTRATOR', 'sm-staff-administrator' );
define( 'WLSM_MENU_STAFF_EXAMINATION', 'sm-staff-examination' );
define( 'WLSM_MENU_STAFF_ACADEMIC_REPORT', 'sm-staff-academic-report' );
define( 'WLSM_MENU_STAFF_ACCOUNTING', 'sm-staff-accounting' );
define( 'WLSM_MENU_STAFF_LIBRARY', 'sm-staff-library' );
define( 'WLSM_MENU_STAFF_TRANSPORT', 'sm-staff-transport' );