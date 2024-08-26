@extends('adminlte::page')

@section('title', 'Create IMC')

@section('content')
        <div class="container-fluid row justify-content-center">
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
        </div><br><br>
        <div class="container-fluid row justify-content-center">
                <form action="{{ route('imc.calculateImc') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                    <label for="cms">Altura:</label>
                    <input type="number" id="cms" name="cms" value="{{ old('cms') }}">
                    </div>
                    
                    <div class="col-md-6">
                    <label for="kgs">Peso:</label>
                    <input type="number" id="kgs" name="kgs" value="{{ old('kgs') }}">
                    </div><br><br>
                  
                    <div class="container-fluid row justify-content-center" style="margin-top:2%;">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>  
          

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

@stop
