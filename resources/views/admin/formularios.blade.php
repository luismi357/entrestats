@extends('adminlte::page')

@section('title', 'Formularios enviados')

@section('content_header')
    <h1>Formularios enviados</h1>
@stop

@section('content')
<div class="container-fluid">
    @if($submissions->isEmpty())
        <p class="text-muted">No hay formularios enviados aún.</p>
    @else
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $s)
                        <tr>
                            <td>{{ $s->user->name }}</td>
                            <td>{{ $s->user->email }}</td>
                            <td>{{ $s->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.formularios.show', $s->user_id) }}" class="btn btn-sm btn-primary">
                                    Ver respuestas
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <a href="{{ url('/home') }}" class="btn btn-secondary">Volver</a>
</div>
@stop
