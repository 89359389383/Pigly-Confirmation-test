<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PiGLy - 新規会員登録</title>
    <link rel="stylesheet" href="{{ asset('css/auth/register_step1.css') }}">
</head>

<body>
    <div class="container">
        <h1 class="logo">PiGLy</h1>
        <h2 class="title">新規会員登録</h2>
        <p class="step">STEP1 アカウント情報の登録</p>

        <form action="{{ route('register.step1') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">お名前</label>
                <input type="text" id="name" name="name" placeholder="名前を入力" value="{{ old('name') }}" required>
                @error('name') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" placeholder="メールアドレスを入力" value="{{ old('email') }}" required>
                @error('email') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" placeholder="パスワードを入力" required>
                @error('password') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="submit-btn">次に進む</button>
        </form>

        <a href="{{ route('login') }}" class="login-link">ログインはこちら</a>
    </div>
</body>

</html>