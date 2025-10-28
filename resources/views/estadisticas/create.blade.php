@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">
    <div class="container px-4 py-5" id="custom-cards">
        <h2 class="pb-2 border-bottom">INSERTA AQUI CUANTO PESO HAS LEVANTADO</h2>
        <div class="row row-cols-1 align-items-stretch g-4 py-5">
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

            <form action="{{ route('estadisticas.store') }}" method="POST">
                @csrf

                {{-- 4 columnas iguales que ocupan el 100% del ancho del div --}}
                <div class="row row-cols-1 row-cols-md-4">
                    <div class="col">
                        <label for="grupo_pecho" class="form-label">Grupo (Pecho)</label>
                        <select id="grupo_pecho" name="grupo_pecho" class="form-control">
                            <option value="">-- Selecciona grupo --</option>
                            @foreach($ejerciciosGrupoPecho as $grupo)
                                <option value="{{ $grupo->id }}" {{ old('grupo_pecho') == $grupo->id ? 'selected' : '' }}>
                                    {{ $grupo->nombre_ejercicio }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" id="pecho" name="pecho" value="{{ old('pecho') }}" class="form-control">
                    </div>

                    <div class="col">
                        <label for="biceps" class="form-label">Bíceps</label>
                        <select id="grupo_biceps" name="grupo_biceps" class="form-control">
                            <option value="">-- Selecciona grupo --</option>
                            @foreach($ejerciciosGrupoBiceps as $grupo)
                                <option value="{{ $grupo->id }}" {{ old('grupo_biceps') == $grupo->id ? 'selected' : '' }}>
                                    {{ $grupo->nombre_ejercicio }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" id="biceps" name="biceps" value="{{ old('biceps') }}" class="form-control">
                    </div>

                    <div class="col">
                        <label for="pierna" class="form-label">Pierna</label>
                        <input type="number" id="pierna" name="pierna" value="{{ old('pierna') }}" class="form-control">
                    </div>

                    <div class="col">
                        <label for="hombro" class="form-label">Hombro</label>
                        <input type="number" id="hombro" name="hombro" value="{{ old('hombro') }}" class="form-control">
                    </div>
                </div>

                {{-- Línea para fecha y botón ocupando también el ancho --}}
                <div class="row g-3 mt-2">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="dia" class="form-label">Día</label>
                        <input type="datetime-local" id="dia" name="dia" value="{{ old('dia') }}" class="form-control">
                    </div>

                    <div class="col-12 col-md-6 col-lg-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                    </div>
                </div>
            </form>

        </div>
    </div>



    @stop

    @section('css')
    {{-- Add here extra stylesheets --}}
    {{--
    <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    @stop

    @section('js')

    @stop