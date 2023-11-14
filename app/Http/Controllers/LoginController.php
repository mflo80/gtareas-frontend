<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function index(){
        $sessionId = session('session_id');

        if ($sessionId) {
            if(Cache::has($sessionId)){
                return redirect()->to('inicio');
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Debe ingresar el correo electrónico.',
            'password.required' => 'Debe ingresar la contraseña.'
        ]);

        $response = Http::withHeaders([ "Accept" => "application/json"])
            -> post(getenv("GTOAUTH_LOGIN"), $credenciales);

        $valores = json_decode($response->body(), true);

        if($response->getStatusCode() == 200){
            request()->session()->regenerate();

            $datos = [
                'token' => $valores['token'],
                'usuario' => $valores['usuario'],
                'ultimo_acceso' => Carbon::now(),
            ];

            do {
                $sessionId = Str::random(40);
            } while (Cache::has($sessionId));

            Cache::put($sessionId, $datos, Carbon::now()->addMinutes(getenv('SESSION_LIFETIME')));
            $request->session()->put('session_id', $sessionId);

            return redirect()->route('tareas.inicio')->with([
                'usuario' => $datos['usuario'],
            ])->withErrors([
                'message' => $valores['message'],
            ]);
        }

        if($response->getStatusCode() == 401 || $response->getStatusCode() == 500){
            return back()->withErrors([
                'message' => $valores['message'],
            ])->onlyInput('email');
        }
	}

    public function logout(Request $request)
    {
        try {
            $sessionId = $request->session()->get('session_id');

            if(Cache::has($sessionId)){
                if(isset($sessionId)){

                    $datos = Cache::get($sessionId);
                    $token = $datos['token'];

                    $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                    ->get(getenv('GTOAUTH_LOGOUT'));

                    $valores = json_decode($response->body(), true);

                    if($response->getStatusCode() == 200 ||
                       $response->getStatusCode() == 403 ||
                       $response->getStatusCode() == 404 ||
                       $response->getStatusCode() == 500){

                        $message = $valores['message'];
                    }
                }
            }

            if(Cache::has($sessionId) == null){
                $message = 'Tu sesión ha expirado.';
            }

            Cache::forget($sessionId);

            $request->session()->invalidate();

            //Auth::guard('user')->logout();

            return redirect()->to('login')->withErrors([
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return redirect()->to('login')->withErrors([
                'message' => 'Error al cerrar sesión.'
            ]);
        }
    }
}
