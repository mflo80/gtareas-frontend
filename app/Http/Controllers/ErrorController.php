<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ErrorController extends Controller
{
    public function index()
    {
        $usuario = $this->getActiveUserData();

        return view('error.404', ['usuario' => $usuario]);
    }

}
