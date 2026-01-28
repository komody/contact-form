<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>管理画面</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inika:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
</head>

<body>
  <header class="header">
    <div class="header-inner">
      <a class="header-logo" href="/">
        FashionablyLate
      </a>
      <form action="/logout" method="post" class="header-logout-form">
        @csrf
        <button type="submit" class="header-logout">logout</button>
      </form>
    </div>
  </header>

  <main>
    <div class="admin-content">
      <h1 class="admin-title">Admin</h1>

      <!-- 検索・フィルターセクション -->
      <div class="admin-filters">
        <form method="GET" action="/search" class="admin-search-form">
          <div class="admin-search-row">
            <input type="text" name="keyword" class="admin-search-input" placeholder="名前やメールアドレスを入力してください" value="{{ request('keyword') }}">
            <select name="gender" class="admin-search-select">
              <option value="">性別</option>
              <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>男性</option>
              <option value="2" {{ request('gender') == '2' ? 'selected' : '' }}>女性</option>
              <option value="3" {{ request('gender') == '3' ? 'selected' : '' }}>その他</option>
            </select>
            <select name="category_id" class="admin-search-select">
              <option value="">お問い合わせの種類</option>
              @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->content }}
              </option>
              @endforeach
            </select>
            <input type="date" name="date" class="admin-search-date" value="{{ request('date') }}">
            <button type="submit" class="admin-search-btn">検索</button>
            <button type="button" class="admin-reset-btn" onclick="resetForm()">リセット</button>
          </div>
        </form>
      </div>

      <!-- エクスポート・ページネーション -->
      <div class="admin-actions">
        <button class="admin-export">エクスポート</button>
        <div class="admin-pagination">
          {{ $contacts->links() }}
        </div>
      </div>

      <!-- お問い合わせ一覧テーブル -->
      <div class="admin-table-wrapper">
        <table class="admin-table">
          <thead>
            <tr>
              <th>お名前</th>
              <th>性別</th>
              <th>メールアドレス</th>
              <th>お問い合わせの種類</th>
              <th>詳細</th>
            </tr>
          </thead>
          <tbody>
            @forelse($contacts as $contact)
            <tr>
              <td>{{ $contact->last_name }} {{ $contact->first_name }}</td>
              <td>{{ $contact->gender_text }}</td>
              <td>{{ $contact->email }}</td>
              <td>{{ $contact->category->content }}</td>
              <td>
                <button class="admin-detail-btn" data-contact-id="{{ $contact->id }}" onclick="openModal('{{ $contact->id }}')">詳細</button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="admin-no-data">お問い合わせがありません</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- モーダル -->
  <div id="contactModal" class="modal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-content">
      <button class="modal-close" onclick="closeModal()">×</button>
      <h2 class="modal-title">お名前</h2>
      <div class="modal-body">
        <div class="modal-row">
          <span class="modal-label">お名前</span>
          <span class="modal-value" id="modal-name"></span>
        </div>
        <div class="modal-row">
          <span class="modal-label">性別</span>
          <span class="modal-value" id="modal-gender"></span>
        </div>
        <div class="modal-row">
          <span class="modal-label">メールアドレス</span>
          <span class="modal-value" id="modal-email"></span>
        </div>
        <div class="modal-row">
          <span class="modal-label">電話番号</span>
          <span class="modal-value" id="modal-tel"></span>
        </div>
        <div class="modal-row">
          <span class="modal-label">住所</span>
          <span class="modal-value" id="modal-address"></span>
        </div>
        <div class="modal-row">
          <span class="modal-label">建物名</span>
          <span class="modal-value" id="modal-building"></span>
        </div>
        <div class="modal-row">
          <span class="modal-label">お問い合わせの種類</span>
          <span class="modal-value" id="modal-category"></span>
        </div>
        <div class="modal-row">
          <span class="modal-label">お問い合わせ内容</span>
          <span class="modal-value" id="modal-detail"></span>
        </div>
      </div>
      <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
      </form>
      <div class="modal-footer">
        <button class="modal-delete-btn" onclick="deleteContact()">削除</button>
      </div>
    </div>
  </div>

  @php
    $contactsData = $contacts->keyBy('id')->map(function($contact) {
      return [
        'name' => $contact->last_name . ' ' . $contact->first_name,
        'gender' => $contact->gender_text,
        'email' => $contact->email,
        'tel' => $contact->tel,
        'address' => $contact->address,
        'building' => $contact->building ?? '',
        'category' => $contact->category->content,
        'detail' => $contact->detail,
      ];
    });
    $contactsJson = json_encode($contactsData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
  @endphp
  <div id="contacts-data" data-contacts="{!! htmlspecialchars($contactsJson, ENT_QUOTES, 'UTF-8') !!}" style="display: none;"></div>
  <script>
    // BladeテンプレートからJavaScriptにデータを渡す
    const contactsDataElement = document.getElementById('contacts-data');
    window.contactsData = JSON.parse(contactsDataElement.getAttribute('data-contacts'));
  </script>
  <script src="{{ asset('js/index.js') }}"></script>
</body>

</html>