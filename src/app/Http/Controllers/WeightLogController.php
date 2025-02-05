<?php

namespace App\Http\Controllers; // このコントローラが所属する名前空間を指定しています。
// 名前空間を指定することで同じクラス名が別の場所で使われていても区別がつくようにしています。

use App\Models\WeightLog; // "weight_logs"テーブルとやり取りするモデルを読み込んでいます。
use Illuminate\Http\Request; // フォームから送られた情報などを受け取るためのクラスです。
use Illuminate\Support\Facades\Auth; // ログインしているユーザー情報を取得するなど、認証に関する機能を使うためのクラスです。
use App\Http\Requests\WeightLogRequest;
use App\Models\WeightTarget; // WeightTargetモデルを追加

class WeightLogController extends Controller // WeightLogControllerクラスは、体重ログに関する処理をまとめたコントローラです。
{
    /**
     * 体重管理画面の表示（一覧）
     */
    public function index() // "index"メソッドでは、体重ログの一覧画面を表示します。
    {
        // ユーザーが認証されていない場合、会員登録画面にリダイレクト
        if (!auth()->check()) {
            return redirect()->route('register.step1');
        }

        $user = Auth::user(); // 現在ログインしているユーザー情報を取得します。
        $weightLogs = WeightLog::where('user_id', $user->id) // WeightLogモデルを使って、ログインユーザーのデータだけを取り出します。
            ->orderBy('date', 'desc') // 日付(date)を新しい順(desc)に並べ替えます。
            ->paginate(8); // ページネーションで1ページあたり8件ずつ表示できるようにします。

        // 目標体重を取得（ユーザーごとの `WeightTarget` がある前提）
        $weightTarget = WeightTarget::where('user_id', $user->id)->first();

        return view('weight_logs.index', compact('weightLogs', 'weightTarget'));
    }

    /**
     * 体重の新規登録
     */
    public function store(WeightLogRequest $request) // "store"メソッドでは、新しい体重ログの登録を行います。
    {
        // バリデーションは別ファイルで行うため、このメソッドからは削除しています。

        WeightLog::create([ // WeightLogモデルのcreateメソッドを使って新しいレコードを作成します。
            'user_id' => Auth::id(), // ログインしているユーザーのIDをセットします。
            'date' => $request->date, // フォームから送られた日付をセットします。
            'weight' => $request->weight, // フォームから送られた体重をセットします。
            'calories' => $request->calories, // フォームから送られたカロリーをセットします（null可）。
            'exercise_time' => $request->exercise_time, // フォームから送られた運動時間をセットします（null可）。
            'exercise_content' => $request->exercise_content, // フォームから送られた運動内容をセットします（null可）。
        ]);

        return redirect()->route('weight_logs.index')->with('success', '体重を登録しました。');
        // 登録が完了したら、体重ログ一覧ページに戻って「体重を登録しました」とメッセージを表示します。
    }

    /**
     * 体重詳細画面の表示
     */
    public function show($weightLogId) // "show"メソッドでは、特定の体重ログの詳細を表示します。
    {
        $weightLog = WeightLog::findOrFail($weightLogId);
        // findOrFailは、指定したIDのレコードがなければエラーを出します。

        return view('weight_logs.show', compact('weightLog'));
        // 'weight_logs.show'というビューを表示し、取得した$weightLogのデータをビューに渡します。
    }

    /**
     * 体重の更新
     */
    public function update(WeightLogRequest $request, $weightLogId) // "update"メソッドでは、既存の体重ログを変更します。
    {
        // バリデーションは別ファイルで行うため、このメソッドからは削除しています。

        $weightLog = WeightLog::findOrFail($weightLogId);
        // 既存のデータをIDで取得し、見つからない場合はエラーを出します。

        $weightLog->update($request->all());
        // フォームから送られた全ての入力データ($request->all())を使って$weightLogを更新します。

        return redirect()->route('weight_logs.index')->with('success', '体重情報を更新しました。');
        // 更新が完了したら、体重ログ一覧ページに戻って「体重情報を更新しました」とメッセージを表示します。
    }

    /**
     * 体重の削除
     */
    public function destroy($weightLogId) // "destroy"メソッドでは、指定したIDの体重ログを削除します。
    {
        $weightLog = WeightLog::findOrFail($weightLogId);
        // 指定されたIDのデータが存在しない場合はエラーを出します。

        $weightLog->delete(); // 取得したデータを削除します。

        return redirect()->route('weight_logs.index')->with('success', '体重情報を削除しました。');
        // 削除が完了したら、体重ログ一覧ページへ戻り「体重情報を削除しました」とメッセージを表示します。
    }

    /**
     * 体重検索
     */
    public function search(Request $request) // "search"メソッドでは、日付範囲を使った検索を行います。
    {
        $user = Auth::user(); // ログインしているユーザー情報を取得します。
        $weightLogs = WeightLog::where('user_id', $user->id) // ログイン中のユーザーに紐づくデータを絞り込みます。
            ->whereBetween('date', [$request->start_date, $request->end_date]) // 日付が指定された範囲内のものだけをさらに絞り込みます。
            ->orderBy('date', 'desc') // 日付で並べ替えて最新から順にします。
            ->paginate(8); // 1ページあたり8件ずつ表示できるようにします。

        return view('weight_logs.index', compact('weightLogs'));
        // 'weight_logs.index'ビューを再利用して、検索結果の体重ログ一覧を表示します。
    }
}
