@extends('adminlte::page')

@section('title', 'Calcular IMC')

@section('content_header')
    <h1>Calcular IMC</h1>
    <p class="mb-0 text-muted">Cuéntanos tus datos y obtén tu índice de masa corporal.</p>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-weight-hanging mr-2" style="color:var(--gold);"></i> Datos de cálculo</h3>
                </div>
                <div class="card-body">

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

                    <form action="{{ route('imc.calculateImc') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label for="sx" class="form-label font-weight-bold">Sexo</label>
                                <select id="sx" class="form-control form-select form-select-lg" name="sexo">
                                    <option value="" selected>Selecciona</option>
                                    <option value="H">Hombre</option>
                                    <option value="M">Mujer</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="cms" class="form-label font-weight-bold">Altura (cm)</label>
                                <input type="number" id="cms" name="cms" value="{{ old('cms') }}" class="form-control form-control-lg" placeholder="Ej. 175">
                            </div>

                            <div class="col-md-4">
                                <label for="kgs" class="form-label font-weight-bold">Peso (kg)</label>
                                <input type="number" id="kgs" name="kgs" value="{{ old('kgs') }}" class="form-control form-control-lg" placeholder="Ej. 78">
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-calculator mr-1"></i> Calcular
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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