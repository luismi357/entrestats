@extends('adminlte::page')

@section('title', 'Training')

@section('content')

    @viteReactRefresh
    @vite('resources/js/app.jsx')

    <div id="app"></div>

@stop