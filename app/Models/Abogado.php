<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $cedula
 * @property string $nombre
 * @property string $apellido
 */
class Abogado extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'abogados';

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'email',
        'telefono',
        'especialidad',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    /**
     * @return BelongsToMany<Caso, $this>
     */
    public function casos(): BelongsToMany
    {
        return $this->belongsToMany(Caso::class, 'caso_abogado')
            ->using(CasoAbogado::class)
            ->withPivot('fecha_asignacion')
            ->withTimestamps();
    }
}
