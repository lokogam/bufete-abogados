<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $cedula
 * @property string $nombre
 * @property string $apellido
 */
class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'email',
        'telefono',
        'direccion',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    /**
     * @return HasMany<Caso, $this>
     */
    public function casos(): HasMany
    {
        return $this->hasMany(Caso::class);
    }
}
