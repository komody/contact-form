let currentContactId = null;

function openModal(contactId) {
  currentContactId = contactId;
  const contact = window.contactsData[contactId];

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

    document.getElementById('contactModal').classList.add('modal-open');
    document.body.style.overflow = 'hidden';
  }
}

function closeModal() {
  document.getElementById('contactModal').classList.remove('modal-open');
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
