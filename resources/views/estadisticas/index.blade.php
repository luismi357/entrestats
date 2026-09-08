@extends('adminlte::page')

@section('title', 'Estadísticas')

@section('content_header')
    <h1>Estadísticas</h1>
    <p class="mb-0 text-muted">Evolución del peso levantado por día.</p>
@stop

@section('content')
<div class="container-fluid">
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="form-group mb-0">
                <label for="chartType" class="text-2 font-weight-semibold">Tipo de gráfico</label>
                <select id="chartType" class="form-control form-select">
                    <option value="line">Líneas</option>
                    <option value="spline">Spline</option>
                    <option value="area">Área</option>
                    <option value="column">Columnas</option>
                    <option value="bar">Barras</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-0">
                <label for="chartDay" class="text-2 font-weight-semibold">Día de entrenamiento</label>
                <select id="chartDay" class="form-control form-select">
                    @foreach ($diasDisponibles as $dia)
                        <option value="{{ $dia }}" {{ $dia == $ultimoDia ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($dia)->format('d-m-Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-line mr-2" style="color:var(--gold);"></i> Peso levantado por ejercicio</h3>
        </div>
        <div class="card-body">
            <div id="containerLine" style="width:100%; height:420px;"></div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-plus-circle mr-2" style="color:var(--gold);"></i> Registrar nuevo entrenamiento</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('estadisticas.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-dumbbell mr-1"></i> Actualizar datos
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-pdf mr-2" style="color:var(--danger);"></i> Descargar PDF de estadísticas</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('estadisticas.pdf') }}" class="row align-items-end g-3">
                <div class="col-md-4">
                    <label for="from" class="text-2 font-weight-semibold">Desde</label>
                    <input type="date" id="from" name="from" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="to" class="text-2 font-weight-semibold">Hasta</label>
                    <input type="date" id="to" name="to" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-download mr-1"></i> Descargar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/highcharts@11/highcharts.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    Highcharts.setOptions({
        colors: ['#e9b558', '#43c793', '#72b8f5', '#f4717f', '#f4c877'],
        chart: {
            backgroundColor: 'transparent',
            style: { fontFamily: "'Inter', sans-serif", color: '#a7adbb' }
        },
        title: { style: { color: '#e8eaef', fontFamily: "'Outfit', sans-serif", fontWeight: '600' } },
        xAxis: {
            lineColor: 'rgba(255,255,255,0.1)',
            tickColor: 'rgba(255,255,255,0.1)',
            gridLineColor: 'rgba(255,255,255,0.06)',
            labels: { style: { color: '#6f7686' } }
        },
        yAxis: {
            lineColor: 'rgba(255,255,255,0.1)',
            gridLineColor: 'rgba(255,255,255,0.06)',
            title: { style: { color: '#a7adbb' } },
            labels: { style: { color: '#6f7686' } }
        },
        legend: { itemStyle: { color: '#a7adbb' }, itemHoverStyle: { color: '#e9b558' } },
        tooltip: {
            backgroundColor: '#141821',
            borderColor: '#e9b558',
            style: { color: '#e8eaef' },
            shadow: { color: 'rgba(0,0,0,0.5)', offsetX: 0, offsetY: 6 }
        },
        plotOptions: {
            series: { borderColor: '#0f1218' },
            column: { colorByPoint: false }
        }
    });

    const estadisticasPorDia = @json($estadisticasPorDia);

    function renderChart(chartType, selectedDay) {
        const data = estadisticasPorDia[selectedDay] || {};
        const categories = Object.keys(data);
        const valores = Object.values(data);

        Highcharts.chart('containerLine', {
            chart: { type: chartType },
            title: { text: `Peso levantado (${selectedDay})` },
            xAxis: {
                categories: categories,
                title: { text: 'Ejercicios' }
            },
            yAxis: {
                title: { text: 'Peso (kg)' }
            },
            series: [{
                name: 'Peso levantado',
                data: valores
            }],
            exporting: { enabled: true }
        });
    }

    const initialDay = document.getElementById('chartDay').value;
    renderChart('column', initialDay);

    document.getElementById('chartType').addEventListener('change', function() {
        const selectedDay = document.getElementById('chartDay').value;
        renderChart(this.value, selectedDay);
    });

    document.getElementById('chartDay').addEventListener('change', function() {
        const selectedType = document.getElementById('chartType').value;
        renderChart(selectedType, this.value);
    });
});
</script>
@stop