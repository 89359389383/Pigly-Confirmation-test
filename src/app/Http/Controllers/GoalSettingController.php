<?php

namespace App\Http\Controllers;

use App\Models\WeightTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\GoalSettingRequest;

class GoalSettingController extends Controller
{
    /**
     * 目標体重設定画面の表示
     */
    public function edit()
    {
        $user = Auth::user();
        $goal = $user->weightTarget;

        return view('weight_logs.goal_setting', compact('goal'));
    }

    /**
     * 目標体重の更新
     */
    public function update(GoalSettingRequest $request)
    {
        $user = Auth::user();
        $goal = $user->weightTarget;

        if ($goal) {
            $goal->update(['target_weight' => $request->target_weight]);
        } else {
            WeightTarget::create([
                'user_id' => $user->id,
                'target_weight' => $request->target_weight,
            ]);
        }

        return redirect()->route('weight_logs.index');
    }
}
