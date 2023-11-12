<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getActiveUserData()
    {
        $sessionId = session('session_id');
        $datos = Cache::get($sessionId);
        $userData = $datos['usuario'];
        return $userData;
    }

    public function getActiveUserToken()
    {
        $sessionId = session('session_id');
        $datos = Cache::get($sessionId);
        $token = $datos['token'];
        return $token;
    }
}
