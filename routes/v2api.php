<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v2\Auth\AuthenticationController;
use Modules\Chat\Http\Controllers\Api\ChatController;

Route::controller(AuthenticationController::class)->group(function () {
    Route::any('login', 'login');
    Route::get('user-demo-login/{role_id}', 'DemoUser');
    Route::post('forget-password', 'emailVerify');
    Route::get('student-profile-details/{id}', 'studentProfile');
});

Route::group(['middleware' => ['auth:api', 'subdomain']], function () {
    Route::get('general-settings', 'Auth\AuthenticationController@generalSettings');
    Route::post('auth/logout', 'Auth\AuthenticationController@logout');
    Route::get('teacher-about', [AuthenticationController::class, 'aboutApi']);

    Route::get('student-profile-details/{id}', 'Auth\AuthenticationController@studentProfile');
    Route::get('student-profile-edit', 'Auth\AuthenticationController@studentProfileEdit');
    Route::post('student-profile-update', 'Auth\AuthenticationController@studentProfileUpdate');
    Route::post('student-profile-img-update', 'Auth\AuthenticationController@studentProfileImgUpdate');
    Route::get('student-record', 'Auth\AuthenticationController@studentRecord');



    Route::get('profile-personal', 'Auth\AuthenticationController@profilePersonal');
    Route::get('profile-parents', 'Auth\AuthenticationController@profileParents');
    Route::get('profile-transport', 'Auth\AuthenticationController@profileTransport');
    Route::get('profile-others', 'Auth\AuthenticationController@profileOthers');
    Route::get('profile-documents', 'Auth\AuthenticationController@profileDocuments');
    Route::post('profile-documents-store', 'Auth\AuthenticationController@profileDocumentsStore');
    Route::get('profile-documents-delete', 'Auth\AuthenticationController@deleteDocument');


    Route::get('admin-teacher-homework', 'Homework\HomeworkController@adminTeacherhomework');
    Route::get('student-homework', 'Homework\HomeworkController@studentHomework');
    Route::get('parent-homework', 'Homework\HomeworkController@parentHomework');
    Route::get('student-homework-view', 'Homework\HomeworkController@studentHomeworkView');
    Route::get('student-homework-file-download', 'Homework\HomeworkController@studentHomeworkFileDownload');
    Route::post('upload-homework-content', 'Homework\HomeworkController@uploadHomeworkContent');

    Route::get('student-assignment', 'Assignment\AssignmentController@studentAssignment');
    Route::get('student-assignment-file-download/{id}', 'Assignment\AssignmentController@studentAssignmentFileDownload');
    Route::get('upload-content-student-view', 'Assignment\AssignmentController@uploadContentView');

    Route::get('student-syllabus', 'Syllabus\SyllabusController@studentSyllabus');

    Route::get('student-others-download', 'OthersStudyMaterial\OthersStudyMaterialController@othersDownload');

    // Attendance 
    Route::get('student-attendance', 'Attendance\AttendanceController@stdAttendCurrMonth');
    Route::get('subject-wise-attendance', 'Attendance\AttendanceController@stdAttendSubjectWise');
    Route::get('record-wise-all-subjects', 'Attendance\AttendanceController@recWiseAllSubjects');

    // Notification
    Route::get('all-notification-list', 'Notification\NotificationController@allNotificationList');
    Route::get('view/all/notification', 'Notification\NotificationController@allNotificationMarkRead');

    //Fees
    Route::get('student-fees', 'Fees\StudentFeesController@studentFeesList');
    Route::get('add-fees-payment', 'Fees\StudentFeesController@addFeesPayment');
    Route::get('gateway-service-charge', 'Fees\StudentFeesController@serviceCharge');
    Route::post('student-fees-payment-stores', 'Fees\StudentFeesController@studentFeesPaymentStore');
    Route::get('student-fees-payment-stores', 'Fees\StudentFeesController@studentFeesPaymentStore');
    Route::get('fees-invoice-view', 'Fees\StudentFeesController@feesInvoiceView');
    Route::get('timeline', 'Timeline\TimelineController@stdTimeline');
    Route::get('remaining-leave', 'Leave\LeaveController@remainingLeave');
    Route::get('apply-leave', 'Leave\LeaveController@applyLeave');
    Route::get('leave-type', 'Leave\LeaveController@leaveType');
    Route::post('student-leave-store', 'Leave\LeaveController@leaveStore');
    Route::get('student-leave-edit', 'Leave\LeaveController@studentLeaveEdit');
    Route::post('student-leave-update', 'Leave\LeaveController@update');
    Route::get('student-teacher', 'Teacher\TeacherController@studentTeacher');



    Route::get('student-library', 'Library\LibraryController@studentBookList');
    Route::get('student-book-issue', 'Library\LibraryController@studentBookIssue');
    Route::get('student-transport', 'Transport\TransportController@studentTransport');
    Route::get('student-dormitory', 'Dormitory\DormitoryController@studentDormitory');

    Route::get('student-class-routine', 'ClassRoutine\ClassRoutineController@studentClassRoutine');
    Route::get('student-exam', 'Exam\ExamController@studentExam');
    Route::get('student-exam-type', 'Exam\ExamController@studentExamType');

    Route::get('student-exam-schedule', 'Exam\ExamController@studentExamSchedule');
    Route::get('student-noticeboard', 'NoticeBoard\NoticeBoardController@studentNoticeboard');
    Route::get('student-single-noticeboard', 'NoticeBoard\NoticeBoardController@studentSingleNoticeboard');

    Route::get('exam-result', 'Exam\ExamController@examResult');
    Route::post('exam-result-search', 'Exam\ExamController@examResultSearch');
    Route::get('student-online-exam', 'Exam\ExamController@studentOnlineExam');

    Route::get('student-view-result', 'Exam\ExamController@studentViewResult');

    Route::get('student-fees-installment', 'Fees\StudentFeesController@studentFees');
    Route::get('student-single-fees-installment', 'Fees\StudentFeesController@studentSingleFees');

    Route::get('direct-fees-generate-add-child', 'Fees\StudentFeesController@directFeesGenerateModalChild');

    Route::post('child-bank-slip-store', 'Fees\StudentFeesController@childBankSlipStore');


    Route::any('change-password', 'Auth\AuthenticationController@updatePassowrdStoreApi');
    Route::post('user-delete', 'Auth\AuthenticationController@deleteUser');
    Route::get('student-subject', 'Auth\AuthenticationController@studentSubject');

    // Route::get('my-wallet', 'Wallet\WalletController@myWallet');
    Route::get('student-lesson-plan', 'Lesson\LessonController@index');

    Route::get('fees-payment-print', 'Fees\StudentFeesController@feesPaymentPrint');
    Route::get('view-lesson-plan-lesson', 'Lesson\LessonController@ViewlessonPlannerLesson');

    // 'Lesson\LessonController
    // Admin API
    Route::any('student-list-search', 'Admin\StudentController@studentDetailsSearch');
    Route::get('student-profile-personal', 'Admin\StudentController@profilePersonal');
    Route::get('student-profile-parents', 'Admin\StudentController@profileParents');
    Route::get('student-profile-transport', 'Admin\StudentController@profileTransport');
    Route::get('student-profile-others', 'Admin\StudentController@profileOthers');
    Route::get('student-profile-documents', 'Admin\StudentController@profileDocuments');

    Route::get('pending-leave-list', 'Admin\LeaveController@allPendingList');
    Route::get('approve-leave-list', 'Admin\LeaveController@allAprroveList');
    Route::get('rejected-leave-list', 'Admin\LeaveController@allRejectedList');
    Route::post('update-approve-leave', 'Admin\LeaveController@updateApproveLeave');

    Route::get('role-list', 'Admin\StaffController@role');
    Route::get('role-wise-staff-list', 'Admin\StaffController@roleWiseStaffList');
    Route::get('individual-staff-details', 'Admin\StaffController@individualStaffDetails');
    Route::post('dormitory-store', 'Admin\DormitoryController@store');

    Route::get('room-list', 'Admin\DormitoryController@index');
    Route::post('room-store', 'Admin\DormitoryController@dormitoryRoomStore');
    Route::get('room-type', 'Admin\DormitoryController@roomType');
    Route::get('dormitory-list', 'Admin\DormitoryController@dormitoryList');

    Route::get('class-list', 'Admin\AttendanceController@classList');
    Route::get('section-list', 'Admin\AttendanceController@sectionList');
    Route::get('subject-list', 'Admin\AttendanceController@subjectList');
    Route::post('student-search', 'Admin\AttendanceController@studentSearch');
    Route::post('student-attendance-holiday', 'Admin\AttendanceController@studentAttendanceHoliday');
    Route::post('subject-attendance-search',  'Admin\AttendanceController@search');
    Route::get('student-attendance-report-search', 'Admin\AttendanceController@studentAttendanceReportSearch');
    Route::get('student-search-attend', 'Admin\AttendanceController@studentSearchAttend');
    Route::get('student-subject-attendance', 'Admin\AttendanceController@studentSubjectAttendanceSearch');
    Route::get('subject-wise-students', 'Admin\AttendanceController@subjectWiseStudents');
    Route::post('submit-student-class-attendance', 'Admin\AttendanceController@studentAttendanceStore');
    Route::post('subject-wise-attendance-submit', 'Admin\AttendanceController@subjectWiseAttendanceStore');
    Route::post('student-subject-holiday-store',  'Admin\AttendanceController@subjectHolidayStore');


    // Fees Group routes
    Route::get('fees-group', 'Admin\FeesGroupController@fees_group_index');
    Route::post('fees-group-store', 'Admin\FeesGroupController@fees_group_store');
    Route::get('fees-group-edit', 'Admin\FeesGroupController@fees_group_edit');
    Route::post('fees-group-update', 'Admin\FeesGroupController@fees_group_update');
    Route::post('fees-group-delete', 'Admin\FeesGroupController@fees_group_delete');

    // Fees type routes
    Route::get('fees-type', 'Admin\FeesTypeController@fees_type_index');
    Route::post('fees-type-store', 'Admin\FeesTypeController@fees_type_store');
    Route::get('fees-type-edit', 'Admin\FeesTypeController@fees_type_edit');
    Route::post('fees-type-update', 'Admin\FeesTypeController@fees_type_update');
    Route::post('fees-type-delete', 'Admin\FeesTypeController@fees_type_delete');

    // Fees invoice routes
    Route::get('fees-invoice', 'Admin\FeesInvoiceController@fees_invoice_index');


    // Upload Content
    Route::get('admin-upload-content-list', 'Admin\ContentController@uploadContents');
    Route::post('store-admin-content', 'Admin\ContentController@storeContent');
    Route::post('delete-admin-content', 'Admin\ContentController@deleteSingle');


    // Staff Notice
    Route::get('admin-staff-notice-list', 'Admin\StaffNoticeController@noticeList');

    //Book
    Route::get('admin-book-list', 'Admin\BookController@bookList');
    Route::get('admin-add-book-dropdown-items', 'Admin\BookController@addBookDropdowns');
    Route::post('admin-book-store', 'Admin\BookController@storeBook');

    //Vehicale And Route
    Route::get('assign-vehicle-to-route', 'Admin\AssignVehicleController@assignToRoute');
    Route::get('assign-vehicle-list', 'Admin\AssignVehicleController@assignList');
    Route::post('store-assign-vehicle-to-route', 'Admin\AssignVehicleController@storeAssign');

    //Vehicle
    Route::get('admin-vehicle-list', 'Admin\VehicleController@vehicleList');
    Route::post('admin-vehicle-store', 'Admin\VehicleController@storeVehicle');
    Route::get('admin-vehicle-drivers', 'Admin\VehicleController@driverList');

    // Route
    Route::get('admin-route-list', 'Admin\RouteController@routeList');
    Route::post('admin-route-store', 'Admin\RouteController@storeRoute');
    Route::post('admin-route-update', 'Admin\RouteController@updateRoute');
    Route::post('admin-route-delete', 'Admin\RouteController@deleteRoute');

    // Library Member
    Route::get('admin-add-member-roles', 'Admin\LibrarayMemberController@roleItems');
    Route::get('admin-add-member-user-names', 'Admin\LibrarayMemberController@userNameList');
    Route::get('admin-add-member-classes', 'Admin\LibrarayMemberController@classList');
    Route::get('admin-add-member-sections', 'Admin\LibrarayMemberController@sectionList');
    Route::get('admin-add-member-students', 'Admin\LibrarayMemberController@studentList');
    Route::get('admin-add-member-parents', 'Admin\LibrarayMemberController@parentList');
    Route::post('store-admin-library-member', 'Admin\LibrarayMemberController@store');

    // Teacher

    // Homework
    Route::get('teacher-homework-list', 'Teacher\HomeworkController@homeworkList');
    Route::get('teacher-homework-search', 'Teacher\HomeworkController@search');
    Route::get('teacher-homework-evaluation-list', 'Teacher\HomeworkController@evaluationHomework');
    Route::get('teacher-add-homework-for-class', 'Teacher\HomeworkController@addHomeworkDropdownListForClasses');
    Route::get('teacher-add-homework-for-subject', 'Teacher\HomeworkController@addHomeworkDropdownListForSubjects');
    Route::get('teacher-add-homework-for-section', 'Teacher\HomeworkController@addHomeworkDropdownListForSection');
    Route::post('teacher-add-homework', 'Teacher\HomeworkController@storeHomeWork');
    Route::post('teacher-store-homework-evaluation', 'Teacher\HomeworkController@storeHomeWorkEvaluation');

    // Book
    Route::get('teacher-book-list', 'Teacher\BookController@bookList');

    // Notice
    Route::get('teacher-notice-list', 'Teacher\NoticeController@noticeList');

    // Content
    Route::get('teacher-content-list', 'Teacher\ContentController@contentList');
    Route::post('teacher-delete-content', 'Teacher\ContentController@doeleteContent');
    Route::post('teacher-create-content', 'Teacher\ContentController@storeContent');

    // Teacher Leave
    Route::get('teacher-leave-list', 'Teacher\Leave\LeaveController@list');
    Route::get('teacher-leave-types', 'Teacher\Leave\LeaveController@types');
    Route::post('teacher-leave-store', 'Teacher\Leave\LeaveController@store');

    Route::get('teacher-attendance-classes', 'Teacher\Attendance\ClassAttendanceController@classes');
    Route::get('teacher-attendance-sections', 'Teacher\Attendance\ClassAttendanceController@sections');
    Route::get('teacher-attendance-subjects', 'Teacher\Attendance\ClassAttendanceController@subjects');
    Route::post('teacher-attendance-students', 'Teacher\Attendance\ClassAttendanceController@students');
    Route::post('teacher-class-attendance-store', 'Teacher\Attendance\ClassAttendanceController@storeAttendance');
    Route::post('teacher-class-attendance-holiday', 'Teacher\Attendance\ClassAttendanceController@holiday');

    // Subject Wise Attendance
    Route::get('teacher-subject-wise-students-attendance', 'Teacher\Attendance\SubjectWiseAttendanceController@studentSubjectAttendanceSearch');
    Route::get('teacher-subject-wise-students', 'Teacher\Attendance\SubjectWiseAttendanceController@subjectWiseStudents');
    Route::post('teacher-subject-attendance-search', 'Teacher\Attendance\SubjectWiseAttendanceController@searchAttendance');
    Route::post('teacher-subject-attendance-submit', 'Teacher\Attendance\SubjectWiseAttendanceController@storeAttendance');
    Route::post('teacher-subject-holiday', 'Teacher\Attendance\SubjectWiseAttendanceController@holiday');

    // subject
    Route::get('subjects', 'Teacher\Subject\SubjectController@index');
    Route::get('class-routine', 'Teacher\ClassRoutine\ClassRoutineController@classRoutineSearch');
    Route::get('teacher-class-routine', 'Teacher\ClassRoutine\ClassRoutineController@teacherClassRoutine');

    // Student Attendance
    Route::get('teacher-search-student-attendance', 'Teacher\Attendance\AttendanceController@studentAttendance');
    Route::get('teacher-search-student-attendance-report', 'Teacher\Attendance\AttendanceController@attendanceReport');

    Route::get('parent-childrens', 'Parent\ParentController@childrens');

    // Chat for Admin

    Route::get('admin-chat-users', 'Admin\Chat\AdminChatController@userList');
    Route::post('admin-chat-send', 'Admin\Chat\AdminChatController@sendMessage');
    Route::post('admin-chat-list', 'Admin\Chat\AdminChatController@messages');
    Route::get('admin-chat-user-search', 'Admin\Chat\AdminUserServiceController@search');
    Route::post('change-admin-chat-user-status', 'Admin\Chat\AdminUserServiceController@changeStatus');
    Route::post('delete-admin-chat-single-message', 'Admin\Chat\AdminChatController@deleteMessage');
    Route::post('forward-admin-chat-single-message', 'Admin\Chat\AdminChatController@forward');
    Route::get('admin-single-chat-files', 'Admin\Chat\AdminChatController@fileList');
    Route::post('chat-user-block-action', 'Admin\Chat\AdminUserServiceController@blockAction');
    Route::get('blocked-chat-users', 'Admin\Chat\AdminUserServiceController@blockedUsers');
    Route::get('single-user-chat-status', 'Admin\Chat\AdminUserServiceController@chatStatus');

    Route::get('admin-chat-groups', 'Admin\Chat\GroupChatController@groupList');
    Route::post('admin-group-chat-send', 'Admin\Chat\GroupChatController@sendGroupMessage');
    Route::get('admin-group-members', 'Admin\Chat\GroupChatController@groupMemberList');
    Route::post('admin-add-group-member', 'Admin\Chat\GroupChatController@addPeople');
    Route::post('admin-group-member-delete', 'Admin\Chat\GroupChatController@removePeople');
    Route::post('admin-group-leave', 'Admin\Chat\GroupChatController@leaveGroup');
    Route::post('admin-group-delete', 'Admin\Chat\GroupChatController@destroy');
    Route::post('admin-group-store', 'Admin\Chat\GroupChatController@store');
    Route::get('admin-group-chats', 'Admin\Chat\GroupChatController@chatList');
    Route::get('admin-group-chat-files', 'Admin\Chat\GroupChatController@fileList');
    Route::post('admin-group-chat-remove', 'Admin\Chat\GroupChatController@removeMessage');
    Route::post('admin-group-chat-forward', 'Admin\Chat\GroupChatController@forward');

    Route::post('broadcasting/auth', 'Admin\Chat\SettingsController@pusherAuth');

    Route::get('my-wallet', 'Student\Payment\WalletController@myWallet');
    Route::post('add-wallet-amount', 'Student\Payment\WalletController@addWalletAmount');
    Route::get('add-amount-methods', 'Student\Payment\WalletController@paymentMethods');
    Route::get('add-amount-banks', 'Student\Payment\WalletController@bankAccounts');
    Route::post('handle-payment-request', 'Student\Payment\PaymentHandlerController@handlePayment');

    Route::middleware(['XSS', 'json.response'])->group(function () {
        Route::get('zoom-class-list', 'Student\Class\ZoomController@classes');
        Route::get('zoom-class-section-list', 'Student\Class\ZoomController@sections');
        Route::get('zoom-class-teacher-list', 'Student\Class\ZoomController@teachers');
        Route::post('zoom-meeting-store', 'Student\Class\ZoomController@store');
        Route::get('zoom-meeting-detail', 'Student\Class\ZoomController@show');
        Route::get('zoom-meeting-update', 'Student\Class\ZoomController@update');
        Route::post('zoom-meeting-delete', 'Student\Class\ZoomController@delete');
        Route::get('zoom-class-meeting-list', 'Student\Class\ZoomController@classList');
        Route::get('zoom-meeting-list', 'Student\Class\ZoomController@meetings');

        Route::get('set-fcm-token', 'PushNotification\PushNotificationController@setFcmToken');
    });

    Route::middleware(['subdomain'])->group(function () {
        Route::get('jitsi/virtual-class', 'Student\Class\JitsiController@index');
        Route::get('jitsi/meetings', 'Student\Class\JitsiController@meetings');
        Route::get('jitsi/settings', 'Student\Class\JitsiController@settings');

        Route::get('bbb/virtual-class', 'Student\Class\BBBController@index');
        Route::get('bbb/meetings', 'Student\Class\BBBController@meetings');
        Route::get('bbb/meeting-join', 'Student\Class\BBBController@meetingJoin');
    });

    Route::get('g-meet/virtual-class', 'Student\Class\GMeetController@index');
    Route::get('g-meet/virtual-meeting', 'Student\Class\GMeetController@meetings');

    Route::post('language-list', 'Language\LanguageController@myLanguages');
    Route::get('user-language-list', 'Language\LanguageController@allList');

    Route::get('bank-payment-list', 'Admin\Payment\BankPaymentController@paymentList');
    Route::post('bank-payment-change-status', 'Admin\Payment\BankPaymentController@changePaymentStatus');
});
Route::get('language-list', 'Language\LanguageController@allList');
