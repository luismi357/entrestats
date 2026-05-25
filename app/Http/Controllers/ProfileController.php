<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Mostrar perfil
     */
    public function index(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Editar perfil
     */
    public function edit(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualizar perfil
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Datos básicos
        $user->name = $request->name;
        $user->email = $request->email;

        // Password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Foto
        if ($request->hasFile('photo')) {

            // Borrar foto anterior
            if ($user->photo) {
                Storage::delete('public/profile_photos/' . $user->photo);
            }

            $photoName = time() . '.' . $request->photo->extension();

            $request->photo->storeAs('public/profile_photos/',$photoName);

            $user->photo = $photoName;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('success', 'Perfil actualizado correctamente');
    }

    

    /**
     * Eliminar cuenta
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}