<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Caso;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Caso
 */
class CasoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numero_expediente' => $this->numero_expediente,
            'estado' => [
                'value' => $this->estado->value,
                'label' => $this->estado->label(),
            ],
            'fecha_inicio' => $this->fecha_inicio?->toDateString(),
            'fecha_finalizacion' => $this->fecha_finalizacion?->toDateString(),
            'descripcion' => $this->descripcion,
            'cliente' => new ClienteResource($this->whenLoaded('cliente')),
            'abogados' => AbogadoResource::collection($this->whenLoaded('abogados')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
