<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Abogado;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Servicio de dominio para la gestión de abogados.
 */
final class AbogadoService
{
    /**
     * Lista paginada de abogados ordenados alfabéticamente con el número de casos.
     * Si se pasa una búsqueda, filtra por cédula, nombre, apellido, especialidad o email.
     *
     * @return LengthAwarePaginator<int, Abogado>
     */
    public function list(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Abogado::query()
            ->withCount('casos')
            ->when($search, static function ($query, string $search): void {
                $query->where(static function ($query) use ($search): void {
                    $query->where('cedula', 'like', "%{$search}%")
                        ->orWhere('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido', 'like', "%{$search}%")
                        ->orWhere('especialidad', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate($perPage);
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
