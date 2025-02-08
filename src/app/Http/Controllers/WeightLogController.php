<?php

namespace App\Http\Controllers;

use App\Models\WeightLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WeightLogRequest;
use App\Models\WeightTarget;

class WeightLogController extends Controller
{
    private const ITEMS_PER_PAGE = 8;

    /**
     * 体重管理画面の表示（一覧）
     */
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('register.step1');
        }

        $user = Auth::user();
        $weightLogs = WeightLog::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(self::ITEMS_PER_PAGE);
        $latestWeightLog = WeightLog::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->first();
        $weightTarget = WeightTarget::where('user_id', $user->id)->first();

        return view('weight_logs.index', compact('weightLogs', 'latestWeightLog', 'weightTarget'));
    }

    /**
     * 体重の新規登録
     */
    public function store(WeightLogRequest $request)
    {
        WeightLog::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'weight' => $request->weight,
            'calories' => $request->calories,
            'exercise_time' => $request->exercise_time,
            'exercise_content' => $request->exercise_content,
        ]);

        return redirect()->route('weight_logs.index');
    }

    /**
     * 体重詳細画面の表示
     */
    public function show($weightLogId)
    {
        $weightLog = WeightLog::findOrFail($weightLogId);
        return view('weight_logs.show', compact('weightLog'));
    }

    /**
     * 体重の更新
     */
    public function update(WeightLogRequest $request, $weightLogId)
    {
        $weightLog = WeightLog::findOrFail($weightLogId);
        $weightLog->update($request->all());

        return redirect()->route('weight_logs.index');
    }

    /**
     * 体重の削除
     */
    public function destroy($weightLogId)
    {
        $weightLog = WeightLog::findOrFail($weightLogId);
        $weightLog->delete();

        return redirect()->route('weight_logs.index');
    }

    /**
     * 体重検索
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = WeightLog::where('user_id', $user->id);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $weightLogs = $query->orderBy('date', 'desc')->paginate(self::ITEMS_PER_PAGE);
        $weightTarget = WeightTarget::where('user_id', $user->id)->first();
        $latestWeightLog = WeightLog::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->first();

        return view('weight_logs.index', compact('weightLogs', 'weightTarget', 'latestWeightLog'))
            ->with('start_date', $request->start_date)
            ->with('end_date', $request->end_date);
    }

    // バリデーションエラー時の処理
    public function storeWithValidationError(Request $request)
    {
        session()->flash('show_modal', true);
        return redirect()->back()->withErrors($request->errors())->withInput();
    }
}
