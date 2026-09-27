<?php

use App\Http\Controllers\AdminListController;
use App\Http\Controllers\OsisChairmanCandidateController;
use App\Http\Controllers\SettingWebController;
use App\Http\Controllers\UserSelectCandidateController;
use App\Http\Controllers\web\AdminController;
use App\Http\Controllers\web\AdminUserController;
use App\Http\Controllers\web\AuthAdminController;
use App\Http\Controllers\web\AuthUserController;
use App\Http\Controllers\web\HomeController;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| ROUTE AUTH 🟢
|--------------------------------------------------------------------------
*/

Route::controller(AuthUserController::class)->prefix('/user')->group(function () {
    Route::get('/login', 'index')->name('login.user');
    Route::post('/auth', 'authenticate')->name('auth.user')->middleware('throttle:voter-login');
    Route::get('/logout', 'userLogout')->name('logout.user');
});

Route::controller(AuthAdminController::class)->prefix('/admin')->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/auth', 'authenticate')->name('auth.admin')->middleware('throttle:5,1');
    Route::get('/logout', 'adminLogout')->name('logout.admin');
});
/*
|--------------------------------------------------------------------------
| END AUTH 🟤
|--------------------------------------------------------------------------
*/








/*
|--------------------------------------------------------------------------
| ROUTE DASHBOARD ADMIN 🟢
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'admin'], function () {

    Route::controller(AdminController::class)->prefix('/admin')->group(function () {
        Route::get('/', 'index')->name('dashboard');
        Route::post('/reset-all-votes', 'resetAllVotes')->name('admin.reset-all-votes');
    });

    Route::controller(OsisChairmanCandidateController::class)->prefix('/admin/osis-candidate')->group(function () {
        Route::get('/', 'index')->name('question');
        Route::get('/create', 'create')->name('question-create');
        Route::post('/', 'store')->name('question-store');
        Route::get('/edit/{id}', 'edit')->name('question-edit');
        Route::post('/{id}', 'update')->name('question-update');
        Route::delete('/{id}', 'destroy')->name('question-delete');
    });




    Route::controller(AdminUserController::class)->prefix('/admin/user')->group(function () {
        Route::get('/', 'index')->name('user');
        Route::post('/reset/{id}', 'resetQuestion')->name('user.reset');
        Route::delete('/{id}', 'destroy')->name('user.delete');
        Route::post('/selected-delete', 'selectedDestroy')->name('user.selected.delete');
        Route::post('/delete-all', 'deleteAll')->name('user.delete.all');
        Route::post('/import-excel', 'import_excel')->name('question.upload');
    });

    Route::controller(AdminListController::class)->prefix('/admin/admin-list')->group(function () {
        Route::get('/', 'index')->name('admin');
        Route::get('/create', 'create')->name('admin.create');
        Route::post('/', 'store')->name('admin.store');
        Route::get('/edit/{id}', 'edit')->name('admin.edit');
        Route::delete('/{id}', 'destroy')->name('admin.delete');
    });

    Route::controller(SettingWebController::class)->prefix('/admin/web-setting')->group(function () {
        Route::get('/', 'index')->name('web-setting.index');
        Route::post('/front-user-page', 'storeFrontPage')->name('web-setting.front-page');
        Route::post('/voting-time', 'storeVotingTime')->name('web-setting.voting-time');
        Route::post('/update-status', [HomeController::class, 'updateStatus'])->name('update.status');
    });
});
/*
|--------------------------------------------------------------------------
| END DASHBOARD ADMIN 🟤
|--------------------------------------------------------------------------
*/






/*
|--------------------------------------------------------------------------
| ROUTE FRONTEND PAGE 🟢
|--------------------------------------------------------------------------
*/
Route::controller(HomeController::class)->prefix('/')->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/live-count', 'liveCount')->name('live.count');
    Route::get('/api/live-votes', 'liveVotesApi')->name('api.live.votes');
    Route::get('/show-off', 'showOff')->name('dashboard.showoff');
});

Route::controller(UserSelectCandidateController::class)->prefix('/user-choose')->group(function () {
    Route::post('/', 'store')->name('result.store');
});
/*
|--------------------------------------------------------------------------
| END FRONTEND PAGE 🟤
|--------------------------------------------------------------------------
*/