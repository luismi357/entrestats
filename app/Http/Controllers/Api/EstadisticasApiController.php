<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estadisticas;
use App\Models\GrupoMuscular;
use App\Models\EjercicioPorGrupoMuscular;
use Illuminate\Http\Request;

class EstadisticasApiController extends Controller
{
    public function gruposMusculares()
    {
        return response()->json(GrupoMuscular::orderBy('nombre_grupo')->get());
    }

    public function ejerciciosPorGrupo($grupoId)
    {
        $ejercicios = EjercicioPorGrupoMuscular::where('grupo_muscular_id', $grupoId)->get();
        return response()->json($ejercicios);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dia' => 'required|date',
            'grupos' => 'required|array|min:1',
            'grupos.*.grupo_id' => 'required|exists:grupos_musculares,id',
            'grupos.*.ejercicios' => 'required|array',
            'grupos.*.ejercicios.*.ejercicio_id' => 'required',
            'grupos.*.ejercicios.*.peso' => 'nullable|numeric',
            'grupos.*.ejercicios.*.series' => 'nullable|integer',
            'grupos.*.ejercicios.*.reps' => 'nullable|integer',
        ]);

        $creados = [];
        foreach ($request->grupos as $grupo) {
            foreach ($grupo['ejercicios'] as $ejercicio) {
                if (!empty($ejercicio['peso']) && !empty($ejercicio['series']) && !empty($ejercicio['reps'])) {
                    $creados[] = Estadisticas::create([
                        'id_user' => $request->user()->id,
                        'grupo_muscular_id' => $grupo['grupo_id'],
                        'ejercicio_id' => $ejercicio['ejercicio_id'],
                        'peso' => $ejercicio['peso'],
                        'series' => $ejercicio['series'],
                        'reps' => $ejercicio['reps'],
                        'dia' => $request->dia,
                    ]);
                }
            }
        }

        return response()->json($creados, 201);
    }

    public function index(Request $request)
    {
        $dias = Estadisticas::where('id_user', $request->user()->id)
            ->select('dia')
            ->distinct()
            ->orderBy('dia', 'desc')
            ->get();

        $resultado = [];
        foreach ($dias as $d) {
            $registros = Estadisticas::with(['grupoMuscular', 'ejercicio'])
                ->where('id_user', $request->user()->id)
                ->where('dia', $d->dia)
                ->get();

            $resultado[] = [
                'dia' => $d->dia,
                'ejercicios' => $registros->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'grupo' => $r->grupoMuscular->nombre_grupo ?? 'Sin grupo',
                        'ejercicio' => $r->ejercicio->nombre_ejercicio ?? 'Sin ejercicio',
                        'peso' => $r->peso,
                        'series' => $r->series,
                        'reps' => $r->reps,
                    ];
                })
            ];
        }

        return response()->json($resultado);
    }
}
