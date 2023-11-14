<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegistroController extends Controller
{
    public function index()
    {
        return view('auth.registro');
    }

    public function registro(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string'],
            'apellido' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nombre.required' => 'Debe ingresar el nombre.',
            'apellido.required' => 'Debe ingresar el apellido.',
            'email.required' => 'Debe ingresar el correo electrónico.',
            'password.required' => 'Debe ingresar la contraseña.',
            'password.min' => 'La contraseña debe contener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $response = Http::withHeaders([ "Accept" => "application/json"])
        -> post(getenv("GTOAUTH_USUARIOS"), $datos);

        $valores = json_decode($response->body(), true);

        if($response->getStatusCode() == 200){
            return redirect()->route('auth.login')->withErrors([
                'message' => $valores['message'],
            ]);
        }

        if($response->getStatusCode() == 400){
            return back()->withErrors([
                'message' => $valores['message'],
            ])->withInput();
        }

        return back()->withErrors([
            'message' => 'Hubo un problema con el registro, intente de nuevo.'
        ]);
    }
}
