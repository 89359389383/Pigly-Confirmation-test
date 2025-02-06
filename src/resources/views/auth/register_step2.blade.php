<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PiGLy - 新規会員登録</title>
    <link rel="stylesheet" href="{{ asset('css/auth/register_step2.css') }}">
</head>

<body>
    <div class="container">
        <h1 class="logo">PiGLy</h1>
        <h2 class="title">新規会員登録</h2>
        <p class="step">STEP2 体重データの入力</p>

        <!-- 修正: フォームのactionにstoreInitialGoalを指定 -->
        <form method="POST" action="{{ route('register.initial_goal') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">現在の体重</label>
                <div class="input-group">
                    <input type="number" name="current_weight" class="form-input" placeholder="現在の体重を入力" required step="0.1">
                    <span class="unit">kg</span>
                </div>
                @error('current_weight')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">目標の体重</label>
                <div class="input-group">
                    <input type="number" name="target_weight" class="form-input" placeholder="目標の体重を入力" required step="0.1">
                    <span class="unit">kg</span>
                </div>
                @error('target_weight')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="submit-btn">アカウント作成</button>
        </form>
    </div>
</body>

</html>