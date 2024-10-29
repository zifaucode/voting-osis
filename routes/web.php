<?php

use App\Http\Controllers\AdminListController;
use App\Http\Controllers\OsisChairmanCandidateController;
use App\Http\Controllers\web\AddictionLevelController;
use App\Http\Controllers\web\AdminController;
use App\Http\Controllers\web\AdminQuestionController;
use App\Http\Controllers\web\AdminUserController;
use App\Http\Controllers\web\AuthAdminController;
use App\Http\Controllers\web\AuthUserController;
use App\Http\Controllers\web\HomeController;
use App\Http\Controllers\web\ResultQuestionController;
use App\Http\Controllers\web\RulesController;
use App\Http\Controllers\web\UserQuestionController;
use App\Http\Controllers\web\UserResultController;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| ROUTE AUTH 🟢
|--------------------------------------------------------------------------
*/

Route::controller(AuthUserController::class)->prefix('/user')->group(function () {
    Route::get('/login', 'index')->name('login.user');
    Route::get('/register', 'register')->name('register');
    Route::post('/new-register', 'newRegister')->name('new.register');
    Route::post('/auth', 'authenticate')->name('auth.user');
    Route::get('/logout', 'userLogout')->name('logout.user');
});

Route::controller(AuthAdminController::class)->prefix('/admin')->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/auth', 'authenticate')->name('auth.admin');
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
        Route::post('/import-excel', 'import_excel')->name('question.upload');
    });

    Route::controller(AdminListController::class)->prefix('/admin/admin-list')->group(function () {
        Route::get('/', 'index')->name('admin');
        Route::get('/create', 'create')->name('admin.create');
        Route::post('/', 'store')->name('admin.store');
        Route::get('/edit/{id}', 'edit')->name('admin.edit');
        Route::delete('/{id}', 'destroy')->name('admin.delete');
    });

    Route::controller(RulesController::class)->prefix('/admin/rules')->group(function () {
        Route::get('/', 'index')->name('rules');
        Route::get('/create', 'create')->name('rules.create');
        Route::post('/', 'store')->name('rules.store');
        Route::post('/{id}', 'update')->name('rules.update');
        Route::get('/edit/{id}', 'edit')->name('rules.edit');
        Route::delete('/{id}', 'destroy')->name('rules.delete');
    });

    Route::controller(AddictionLevelController::class)->prefix('/admin/addiction-level')->group(function () {
        Route::get('/', 'index')->name('addiction-level');
        Route::get('/create', 'create')->name('addiction-level.create');
        Route::post('/', 'store')->name('addiction-level.store');
        Route::post('/{id}', 'store')->name('addiction-level.update');
        Route::get('/edit/{id}', 'edit')->name('addiction-level.edit');
        Route::post('/{id}', 'update')->name('addiction-level.update');
        Route::delete('/{id}', 'destroy')->name('addiction-level.delete');
    });
});
/*
|--------------------------------------------------------------------------
| END DASHBOARD ADMIN 🟤
|--------------------------------------------------------------------------
*/






/*
|--------------------------------------------------------------------------
| ROUTE USER PAGE 🟢
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'user'], function () {
    Route::controller(UserQuestionController::class)->prefix('/user/question')->group(function () {
        Route::get('/', 'index')->name('question.user');
    });

    Route::controller(UserResultController::class)->prefix('/user/result')->group(function () {
        Route::get('/', 'index')->name('result.user');
    });

    Route::controller(ResultQuestionController::class)->prefix('/user/result')->group(function () {
        Route::post('/', 'store')->name('result.store');
        Route::get('/print/{id}', 'print')->name('result.print');
    });
});
/*
|--------------------------------------------------------------------------
| END USER PAGE 🟤
|--------------------------------------------------------------------------
*/






/*
|--------------------------------------------------------------------------
| ROUTE FRONTEND PAGE 🟢
|--------------------------------------------------------------------------
*/
Route::controller(HomeController::class)->prefix('/')->group(function () {
    Route::get('/', 'index')->name('dashboard');
});
/*
|--------------------------------------------------------------------------
| END FRONTEND PAGE 🟤
|--------------------------------------------------------------------------
*/