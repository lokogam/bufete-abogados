<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Abogado;
use Illuminate\Database\Eloquent\Collection;

/**
 * Servicio de dominio para la gestión de abogados.
 */
final class AbogadoService
{
    /**
     * Lista los abogados ordenados alfabéticamente con el número de casos.
     *
     * @return Collection<int, Abogado>
     */
    public function list(): Collection
    {
        return Abogado::query()
            ->withCount('casos')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Crea un nuevo abogado.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Abogado
    {
        return Abogado::create($data);
    }

    /**
     * Actualiza los datos de un abogado.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Abogado $abogado, array $data): Abogado
    {
        $abogado->update($data);

        return $abogado->fresh();
    }

    /**
     * Elimina un abogado de forma lógica (soft delete).
     */
    public function delete(Abogado $abogado): void
    {
        $abogado->delete();
    }
}
