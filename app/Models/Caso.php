<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CasoEstado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $numero_expediente
 * @property int $cliente_id
 * @property string $fecha_inicio
 * @property string|null $fecha_finalizacion
 * @property CasoEstado $estado
 */
class Caso extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'casos';

    protected $fillable = [
        'numero_expediente',
        'cliente_id',
        'fecha_inicio',
        'fecha_finalizacion',
        'estado',
        'descripcion',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_finalizacion' => 'date',
        'estado' => CasoEstado::class,
    ];

    /**
     * @return BelongsTo<Cliente, $this>
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * @return BelongsToMany<Abogado, $this>
     */
    public function abogados(): BelongsToMany
    {
        return $this->belongsToMany(Abogado::class, 'caso_abogado')
            ->using(CasoAbogado::class)
            ->withPivot('fecha_asignacion')
            ->withTimestamps();
    }
}
