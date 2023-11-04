<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CheckAuthCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        dd(Cookie::get('userdata'));

        $userData = json_decode($request->cookie('userdata'), true);

        if (!$userData) {
            return redirect(getenv('GTLOGIN_WEB'));
        }

        // Guarda los datos del usuario en la sesión para que puedan ser accedidos en otros lugares de la aplicación
        $request->session()->put('userData', $userData);

        return $next($request);
    }
}
