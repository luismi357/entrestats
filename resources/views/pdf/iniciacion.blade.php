@extends('adminlte::page')

@section('title', 'Training')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <style>
        body { font-family: sans-serif; }
        .encabezado { background: #f4f4f4; padding: 15px; }
        .total { text-align: right; font-weight: bold; font-size: 18px; }
    </style>
</head>
<body>
    <div class="encabezado">
        <h2>Factura #{{ $datos['numero'] }}</h2>
        <p>Fecha: {{ date('d/m/Y') }}</p>
    </div>
    <p>Cliente: {{ $datos['cliente'] }}</p>
    <hr>
    <div class="total">
        Total a pagar: ${{ $datos['total'] }}
    </div>
</body>
</html>

@stop