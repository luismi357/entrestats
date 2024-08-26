@extends('adminlte::page')

@section('title', 'Create IMC')

@section('content')
    <div class="container-fluid row justify-content-center">
        <h1>Resultado del IMC</h1>
    </div>
        @if(isset($calculoImc))
        <div class="container-fluid row justify-content-center">
        <h2>Tu IMC es: <strong>{{ number_format($calculoImc, 2) }}</strong></h2>
        </div>
        <!-- Interpretación del IMC -->
        @if($calculoImc < 18.5)
        <div class="container-fluid row justify-content-center">
            <h1 style="color:red">Estás por debajo del peso ideal.</h1>
        </div>
        @elseif($calculoImc < 24.9)
        <div class="container-fluid row justify-content-center">
            <h2 style="color:green;">Estás en el peso ideal.</h2>
        </div>
        @elseif($calculoImc < 29.9)
        <div class="container-fluid row justify-content-center">
            <h2 style="color:orange">Estás en sobrepeso.</h2>
        </div>
        @else
        <div class="container-fluid row justify-content-center">
            <h1 style="color:red">Estás en obesidad.</h1>
        </div>
        @endif
    @endif
    <div class="container-fluid row justify-content-center">
        <a href="{{ route('imc.index') }}" class="btn btn-warning">Calcular otro IMC</a>
    </div>
    </div>
          

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

@stop
