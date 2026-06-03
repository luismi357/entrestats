@extends('adminlte::page')

@section('title', 'Admin Chat')

@section('content_header')
    <h1>Panel de Administración - Chat</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">Usuarios con mensajes</div>
        <div class="card-body">
            @if($users->isEmpty())
                <p class="text-muted">No hay mensajes de usuarios aún.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Último mensaje</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @php
                                    $last = \App\Models\Message::where(function($q) use($u) {
                                        $q->where('user_id', $u->id)->whereNull('receiver_id')
                                          ->orWhere('receiver_id', $u->id);
                                    })->orderBy('created_at', 'desc')->first();
                                @endphp
                                {{ $last ? Str::limit($last->content, 40) : '—' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.chat.conversation', $u->id) }}" class="btn btn-primary btn-sm">
                                    Ver chat
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@stop
