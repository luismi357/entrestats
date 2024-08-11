@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Panel de control</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
    <div class="container-fluid">
        <div class="col-md-6 col-sm-6">
            <h1>{{ $chart->options['chart_title'] }}</h1>
            {!! $chart->renderHtml() !!}
        </div>
        <div class="col-md-6 col-sm-6">

        </div>
    </div>
    
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    {!! $chart->renderChartJsLibrary() !!}
    {!! $chart->renderJs() !!}
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
