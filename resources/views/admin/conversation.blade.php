@extends('adminlte::page')

@section('title', 'Chat con usuario')

@section('content_header')
    <h1>Chat con: {{ $user->name }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.chat') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left mr-1"></i> Volver al panel
    </a>

    <div class="card mb-3">
        <div class="card-body d-flex flex-column gap-2" style="max-height: 460px; overflow-y: auto;" id="messagesContainer">
            @forelse($messages as $msg)
                @php $esAdmin = $msg->receiver_id !== null; @endphp
                <div class="msg-bubble {{ $esAdmin ? 'msg-own align-self-end' : 'msg-other' }}">
                    <small class="d-block mb-1">{{ $msg->user->name }}</small>
                    <div>{{ $msg->content }}</div>
                    <small class="d-block mt-1">{{ $msg->created_at->format('H:i d/m/Y') }}</small>
                </div>
            @empty
                <p class="text-muted text-center py-4 mb-0">No hay mensajes en esta conversación.</p>
            @endforelse
        </div>
    </div>

    <form method="POST" action="{{ route('admin.chat.send', $user->id) }}">
        @csrf
        <div class="input-group">
            <input type="text" name="content" class="form-control form-control-lg" placeholder="Escribe tu respuesta..." required autocomplete="off">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane mr-1"></i> Enviar
                </button>
            </div>
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