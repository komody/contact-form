<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>認証 会員登録画面</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inika:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/register.css') }}" />
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <a class="header__logo" href="/">
        FashionablyLate
      </a>
      <a class="header__link" href="/login">login</a>
    </div>
  </header>

  <main>
    <div class="auth-form__content">
      <div class="auth-form__heading">
        <h2>Register</h2>
      </div>
      <form class="auth-form" action="/register" method="post">
        @csrf
        <div class="auth-form__group">
          <label class="auth-form__label">お名前</label>
          <div class="auth-form__input">
            <input type="text" name="name" value="{{ old('name') }}" placeholder="例:山田 太郎" required />
          </div>
          @error('name')
          <div class="auth-form__error">{{ $message }}</div>
          @enderror
        </div>
        <div class="auth-form__group">
          <label class="auth-form__label">メールアドレス</label>
          <div class="auth-form__input">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="例:test@example.com" required />
          </div>
          @error('email')
          <div class="auth-form__error">{{ $message }}</div>
          @enderror
        </div>
        <div class="auth-form__group">
          <label class="auth-form__label">パスワード</label>
          <div class="auth-form__input">
            <input type="password" name="password" placeholder="例:coachtech106" required />
          </div>
          @error('password')
          <div class="auth-form__error">{{ $message }}</div>
          @enderror
        </div>
        <div class="auth-form__group">
          <label class="auth-form__label">パスワード確認</label>
          <div class="auth-form__input">
            <input type="password" name="password_confirmation" placeholder="パスワードを再入力" required />
          </div>
        </div>
        <div class="auth-form__button">
          <button class="auth-form__button-submit" type="submit">登録</button>
        </div>
      </form>
    </div>
  </main>
</body>

</html>