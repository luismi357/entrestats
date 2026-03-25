@extends('adminlte::page')

@section('title', 'Create IMC')

@section('content')
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-md-8">
            <h1 class="text-center mb-4">Inserta los datos para calcular tu IMC</h1>
            <hr style="border: 1px solid black;">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('imc.calculateImc') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label for="sx">Sexo:</label>
                    <select id="sx" class="form-select form-select-lg mb-3" name="sexo">
                        <option value="" selected>Selecciona</option>
                        <option value="H">Hombre</option>
                        <option value="M">Mujer</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="cms">Altura (cm):</label>
                    <input type="number" id="cms" name="cms" value="{{ old('cms') }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label for="kgs">Peso (kg):</label>
                    <input type="number" id="kgs" name="kgs" value="{{ old('kgs') }}" class="form-control">
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
    </style>
@stop

@section('js')
@stop