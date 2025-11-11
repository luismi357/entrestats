@extends('adminlte::page')

@section('title', 'Estadísticas')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <label for="chartType">Tipo de gráfico:</label>
            <select id="chartType" class="form-control">
                <option value="line">Líneas</option>
                <option value="spline">Spline</option>
                <option value="area">Área</option>
                <option value="column">Columnas</option>
                <option value="bar">Barras</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="chartDay">Selecciona el día:</label>
            <select id="chartDay" class="form-control">
                @foreach ($diasDisponibles as $dia)
                    <option value="{{ $dia }}" {{ $dia == $ultimoDia ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::parse($dia)->format('d-m-Y') }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="containerLine" style="width:100%; height:400px;"></div>

    <div class="text-center mt-4">
        <a href="{{ route('estadisticas.create') }}" class="btn btn-warning">Actualizar Datos</a>
    </div>
</div>
@stop

@section('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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