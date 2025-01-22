<?php

use Illuminate\Support\Facades\Route;
use Modules\BehaviourRecords\Http\Controllers\BehaviourCommentController;
use Modules\BehaviourRecords\Http\Controllers\BehaviourRecordsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['subdomain']], function () {
    Route::prefix('behaviour_records')->middleware('auth')->group(function () {
        // -----------------Assign incident----------------- //
        Route::get('assign_incident', [BehaviourRecordsController::class, 'assignIncident'])->name('behaviour_records.assign-incident')->middleware('userRolePermission:behaviour_records.assign-incident');
        Route::post('assign_incident_search', [BehaviourRecordsController::class, 'assignIncidentSearch'])->name('behaviour_records.assign_incident_search');
        Route::get('assign_incident_datatable', [BehaviourRecordsController::class, 'assignIncidentDatatable'])->name('behaviour_records.assign_incident_datatable');
        Route::post('assign_incident_save', [BehaviourRecordsController::class, 'assignIncidentSave'])->name('behaviour_records.assign_incident_save')->middleware('userRolePermission:behaviour_records.assign_incident_save');
        Route::delete('assign_incident_delete/{id}', [BehaviourRecordsController::class, 'assignIncidentDelete'])->name('behaviour_records.assign_incident_delete')->middleware('userRolePermission:behaviour_records.assign_incident_delete');
        Route::post('get_student_incident', [BehaviourRecordsController::class, 'getStudentIncident'])->name('behaviour_records.get_student_incident');

        // -----------------Incidents----------------- //
        Route::get('incident', [BehaviourRecordsController::class, 'incident'])->name('behaviour_records.incident')->middleware('userRolePermission:behaviour_records.incident');
        Route::post('incident_create', [BehaviourRecordsController::class, 'incidentCreate'])->name('behaviour_records.incident_create')->middleware('userRolePermission:behaviour_records.incident_create');
        Route::post('incident_update', [BehaviourRecordsController::class, 'incidentUpdate'])->name('behaviour_records.incident_update')->middleware('userRolePermission:behaviour_records.incident_update');
        Route::get('incident_delete/{id}', [BehaviourRecordsController::class, 'incidentDelete'])->name('behaviour_records.incident_delete')->middleware('userRolePermission:behaviour_records.incident_delete');

        // -----------------Report----------------- //
        Route::get('student_incident_report', [BehaviourRecordsController::class, 'studentIncidentReport'])->name('behaviour_records.student_incident_report')->middleware('userRolePermission:behaviour_records.student_incident_report');
        Route::get('student_incident_report_search', [BehaviourRecordsController::class, 'studentIncidentReportSearch'])->name('behaviour_records.student_incident_report_search');
        Route::get('view_student_all_incident_modal/{id}', [BehaviourRecordsController::class, 'viewStudentAllIncidentModal'])->name('behaviour_records.view_student_all_incident_modal');

        Route::get('student_behaviour_rank_report', [BehaviourRecordsController::class, 'studentBehaviourRankReport'])->name('behaviour_records.student_behaviour_rank_report')->middleware('userRolePermission:behaviour_records.student_behaviour_rank_report');
        Route::get('student_behaviour_rank_report_search', [BehaviourRecordsController::class, 'studentBehaviourRankReportSearch'])->name('behaviour_records.student_behaviour_rank_report_search');
        Route::get('view_behaviour_rank_modal/{id}', [BehaviourRecordsController::class, 'viewBehaviourRankModal'])->name('behaviour_records.view_behaviour_rank_modal');

        Route::get('class_section_wise_rank_report', [BehaviourRecordsController::class, 'classSectionWiseRankReport'])->name('behaviour_records.class_section_wise_rank_report')->middleware('userRolePermission:behaviour_records.class_section_wise_rank_report');
        Route::get('view_class_section_wise_modal/{id}', [BehaviourRecordsController::class, 'viewClassSectionWiseModal'])->name('behaviour_records.view_class_section_wise_modal');

        Route::get('incident_wise_report', [BehaviourRecordsController::class, 'incidentWiseReport'])->name('behaviour_records.incident_wise_report')->middleware('userRolePermission:behaviour_records.incident_wise_report');
        Route::get('view_incident_wise_report_modal/{id}', [BehaviourRecordsController::class, 'viewIncidentWiseReportModal'])->name('behaviour_records.view_incident_wise_report_modal');

        // -----------------Setting----------------- //
        Route::get('setting', [BehaviourRecordsController::class, 'setting'])->name('behaviour_records.setting')->middleware('userRolePermission:behaviour_records.setting');
        Route::put('setting_update', [BehaviourRecordsController::class, 'settingUpdate'])->name('behaviour_records.setting_update');

        // -----------------comment secton----------------- //
        Route::get('incident_comment/{id}', [BehaviourCommentController::class, 'incidentComment'])->name('behaviour_records.incident_comment');
        Route::post('incident_comment_save', [BehaviourCommentController::class, 'incidentCommentSave'])->name('behaviour_records.incident_comment_save');
        Route::get('get_incident_comment/{id}', [BehaviourCommentController::class, 'getIncidentComment'])->name('behaviour_records.get_incident_comment');
    });
});
