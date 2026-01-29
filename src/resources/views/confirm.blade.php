<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>お問い合わせ確認画面</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inika:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/confirm.css') }}" />
</head>

<body class="confirm-body">
  <header class="header">
    <div class="header-inner">
      <a class="header-logo" href="/">
        FashionablyLate
      </a>
    </div>
  </header>

  <main>
    <div class="confirm-content">
      <h2 class="confirm-title">Confirm</h2>
      <form class="form" action="/contacts" method="post">
        @csrf
        <div class="confirm-table">
          <table class="confirm-table-inner">
            <tr class="confirm-table-row">
              <th class="confirm-table-header">お名前</th>
              <td class="confirm-table-text">
                {{ $contact['first_name'] }} {{ $contact['last_name'] }}
              </td>
            </tr>
            <tr class="confirm-table-row">
              <th class="confirm-table-header">性別</th>
              <td class="confirm-table-text">
                @if($contact['gender'] == 1)
                男性
                @elseif($contact['gender'] == 2)
                女性
                @else
                その他
                @endif
              </td>
            </tr>
            <tr class="confirm-table-row">
              <th class="confirm-table-header">メールアドレス</th>
              <td class="confirm-table-text">
                {{ $contact['email'] }}
              </td>
            </tr>
            <tr class="confirm-table-row">
              <th class="confirm-table-header">電話番号</th>
              <td class="confirm-table-text">
                {{ $contact['tel'] }}
              </td>
            </tr>
            <tr class="confirm-table-row">
              <th class="confirm-table-header">住所</th>
              <td class="confirm-table-text">
                {{ $contact['address'] }}
              </td>
            </tr>
            <tr class="confirm-table-row">
              <th class="confirm-table-header">建物名</th>
              <td class="confirm-table-text">
                {{ $contact['building'] ?? '' }}
              </td>
            </tr>
            <tr class="confirm-table-row">
              <th class="confirm-table-header">お問い合わせの種類</th>
              <td class="confirm-table-text">
                {{ $category->content }}
              </td>
            </tr>
            <tr class="confirm-table-row">
              <th class="confirm-table-header">お問い合わせ内容</th>
              <td class="confirm-table-text">
                {{ $contact['detail'] }}
              </td>
            </tr>
          </table>
        </div>
        <div class="form-button">
          <button class="form-button-submit" type="submit">送信</button>
          <a href="/" class="form-button-edit">修正</a>
        </div>
      </form>
    </div>
  </main>
</body>

</html>