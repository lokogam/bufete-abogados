<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Caso;
use Illuminate\View\View;

class CasoWebController extends Controller
{
    /**
     * Listado de casos en orden ascendente por expediente.
     */
    public function index(): View
    {
        $casos = Caso::query()
            ->with(['cliente', 'abogados'])
            ->orderBy('numero_expediente')
            ->get();

        return view('casos.index', ['casos' => $casos]);
    }

    /**
     * Detalle completo de un caso.
     */
    public function show(Caso $caso): View
    {
        $caso->load(['cliente', 'abogados']);

        return view('casos.show', ['caso' => $caso]);
    }
}
