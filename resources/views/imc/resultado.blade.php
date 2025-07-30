@extends('adminlte::page')

@section('title', 'Create IMC')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="col-md-8">
        <h1 class="text-center mb-4">Resultado del IMC</h1>
        <hr style="border: 1px solid black;">
        <div style="col-lg-12 col-md-12 col-sm-12">
             <div style="col-lg-6 col-md-6 col-sm-6">
        @if(isset($calculoImc))
           
                <h2>Tu IMC es: <strong>{{ number_format($calculoImc, 2) }}</strong></h2>
            
            <!-- Interpretación del IMC -->
             
            @if($sexo == 'M')
                
                    <h3>Eres mujer</h3>
               
            @else
               
                    <h3>Eres hombre</h3>
               
            @endif
            @if($calculoImc < 18.5 && $sexo == 'H')
                
                    <h1 style="color:red">Estás por debajo del peso ideal.</h1>
               
            @elseif($calculoImc < 24.9 && $sexo == 'H')
                
                    <h2 style="color:green;">Estás en el peso ideal.</h2>
                
            @elseif($calculoImc < 29.9 && $sexo == 'H')
               
                    <h2 style="color:orange">Estás en sobrepeso.</h2>
               
            @elseif($calculoImc >= 30 && $sexo == 'H')
                
                    <h1 style="color:red">Estás en obesidad.</h1>
               
            @endif

            @if($calculoImc < 18.5 && $sexo == 'M')
                
                    <h1 style="color:red">Estás por debajo del peso ideal.</h1>
                
            @elseif($calculoImc >= 18.5 && $calculoImc <= 24.9 && $sexo == 'M')
               
                    <h2 style="color:green;">Estás en el peso ideal.</h2>
              
            @elseif($calculoImc >= 25 && $calculoImc <= 29.9 && $sexo == 'M')
                
                    <h2 style="color:orange">Estás en sobrepeso.</h2>
                
            @elseif($calculoImc >= 30 && $sexo == 'M')
                    <h1 style="color:red">Estás en obesidad.</h1>
               
            @endif
            </div>
            </div>
        @endif
        <div class="container-fluid row justify-content-center">
            <a href="{{ route('imc.index') }}" class="btn btn-warning">Calcular otro IMC</a>
        </div>
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