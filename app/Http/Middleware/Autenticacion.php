<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class Autenticacion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionId = $request->session()->get('session_id');

        if ($sessionId && Cache::has($sessionId)) {
            $datos = Cache::get($sessionId);
            $token = $datos['token'];
            $ultimo_acceso = $datos['ultimo_acceso'];

            if ($ultimo_acceso || now()->diffInMinutes($ultimo_acceso) >= getenv('SESSION_LASTACCESS')) {
                $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                    ->get(getenv('GTOAUTH_AUTENTICADO'));

                if($response->getStatusCode() == 401){
                    return redirect()->route('auth.login')->withErrors([
                        'message' => 'Se ha vencido la sesión.'
                    ], 403);
                }

                if($response->getStatusCode() == 200){
                    $datos['ultimo_acceso'] = Carbon::now();
                    Cache::put($sessionId , $datos, Carbon::now()->addMinutes(getenv('SESSION_LIFETIME')));
                }
            }
            return $next($request);
        }

        return redirect()->route('auth.login')->withErrors([
            'message' => 'No se encuentra autenticado.'
        ], 403);
    }
}
