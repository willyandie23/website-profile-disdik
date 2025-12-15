<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LinkController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\FieldController;
use App\Http\Controllers\API\AppLogController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\GaleryController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\DownloadController;
use App\Http\Controllers\API\IdentityController;
use App\Http\Controllers\API\JenisCutiController;
use App\Http\Controllers\API\OrganizationController;
use App\Http\Controllers\API\PengajuanCutiController;
use App\Http\Controllers\Frontend\DownloadController as FrontendDownloadController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:api');
Route::resource('app-logs', AppLogController::class, ['only' => ['index', 'show']]);
Route::get('/organizations', [OrganizationController::class, 'index']);
Route::get('/banner', [BannerController::class, 'index']);
Route::get('/galery', [GaleryController::class, 'index']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/downloads', [DownloadController::class, 'index']);
Route::post('/downloads/{id}/increment', [FrontendDownloadController::class, 'incrementDownload']);
Route::post('/downloads/{id}/download', [DownloadController::class, 'download']);
Route::get('/identities', [IdentityController::class, 'index']);
Route::get('/fields', [FieldController::class, 'index']);
Route::get('/links', [LinkController::class, 'index']);

Route::get('/jenis-cuti', [JenisCutiController::class, 'index']);
Route::get('/pengajuan-cuti', [PengajuanCutiController::class, 'index']);
Route::get('/pengajuan-cuti/{id}', [PengajuanCutiController::class, 'show']);
Route::post('/pengajuan-cuti', [PengajuanCutiController::class, 'store']);


Route::middleware('auth:api')->group(function () {
    Route::post('/banner', [BannerController::class, 'store']);
    Route::get('/banner/{id}', [BannerController::class, 'show']);
    Route::put('/banner/{id}', [BannerController::class, 'update']);
    Route::delete('/banner/{id}', [BannerController::class, 'destroy']);

    Route::post('/organizations', [OrganizationController::class, 'store']);
    Route::get('/organizations/{id}', [OrganizationController::class, 'show']);
    Route::put('/organizations/{id}', [OrganizationController::class, 'update']);
    Route::delete('/organizations/{id}', [OrganizationController::class, 'destroy']);

    Route::post('/galery', [GaleryController::class, 'store']);
    Route::get('/galery/{id}', [GaleryController::class, 'show']);
    Route::put('/galery/{id}', [GaleryController::class, 'update']);
    Route::delete('/galery/{id}', [GaleryController::class, 'destroy']);

    Route::post('/news', [NewsController::class, 'store']);
    Route::get('/news/{id}', [NewsController::class, 'show']);
    Route::put('/news/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);

    Route::post('/downloads', [DownloadController::class, 'store']);
    Route::get('/downloads/{id}', [DownloadController::class, 'show']);
    Route::put('/downloads/{id}', [DownloadController::class, 'update']);
    Route::delete('/downloads/{id}', [DownloadController::class, 'destroy']);

    Route::post('/identities', [IdentityController::class, 'store']);
    Route::get('/identities/{id}', [IdentityController::class, 'show']);
    Route::put('/identities/{id}', [IdentityController::class, 'update']);
    Route::delete('/identities/{id}', [IdentityController::class, 'destroy']);

    Route::post('/fields', [FieldController::class, 'store']);
    Route::get('/fields/{id}', [FieldController::class, 'show']);
    Route::put('/fields/{id}', [FieldController::class, 'update']);
    Route::delete('/fields/{id}', [FieldController::class, 'destroy']);

    Route::post('/links', [LinkController::class, 'store']);
    Route::get('/links/{id}', [LinkController::class, 'show']);
    Route::put('/links/{id}', [LinkController::class, 'update']);
    Route::delete('/links/{id}', [LinkController::class, 'destroy']);

    Route::post('/jenis-cuti', [JenisCutiController::class, 'store']);
    Route::get('/jenis-cuti/{id}', [JenisCutiController::class, 'show']);
    Route::put('/jenis-cuti/{id}', [JenisCutiController::class, 'update']);
    Route::delete('/jenis-cuti/{id}', [JenisCutiController::class, 'destroy']);

    Route::post('/pengajuan-cuti/{id}/teruskan', [PengajuanCutiController::class, 'teruskan']);
    Route::post('/pengajuan-cuti/{id}/minta_revisi', [PengajuanCutiController::class, 'mintaRevisi']);
    Route::post('/pengajuan-cuti/{id}/tolak', [PengajuanCutiController::class, 'tolak']);
    Route::post('/pengajuan-cuti/{id}/cancel_approval', [PengajuanCutiController::class, 'cancelApproval']);
    Route::post('/pengajuan-cuti/{id}/batalkan', [PengajuanCutiController::class, 'batalkan']);
    Route::post('/pengajuan-cuti/{id}/upload_final_pdf', [PengajuanCutiController::class, 'uploadFinalPdf']);
});
