@extends('adminlte::page')

@section('title', 'Formulario de usuario')

@section('content_header')
    <h1>Formulario de {{ $user->name }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <p class="text-muted">Enviado el {{ $submission->created_at->format('d-m-Y H:i') }}</p>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Pregunta</th>
                        <th>Respuesta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submission->respuestas as $r)
                    <tr>
                        <td>{{ $r['pregunta'] }}</td>
                        <td>{{ $r['respuesta'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('admin.formularios.index') }}" class="btn btn-secondary">Volver a la lista</a>
</div>
@stop
