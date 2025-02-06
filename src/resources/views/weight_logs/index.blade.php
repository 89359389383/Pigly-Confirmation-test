<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>PiGLy - Weight Tracker</title>
    <link rel="stylesheet" href="{{ asset('css/weight_logs/index.css') }}">
</head>

<body>
    <header class="header">
        <div class="logo">PiGLy</div>
        <div class="header-buttons">
            <!-- 目標体重設定ボタンをクリックすると goal_setting ページへ遷移 -->
            <a href="{{ route('weight_logs.goal_setting') }}" class="header-btn">目標体重設定</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="header-btn">ログアウト</button>
            </form>
        </div>
    </header>
    <main class="main-content">
        <div class="metrics">
            <div class="metric-box">
                <div class="metric-label">目標体重</div>
                <div class="metric-value">
                    {{ $weightTarget ? $weightTarget->target_weight : '未設定' }}
                    <span class="metric-unit">kg</span>
                </div>
            </div>
            <div class="metric-box">
                <div class="metric-label">目標まで</div>
                <div class="metric-value">
                    @if ($latestWeightLog && $weightTarget)
                    {{ number_format($latestWeightLog->weight - $weightTarget->target_weight, 1) }}
                    @else
                    未登録
                    @endif
                    <span class="metric-unit">kg</span>
                </div>
            </div>
            <div class="metric-box">
                <div class="metric-label">最新体重</div>
                <div class="metric-value">
                    {{ $latestWeightLog ? $latestWeightLog->weight : '未登録' }}
                    <span class="metric-unit">kg</span>
                </div>
            </div>
        </div>
        <div class="controls">
            <form action="{{ route('weight_logs.search') }}" method="GET">
                <div class="date-range">
                    <input type="date" name="start_date" class="date-input" value="{{ request('start_date') }}">
                    <span>~</span>
                    <input type="date" name="end_date" class="date-input" value="{{ request('end_date') }}">
                    <button type="submit" class="search-btn">検索</button>
                </div>
            </form>
            <button class="add-data-btn">データ追加</button>
        </div>
        <!-- リセットボタン -->
        @if(request('start_date') || request('end_date'))
        <a href="{{ route('weight_logs.index') }}" class="reset-btn">リセット</a>
        @endif
        <table class="data-table">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>体重</th>
                    <th>食事摂取カロリー</th>
                    <th>運動時間</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($weightLogs as $weightLog)
                <tr data-id="{{ $weightLog->id }}">
                    <td>{{ \Carbon\Carbon::parse($weightLog->date)->format('Y/m/d') }}</td>
                    <td>{{ $weightLog->weight }}<span class="metric-unit">kg</span></td>
                    <td>{{ $weightLog->calories ? $weightLog->calories . 'cal' : '未登録' }}</td>
                    <td>{{ $weightLog->exercise_time ? \Carbon\Carbon::parse($weightLog->exercise_time)->format('H:i') : '未登録' }}</td>
                    <td><a href="{{ route('weight_logs.show', $weightLog->id) }}" class="edit-btn">✎</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">データがありません</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <!-- ページネーション -->
        <div class="pagination">
            {{ $weightLogs->appends(request()->query())->links('vendor.pagination.default') }}
        </div>
    </main>
</body>

</html>