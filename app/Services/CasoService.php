<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Caso;
use Illuminate\Database\Eloquent\Collection;

/**
 * Servicio de dominio para la gestión de casos judiciales.
 */
final class CasoService
{
    /**
     * Lista los casos con cliente y abogados, ordenados por expediente.
     *
     * @return Collection<int, Caso>
     */
    public function list(): Collection
    {
        return Caso::query()
            ->with(['cliente', 'abogados'])
            ->orderBy('numero_expediente')
            ->get();
    }

    /**
     * Crea un caso y sincroniza los abogados asignados.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Caso
    {
        $abogados = $data['abogados'] ?? [];
        unset($data['abogados']);

        $caso = Caso::create($data);
        $this->syncAbogados($caso, $abogados);

        return $caso->load(['cliente', 'abogados']);
    }

    /**
     * Actualiza un caso y sincroniza los abogados asignados.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Caso $caso, array $data): Caso
    {
        $abogados = $data['abogados'] ?? [];
        unset($data['abogados']);

        $caso->update($data);
        $this->syncAbogados($caso, $abogados);

        return $caso->fresh()->load(['cliente', 'abogados']);
    }

    /**
     * Elimina un caso de forma lógica (soft delete).
     */
    public function delete(Caso $caso): void
    {
        $caso->delete();
    }

    /**
     * Sincroniza la relación muchos a muchos con fecha de asignación actual.
     *
     * @param  array<int>  $abogadoIds
     */
    private function syncAbogados(Caso $caso, array $abogadoIds): void
    {
        $pivot = [];

        foreach ($abogadoIds as $abogadoId) {
            $pivot[$abogadoId] = ['fecha_asignacion' => now()->toDateString()];
        }

        $caso->abogados()->sync($pivot);
    }
}
