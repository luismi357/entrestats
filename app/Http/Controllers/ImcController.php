<?php

namespace App\Http\Controllers;

use App\Models\Imc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class imcController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $userId = Auth::id();
        return view('imc.index');
    }

    public function calculateImc(Request $request)
    {
        $validatedData = $request->validate([
            'cms' => 'required|numeric',
            'kgs' => 'required|numeric',
        ]);

         // Obtener los datos del formulario
         $altura = $request->input('cms');
         $peso = $request->input('kgs');

        $userId = Auth::id();

        if (is_null($userId)) {
            return redirect()->route('imc.index')->withErrors('El usuario no está autenticado');
        }

        //Calculamos IMC
        $calculoImc = $peso / (($altura / 100) ** 2);


        // Crear una nueva estadística con el ID del usuario autenticado
        $Imc = new Imc();
        $Imc->user_id = $userId;
        $Imc->cms = $request->input('cms');
        $Imc->kgs = $request->input('kgs');
        $Imc->resultado = $calculoImc;
        //$Imc->resultado = $request->input('resultado');
        $Imc->save();

        return view('imc.resultado',compact('calculoImc'));
    }

 
}
