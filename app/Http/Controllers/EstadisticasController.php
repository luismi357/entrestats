<?php

namespace App\Http\Controllers;

use App\Models\Estadisticas;
use App\Models\GrupoMuscular;
use App\Models\EjercicioPorGrupoMuscular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::id();

        // Obtener todos los días distintos donde el usuario registró algo
        $diasDisponibles = Estadisticas::where('id_user', $userId)
            ->select(DB::raw('DATE(dia) as fecha'))
            ->distinct()
            ->orderBy('fecha', 'desc')
            ->pluck('fecha');

        // Array final de datos
        $estadisticasPorDia = [];

        foreach ($diasDisponibles as $fecha) {
            // Obtener los registros de ese día agrupados por grupo muscular
            $grupos = Estadisticas::where('id_user', $userId)
                ->whereDate('dia', $fecha)
                ->select(
                    'grupo_muscular_id',
                    DB::raw('AVG(peso) as promedio_peso'),
                    DB::raw('SUM(series) as total_series'),
                    DB::raw('SUM(reps) as total_reps')
                )
                ->groupBy('grupo_muscular_id')
                ->with('grupoMuscular:id,nombre_grupo') // para traer el nombre
                ->get();
    
            $estadisticasPorDia[$fecha] = $grupos;
        }

        return view('estadisticas.index', compact('diasDisponibles', 'estadisticasPorDia'));
    }


    public function getEjerciciosByGrupo($grupoId)
    {
        $ejercicios = EjercicioPorGrupoMuscular::where('grupo_muscular_id', $grupoId)
            ->orderBy('nombre_ejercicio')
            ->get();

        return response()->json($ejercicios);
    }

    public function create()
    {
        $gruposMusculares = GrupoMuscular::orderBy('nombre_grupo')->get();
        return view('estadisticas.create', compact('gruposMusculares'));
    }

    private function calcularPorcentaje($userMetric, $metricName, $userId)
    {
        $estadisticasMaxMetric = Estadisticas::select('id_user', DB::raw("MAX($metricName) as max_$metricName"))
            ->where('id_user', '!=', $userId)
            ->groupBy('id_user')
            ->get();

        $totalUsuarios = $estadisticasMaxMetric->count();
        $totalMenor = $estadisticasMaxMetric->where("max_$metricName", '<', $userMetric)->count();

        return $totalUsuarios ? ($totalMenor / $totalUsuarios) * 100 : 0;
    }


    public function generalEstadisticas()
    {
        return view('estadisticas.general');
    }


    public function store(Request $request)
    {
        $request->validate([
            'dia' => 'required|date',
            'grupos' => 'required|array|min:1',
            'grupos.*.grupo_id' => 'required|exists:grupos_musculares,id',
            'grupos.*.ejercicio_id' => 'required|exists:ejercicios_por_grupo_muscular,id',
            'grupos.*.peso' => 'required|numeric|min:1',
            'grupos.*.series' => 'required|numeric|min:1',
            'grupos.*.reps' => 'required|numeric|min:1',
        ]);

        foreach ($request->grupos as $grupo) {
            Estadisticas::create([
                'id_user' => auth()->id(),
                'grupo_muscular_id' => $grupo['grupo_id'],
                'ejercicio_id' => $grupo['ejercicio_id'],
                'peso' => $grupo['peso'],
                'series' => $grupo['series'],
                'reps' => $grupo['reps'],
                'dia' => $request->dia,
            ]);
        }

        return redirect()->route('estadisticas.create')
            ->with('success', 'Tus estadísticas se han guardado correctamente.');
    }
}
