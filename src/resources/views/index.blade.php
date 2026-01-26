<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>入力画面</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <a class="header__logo" href="/">
        FashionablyLate
      </a>
    </div>
  </header>

  <main>
    <div class="contact-form__content">
      <div class="contact-form__heading">
        <h2>Contact</h2>
      </div>
      <form class="form" action="/contacts/confirm" method="post">
        @csrf
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">お名前</span>
            <span class="form__label--required">必須</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--text form__input--name">
              <input type="text" name="first_name" value="{{ old('first_name', $oldData['first_name'] ?? '') }}" placeholder="例:山田" maxlength="8" required />
              <input type="text" name="last_name" value="{{ old('last_name', $oldData['last_name'] ?? '') }}" placeholder="例:太郎" maxlength="8" required />
            </div>
            <div class="form__error">
              @error('first_name')
              {{ $message }}
              @enderror
              @error('last_name')
              {{ $message }}
              @enderror
            </div>
          </div>
        </div>
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">性別</span>
            <span class="form__label--required">必須</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--radio">
              <label class="radio-label">
                <input type="radio" name="gender" value="1" {{ old('gender', $oldData['gender'] ?? '') == '1' ? 'checked' : '' }} required />
                <span>男性</span>
              </label>
              <label class="radio-label">
                <input type="radio" name="gender" value="2" {{ old('gender', $oldData['gender'] ?? '') == '2' ? 'checked' : '' }} />
                <span>女性</span>
              </label>
              <label class="radio-label">
                <input type="radio" name="gender" value="3" {{ old('gender', $oldData['gender'] ?? '') == '3' ? 'checked' : '' }} />
                <span>その他</span>
              </label>
            </div>
            <div class="form__error">
              @error('gender')
              {{ $message }}
              @enderror
            </div>
          </div>
        </div>
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">メールアドレス</span>
            <span class="form__label--required">必須</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--text">
              <input type="email" name="email" value="{{ old('email', $oldData['email'] ?? '') }}" placeholder="例:test@example.com" required />
            </div>
            <div class="form__error">
              @error('email')
              {{ $message }}
              @enderror
            </div>
          </div>
        </div>
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">電話番号</span>
            <span class="form__label--required">必須</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--text form__input--tel">
              <input type="tel" name="tel1" value="{{ old('tel1', $oldData['tel1'] ?? '') }}" placeholder="080" maxlength="5" required />
              <span>-</span>
              <input type="tel" name="tel2" value="{{ old('tel2', $oldData['tel2'] ?? '') }}" placeholder="1334" maxlength="5" required />
              <span>-</span>
              <input type="tel" name="tel3" value="{{ old('tel3', $oldData['tel3'] ?? '') }}" placeholder="5678" maxlength="5" required />
            </div>
            <div class="form__error">
              @error('tel1')
              {{ $message }}
              @enderror
              @error('tel2')
              {{ $message }}
              @enderror
              @error('tel3')
              {{ $message }}
              @enderror
            </div>
          </div>
        </div>
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">住所</span>
            <span class="form__label--required">必須</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--text">
              <input type="text" name="address" value="{{ old('address', $oldData['address'] ?? '') }}" placeholder="例:東京都渋谷区千駄ヶ谷1-2-3" required />
            </div>
            <div class="form__error">
              @error('address')
              {{ $message }}
              @enderror
            </div>
          </div>
        </div>
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">建物名</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--text">
              <input type="text" name="building" value="{{ old('building', $oldData['building'] ?? '') }}" placeholder="例:千駄ヶ谷マンション101" />
            </div>
            <div class="form__error">
              @error('building')
              {{ $message }}
              @enderror
            </div>
          </div>
        </div>
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">お問い合わせの種類</span>
            <span class="form__label--required">必須</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--select">
              <select name="category_id" required>
                <option value="">選択してください</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $oldData['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                  {{ $category->content }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="form__error">
              @error('category_id')
              {{ $message }}
              @enderror
            </div>
          </div>
        </div>
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">お問い合わせ内容</span>
            <span class="form__label--required">必須</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--textarea">
              <textarea name="detail" placeholder="お問い合わせ内容をご記載ください" maxlength="120" required>{{ old('detail', $oldData['detail'] ?? '') }}</textarea>
            </div>
            <div class="form__error">
              @error('detail')
              {{ $message }}
              @enderror
            </div>
          </div>
        </div>
        <div class="form__button">
          <button class="form__button-submit" type="submit">確認画面</button>
        </div>
      </form>
    </div>
  </main>
</body>

</html>