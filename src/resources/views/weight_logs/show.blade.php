<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PiGLy - Weight Log</title>
    <link rel="stylesheet" href="{{ asset('css/weight_logs/show.css') }}">
</head>

<body>
    <header class="header">
        <a href="{{ route('weight_logs.index') }}" class="logo">PiGLy</a>
        <div class="header-buttons">
            <a href="{{ route('weight_logs.goal_setting') }}" class="header-button">⚙️ 目標体重設定</a>
            <a href="{{ route('logout') }}" class="header-button">🚪 ログアウト</a>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h1>Weight Log</h1>

            <!-- フォーム -->
            <form method="POST" action="{{ route('weight_logs.update', $weightLog->id) }}">
                @csrf
                @method('PUT')

                <!-- 日付 -->
                <div class="form-group">
                    <label for="date">日付</label>
                    <input type="date" name="date" value="{{ $weightLog->date }}">
                </div>

                <!-- 体重 -->
                <div class="form-group">
                    <label for="weight">体重</label>
                    <div class="input-wrapper">
                        <input type="number" name="weight" value="{{ $weightLog->weight }}" step="0.1">
                        <span class="unit">kg</span>
                    </div>
                </div>

                <!-- 摂取カロリー -->
                <div class="form-group">
                    <label for="calories">摂取カロリー</label>
                    <div class="input-wrapper">
                        <input type="number" name="calories" value="{{ $weightLog->calories }}">
                        <span class="unit">cal</span>
                    </div>
                </div>

                <!-- 運動時間 -->
                <div class="form-group">
                    <label for="exercise-time">運動時間</label>
                    <input type="time" name="exercise_time" value="{{ $weightLog->exercise_time }}">
                </div>

                <!-- 運動内容 -->
                <div class="form-group">
                    <label for="exercise-details">運動内容</label>
                    <textarea name="exercise_content" placeholder="運動内容を追加">{{ $weightLog->exercise_content }}</textarea>
                </div>

                <!-- ボタン -->
                <div class="button-group">
                    <a href="{{ route('weight_logs.index') }}" class="btn btn-back">戻る</a>
                    <button type="submit" class="btn btn-update">更新</button>
                </div>
            </form>

            <!-- 削除ボタン (別のフォームでDELETEリクエスト) -->
            <form method="POST" action="{{ route('weight_logs.destroy', $weightLog->id) }}" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete">🗑️</button>
            </form>
        </div>
    </div>
</body>

</html>