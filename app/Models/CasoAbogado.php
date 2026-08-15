<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Modelo pivote para la relación N:M entre casos y abogados.
 */
class CasoAbogado extends Pivot
{
    protected $table = 'caso_abogado';

    protected $casts = [
        'fecha_asignacion' => 'date',
    ];
}
