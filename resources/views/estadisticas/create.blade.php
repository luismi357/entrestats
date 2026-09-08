@extends('adminlte::page')

@section('title', 'Registrar entrenamiento')

@section('content_header')
    <h1>Registrar entrenamiento</h1>
    <p class="mb-0 text-muted">Anota cuánto peso has levantado en cada ejercicio.</p>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-dumbbell mr-2" style="color:var(--gold);"></i> Nuevo registro</h3>
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

            <form action="{{ route('estadisticas.store') }}" method="POST">
                @csrf

                {{-- CONTENEDOR DE GRUPOS --}}
                <div id="grupos-container" class="row g-4">

                    {{-- GRUPO 0 --}}
                    <div class="col-12 grupo-item card shadow-sm p-4 border-0">
                        <div class="form-group mb-0">
                            <label class="form-label fw-bold">Grupo muscular</label>
                            <select name="grupos[0][grupo_id]"
                                    class="form-control grupo-select"
                                    data-index="0">
                                <option value="">-- Selecciona grupo --</option>
                                @foreach($gruposMusculares as $grupo)
                                    <option value="{{ $grupo->id }}">{{ $grupo->nombre_grupo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="ejercicio-container row g-3 mt-1"></div>
                    </div>
                </div>

                {{-- AÑADIR GRUPO --}}
                <div class="mt-4">
                    <button type="button" id="add-grupo" class="btn btn-outline-primary">
                        <i class="fas fa-plus mr-1"></i> Añadir otro grupo muscular
                    </button>
                </div>

                {{-- FECHA + GUARDAR --}}
                <div class="row g-3 mt-4 align-items-end">
                    <div class="col-md-4">
                        <label class="text-2 font-weight-semibold">Día</label>
                        <input type="datetime-local" name="dia" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="fas fa-save mr-1"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
document.addEventListener("DOMContentLoaded", () => {

    const container = document.getElementById("grupos-container");
    const addButton = document.getElementById("add-grupo");
    let index = 1;

    addButton.addEventListener("click", () => {

        const div = document.createElement("div");
        div.className = "col-12 grupo-item card shadow-sm p-4 border-0";

        div.innerHTML = `
            <div class="form-group mb-0">
                <label class="form-label fw-bold">Grupo muscular</label>
                <select name="grupos[${index}][grupo_id]"
                        class="form-control grupo-select"
                        data-index="${index}">
                    <option value="">-- Selecciona grupo --</option>
                    @foreach($gruposMusculares as $grupo)
                        <option value="{{ $grupo->id }}">{{ $grupo->nombre_grupo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="ejercicio-container row g-3 mt-1"></div>

            <button type="button"
                    class="btn btn-danger btn-sm mt-3 remove-grupo"
                    style="align-self:flex-start;">
                <i class="fas fa-trash-alt mr-1"></i> Eliminar grupo
            </button>
        `;

        container.appendChild(div);
        index++;
    });

    container.addEventListener("click", e => {
        if (e.target.classList.contains("remove-grupo")) {
            e.target.closest(".grupo-item").remove();
        }
    });

    container.addEventListener("change", async e => {

        if (!e.target.classList.contains("grupo-select")) return;

        const grupoId = e.target.value;
        const index = e.target.dataset.index;
        const ejercicioContainer = e.target
            .closest(".grupo-item")
            .querySelector(".ejercicio-container");

        ejercicioContainer.innerHTML = "";

        if (!grupoId) return;

        const response = await fetch(`{{ route('grupos.ejercicios', ':id') }}`.replace(':id', grupoId));
        const ejercicios = await response.json();

        ejercicios.forEach(ej => {
            ejercicioContainer.innerHTML += `
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm p-3 text-center">

                        <img src="${ej.imagen}"
                             class="mx-auto"
                             style="height:150px; object-fit:contain;">

                        <div class="card-body px-1 pb-1">

                            <h6 class="fw-bold">${ej.nombre_ejercicio}</h6>

                            <input type="number"
                                   class="form-control form-control-sm mt-2"
                                   placeholder="Peso (kg)"
                                   name="grupos[${index}][ejercicios][${ej.id}][peso]">

                            <input type="number"
                                   class="form-control form-control-sm mt-2"
                                   placeholder="Series"
                                   name="grupos[${index}][ejercicios][${ej.id}][series]">

                            <input type="number"
                                   class="form-control form-control-sm mt-2"
                                   placeholder="Reps"
                                   name="grupos[${index}][ejercicios][${ej.id}][reps]">

                        </div>
                    </div>
                </div>
            `;
        });
    });
});
</script>
@stop