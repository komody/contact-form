<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ログイン画面</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inika:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
</head>

<body class="login-body">
  <header class="header">
    <div class="header-inner">
      <a class="header-logo" href="/">
        FashionablyLate
      </a>
      <a class="header-register-link" href="/register">register</a>
    </div>
  </header>

  <main>
    <div class="auth-form-content">
      <h2 class="auth-form-heading">Login</h2>
      <form class="auth-form" action="/login" method="post">
        @csrf
        <div class="auth-form-group">
          <label class="auth-form-label">メールアドレス</label>
          <div class="auth-form-input">
            <input class="auth-form-input-field" type="email" name="email" value="{{ old('email') }}" placeholder="例: test@example.com" required />
          </div>
          @error('email')
          <div class="auth-form-error">{{ $message }}</div>
          @enderror
        </div>
        <div class="auth-form-group">
          <label class="auth-form-label">パスワード</label>
          <div class="auth-form-input">
            <input class="auth-form-input-field" type="password" name="password" placeholder="例: coachtech1106" required />
          </div>
          @error('password')
          <div class="auth-form-error">{{ $message }}</div>
          @enderror
        </div>
        <div class="auth-form-button">
          <button class="auth-form-button-submit" type="submit">ログイン</button>
        </div>
      </form>
    </div>
  </main>
</body>

</html>