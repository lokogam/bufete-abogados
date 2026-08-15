<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Collection;

/**
 * Servicio de dominio para la gestión de clientes.
 */
final class ClienteService
{
    /**
     * Lista los clientes ordenados alfabéticamente con el número de casos.
     *
     * @return Collection<int, Cliente>
     */
    public function list(): Collection
    {
        return Cliente::query()
            ->withCount('casos')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();
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
