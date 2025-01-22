<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['XSS', 'subdomain']], function () {
    Route::group(['middleware' => ['AlumniMIddleware']], function () {
        Route::get('alumni-dashboard', 'Alumni\AlumniPanelController@alumniDashboard')->name('alumni-dashboard');
        Route::get('view-event/{id}', 'Alumni\AlumniPanelController@viewEvent')->name('alumni-view-event');
        Route::get('view-document/{id}', 'Alumni\AlumniPanelController@viewDocument')->name('alumni-view-document');
        Route::get('student-profile-alumni', 'Alumni\AlumniPanelController@studentProfile')->name('alumni-student-profile');

    });
});
//Route::get('alumni-dashboard', 'Alumni\AlumniPanelController@alumniDashboard')->name('alumni-dashboard')->middleware('userRolePermission:alumni-dashboard');
