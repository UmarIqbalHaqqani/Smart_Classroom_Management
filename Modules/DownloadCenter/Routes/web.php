<?php

use Illuminate\Support\Facades\Route;
use Modules\DownloadCenter\Http\Controllers\ContentListController;
use Modules\DownloadCenter\Http\Controllers\ContentTypeController;
use Modules\DownloadCenter\Http\Controllers\VideoUploadController;
use Modules\DownloadCenter\Http\Controllers\ContentShareListController;

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
    Route::prefix('download-center')->middleware('auth')->group(function () {
        // -----------------Content Type----------------- //
        Route::get('content-type', [ContentTypeController::class, 'contentType'])->name('download-center.content-type');
        Route::post('content-type-save', [ContentTypeController::class, 'contentTypeSave'])->name('download-center.content-type-save');
        Route::get('content-type-edit/{id}', [ContentTypeController::class, 'contentTypeEdit'])->name('download-center.content-type-edit');
        Route::post('content-type-update', [ContentTypeController::class, 'contentTypeUpdate'])->name('download-center.content-type-update');
        Route::get('content-type-delete/{id}', [ContentTypeController::class, 'contentTypeDelete'])->name('download-center.content-type-delete');

        // -----------------Content List----------------- //
        Route::get('content-list', [ContentListController::class, 'contentList'])->name('download-center.content-list');
        Route::post('content-list-save', [ContentListController::class, 'contentListSave'])->name('download-center.content-list-save')->middleware('userRolePermission:download-center.content-list-save');
        Route::get('content-list-edit/{id}', [ContentListController::class, 'contentListEdit'])->name('download-center.content-list-edit');
        Route::post('content-list-update', [ContentListController::class, 'contentListUpdate'])->name('download-center.content-list-update')->middleware('userRolePermission:download-center.content-list-update');
        Route::get('content-list-delete/{id}', [ContentListController::class, 'contentListDelete'])->name('download-center.content-list-delete')->middleware('userRolePermission:download-center.content-list-delete');
        Route::get('content-list-search', [ContentListController::class, 'contentListSearch'])->name('download-center.content-list-search')->middleware('userRolePermission:download-center.content-list-search');

        // -----------------Content Share List----------------- //
        Route::get('content-share-list', [ContentShareListController::class, 'contentShareList'])->name('download-center.content-share-list');
        Route::post('content-share-list-save', [ContentShareListController::class, 'contentShareListSave'])->name('download-center.content-share-list-save')->middleware('userRolePermission:download-center.content-share-list-save');
        Route::post('content-generate-url-save', [ContentShareListController::class, 'contentGenarteUrlSave'])->name('download-center.content-generate-url-save')->middleware('userRolePermission:download-center.content-generate-url-save');
        Route::get('content-share-list-delete/{id}', [ContentShareListController::class, 'contentShareListDelete'])->name('download-center.content-share-list-delete');
        Route::get('content-share-link-modal/{id}', [ContentShareListController::class, 'contentShareLinkModal'])->name('download-center.content-share-link-modal');
        Route::get('content-view-link-modal/{id}', [ContentShareListController::class, 'contentViewLinkModal'])->name('download-center.content-view-link-modal');

        // -----------------Video List----------------- //
        Route::get('video-list', [VideoUploadController::class, 'videoList'])->name('download-center.video-list');
        Route::post('video-list-save', [VideoUploadController::class, 'videoListSave'])->name('download-center.video-list-save')->middleware('userRolePermission:download-center.video-list-save');
        Route::post('video-list-update', [VideoUploadController::class, 'videoListUpdate'])->name('download-center.video-list-update')->middleware('userRolePermission:download-center.video-list-update');
        Route::get('video-list-delete/{id}', [VideoUploadController::class, 'videoListDelete'])->name('download-center.video-list-delete')->middleware('userRolePermission:download-center.video-list-delete');
        Route::get('video-list-search', [VideoUploadController::class, 'videoListSearch'])->name('download-center.video-list-search')->middleware('userRolePermission:download-center.video-list-search');
        Route::get('video-list-view-modal/{id}', [VideoUploadController::class, 'videoListViewModal'])->name('download-center.video-list-view-modal');
        Route::get('video-list-edit-modal/{id}', [VideoUploadController::class, 'videoListEditModal'])->name('download-center.video-list-edit-modal');

        Route::get('content-share-list/{id}', [ContentShareListController::class, 'parentContentShareList'])->name('download-center.parent-content-share-list');
        Route::get('video-list/{id}', [VideoUploadController::class, 'parentVideoList'])->name('download-center.parent-video-list');
    });
});
Route::get('download-center/content-share-link/{url}', [ContentShareListController::class, 'contentShareLink'])->name('download-center.content-share-link');
