<?php require_once __DIR__ . '/../public/header.php'; ?>

<style>
.chat-bubble {
  max-width: 75%;
  padding: 10px 15px;
  border-radius: 20px;
  margin-bottom: 10px;
  position: relative;
  word-wrap: break-word;
  display: inline-block;
  clear: both;
}

.chat-bubble.incoming {
  background-color: #e9ecef;
  float: left;
  text-align: left;
  border-top-left-radius: 0;
}

.chat-bubble.outgoing {
  background-color: #0d6efd;
  color: white;
  float: right;
  text-align: right;
  border-top-right-radius: 0;
}

.chat-scroll {
  height: 450px;
  overflow-y: auto;
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
}
</style>

<div class="container-fluid mt-5 pt-4">
  <div class="row">
    <!-- قائمة المستخدمين -->
    <div class="col-md-4 border-end">
      <h5 class="fw-bold mb-3"><i class="fas fa-comments me-2 text-primary"></i>Chats</h5>
      <ul id="chatList" class="list-group">
        <li class="list-group-item text-muted">Loading...</li>
      </ul>
    </div>

    <!-- نافذة المحادثة -->
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          Konversation mit <span id="partnerName">...</span>
        </div>
        <div class="card-body chat-scroll" id="chatBox">
          <div class="text-muted text-center mt-5">Wähle einen Chat</div>
        </div>
        <div class="card-footer bg-white">
          <form id="messageForm" class="d-flex gap-2" style="display: none;">
            <input type="hidden" name="receiver_id" id="to_id">
            <input type="text" name="content" id="messageInput" class="form-control"
              placeholder="Nachricht schreiben..." required>
            <button type="submit" class="btn btn-primary">Senden</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let currentUserId = <?= $_SESSION['user_id'] ?? 'null' ?>;
let activeChatId = null;
let interval = null;

function loadChatUsers() {
  fetch('/public/messages/getChatUsers')
    .then(res => res.json())
    .then(users => {
      const chatList = document.getElementById('chatList');
      chatList.innerHTML = '';

      if (users.length === 0) {
        const empty = document.createElement('li');
        empty.className = 'list-group-item text-muted';
        empty.textContent = 'Keine Nachrichten vorhanden';
        chatList.appendChild(empty);
        return;
      }

      users.forEach(user => {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-start user-link';
        li.style.cursor = 'pointer';
        li.dataset.userId = user.id;

        li.innerHTML = `
          <div>
            <div><strong>${user.name}</strong></div>
            <small class="text-muted">${new Date(user.last_message_time).toLocaleString()}</small>
          </div>
          ${user.unread_count > 0 ? `<span class="badge bg-danger mt-1">${user.unread_count}</span>` : ''}
        `;

        li.addEventListener('click', () => openChat(user.id, user.name));
        chatList.appendChild(li);
      });
    });
}

function openChat(userId, userName) {
  activeChatId = userId;
  document.getElementById('to_id').value = userId;
  document.getElementById('partnerName').textContent = userName;
  document.getElementById('messageForm').style.display = 'flex';

  // ✅ تعليم الرسائل كمقروءة
  fetch(`/public/messages/markAsRead/${userId}`);

  // ✅ إعادة تحميل الرسائل والمستخدمين
  loadMessages();
  loadChatUsers(); // ⬅ لإخفاء الرقم الأحمر بعد القراءة

  if (interval) clearInterval(interval);
  interval = setInterval(loadMessages, 3000);
}

function loadMessages() {
  if (!activeChatId) return;
  fetch('/public/messages/fetch/' + activeChatId)
    .then(res => res.json())
    .then(messages => {
      const box = document.getElementById('chatBox');
      box.innerHTML = '';
      messages.forEach(msg => {
        const div = document.createElement('div');
        const direction = msg.sender_id == currentUserId ? 'outgoing' : 'incoming';
        div.className = 'chat-bubble ' + direction;
        div.innerHTML = `
          <div>${msg.message}</div>
          <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">${msg.created_at}</small>
        `;
        box.appendChild(div);
      });
      box.scrollTop = box.scrollHeight;
    });
}

document.getElementById('messageForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const text = document.getElementById('messageInput').value.trim();
  if (!text || !activeChatId) return;

  const formData = new FormData(this);
  fetch('/public/messages/send', {
    method: 'POST',
    body: formData
  }).then(res => res.text()).then(response => {
    if (response === 'ok') {
      this.reset();
      loadMessages();
    }
  });
});

loadChatUsers();
</script>

<?php require_once __DIR__ . '/../public/footer.php'; ?>