@extends('adminlte::page')

@section('title', 'Estadisticas')

@section('content')
    <div class="container-fluid">
        <h1>INSERTA AQUI CUANTO PESO HAS LEVANTADO:</h1>
                @if ($errors->any())
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('estadisticas.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                    <label for="pecho">Pecho:</label>
                    <input type="number" id="pecho" name="pecho" value="{{ old('pecho') }}">
                    </div>
                    
                    <div class="col-md-6">
                    <label for="biceps">Bíceps:</label>
                    <input type="number" id="biceps" name="biceps" value="{{ old('biceps') }}">
                    </div>
                    <div class="col-md-6">
                    <label for="pierna">Pierna:</label>
                    <input type="number" id="pierna" name="pierna" value="{{ old('pierna') }}">
                    </div>
                    <div class="col-md-6">
                    <label for="hombro">Hombro:</label>
                    <input type="number" id="hombro" name="hombro" value="{{ old('hombro') }}">
                    </div>
                    <div class="col-md-12">
                    <label for="dia">Día:</label>
                    <input type="datetime-local" id="dia" name="dia" value="{{ old('dia') }}">
                    </div>
                    <button type="submit">Guardar</button>
                </form>
            </div>
          

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

@stop
