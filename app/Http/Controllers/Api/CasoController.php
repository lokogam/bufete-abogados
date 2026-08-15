<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CasoResource;
use App\Models\Caso;

class CasoController extends Controller
{
    /**
     * Devuelve toda la información de un caso, incluyendo cliente y abogados.
     */
    public function show(Caso $caso): CasoResource
    {
        $caso->load(['cliente', 'abogados']);

        return new CasoResource($caso);
    }
}
