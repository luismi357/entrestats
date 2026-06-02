<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Imc;
use Illuminate\Http\Request;

class ImcApiController extends Controller
{
    public function index(Request $request)
    {
        $imcs = Imc::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($imcs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cms' => 'required|numeric',
            'kgs' => 'required|numeric',
            'sexo' => 'nullable|string',
        ]);

        $altura = $request->cms / 100;
        $resultado = $request->kgs / ($altura * $altura);

        $imc = Imc::create([
            'user_id' => $request->user()->id,
            'cms' => $request->cms,
            'kgs' => $request->kgs,
            'sexo' => $request->sexo,
            'resultado' => round($resultado, 2),
        ]);

        return response()->json($imc, 201);
    }
}
