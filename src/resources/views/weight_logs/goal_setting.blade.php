<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PiGLy - 目標体重設定</title>
    <link rel="stylesheet" href="{{ asset('css/weight_logs/goal_setting.css') }}">
</head>

<style>
    .main-content {
        max-width: 400px;
        margin: auto;
        margin-top: 150px;
        padding: 2rem;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .button {
        display: inline-flex;
        /* flexboxを適用してボタン内の文字を中央配置 */
        justify-content: center;
        /* 横中央 */
        align-items: center;
        /* 縦中央 */
        text-align: center;
        /* 文字を中央揃え */
        padding: 10px 20px;
        /* 余白を追加 */
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        text-decoration: none;
        /* リンクの下線を消す */
        width: 120px;
        /* ボタン幅を指定 */
        height: 40px;
        /* ボタンの高さを指定 */
    }

    .button-back {
        background-color: #e0e0e0;
        color: #333;
    }

    .button-update {
        background: linear-gradient(to right, #b19cd9, #ff69b4);
        color: white;
    }
</style>

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