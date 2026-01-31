@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom">INSERTA AQUÍ CUÁNTO PESO HAS LEVANTADO</h2>

        {{-- ERRORES --}}
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
            <div id="grupos-container" class="row row-cols-1 row-cols-md-1 g-4">

                {{-- GRUPO 0 --}}
                <div class="col grupo-item card shadow-sm p-3">

                    <label class="form-label fw-bold">Grupo muscular</label>
                    <select name="grupos[0][grupo_id]"
                            class="form-control grupo-select"
                            data-index="0">
                        <option value="">-- Selecciona grupo --</option>
                        @foreach($gruposMusculares as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->nombre_grupo }}</option>
                        @endforeach
                    </select>

                    {{-- EJERCICIOS --}}
                    <div class="ejercicio-container row row-cols-4 g-3 mt-3"></div>

                </div>
            </div>

            {{-- AÑADIR GRUPO --}}
            <div class="mt-3">
                <button type="button" id="add-grupo" class="btn btn-success">
                    + Añadir otro grupo muscular
                </button>
            </div>

            {{-- FECHA + GUARDAR --}}
            <div class="row g-3 mt-4 align-items-end">
                <div class="col-md-4">
                    <label>Día</label>
                    <input type="datetime-local" name="dia" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        Guardar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
document.addEventListener("DOMContentLoaded", () => {

    const container = document.getElementById("grupos-container");
    const addButton = document.getElementById("add-grupo");
    let index = 1;

    // ➕ AÑADIR GRUPO
    addButton.addEventListener("click", () => {

        const div = document.createElement("div");
        div.className = "col grupo-item card shadow-sm p-3";

        div.innerHTML = `
            <label class="form-label fw-bold">Grupo muscular</label>
            <select name="grupos[${index}][grupo_id]"
                    class="form-control grupo-select"
                    data-index="${index}">
                <option value="">-- Selecciona grupo --</option>
                @foreach($gruposMusculares as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->nombre_grupo }}</option>
                @endforeach
            </select>

            <div class="ejercicio-container row row-cols-2 g-3 mt-3"></div>

            <button type="button"
                    class="btn btn-danger btn-sm mt-3 remove-grupo">
                Eliminar grupo
            </button>
        `;

        container.appendChild(div);
        index++;
    });

    // ❌ ELIMINAR GRUPO
    container.addEventListener("click", e => {
        if (e.target.classList.contains("remove-grupo")) {
            e.target.closest(".grupo-item").remove();
        }
    });

    // 🎯 CARGAR EJERCICIOS VISUALES
    container.addEventListener("change", async e => {

        if (!e.target.classList.contains("grupo-select")) return;

        const grupoId = e.target.value;
        const index = e.target.dataset.index;
        const ejercicioContainer = e.target
            .closest(".grupo-item")
            .querySelector(".ejercicio-container");

        ejercicioContainer.innerHTML = "";

        if (!grupoId) return;

        const response = await fetch(`/grupos/${grupoId}/ejercicios`);
        const ejercicios = await response.json();

        ejercicios.forEach(ej => {
            ejercicioContainer.innerHTML += `
                <div class="col">
                    <div class="card h-100 shadow-sm p-2">

                        <img src="${ej.imagen}"
                             class="mx-auto"
                             style="height:90px; object-fit:contain">

                        <div class="card-body text-center">

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