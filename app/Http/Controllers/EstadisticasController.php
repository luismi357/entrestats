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
         // Obtener el total de registros
        $ads = Estadisticas::get();
    
        $userId = Auth::id();



    // Obtener las estadísticas máximas del usuario autenticado
    $pechoUser = Estadisticas::where('id_user', $userId)->max('pecho') ?? 0;
    $bicepsUser = Estadisticas::where('id_user', $userId)->max('biceps') ?? 0;
    $piernaUser = Estadisticas::where('id_user', $userId)->max('pierna') ?? 0;
    $hombroUser = Estadisticas::where('id_user', $userId)->max('hombro') ?? 0;

    // Función para calcular el porcentaje de superación para una métrica
    $calcularPorcentaje = function($userMetric, $metricName) use ($userId) {
        $estadisticasMaxMetric = Estadisticas::select('id_user', DB::raw("MAX($metricName) as max_$metricName"))
            ->where('id_user', '!=', $userId)
            ->groupBy('id_user')
            ->get();

        $totalUsuarios = $estadisticasMaxMetric->count();
        $totalMenor = $estadisticasMaxMetric->where("max_$metricName", '<', $userMetric)->count();

        return $totalUsuarios ? ($totalMenor / $totalUsuarios) * 100 : 0;
    };

    // Calcular porcentajes para pecho y bíceps
    $porcentajeSuperadoPecho = $calcularPorcentaje($pechoUser, 'pecho');
    $porcentajeSuperadoBiceps = $calcularPorcentaje($bicepsUser, 'biceps');
    $porcentajeSuperadoPierna = $calcularPorcentaje($piernaUser, 'pierna');
    $porcentajeSuperadoHombro = $calcularPorcentaje($hombroUser, 'hombro');


   

    return view('estadisticas.index', compact(
        'ads', 'pechoUser', 'porcentajeSuperadoPecho',
        'bicepsUser', 'porcentajeSuperadoBiceps',
        'piernaUser', 'porcentajeSuperadoPierna',
        'hombroUser', 'porcentajeSuperadoHombro'
    ));
    }

    public function create()
    {
        return view('estadisticas.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pecho' => 'required|numeric', // Validación numérica
            'biceps' => 'required|numeric', // Validación numérica
            'pierna' => 'required|numeric', // Validación numérica
            'hombro' => 'required|numeric', // Validación numérica
            'dia' => 'required|date',
        ]);

         // Obtener el ID del usuario autenticado
         $userId = Auth::id();

         if (is_null($userId)) {
             return redirect()->route('estadisticas.create')->withErrors('El usuario no está autenticado');
         }

        // Crear una nueva estadística con el ID del usuario autenticado
        $estadistica = new Estadisticas();
        $estadistica->id_user = Auth::id(); // Asigna el ID del usuario autenticado
        $estadistica->pecho = $request->input('pecho');
        $estadistica->biceps = $request->input('biceps');
        $estadistica->pierna = $request->input('pierna');
        $estadistica->hombro = $request->input('hombro');
        $estadistica->dia = $request->input('dia');
        $estadistica->save();

        return redirect()->route('estadisticas.create')->with('success', 'Estadística guardada exitosamente');
    }
}