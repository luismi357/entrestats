@extends('adminlte::page')

@section('title', 'Chat')

@section('content_header')
    <h1>Chat en vivo</h1>
    <p class="mb-0 text-muted">Conversa con tu entrenador.</p>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-comments mr-2" style="color:var(--gold);"></i> Chat en vivo</h3>
        </div>
        <div class="card-body">
            <div id="messages" class="d-flex flex-column gap-2 mb-3" style="max-height:460px; overflow-y:auto; min-height:220px;"></div>

            <div class="input-group">
                <input id="message" type="text" class="form-control form-control-lg" placeholder="Escribe un mensaje..." autocomplete="off">
                <div class="input-group-append">
                    <button id="send" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane mr-1"></i> Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    const user = @json(auth()->user());
    const messagesEl = document.getElementById('messages');

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function fetchMessages() {
        fetch('/messages')
            .then(response => response.json())
            .then(messages => {
                messagesEl.innerHTML = '';
                messages.forEach(m => {
                    const own = Number(m.user_id) === Number(user.id);
                    const div = document.createElement('div');
                    div.className = 'msg-bubble ' + (own ? 'msg-own align-self-end' : 'msg-other');
                    div.innerHTML = `<small class="d-block mb-1">${m.user?.name ?? 'Usuario'}</small>
                        <div>${m.content.replace(/</g,'&lt;')}</div>
                        <small class="d-block mt-1">${m.created_at ? new Date(m.created_at).toLocaleString('es-ES', {day:'2-digit',month:'2-digit',hour:'2-digit',minute:'2-digit'}) : ''}</small>`;
                    messagesEl.appendChild(div);
                });
                scrollToBottom();
            });
    }

    function sendMessage() {
        const messageInput = document.getElementById('message');
        const message = messageInput.value.trim();
        if (!message) return;

        fetch('/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: user.id,
                content: message
            })
        }).finally(() => {
            messageInput.value = '';
            messageInput.focus();
        });
    }

    document.getElementById('send').addEventListener('click', sendMessage);
    document.getElementById('message').addEventListener('keydown', e => {
        if (e.key === 'Enter') sendMessage();
    });

    fetchMessages();
    setInterval(fetchMessages, 3000);
</script>
@stop