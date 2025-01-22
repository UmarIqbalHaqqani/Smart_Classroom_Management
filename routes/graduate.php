<?php
Route::group(['prefix' => 'graduates', 'as' => 'alumni.', 'middleware' => ['auth', 'subdomain']], static function ($routes) {

$routes->get('graduate-list', 'GraduateListController@index')->name('graduate-list');
    $routes->post('graduate-search', 'GraduateListController@search')->name('graduate-search');
    $routes->get('graduate-search-datatable', 'GraduateListController@gradauateDatatable')->name('graduate_search_datatable');

    $routes->get('view-transcript/{id}', 'GraduateListController@viewTranscript')->name('view-transcript')->middleware('userRolePermission:alumni.view-transcript');
    $routes->get('print-transcript/{id}', 'GraduateListController@printTranscript')->name('print-transcript')->middleware('userRolePermission:alumni.print-transcript');
    $routes->get('edit-revert-as-student/{id}', 'GraduateListController@editRevertAsStudent')->name('edit-revert-as-student')->middleware('userRolePermission:alumni.edit-revert-as-student');
    $routes->post('revert-as-student', 'GraduateListController@revertAsStudent')->name('revert-as-student')->middleware('userRolePermission:alumni.revert-as-student');
});