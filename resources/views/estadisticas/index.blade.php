@extends('adminlte::page')

@section('title', 'Estadisticas')

@section('content')
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pecho</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Aquí va el contenido del modal -->
          <div id="resultadosPecho"><h4>Has superado al <span id="pecho-porcentaje">{{ $porcentajeSuperadoPecho }}</span>% de los usuarios en pecho</h4></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary">Guardar cambios</button>
        </div>
      </div>
    </div>
  </div>
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
        <span style="margin-top:2%;">
            
            <div id="resultadosBiceps" class="col-md-12S"><h3>Has superado al <span id="biceps-porcentaje">{{ $porcentajeSuperadoBiceps }}</span>% de los usuarios en bíceps</h3></div>
            <div id="resultadosPierna"><h3>Has superado al <span id="pierna-porcentaje">{{ $porcentajeSuperadoPierna }}</span>% de los usuarios en pierna</h3></div>
            <div id="resultadosHombro"><h3>Has superado al <span id="hombro-porcentaje">{{ $porcentajeSuperadoHombro }}</span>% de los usuarios en hombro</h3></div>
        </span>
    </div>

      
    <div class="container text-center">
        <div class="row">
          <div class="col" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="cursor:pointer;">
            <img src="https://www.shareicon.net/data/128x128/2016/06/18/596432_torso_512x512.png" alt="">
          </div>
          <div class="col">
            <img src="https://cdn-icons-png.flaticon.com/512/787/787343.png" style="width: 50%;" alt="">
          </div>
          <div class="col">
            <img src="https://cdn-icons-png.flaticon.com/512/5850/5850570.png" style="width: 53%;" alt="">
          </div>
          <div class="col">
            <img src="https://www.shareicon.net/data/128x128/2016/06/18/596479_hand_512x512.png" alt="">
          </div>
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Función para renderizar el gráfico según el tipo seleccionado
        const porcentajesPorDia = @json($porcentajesPorDia);

        function updateFrases(data) {
            document.getElementById('pecho-porcentaje').innerText = data.pecho || 0;
            document.getElementById('biceps-porcentaje').innerText = data.biceps || 0;
            document.getElementById('pierna-porcentaje').innerText = data.pierna || 0;
            document.getElementById('hombro-porcentaje').innerText = data.hombro || 0;
        }

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
            updateFrases(data);
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

