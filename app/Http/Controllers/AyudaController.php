<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AyudaController extends Controller
{
    public function tutorial()
    {
        $usuarioLogueado = $this->getActiveUserData();

        return view('opciones.tutorial', ['usuarioLogueado' => $usuarioLogueado]);
    }

    public function acerca()
    {
        $usuarioLogueado = $this->getActiveUserData();

        return view('opciones.acerca', ['usuarioLogueado' => $usuarioLogueado]);
    }

    public function formulario_password()
    {
        $usuarioLogueado = $this->getActiveUserData();

        return view('opciones.password', ['usuarioLogueado' => $usuarioLogueado]);
    }

    public function modificar_password(Request $request)
    {
        $usuarioLogueado = $this->getActiveUserData();
        $token = $this->getActiveUserToken();

        $datos = $request->validate([
            'nombre' => ['required', 'string'],
            'apellido' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Debe ingresar la contraseña.',
            'password.min' => 'La contraseña debe contener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer $token"
        ])->put(getenv("GTOAUTH_USUARIOS") ."/". $usuarioLogueado['id'], $datos);

        $valores = json_decode($response->body(), true);

        if($response->getStatusCode() == 200){
            return redirect()->route('opciones.password')->withErrors([
                'message' => $valores['message'],
            ]);
        }

        return back()->withErrors([
            'message' => $valores['message'],
        ]);
    }
}
