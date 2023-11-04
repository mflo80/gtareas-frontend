<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TareaController extends Controller
{
    public function index()
    {
        return view('tareas.crear');
    }

    public function guardar(Request $request)
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string'],
            'texto' => ['required', 'string'],
            'fecha_hora_inicio' => ['required', 'date_format:Y-m-d\TH:i'],
            'fecha_hora_fin' => ['required', 'date_format:Y-m-d\TH:i'],
            'categoria' => ['required', 'string'],
            'estado' => ['required', 'string'],
        ], [
            'titulo.required' => 'Debe ingresar el título de la tarea.',
            'texto.required' => 'Debe ingresar el texto de la tarea.',
        ]);

        $fechaHoraInicio = DateTime::createFromFormat('Y-m-d\TH:i', $datos['fecha_hora_inicio']);
        $datos['fecha_hora_inicio'] = $fechaHoraInicio->format('Y-m-d H:i:s');

        $fechaHoraFin = DateTime::createFromFormat('Y-m-d\TH:i', $datos['fecha_hora_fin']);
        $datos['fecha_hora_fin'] = $fechaHoraFin->format('Y-m-d H:i:s');

        $datos['id_usuario'] = auth()->user()->id;

        $response = Http::withHeaders([ "Accept" => "application/json"])
        -> post(getenv("GTAPI_TAREAS"), $datos);

        $valores = json_decode($response->body(), true);

        if($response->getStatusCode() == 200){
            return redirect()->route('tareas.crear')->withErrors([
                'message' => $valores['message'],
            ]);
        }

        return redirect()->route('tareas.crear')->withErrors([
            'message' => $valores['message'],
        ]);
    }
}
