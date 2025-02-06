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
    public function index()
    {
        // ユーザーが認証されていない場合、会員登録画面にリダイレクト
        if (!auth()->check()) {
            return redirect()->route('register.step1');
        }

        $user = Auth::user(); // 現在ログインしているユーザー情報を取得

        // 体重ログの一覧（ページネーション 8件ずつ）
        $weightLogs = WeightLog::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(8);

        // 最新の体重記録を取得（1件）
        $latestWeightLog = WeightLog::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->first();

        // 目標体重を取得
        $weightTarget = WeightTarget::where('user_id', $user->id)->first();

        // ビューにデータを渡す
        return view('weight_logs.index', compact('weightLogs', 'latestWeightLog', 'weightTarget'));
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

        return redirect()->route('weight_logs.index');
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

        return redirect()->route('weight_logs.index');
    }

    /**
     * 体重の削除
     */
    public function destroy($weightLogId) // "destroy"メソッドでは、指定したIDの体重ログを削除します。
    {
        $weightLog = WeightLog::findOrFail($weightLogId);
        // 指定されたIDのデータが存在しない場合はエラーを出します。

        $weightLog->delete(); // 取得したデータを削除します。

        return redirect()->route('weight_logs.index');
    }

    /**
     * 体重検索
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = WeightLog::where('user_id', $user->id);

        // 検索開始日が入力されている場合
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        // 検索終了日が入力されている場合
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // 体重データを取得
        $weightLogs = $query->orderBy('date', 'desc')->paginate(8);

        // 目標体重を取得
        $weightTarget = WeightTarget::where('user_id', $user->id)->first();

        // 最新の体重データを取得
        $latestWeightLog = WeightLog::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->first();

        return view('weight_logs.index', compact('weightLogs', 'weightTarget', 'latestWeightLog'))
            ->with('start_date', $request->start_date)
            ->with('end_date', $request->end_date);
    }
}
