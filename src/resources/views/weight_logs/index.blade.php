<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>PiGLy - Weight Tracker</title>
    <link rel="stylesheet" href="{{ asset('css/weight_logs/index.css') }}">
    <style>
        .controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: white;
            border-bottom: 1px solid #eee;
        }

        .header-buttons {
            display: flex;
            gap: 1rem;
        }

        .header-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            background-color: #f0f0f0;
            color: #333;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            text-decoration: none;
        }

        .search-results {
            margin-bottom: 20px;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal {
            background-color: white;
            padding: 1.088rem;
            border-radius: 5.6px;
            width: 90%;
            max-width: 408px;
        }

        .modal-title {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 0.816rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            margin-bottom: 0.272rem;
            font-size: 0.68rem;
        }

        .required-badge {
            background-color: #ff4444;
            color: white;
            font-size: 0.68rem;
            padding: 0.125rem 0.375rem;
            border-radius: 2px;
            margin-left: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.272rem;
            border: 1px solid #e0e0e0;
            border-radius: 2.4px;
            font-size: 0.646rem;
        }

        .form-input::placeholder {
            color: #999;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .button {
            flex: 1;
            padding: 0.544rem;
            border: none;
            border-radius: 2.4px;
            font-size: 0.646rem;
            cursor: pointer;
            text-align: center;
        }

        .btn-back {
            background-color: #e0e0e0;
            color: #666;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-submit {
            padding: 0.5rem 1.5rem;
            background: linear-gradient(to right, #b19cd9, #ff69b4);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .unit {
            position: relative;
        }

        .unit::after {
            position: absolute;
            right: 0.68rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 0.646rem;
        }

        .unit-kg::after {
            content: 'kg';
        }

        .unit-cal::after {
            content: 'cal';
        }

        textarea.form-input {
            height: 54.4px;
            resize: vertical;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="logo">PiGLy</div>
        <div class="header-buttons">
            <a href="{{ route('weight_logs.goal_setting') }}" class="header-btn">⚙️ 目標体重設定</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="header-btn">🚪 ログアウト</button>
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
                    @if(request('start_date') || request('end_date'))
                    <a href="{{ route('weight_logs.index') }}" class="reset-btn">リセット</a>
                    @endif
                </div>
            </form>
            <button class="add-data-btn">データ追加</button>
        </div>

        @if(request('start_date') || request('end_date'))
        <div class="search-results">
            <p>
                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('Y年m月d日') : '開始日未指定' }}
                ～
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('Y年m月d日') : '終了日未指定' }}
                の検索結果 {{ $weightLogs->total() }} 件
            </p>
        </div>
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

        <div class="pagination">
            {{ $weightLogs->appends(request()->query())->links('vendor.pagination.default') }}
        </div>
    </main>

    <div id="modal-overlay" class="modal-overlay">
        <div class="modal">
            <h2 class="modal-title">Weight Logを追加</h2>
            <form id="weightLogForm" action="{{ route('weight_logs.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">日付 <span class="required-badge">必須</span></label>
                    <input type="date" class="form-input" name="date" value="{{ now()->toDateString() }}">
                    @error('date')
                    <p class="error-message" style="color: red;">
                        {!! nl2br(e($message)) !!}
                    </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">体重 <span class="required-badge">必須</span></label>
                    <input type="number" class="form-input" name="weight" step="0.1" placeholder="50.0">
                    @error('weight')
                    <p class="error-message" style="color: red;">
                        {!! nl2br(e($message)) !!}
                    </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">摂取カロリー <span class="required-badge">必須</span></label>
                    <input type="number" class="form-input" name="calories" placeholder="1200">
                    @error('calories')
                    <p class="error-message" style="color: red;">
                        {!! nl2br(e($message)) !!}
                    </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">運動時間 <span class="required-badge">必須</span></label>
                    <input type="time" class="form-input" name="exercise_time">
                    @error('exercise_time')
                    <p class="error-message" style="color: red;">
                        {!! nl2br(e($message)) !!}
                    </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">運動内容</label>
                    <textarea name="exercise_content" class="form-input" placeholder="運動内容を追加" rows="4"></textarea>
                    @error('exercise_content')
                    <p class="error-message" style="color: red;">
                        {!! nl2br(e($message)) !!}
                    </p>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="button" id="closeModal" class="btn btn-back">戻る</button>
                    <button type="submit" class="btn btn-submit">登録</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modalOverlay = document.getElementById("modal-overlay");
            const addDataBtn = document.querySelector(".add-data-btn");
            const closeModalBtn = document.getElementById("closeModal");

            addDataBtn.addEventListener("click", () => {
                modalOverlay.style.display = "flex";
            });

            closeModalBtn.addEventListener("click", () => {
                modalOverlay.style.display = "none";
            });

            if (document.querySelector(".error-message")) {
                modalOverlay.style.display = "flex";
            } else {
                modalOverlay.style.display = "none";
            }
        });
    </script>
</body>

</html>