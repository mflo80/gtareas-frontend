<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ErrorController extends Controller
{
    public function index()
    {
        $usuarioLogueado = $this->getActiveUserData();

        return view('error.404', ['usuarioLogueado' => $usuarioLogueado]);
    }

}
