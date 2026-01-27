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
    <div class="header__inner">
      <a class="header__logo" href="/">
        FashionablyLate
      </a>
      <form action="/logout" method="post" class="header__logout-form">
        @csrf
        <button type="submit" class="header__logout">logout</button>
      </form>
    </div>
  </header>

  <main>
    <div class="admin__content">
      <h1 class="admin__title">Admin</h1>

      <!-- 検索・フィルターセクション -->
      <div class="admin__filters">
        <form method="GET" action="/search" class="admin__search-form">
          <div class="admin__search-row">
            <input type="text" name="keyword" class="admin__search-input" placeholder="名前やメールアドレスを入力してください" value="{{ request('keyword') }}">
            <select name="gender" class="admin__search-select">
              <option value="">性別</option>
              <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>男性</option>
              <option value="2" {{ request('gender') == '2' ? 'selected' : '' }}>女性</option>
              <option value="3" {{ request('gender') == '3' ? 'selected' : '' }}>その他</option>
            </select>
            <select name="category_id" class="admin__search-select">
              <option value="">お問い合わせの種類</option>
              @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->content }}
              </option>
              @endforeach
            </select>
            <input type="date" name="date" class="admin__search-date" value="{{ request('date') }}">
            <button type="submit" class="admin__search-btn">検索</button>
            <button type="button" class="admin__reset-btn" onclick="resetForm()">リセット</button>
          </div>
        </form>
      </div>

      <!-- エクスポート・ページネーション -->
      <div class="admin__actions">
        <button class="admin__export">エクスポート</button>
        <div class="admin__pagination">
          {{ $contacts->links() }}
        </div>
      </div>

      <!-- お問い合わせ一覧テーブル -->
      <div class="admin__table-wrapper">
        <table class="admin__table">
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
                <button class="admin__detail-btn" data-contact-id="{{ $contact->id }}" onclick="openModal({{ $contact->id }})">詳細</button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="admin__no-data">お問い合わせがありません</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- モーダル -->
  <div id="contactModal" class="modal">
    <div class="modal__overlay" onclick="closeModal()"></div>
    <div class="modal__content">
      <button class="modal__close" onclick="closeModal()">×</button>
      <h2 class="modal__title">お名前</h2>
      <div class="modal__body">
        <div class="modal__row">
          <span class="modal__label">お名前</span>
          <span class="modal__value" id="modal-name"></span>
        </div>
        <div class="modal__row">
          <span class="modal__label">性別</span>
          <span class="modal__value" id="modal-gender"></span>
        </div>
        <div class="modal__row">
          <span class="modal__label">メールアドレス</span>
          <span class="modal__value" id="modal-email"></span>
        </div>
        <div class="modal__row">
          <span class="modal__label">電話番号</span>
          <span class="modal__value" id="modal-tel"></span>
        </div>
        <div class="modal__row">
          <span class="modal__label">住所</span>
          <span class="modal__value" id="modal-address"></span>
        </div>
        <div class="modal__row">
          <span class="modal__label">建物名</span>
          <span class="modal__value" id="modal-building"></span>
        </div>
        <div class="modal__row">
          <span class="modal__label">お問い合わせの種類</span>
          <span class="modal__value" id="modal-category"></span>
        </div>
        <div class="modal__row">
          <span class="modal__label">お問い合わせ内容</span>
          <span class="modal__value" id="modal-detail"></span>
        </div>
      </div>
      <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
      </form>
      <div class="modal__footer">
        <button class="modal__delete-btn" onclick="deleteContact()">削除</button>
      </div>
    </div>
  </div>

  <script>
    // お問い合わせデータをJavaScriptで使用可能にする
    const contactsData = {
      @foreach($contacts as $contact)
      {{ $contact->id }}: {
        name: @json($contact->last_name . ' ' . $contact->first_name),
        gender: @json($contact->gender_text),
        email: @json($contact->email),
        tel: @json($contact->tel),
        address: @json($contact->address),
        building: @json($contact->building ?? ''),
        category: @json($contact->category->content),
        detail: @json($contact->detail)
      },
      @endforeach
    };

    let currentContactId = null;

    function openModal(contactId) {
      currentContactId = contactId;
      const contact = contactsData[contactId];
      
      if (contact) {
        document.getElementById('modal-name').textContent = contact.name;
        document.getElementById('modal-gender').textContent = contact.gender;
        document.getElementById('modal-email').textContent = contact.email;
        document.getElementById('modal-tel').textContent = contact.tel;
        document.getElementById('modal-address').textContent = contact.address;
        document.getElementById('modal-building').textContent = contact.building || '';
        document.getElementById('modal-category').textContent = contact.category;
        // 改行を<br>タグに変換
        document.getElementById('modal-detail').innerHTML = contact.detail.replace(/\n/g, '<br>');
        
        // 削除フォームのactionを設定
        document.getElementById('deleteForm').action = `/delete/${contactId}`;
        
        document.getElementById('contactModal').classList.add('modal--open');
        document.body.style.overflow = 'hidden';
      }
    }

    function closeModal() {
      document.getElementById('contactModal').classList.remove('modal--open');
      document.body.style.overflow = '';
      currentContactId = null;
    }

    function deleteContact() {
      if (!currentContactId) return;
      
      if (confirm('このお問い合わせを削除してもよろしいですか？')) {
        document.getElementById('deleteForm').submit();
      }
    }

    // ESCキーでモーダルを閉じる
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeModal();
      }
    });

    // リセットボタンの処理
    function resetForm() {
      window.location.href = '/reset';
    }
  </script>
</body>

</html>
