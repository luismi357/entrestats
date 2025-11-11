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

        // Días con registros
        $diasDisponibles = Estadisticas::where('id_user', $userId)
            ->select(DB::raw('DATE(dia) as fecha'))
            ->distinct()
            ->orderBy('fecha', 'desc')
            ->pluck('fecha');

        // Si hay días, toma el más reciente
        $ultimoDia = $diasDisponibles->first();

        $estadisticasPorDia = [];

        foreach ($diasDisponibles as $fecha) {
            // Traer todas las entradas reales de ese día con sus relaciones
            $registros = Estadisticas::where('id_user', $userId)
                ->whereDate('dia', $fecha)
                ->select('grupo_muscular_id', 'ejercicio_id', 'peso')
                ->with([
                    'grupoMuscular:id,nombre_grupo',
                    'ejercicio:id,nombre_ejercicio'
                ])
                ->get();

            // Estructuramos los datos en formato { "Pecho - Press Banca": 80, "Pecho - Aperturas": 20 }
            $datosGrafica = [];
            foreach ($registros as $r) {
                $nombreGrupo = $r->grupoMuscular->nombre_grupo ?? 'Desconocido';
                $nombreEjercicio = $r->ejercicio->nombre_ejercicio ?? 'Ejercicio';
                $clave = "{$nombreGrupo} - {$nombreEjercicio}";
                $datosGrafica[$clave] = $r->peso;
            }

            $estadisticasPorDia[$fecha] = $datosGrafica;
        }

        return view('estadisticas.index', compact('diasDisponibles', 'estadisticasPorDia', 'ultimoDia'));
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

        return redirect()->route('estadisticas.index')
            ->with('success', 'Tus estadísticas se han guardado correctamente.');
    }
}
