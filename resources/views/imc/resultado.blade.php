@extends('adminlte::page')

@section('title', 'Resultado del IMC')

@section('content_header')
    <h1>Resultado del IMC</h1>
@stop

@section('content')
@php
    $categoria = '';
    $claseColor = '';

    if (isset($calculoImc)) {
        if ($sexo == 'H') {
            if ($calculoImc < 18.5) { $categoria = 'Por debajo del peso ideal'; $claseColor = 'var(--danger)'; }
            elseif ($calculoImc < 24.9) { $categoria = 'Peso ideal'; $claseColor = 'var(--success)'; }
            elseif ($calculoImc < 29.9) { $categoria = 'Sobrepeso'; $claseColor = 'var(--warn)'; }
            else { $categoria = 'Obesidad'; $claseColor = 'var(--danger)'; }
        } else {
            if ($calculoImc < 18.5) { $categoria = 'Por debajo del peso ideal'; $claseColor = 'var(--danger)'; }
            elseif ($calculoImc <= 24.9) { $categoria = 'Peso ideal'; $claseColor = 'var(--success)'; }
            elseif ($calculoImc <= 29.9) { $categoria = 'Sobrepeso'; $claseColor = 'var(--warn)'; }
            else { $categoria = 'Obesidad'; $claseColor = 'var(--danger)'; }
        }
    }

    $barra = 0;
    if (isset($calculoImc)) {
        $barra = max(0, min(100, ($calculoImc / 40) * 100));
    }
@endphp

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-7">
            @if(isset($calculoImc))
                <div class="card text-center">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="card-title mb-0">Tu índice de masa corporal</span>
                        <span class="badge" style="background: {{ $claseColor }}; color:#fff;">
                            {{ ucfirst($categoria) }}
                        </span>
                    </div>
                    <div class="card-body py-5">
                        <div style="font-weight:800; font-family:'Outfit',sans-serif; letter-spacing:-0.02em;">
                            <span style="font-size:4.5rem; color: {{ $claseColor }};">{{ number_format($calculoImc, 2) }}</span>
                            <span class="text-muted" style="font-size:1.4rem;"> kg/m²</span>
                        </div>

                        <div class="mx-auto mt-4" style="max-width:420px;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Bajo peso</span>
                                <span class="text-muted small">Peso ideal</span>
                                <span class="text-muted small">Sobrepeso</span>
                                <span class="text-muted small">Obesidad</span>
                            </div>
                            <div class="progress" style="height:8px;">
                                <div class="progress-bar" style="width: {{ $barra }}%;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <span class="text-muted small">14</span>
                                <span class="text-muted small">18.5</span>
                                <span class="text-muted small">25</span>
                                <span class="text-muted small">30</span>
                                <span class="text-muted small">40</span>
                            </div>
                        </div>

                        <p class="text-muted mt-4 mb-0">Sexo: {{ $sexo == 'M' ? 'Mujer' : 'Hombre' }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('imc.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-redo mr-1"></i> Calcular otro IMC
                        </a>
                    </div>
                </div>
            @else
                <div class="alert alert-info text-center">
                    No hay un cálculo disponible. <a href="{{ route('imc.index') }}">Calculemos tu IMC</a>.
                </div>
            @endif
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .progress-bar { transition: width 0.8s ease; }
    </style>
@stop

@section('js')

@stop