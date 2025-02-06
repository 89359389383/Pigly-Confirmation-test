<?php

namespace App\Http\Controllers; // このファイルのクラスが所属する名前空間を指定しています。
// 名前空間を使うことで、同じ名前のクラスが他にあっても衝突を防ぐことができます。

use App\Models\WeightTarget; // "weight_targets"テーブルとやり取りするためのモデルを読み込みます。
use Illuminate\Http\Request; // フォームなどからデータを受け取るためのクラスを読み込みます。
use Illuminate\Support\Facades\Auth; // ログインしているユーザー情報を取得するための機能を使います。
use App\Http\Requests\GoalSettingRequest;

class GoalSettingController extends Controller // GoalSettingControllerクラスは、目標体重の設定や更新を行うコントローラです。
{
    /**
     * 目標体重設定画面の表示
     */
    public function edit() // "edit"メソッドは、目標体重を編集する画面を表示する役割を持っています。
    {
        $user = Auth::user(); // 現在ログインしているユーザー情報を取得します。
        $goal = $user->weightTarget; // ユーザーに紐づいた「目標体重」のデータを取り出します。

        return view('weight_logs.goal_setting', compact('goal'));
        // 'weight_logs.goal_setting'というテンプレートに、$goal（目標体重）を渡して表示します。
    }

    /**
     * 目標体重の更新
     */
    public function update(GoalSettingRequest $request) // "update"メソッドは、実際に目標体重を更新する処理を行います。
    {
        // バリデーションは別ファイルで行うため、ここでは削除しています。

        $user = Auth::user(); // ログインしているユーザーの情報を取得します。
        $goal = $user->weightTarget; // ユーザーの目標体重データを取得します。

        if ($goal) { // もしすでに目標体重が登録されている場合
            $goal->update(['target_weight' => $request->target_weight]);
            // 既存のデータを、新しく入力された目標体重に更新します。
        } else {
            WeightTarget::create([
                'user_id' => $user->id, // ログイン中のユーザーIDを関連付けます。
                'target_weight' => $request->target_weight, // 入力された目標体重を保存します。
            ]);
            // まだ目標体重が登録されていない場合は、新しく目標体重のデータを作ります。
        }

        return redirect()->route('weight_logs.index');
    }
}
