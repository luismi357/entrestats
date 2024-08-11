<?php

namespace App\Http\Controllers;

use App\Models\Imc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

class ImcController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function create()
    {
        return view('imc.create');
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
        $estadistica = new Imc();
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
