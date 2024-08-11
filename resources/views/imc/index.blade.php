@extends('adminlte::page')

@section('title', 'IMC')

@section('content')

<div class="container-fluid">
    <div class="row">  
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <!-- Selector para el tipo de gráfico -->
            <label for="chartType">Selecciona el tipo de gráfico:</label>
            <select id="chartType" class="form-control" style="width: 200px;">
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
        <div class="col-md-4"></div>
        <div class="container-fluid" style="margin-top: 2%;">
            <div class="row">
                <div class="col-md-1"></div>
                <div id="containerLine" class="col-md-10" style="width: 100%; height: 400px;"></div>
                <div class="col-md-1"></div>
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
        function renderChart(chartType) {
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
                    name: 'Porcentaje Superado',
                    data: [
                        {{ $porcentajeSuperadoPecho }},
                        {{ $porcentajeSuperadoBiceps }},
                        {{ $porcentajeSuperadoPierna }},
                        {{ $porcentajeSuperadoHombro }}
                    ]
                }],
                exporting: {
                    enabled: true // Habilitar la exportación
                }
            });
        }

        // Renderizar el gráfico inicialmente con el tipo 'line'
        renderChart('line');

        // Actualizar el gráfico cuando el usuario cambie la selección
        document.getElementById('chartType').addEventListener('change', function () {
            var selectedType = this.value;
            renderChart(selectedType);
        });
   
    });
</script>
@stop

