<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WeightTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\GoalSettingRequest;
use App\Models\WeightLog;

class AuthController extends Controller
{
    /**
     * 会員登録画面（ステップ1）の表示
     */
    public function showRegistrationForm()
    {
        return view('auth.register_step1');
    }

    /**
     * 会員登録処理（ステップ1）
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('register.step2');
    }

    /**
     * 初期目標体重登録画面（ステップ2）の表示
     */
    public function showInitialGoalForm()
    {
        return view('auth.register_step2');
    }

    /**
     * 初期目標体重の登録
     */
    public function storeInitialGoal(GoalSettingRequest $request)
    {
        $userId = Auth::id();

        WeightTarget::create([
            'user_id' => Auth::id(),
            'target_weight' => $request->target_weight,
        ]);

        // 現在の体重を weight_logs に登録
        WeightLog::create([
            'user_id' => $userId,
            'date' => now()->toDateString(),
            'weight' => $request->current_weight,
            'calories' => null,
            'exercise_time' => null,
            'exercise_content' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('weight_logs.index');
    }

    /**
     * ログイン画面の表示
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            return redirect()->route('weight_logs.index');
        }

        return back()->withErrors(['email' => 'ログイン情報が正しくありません。']);
    }

    /**
     * ログアウト処理
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
