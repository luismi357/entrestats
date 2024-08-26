<?php
namespace App\Http\Controllers;

use App\Models\Estadisticas;
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

        // Obtener los días disponibles de las estadísticas
        $diasDisponibles = Estadisticas::where('id_user', $userId)
            ->orderBy('dia', 'desc')
            ->pluck('dia')
            ->unique()
            ->toArray();

        $porcentajesPorDia = [];

        $ultimoDia = count($diasDisponibles) > 0 ? $diasDisponibles[0] : null;

        foreach ($diasDisponibles as $dia) {
            // Obtener las estadísticas del usuario para ese día
            $estadisticasDia = Estadisticas::where('id_user', $userId)
                ->where('dia', $dia)
                ->orderBy('created_at','desc')
                ->first();
    
            if ($estadisticasDia) {
                $pechoUser = $estadisticasDia->pecho;
                $bicepsUser = $estadisticasDia->biceps;
                $piernaUser = $estadisticasDia->pierna;
                $hombroUser = $estadisticasDia->hombro;

                // Calcular porcentajes para ese día
                $porcentajeSuperadoPecho = $this->calcularPorcentaje($pechoUser, 'pecho', $userId);
                $porcentajeSuperadoBiceps = $this->calcularPorcentaje($bicepsUser, 'biceps', $userId);
                $porcentajeSuperadoPierna = $this->calcularPorcentaje($piernaUser, 'pierna', $userId);
                $porcentajeSuperadoHombro = $this->calcularPorcentaje($hombroUser, 'hombro', $userId);

                // Guardar los porcentajes en el array
                $porcentajesPorDia[$dia] = [
                    'pecho' => $porcentajeSuperadoPecho,
                    'biceps' => $porcentajeSuperadoBiceps,
                    'pierna' => $porcentajeSuperadoPierna,
                    'hombro' => $porcentajeSuperadoHombro,
                ];
            }
        }

        return view('estadisticas.index', compact('diasDisponibles', 'porcentajesPorDia', 'ultimoDia'));
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

    public function create()
    {
        return view('estadisticas.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pecho' => 'required|numeric',
            'biceps' => 'required|numeric',
            'pierna' => 'required|numeric',
            'hombro' => 'required|numeric',
            'dia' => 'required|date',
        ]);

        $userId = Auth::id();

        if (is_null($userId)) {
            return redirect()->route('estadisticas.create')->withErrors('El usuario no está autenticado');
        }

        // Crear una nueva estadística con el ID del usuario autenticado
        $estadistica = new Estadisticas();
        $estadistica->id_user = $userId;
        $estadistica->pecho = $request->input('pecho');
        $estadistica->biceps = $request->input('biceps');
        $estadistica->pierna = $request->input('pierna');
        $estadistica->hombro = $request->input('hombro');
        $estadistica->dia = $request->input('dia');
        $estadistica->save();

        return redirect()->route('estadisticas.create')->with('success', 'Estadística guardada exitosamente');
    }
}
?>