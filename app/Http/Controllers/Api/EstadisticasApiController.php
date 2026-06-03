<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estadisticas;
use App\Models\GrupoMuscular;
use App\Models\EjercicioPorGrupoMuscular;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class EstadisticasApiController extends Controller
{
    public function gruposMusculares()
    {
        return response()->json(GrupoMuscular::orderBy('nombre_grupo')->get());
    }

    public function ejerciciosPorGrupo($grupoId)
    {
        $ejercicios = EjercicioPorGrupoMuscular::where('grupo_muscular_id', $grupoId)
            ->get()
            ->map(function ($ej) {
                $nombreArchivo = Str::slug($ej->nombre_ejercicio) . '.gif';
                $ruta = 'ejercicios/' . $nombreArchivo;

                return [
                    'id' => $ej->id,
                    'nombre_ejercicio' => $ej->nombre_ejercicio,
                    'imagen' => Storage::disk('public')->exists($ruta)
                        ? asset('storage/' . $ruta)
                        : asset('storage/ejercicios/default.png'),
                ];
            });

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

    public function generarPdf(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $from = $request->from;
        $to = $request->to;
        $userId = $request->user()->id;

        $dias = Estadisticas::where('id_user', $userId)
            ->whereBetween('dia', [$from, $to])
            ->select('dia')
            ->distinct()
            ->orderBy('dia', 'asc')
            ->get();

        $data = [];
        foreach ($dias as $d) {
            $registros = Estadisticas::with(['grupoMuscular', 'ejercicio'])
                ->where('id_user', $userId)
                ->where('dia', $d->dia)
                ->get();

            $data[] = [
                'dia' => $d->dia,
                'ejercicios' => $registros->map(function ($r) {
                    return [
                        'grupo' => $r->grupoMuscular->nombre_grupo ?? 'Sin grupo',
                        'ejercicio' => $r->ejercicio->nombre_ejercicio ?? 'Sin ejercicio',
                        'peso' => $r->peso,
                        'series' => $r->series,
                        'reps' => $r->reps,
                    ];
                }),
            ];
        }

        $pdf = Pdf::loadView('estadisticas.pdf', [
            'data' => $data,
            'from' => $from,
            'to' => $to,
            'userName' => $request->user()->name,
            'userEmail' => $request->user()->email,
            'userSexo' => $request->user()->sexo,
        ]);

        $base64 = base64_encode($pdf->output());
        return response()->json(['pdf' => $base64]);
    }
}
