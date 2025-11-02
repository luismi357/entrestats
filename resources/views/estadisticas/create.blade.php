@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="container px-4 py-5" id="custom-cards">
        <h2 class="pb-2 border-bottom">INSERTA AQUÍ CUÁNTO PESO HAS LEVANTADO</h2>

        {{-- Errores y mensajes --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('estadisticas.store') }}" method="POST">
            @csrf

            {{-- Contenedor dinámico de grupos musculares --}}
            <div id="grupos-container" class="row row-cols-1 row-cols-md-4 g-4">
                <div class="col grupo-item">
                    <label class="form-label">Grupo Muscular</label>
                    <select name="grupos[0][grupo_id]" class="form-control grupo-select" data-index="0">
                        <option value="">-- Selecciona grupo --</option>
                        @foreach($gruposMusculares as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->nombre_grupo }}</option>
                        @endforeach
                    </select>

                    <div class="ejercicio-container mt-2"></div>

                    <label class="form-label mt-2">Peso (kg)</label>
                    <input type="number" name="grupos[0][peso]" class="form-control" placeholder="Ej: 60">

                    <label class="form-label mt-2">Series</label>
                    <input type="number" name="grupos[0][series]" class="form-control" placeholder="Ej: 10">

                    <label class="form-label mt-2">Repeticiones</label>
                    <input type="number" name="grupos[0][reps]" class="form-control" placeholder="Ej: 4">
                </div>
            </div>

            {{-- Botón para añadir más --}}
            <div class="mt-3">
                <button type="button" id="add-grupo" class="btn btn-success">
                    + Añadir otro grupo muscular
                </button>
            </div>

            {{-- Línea para fecha y guardar --}}
            <div class="row g-3 mt-4 align-items-end">
                <div class="col-md-6 col-lg-4">
                    <label for="dia" class="form-label">Día</label>
                    <input type="datetime-local" id="dia" name="dia" value="{{ old('dia') }}" class="form-control">
                </div>
                <div class="col-md-6 col-lg-2">
                    <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const addButton = document.getElementById("add-grupo");
        const container = document.getElementById("grupos-container");
        let index = 1;

        // ➕ Añadir bloque nuevo
        addButton.addEventListener("click", () => {
            const newGroup = document.createElement("div");
            newGroup.classList.add("col", "grupo-item");

            newGroup.innerHTML = `
            <label class="form-label">Grupo Muscular</label>
            <select name="grupos[${index}][grupo_id]" class="form-control grupo-select" data-index="${index}">
                <option value="">-- Selecciona grupo --</option>
                @foreach($gruposMusculares as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->nombre_grupo }}</option>
                @endforeach
            </select>

            <div class="ejercicio-container mt-2"></div>

            <label class="form-label mt-2">Peso (kg)</label>
            <input type="number" name="grupos[${index}][peso]" class="form-control" placeholder="Ej: 60">

            <label class="form-label mt-2">Series</label>
            <input type="number" name="grupos[${index}][series]" class="form-control" placeholder="Ej: 10">

             <label class="form-label mt-2">Repeticiones</label>
            <input type="number" name="grupos[${index}][reps]" class="form-control" placeholder="Ej: 4">

            <button type="button" class="btn btn-danger btn-sm mt-2 remove-grupo">Eliminar</button>
        `;

            container.appendChild(newGroup);
            index++;
        });

        // ❌ Eliminar bloque
        container.addEventListener("click", (e) => {
            if (e.target.classList.contains("remove-grupo")) {
                e.target.closest(".grupo-item").remove();
            }
        });

        // 🎯 Cargar ejercicios cuando se elige un grupo
        container.addEventListener("change", async (e) => {
            if (e.target.classList.contains("grupo-select")) {
                const grupoId = e.target.value;
                const index = e.target.dataset.index;
                const ejercicioContainer = e.target.closest(".grupo-item").querySelector(".ejercicio-container");

                if (!grupoId) {
                    ejercicioContainer.innerHTML = "";
                    return;
                }

                // Petición AJAX
                const response = await fetch(`/grupos/${grupoId}/ejercicios`);
                const ejercicios = await response.json();

                let html = `
                <label class="form-label mt-2">Ejercicio</label>
                <select name="grupos[${index}][ejercicio_id]" class="form-control">
                    <option value="">-- Selecciona ejercicio --</option>
            `;

                ejercicios.forEach(ej => {
                    html += `<option value="${ej.id}">${ej.nombre_ejercicio}</option>`;
                });

                html += `</select>`;
                ejercicioContainer.innerHTML = html;
            }
        });
    });
</script>
@stop