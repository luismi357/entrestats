<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminFormularioController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()?->email !== 'luismiortegasancho@gmail.com') {
                abort(403, 'No autorizado');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $submissions = FormSubmission::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.formularios', compact('submissions'));
    }

    public function show($userId)
    {
        $user = User::findOrFail($userId);
        $submission = FormSubmission::where('user_id', $userId)->firstOrFail();

        return view('admin.formulario-detalle', compact('user', 'submission'));
    }
}
