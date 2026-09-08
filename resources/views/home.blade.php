@extends('adminlte::page')

@section('title', 'Panel de control')

@section('content_header')
    <h1>Panel de control</h1>
    <p class="mb-0 text-muted">Resumen de tu progreso, todo en un vistazo.</p>
@stop

@section('content')
<div class="container-fluid">

    {{-- Métricas principales --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(233,181,88,.14); color:var(--gold);">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-label">Usuarios</div>
                    <div class="stat-value">{{ number_format($totalUsuarios, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(79,212,162,.14); color:var(--success);">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <div>
                    <div class="stat-label">Entrenamientos</div>
                    <div class="stat-value">{{ number_format($totalEntrenamientos, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(114,184,245,.14); color:var(--info);">
                    <i class="fas fa-fire"></i>
                </div>
                <div>
                    <div class="stat-label">Volumen total</div>
                    <div class="stat-value">{{ number_format($volumenTotal ?? 0, 0, ',', '.') }}<small style="font-size:.9rem; color:var(--text-3);"> kg</small></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(244,113,127,.14); color:var(--danger);">
                    <i class="fas fa-weight-hanging"></i>
                </div>
                <div>
                    <div class="stat-label">IMC calculados</div>
                    <div class="stat-value">{{ number_format($totalImc, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Gráfico --}}
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-2" style="color:var(--gold);"></i> Altas registradas</h3>
                </div>
                <div class="card-body">
                    <div class="chartjs-render-monitor">
                        {!! $chart->renderHtml() !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- Accesos rápidos --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt mr-2" style="color:var(--gold);"></i> Accesos rápidos</h3>
                </div>
                <div class="card-body d-flex flex-column gap-2">
                    <a href="{{ route('estadisticas.create') }}" class="btn btn-primary btn-lg btn-block text-left">
                        <i class="fas fa-dumbbell mr-2"></i> Registrar entrenamiento
                    </a>
                    <a href="{{ route('estadisticas.index') }}" class="btn btn-outline-primary btn-block text-left">
                        <i class="fas fa-chart-line mr-2"></i> Ver estadísticas
                    </a>
                    <a href="{{ route('imc.index') }}" class="btn btn-outline-secondary btn-block text-left">
                        <i class="fas fa-weight-hanging mr-2"></i> Calcular IMC
                    </a>
                    <a href="{{ url('/chat') }}" class="btn btn-outline-secondary btn-block text-left">
                        <i class="fas fa-comments mr-2"></i> Abrir el chat
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-block text-left">
                        <i class="fas fa-user-edit mr-2"></i> Editar mi perfil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">

        {{-- Últimos usuarios --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-plus mr-2" style="color:var(--gold);"></i> Últimos registros</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Registrado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosUsuarios as $u)
                                    <tr>
                                        <td class="font-weight-bold">{{ $u->name }}</td>
                                        <td class="text-muted">{{ $u->email }}</td>
                                        <td class="text-muted">{{ $u->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-muted text-center py-4">Todavía no hay usuarios registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actividad resumen --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-activity mr-2" style="color:var(--gold);"></i> Plataforma</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div style="background:var(--bg-hover); border-radius:14px; padding:1rem;">
                                <i class="fas fa-comments mb-2" style="color:var(--info); font-size:1.4rem;"></i>
                                <div style="font-size:2rem; font-weight:800; font-family:'Outfit',sans-serif; line-height:1.2;">{{ number_format($totalMensajes, 0, ',', '.') }}</div>
                                <div class="text-muted small">Mensajes</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="background:var(--bg-hover); border-radius:14px; padding:1rem;">
                                <i class="fas fa-clipboard-check mb-2" style="color:var(--success); font-size:1.4rem;"></i>
                                <div style="font-size:2rem; font-weight:800; font-family:'Outfit',sans-serif; line-height:1.2;">{{ number_format($totalFormularios, 0, ',', '.') }}</div>
                                <div class="text-muted small">Cuestionarios</div>
                            </div>
                        </div>
                    </div>

                    <div class="callout callout-primary mt-4 mb-0">
                        <i class="fas fa-info-circle mr-1"></i>
                        Registra tus levantamientos cada sesión para que tus estadísticas y gráficos se mantengan actualizados.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    {!! $chart->renderChartJsLibrary() !!}
    <script>
        if (window.Chart) {
            if (Chart.defaults.global) {
                Chart.defaults.global.defaultFontColor = '#a7adbb';
                Chart.defaults.global.defaultColor = 'rgba(255,255,255,0.08)';
            } else {
                Chart.defaults.color = '#a7adbb';
                Chart.defaults.borderColor = 'rgba(255,255,255,0.08)';
            }
        }
    </script>
    {!! $chart->renderJs() !!}
@stop