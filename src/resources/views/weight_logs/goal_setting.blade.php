<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PiGLy - 目標体重設定</title>
    <link rel="stylesheet" href="{{ asset('css/weight_logs/goal_setting.css') }}">
</head>

<body>
    <header class="header">
        <a href="#" class="logo">PiGLy</a>
        <div class="header-buttons">
            <button class="header-button">
                ⚙️ 目標体重設定
            </button>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="header-button">🚪 ログアウト</button>
            </form>
        </div>
    </header>

    <main class="main-content">
        <h1 class="title">目標体重設定</h1>

        <form method="POST" action="{{ route('weight_logs.goal_update') }}">
            @csrf
            @method('PUT')

            <div class="weight-input">
                <input type="number" name="target_weight"
                    value="{{ old('target_weight', $goal->target_weight ?? '') }}"
                    step="0.1" min="0" max="999.9">
                <span>kg</span>
            </div>

            @error('target_weight')
            <p class="error-message" style="color: red;">
                {!! nl2br(e($message)) !!}
            </p>
            @enderror

            <div class="button-group">
                <a href="{{ route('weight_logs.index') }}" class="button button-back">戻る</a>
                <button type="submit" class="button button-update">更新</button>
            </div>
        </form>
    </main>
</body>

</html>