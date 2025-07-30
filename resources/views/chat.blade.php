@extends('layouts.app')
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Live Chat</div>
        
        <div class="card-body">
            <ul id="messages" class="list-group"></ul>
            <input id="message" type="text" class="form-control" placeholder="Escribe un mensaje">
            <button id="send" class="btn btn-primary mt-2">Enviar</button>
        </div>
    </div>
</div>

<script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
    const user = @json(auth()->user());
    
    function fetchMessages() {
        fetch('/messages')
            .then(response => response.json())
            .then(messages => {
                messages.forEach(message => {
                    appendMessage(message);
                });
            });
    }

    function appendMessage(message) {
        const messagesList = document.getElementById('messages');
        const li = document.createElement('li');
        li.classList.add('list-group-item');
        li.textContent = `${message.user.name}: ${message.message}`;
        messagesList.appendChild(li);
    }

    document.getElementById('send').addEventListener('click', () => {
    const messageInput = document.getElementById('message');
    const message = messageInput.value;

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
    });

    messageInput.value = '';
});

    /*window.Echo.private('chat')
        .listen('MessageSent', (e) => {
            appendMessage(e.message);
        });*/

    fetchMessages();
    setInterval(fetchMessages, 3000);
</script>
@endsection
