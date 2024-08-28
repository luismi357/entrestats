@extends('adminlte::page')

@section('title', 'Estadisticas')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">  
        <div class="col-md-6">
            <!-- Selector para el tipo de gráfico -->
            <label for="chartType">Selecciona el tipo de gráfico:</label>
            <select id="chartType" class="form-control" style="width: 100%;">
                <option value="line">Líneas</option>
                <option value="spline">Spline</option>
                <option value="area">Area</option>
                <option value="areaspline">Areaspline</option>
                <option value="column">Columnas</option>
                <option value="bar">Barras</option>
                <option value="pie">Pie</option>
                <option value="scatter">Scatter</option>
                
                <!-- Puedes añadir más tipos de gráficos que soporte Highcharts -->
            </select>
        </div>
        <!-- Selector para el día -->
        <div class="col-md-6">
            <label for="chartDay">Selecciona el día:</label>
            <select id="chartDay" class="form-control" style="width: 100%;">
                @foreach($diasDisponibles as $dia)
                <option value="{{ $dia }}" {{ $dia == $ultimoDia ? 'selected' : '' }}>
                    <!-- Parsea el dia quitando la hora y mostrando solo la fecha en d-m-Y con objeto de tipo Carbon -->
                    {{ \Carbon\Carbon::parse($dia)->format('d-m-Y') }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <div id="resultadosPecho">Has superado al {{ $porcentajeSuperadoPecho }}% de los usuarios en pecho</div>
            <div id="resultadosPecho">Has superado al {{ $porcentajeSuperadoBiceps }}% de los usuarios en biceps</div>
            <div id="resultadosPecho">Has superado al {{ $porcentajeSuperadoPierna }}% de los usuarios en pierna</div>
            <div id="resultadosPecho">Has superado al {{ $porcentajeSuperadoHombro }}% de los usuarios en hombro</div>
        </div>
    </div>
    
    <div class="container-fluid" style="margin-top: 2%;">
        <div class="row">
            <div class="col-md-1"></div>
            <div id="containerLine" class="col-md-10" style="width: 100%; height: 400px;"></div>
            <div class="col-md-1"></div>
        </div>
    </div>
    <div class="row justify-content-center" style="margin-top: 2%;">
        <div class="row">
            <a href="{{ route('estadisticas.create')}}" class="btn btn-warning">Actualizar Datos</a>
        </div>
    </div>
</div>
</div>
@stop

@section('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Función para renderizar el gráfico según el tipo seleccionado
        const porcentajesPorDia = @json($porcentajesPorDia);
        function renderChart(chartType, selectedDay) {
            const data = porcentajesPorDia[selectedDay] || {pecho: 0, biceps: 0, pierna: 0, hombro: 0};
            Highcharts.chart('containerLine', {
                chart: {
                    type: chartType
                },
                title: {
                    text: 'Porcentaje de Usuarios Superados'
                },
                xAxis: {
                    categories: ['pecho', 'biceps', 'pierna', 'hombro']
                },
                yAxis: {
                    title: {
                        text: 'Porcentaje (%)'
                    },
                    max: 100
                },
                series: [{
                    name: `Porcentaje Superado (${selectedDay})`,
                    data: [data.pecho, data.biceps, data.pierna, data.hombro]
                }],
                exporting: {
                    enabled: true // Habilitar la exportación
                }
            });
        }

        // Renderizar el gráfico inicialmente con el tipo 'line'
        const initialDay = document.getElementById('chartDay').value;
        renderChart('line',initialDay);

        // Actualizar el gráfico cuando el usuario cambie la selección
        document.getElementById('chartType').addEventListener('change', function () {
            var selectedDay = initialDay;
            var selectedType = this.value;
            renderChart(selectedType, selectedDay);
        });
        //Actualizar el grafico cuando el usuario cambie el dia
        document.getElementById('chartDay').addEventListener('change', function () {
            var selectedDay = this.value;
            var selectedType = document.getElementById('chartType').value;
            renderChart(selectedType, selectedDay);
        });

        document.getElementById('resultadosPecho').addEventListener('change', function () {
            var selectedDay = this.value;
            var selectedType = document.getElementById('chartType').value;
            renderChart(selectedType, selectedDay);
        });
        

    });
</script>
@stop

