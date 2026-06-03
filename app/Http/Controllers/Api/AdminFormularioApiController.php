<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Http\Request;

class AdminFormularioApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->user()?->email !== 'luismiortegasancho@gmail.com') {
                abort(403, 'No autorizado');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $submissions = FormSubmission::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'user_id' => $s->user_id,
                    'user_name' => $s->user->name,
                    'user_email' => $s->user->email,
                    'created_at' => $s->created_at,
                ];
            });

        return response()->json($submissions);
    }

    public function show($userId)
    {
        $submission = FormSubmission::with('user:id,name,email')
            ->where('user_id', $userId)
            ->firstOrFail();

        return response()->json($submission);
    }
}
