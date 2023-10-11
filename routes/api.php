<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\WorkerAuthController;
use App\Http\Controllers\ClientOrderController;
use App\Http\Controllers\WorkerReviewController;
use App\Http\Controllers\WorkerProfileController;
use App\Http\Controllers\AdminDashboard\PostStatusController;
use App\Http\Controllers\AdminDashboard\AdminNotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AdminController::class)->prefix('admin')->middleware('DbBackup')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('userProfile', 'userProfile');
});


Route::controller(WorkerAuthController::class)->prefix('worker')->middleware('DbBackup')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('userProfile', 'userProfile');
    Route::get('verify/{token}','verify');
});

Route::prefix('worker')->group(function () {

    Route::get('export', [FileController::class, 'export']);
    Route::post('import', [FileController::class, 'import']);
    Route::get('pendeing/orders', [ClientOrderController::class, 'workerOrder']);
    Route::put('update/order/{id}', [ClientOrderController::class, 'update']);
    Route::get('/review/post/{postId}', [WorkerReviewController::class, 'postRate']);
    Route::get('/profile', [WorkerProfileController::class, 'userProfile']);
    Route::get('/profile/edit', [WorkerProfileController::class, 'edit']);
    Route::post('/profile/update', [WorkerProfileController::class, 'update']);
    Route::delete('/profile/posts/delete', [WorkerProfileController::class, 'delete']);
});

Route::controller(ClientAuthController::class)->prefix('client')->middleware('DbBackup')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('userProfile', 'userProfile');
});

Route::get('/unauthorized', function () {
    return response()->json([
        "message" => "Unauthorized"
    ], 401);
})->name('login');


Route::controller(PostController::class)->prefix('worker/post')->group(function () {
    Route::post('/add', 'store')->middleware('auth:worker');
    Route::get('/show', 'index')->middleware('auth:admin');
    Route::get('/approved', 'approved');
    Route::get('/getPost/{id}', 'getPost');
});


Route::controller(AdminNotificationController::class)->middleware('auth:admin')->prefix('admin/notifications')->group(function () {
    
    Route::get('/index', 'index');
    Route::get('/unread', 'unread');
    Route::get('/markRead', 'markRead');
    Route::get('/deleteAll', 'deleteAll');
    Route::get('/delete/{id}', 'delete');
    
});

Route::post('worker/review', [WorkerReviewController::class, 'store'])->middleware('auth:client');


Route::controller(PostStatusController::class)->prefix('admin/post')->group(function () {
    Route::post('/status', 'changeStatus')->middleware('auth:admin');
});

Route::prefix('client')->group(function () {
    Route::controller(ClientOrderController::class)->prefix('/order')->group(function () {
        Route::post('/request', 'addOrder')->middleware('auth:client');
        Route::get('/approved', 'approvedOrders')->middleware('auth:client');
    });

    Route::controller(PaymentController::class)->group(function () {
        Route::get('/pay/{serviceId}', 'pay');
    });
});