@extends('adminlte::page')

@section('title', 'Create IMC')

@section('content')
    <div class="container-fluid row justify-content-center">
        <div class="col-md-2"></div>
        <div class="col-md-8">
        <h1>Inserta los datos para calcular tu IMC</h1>
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
    </div>
    <div class="col-md-2"></div>
    </div><br><br>
    <div class="container row justify-content-center">
        <form action="{{ route('imc.calculateImc') }}" method="POST" class="row g-3">
            @csrf
                <div class="col-md-4 centered-element" >
                    <label for="sx">Sexo: </label>
                    <select id="sx" class="form-select form-select-lg mb-3" name="sexo">
                        <option value="" selected>Selecciona</option>
                        <option value="H">Hombre</option>
                        <option value="M">Mujer</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="cms">Altura:</label>
                    <input type="number" id="cms" name="cms" value="{{ old('cms') }}">
                </div>

                <div class="col-md-4">
                    <label for="kgs">Peso:</label>
                    <input type="number" id="kgs" name="kgs" value="{{ old('kgs') }}">
                </div>

                <div class="container-fluid row justify-content-center" style="margin-top:2%;">
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
        </form>
    </div>
    

@stop

@section('css')
    
@stop

@section('js')

@stop
