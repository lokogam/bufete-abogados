<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cliente;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Servicio de dominio para la gestión de clientes.
 */
final class ClienteService
{
    /**
     * Lista paginada de clientes ordenados alfabéticamente con el número de casos.
     * Si se pasa una búsqueda, filtra por cédula, nombre, apellido, email o teléfono.
     *
     * @return LengthAwarePaginator<int, Cliente>
     */
    public function list(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Cliente::query()
            ->withCount('casos')
            ->when($search, static function ($query, string $search): void {
                $query->where(static function ($query) use ($search): void {
                    $query->where('cedula', 'like', "%{$search}%")
                        ->orWhere('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('telefono', 'like', "%{$search}%");
                });
            })
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate($perPage);
    }

    /**
     * Crea un nuevo cliente.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Cliente
    {
        return Cliente::create($data);
    }

    /**
     * Actualiza los datos de un cliente.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Cliente $cliente, array $data): Cliente
    {
        $cliente->update($data);

        return $cliente->fresh();
    }

    /**
     * Elimina un cliente de forma lógica (soft delete).
     */
    public function delete(Cliente $cliente): void
    {
        $cliente->delete();
    }
}
