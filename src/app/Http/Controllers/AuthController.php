<?php

namespace App\Http\Controllers; // このファイルが所属する「名前空間」を指定しています。
// 名前空間を使うことでクラスをグループ化し、同じ名前のクラスがあっても区別できるようにしています。

use App\Models\User; // データベースの「users」テーブルとやりとりするためのUserモデルを読み込みます。
use App\Models\WeightTarget; // データベースの「weight_targets」テーブルとやりとりするためのWeightTargetモデルを読み込みます。
use Illuminate\Http\Request; // HTTPリクエストを受け取るためのクラスを読み込みます。
use Illuminate\Support\Facades\Auth; // ログインなどの認証機能を利用するためのクラスを読み込みます。
use Illuminate\Support\Facades\Hash; // パスワードのハッシュ化（暗号化）をするためのクラスを読み込みます。
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\GoalSettingRequest;
use App\Models\WeightLog; // WeightLogモデルを追加

class AuthController extends Controller // AuthControllerは「登録」や「ログイン」などの認証に関する処理をまとめたコントローラです。
{
    /**
     * 会員登録画面（ステップ1）の表示
     */
    public function showRegistrationForm() // 「登録画面を表示する」ためのメソッドです。
    {
        return view('auth.register_step1'); // authディレクトリのregister_step1ビュー（画面）を表示します。
    }

    /**
     * 会員登録処理（ステップ1）
     */
    public function register(RegisterRequest $request) // フォームから送信された情報を使って会員登録を実行するメソッドです。
    {
        // バリデーションコードは別ファイルで行うため、ここでは省略します。

        $user = User::create([ // 新しいユーザーを作成します。
            'name' => $request->name, // ユーザーの名前をデータベースに保存します。
            'email' => $request->email, // ユーザーのメールアドレスをデータベースに保存します。
            'password' => Hash::make($request->password), // パスワードをハッシュ化して保存します。
        ]);

        Auth::login($user); // 登録直後に、そのままログイン状態にします。

        return redirect()->route('register.step2'); // 「ステップ2（初期目標体重の登録画面）」にリダイレクト（ページ移動）します。
    }

    /**
     * 初期目標体重登録画面（ステップ2）の表示
     */
    public function showInitialGoalForm() // 初期目標体重を入力する画面を表示するメソッドです。
    {
        return view('auth.register_step2'); // authディレクトリのregister_step2ビュー（画面）を表示します。
    }

    /**
     * 初期目標体重の登録
     */
    public function storeInitialGoal(GoalSettingRequest $request) // フォームから入力された初期目標体重をデータベースに保存するメソッドです。
    {
        $userId = Auth::id(); // ログイン中のユーザーのIDを取得

        WeightTarget::create([ // 新しい目標体重データを作成します。
            'user_id' => Auth::id(), // ログイン中のユーザーのIDを設定します。
            'target_weight' => $request->target_weight, // 入力された目標体重をデータベースに保存します。
        ]);

        // 現在の体重を weight_logs に登録
        WeightLog::create([
            'user_id' => $userId, // ユーザーID
            'date' => now()->toDateString(), // 現在の日付
            'weight' => $request->current_weight, // 入力された現在の体重
            'calories' => null, // 初回登録時はカロリー情報なし
            'exercise_time' => null, // 初回登録時は運動時間なし
            'exercise_content' => null, // 初回登録時は運動内容なし
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('weight_logs.index');
        // weight_logs.indexのルートにリダイレクトします。
    }

    /**
     * ログイン画面の表示
     */
    public function showLoginForm() // ログイン画面を表示するメソッドです。
    {
        return view('auth.login'); // authディレクトリのloginビュー（画面）を表示します。
    }

    /**
     * ログイン処理
     */
    public function login(LoginRequest $request) // フォームから送信された情報を使ってログインを行うメソッドです。
    {
        // バリデーションコードは別ファイルで行うため、ここでは省略します。

        // 下記のように配列を作り、メールアドレスとパスワードを取り出します。
        $credentials = [
            'email' => $request->email, // フォームから送信されたメールアドレス
            'password' => $request->password, // フォームから送信されたパスワード
        ];

        if (Auth::attempt($credentials)) { // Auth::attemptでログインを試み、成功したらtrueが返ってきます。
            return redirect()->route('weight_logs.index'); // ログインに成功したら体重ログ一覧画面に移動します。
        }

        // ログインに失敗した場合はこちらの処理を実行し、エラーを返します。
        return back()->withErrors(['email' => 'ログイン情報が正しくありません。']);
    }

    /**
     * ログアウト処理
     */
    public function logout() // ログイン状態を終了させるメソッドです。
    {
        Auth::logout(); // 現在ログインしているユーザーをログアウトさせます。
        return redirect()->route('login'); // ログアウト後にログイン画面へリダイレクトします。
    }
}
