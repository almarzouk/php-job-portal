<?php require_once __DIR__ . '/../public/header.php'; ?>

<style>
.chat-bubble {
  max-width: 70%;
  padding: 10px 15px;
  border-radius: 20px;
  margin-bottom: 8px;
  position: relative;
}

.chat-bubble.incoming {
  background-color: #e9ecef;
  align-self: flex-start;
  border-bottom-left-radius: 0;
}

.chat-bubble.outgoing {
  background-color: #0d6efd;
  color: white;
  align-self: flex-end;
  border-bottom-right-radius: 0;
}

.chat-container {
  display: flex;
  flex-direction: column;
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
      <h5 class="mb-3">📨 Chats</h5>
      <div class="list-group">
        <?php foreach ($chatUsers as $user): ?>
        <a href="#"
          class="list-group-item list-group-item-action d-flex justify-content-between align-items-center user-link"
          data-user-id="<?= $user['id'] ?>">
          <?= htmlspecialchars($user['name']) ?>
          <?php if (!empty($user['unread_count'])): ?>
          <span class="badge bg-danger rounded-pill"><?= $user['unread_count'] ?></span>
          <?php endif; ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- نافذة المحادثة -->
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <strong>Konversation mit <span id="partnerName">...</span></strong>
        </div>
        <div class="card-body chat-scroll d-flex flex-column" id="messageBox">
          <div class="text-muted text-center mt-5">Wählen Sie einen Benutzer aus der Liste.</div>
        </div>
        <div class="card-footer bg-white">
          <form id="messageForm" class="d-flex gap-2" style="display: none;">
            <input type="hidden" id="receiverId">
            <input type="text" id="messageInput" class="form-control" placeholder="Nachricht schreiben..." required>
            <button type="submit" class="btn btn-primary">Senden</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let currentReceiverId = null;
const messageBox = document.getElementById('messageBox');
const messageForm = document.getElementById('messageForm');
const messageInput = document.getElementById('messageInput');
const receiverInput = document.getElementById('receiverId');
const partnerName = document.getElementById('partnerName');

document.querySelectorAll('.user-link').forEach(link => {
  link.addEventListener('click', async function(e) {
    e.preventDefault();
    const userId = this.dataset.userId;
    const userName = this.textContent.trim();

    currentReceiverId = userId;
    receiverInput.value = userId;
    partnerName.textContent = userName;
    messageForm.style.display = 'flex';

    await loadMessages();
  });
});

async function loadMessages() {
  if (!currentReceiverId) return;
  const res = await fetch(`/public/messages/fetch/${currentReceiverId}`);
  const messages = await res.json();

  messageBox.innerHTML = '';
  messages.forEach(msg => {
    const div = document.createElement('div');
    div.className = 'chat-bubble ' + (msg.sender_id == currentReceiverId ? 'incoming' : 'outgoing');
    div.innerHTML = `<div>${msg.message}</div><small class="text-muted d-block mt-1">${msg.created_at}</small>`;
    messageBox.appendChild(div);
  });

  messageBox.scrollTop = messageBox.scrollHeight;
}

messageForm.addEventListener('submit', async function(e) {
  e.preventDefault();
  const text = messageInput.value.trim();
  if (!text || !currentReceiverId) return;

  await fetch('/public/messages/send', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `receiver_id=${currentReceiverId}&content=${encodeURIComponent(text)}`
  });

  messageInput.value = '';
  await loadMessages();
});

// تحديث الرسائل كل 5 ثواني
setInterval(() => {
  if (currentReceiverId) loadMessages();
}, 5000);
</script>

<?php require_once __DIR__ . '/../public/footer.php'; ?>