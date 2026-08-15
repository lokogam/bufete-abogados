<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Abogado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @mixin Abogado
 */
class AbogadoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cedula' => $this->cedula,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'nombre_completo' => $this->nombre_completo,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'especialidad' => $this->especialidad,
            'fecha_asignacion' => $this->whenPivotLoaded(
                'caso_abogado',
                fn () => $this->pivot?->fecha_asignacion ? Carbon::parse($this->pivot->fecha_asignacion)->toDateString() : null,
            ),
        ];
    }
}
