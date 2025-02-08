<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PiGLy - 新規会員登録</title>
    <link rel="stylesheet" href="{{ asset('css/auth/register_step2.css') }}">
</head>

<style>
    .input-group {
        display: flex;
        align-items: center;
        /* 垂直中央揃え */
        gap: 0.5rem;
        /* インプットと単位の間に適度なスペースを追加 */
        width: 100%;
        /* 幅を統一 */
    }

    .form-input {
        flex: 1;
        /* 入力欄が可能な限りの幅を取るようにする */
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
    }

    .unit {
        font-size: 1rem;
        color: #333;
        white-space: nowrap;
        /* kgが折り返されないようにする */
    }

    .step {
        font-size: 1rem;
        color: #666;
        margin-bottom: 30px;
        justify-content: center;
        text-align: center;
    }
</style>

<body>
    <div class="container">
        <h1 class="logo">PiGLy</h1>
        <h2 class="title">新規会員登録</h2>
        <p class="step">STEP2 体重データの入力</p>

        <form method="POST" action="{{ route('register.initial_goal') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">現在の体重</label>
                <div class="input-group">
                    <input type="number" name="current_weight" class="form-input" placeholder="現在の体重を入力" step="0.1">
                    <span class="unit">kg</span>
                </div>
                @error('weight')
                <p class="error-message" style="color: red;">
                    {!! nl2br(e($message)) !!}
                </p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">目標の体重</label>
                <div class="input-group">
                    <input type="number" name="target_weight" class="form-input" placeholder="目標の体重を入力" step="0.1">
                    <span class="unit">kg</span>
                </div>
                @error('target_weight')
                <p class="error-message" style="color: red;">
                    {!! nl2br(e($message)) !!}
                </p>
                @enderror
            </div>

            <button type="submit" class="submit-btn">アカウント作成</button>
        </form>
    </div>
</body>

</html>