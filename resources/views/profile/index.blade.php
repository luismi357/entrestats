@extends('adminlte::page')

@section('title', 'Mi Perfil')

@section('content_header')
    <h1>Mi Perfil</h1>
@stop

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp
<div class="row">

    {{-- FOTO PERFIL --}}
    <div class="col-md-4">

        <div class="card card-primary card-outline">

            <div class="card-body box-profile">

                <div class="text-center">

                    @if($user->photo)
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ Storage::url('profile_photos/' . $user->photo) }}"
                             style="width:150px; height:150px; object-fit:cover;">
                    @else
                        <img class="profile-user-img img-fluid img-circle"
                             src="https://i.pravatar.cc/300"
                             style="width:150px; height:150px; object-fit:cover;">
                    @endif

                </div>

                <h3 class="profile-username text-center">
                    {{ $user->name }}
                </h3>

                <p class="text-muted text-center">
                    Usuario registrado
                </p>

            </div>

        </div>

    </div>

    {{-- FORMULARIO --}}
    <div class="col-md-8">

        <div class="card">

            <div class="card-header bg-primary">
                <h3 class="card-title">Editar Perfil</h3>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('profile.update') }}"
                      enctype="multipart/form-data">

                    @csrf
                    @method('PATCH')

                    {{-- FOTO --}}
                    <div class="form-group">
                        <label>Foto de perfil</label>
                        <input type="file"
                               name="photo"
                               class="form-control">
                    </div>

                    {{-- NOMBRE --}}
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $user->name) }}"
                               required>
                    </div>

                    {{-- EMAIL --}}
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $user->email) }}"
                               required>
                    </div>

                    {{-- PASSWORD --}}
                    <div class="form-group">
                        <label>Nueva contraseña</label>
                        <input type="password"
                               name="password"
                               class="form-control">
                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div class="form-group">
                        <label>Confirmar contraseña</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">
                        Guardar cambios
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@stop