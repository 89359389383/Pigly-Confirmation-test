<?php

use App\Http\Controllers\WeightLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoalSettingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/weight_logs', [WeightLogController::class, 'index'])->name('weight_logs.index');

// 体重管理の他のルートは認証が必要
Route::middleware('auth')->group(function () {
    // 体重登録
    Route::post('/weight_logs/create', [WeightLogController::class, 'store'])->name('weight_logs.store');

    // 体重検索
    Route::get('/weight_logs/search', [WeightLogController::class, 'search'])->name('weight_logs.search');

    // 目標設定
    Route::get('/weight_logs/goal_setting', [GoalSettingController::class, 'edit'])->name('weight_logs.goal_setting');
    Route::put('/weight_logs/goal_setting', [GoalSettingController::class, 'update'])->name('weight_logs.goal_update');

    // 体重詳細
    Route::get('/weight_logs/{weightLogId}', [WeightLogController::class, 'show'])->name('weight_logs.show');

    // 体重更新
    Route::put('/weight_logs/{weightLogId}/update', [WeightLogController::class, 'update'])->name('weight_logs.update');

    // 体重削除
    Route::delete('/weight_logs/{weightLogId}/delete', [WeightLogController::class, 'destroy'])->name('weight_logs.destroy');
});

// 会員登録
Route::get('/register/step1', [AuthController::class, 'showRegistrationForm'])->name('register.step1');
Route::post('/register/step1', [AuthController::class, 'register'])->name('register.store');

// 初期目標体重登録
Route::get('/register/step2', [AuthController::class, 'showInitialGoalForm'])->name('register.step2');
Route::post('/register/step2', [AuthController::class, 'storeInitialGoal'])->name('register.initial_goal');

// ログイン
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

// ログアウト
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
