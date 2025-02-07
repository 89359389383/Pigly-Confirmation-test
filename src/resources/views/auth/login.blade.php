<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PiGLy - ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>

<body>
    <div class="container">
        <h1 class="logo">PiGLy</h1>
        <h2 class="title">ログイン</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="text" id="email" name="email" placeholder="メールアドレスを入力" value="{{ old('email') }}">
                @error('email')
                <p class="error-message" style="color: red;">{!! nl2br(e($message)) !!}
                </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" placeholder="パスワードを入力">
                @error('password')
                <p class="error-message" style="color: red;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="submit-btn">ログイン</button>
        </form>

        <a href="{{ route('register.step1') }}" class="login-link">アカウント作成はこちら</a>
    </div>
</body>

</html>