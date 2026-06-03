@extends('adminlte::page')

@section('title', 'Chat con usuario')

@section('content_header')
    <h1>Chat con: {{ $user->name }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.chat') }}" class="btn btn-outline-secondary mb-3">← Volver al panel</a>

    <div class="card mb-3">
        <div class="card-body" style="max-height: 400px; overflow-y: auto;" id="messagesContainer">
            @forelse($messages as $msg)
                @php $esAdmin = $msg->receiver_id !== null; @endphp
                <div class="mb-2 p-2 rounded {{ $esAdmin ? 'bg-primary text-white text-end' : 'bg-light' }}">
                    <small class="{{ $esAdmin ? 'text-white-50' : 'text-muted' }}">{{ $msg->user->name }}</small>
                    <div>{{ $msg->content }}</div>
                    <small class="{{ $esAdmin ? 'text-white-50' : 'text-muted' }}">{{ $msg->created_at->format('H:i d/m/Y') }}</small>
                </div>
            @empty
                <p class="text-muted">No hay mensajes en esta conversación.</p>
            @endforelse
        </div>
    </div>

    <form method="POST" action="{{ route('admin.chat.send', $user->id) }}">
        @csrf
        <div class="input-group">
            <input type="text" name="content" class="form-control" placeholder="Escribe tu respuesta..." required>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var container = document.getElementById('messagesContainer');
        if (container) container.scrollTop = container.scrollHeight;
    });
</script>
@stop
