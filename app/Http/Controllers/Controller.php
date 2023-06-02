<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Retorna una respuesta exitosa básica
     *
     * @param [String] $message
     * @return void
     */
    public function basicResponse($message)
    {
        return response()->json([
            'message' => $message
        ]);
    }

    /**
     * Retorna una respuesta con un array de datos
     *
     * @param [type] $data
     * @return void
     */
    public function responseWhidthData($message, $data)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ]);
    }
}
