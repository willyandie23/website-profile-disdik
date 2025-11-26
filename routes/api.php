<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\AppLogController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\GaleryController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\DownloadController;
use App\Http\Controllers\API\FieldController;
use App\Http\Controllers\API\IdentityController;
use App\Http\Controllers\API\LinkController;
use App\Http\Controllers\API\OrganizationController;
use App\Http\Controllers\Frontend\DownloadController as FrontendDownloadController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:api');
Route::resource('app-logs', AppLogController::class, ['only' => ['index', 'show']]);

Route::middleware('auth:api', )->group(function () {
    
});