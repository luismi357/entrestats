@extends('adminlte::page')

@section('title', 'Chat')

@section('content_header')
    <h1>Live Chat</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">Live Chat</div>
        
        <div class="card-body">
            <ul id="messages" class="list-group"></ul>
            <input id="message" type="text" class="form-control" placeholder="Escribe un mensaje">
            <button id="send" class="btn btn-primary mt-2">Enviar</button>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    const user = @json(auth()->user());
    
    function fetchMessages() {
        fetch('/messages')
            .then(response => response.json())
            .then(messages => {
                const list = document.getElementById('messages');
                list.innerHTML = '';
                messages.forEach(m => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = `${m.user?.name ?? 'Usuario'}: ${m.content}`;
                    list.appendChild(li);
                });
            });
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

    fetchMessages();
    setInterval(fetchMessages, 3000);
</script>
@stop
