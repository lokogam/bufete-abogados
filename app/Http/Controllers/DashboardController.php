<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\CasoEstado;
use App\Models\Abogado;
use App\Models\Caso;
use App\Models\Cliente;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'casosEnTramite' => Caso::where('estado', CasoEstado::EnTramite->value)->count(),
            'casosArchivados' => Caso::where('estado', CasoEstado::Archivado->value)->count(),
            'totalClientes' => Cliente::count(),
            'totalAbogados' => Abogado::count(),
        ]);
    }
}
