<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ErrorController extends Controller
{
    public function error_404()
    {
        $usuarioLogueado = $this->getActiveUserData();

        return view('error.404', ['usuarioLogueado' => $usuarioLogueado]);
    }

    public function error_500()
    {
        $usuarioLogueado = $this->getActiveUserData();

        return view('error.500', ['usuarioLogueado' => $usuarioLogueado]);
    }
}
