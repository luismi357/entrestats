<?php

namespace App\Http\Controllers;

use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Estadisticas;
use App\Models\Message;
use App\Models\Imc;
use App\Models\FormSubmission;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $chart_options = [
            'chart_title' => 'Altas registradas',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\User',
            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
        ];
        $chart = new LaravelChart($chart_options);

        $totalUsuarios = User::count();
        $totalEntrenamientos = Estadisticas::count();
        $totalMensajes = Message::count();
        $totalImc = Imc::count();
        $totalFormularios = FormSubmission::count();

        $volumenTotal = Estadisticas::selectRaw('SUM(peso * series * reps) as volumen')
            ->value('volumen');

        $ultimosUsuarios = User::latest()->take(5)->get(['name', 'email', 'created_at']);

        return view('home', compact(
            'chart',
            'totalUsuarios',
            'totalEntrenamientos',
            'totalMensajes',
            'totalImc',
            'totalFormularios',
            'volumenTotal',
            'ultimosUsuarios'
        ));
    }

    public function estadisticas(){
        return view('estadisticas');
    }
}