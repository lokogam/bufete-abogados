<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CasoEstado;
use App\Models\Caso;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Servicio de dominio para la gestión de casos judiciales.
 */
final class CasoService
{
    /**
     * Lista paginada de casos con cliente y abogados, ordenados por expediente.
     * Si se pasa una búsqueda, filtra por número de expediente, datos del cliente
     * o etiqueta del estado. El parámetro "estado" permite filtrar por el valor
     * exacto del enum (en_tramite, archivado, sentenciado, desistido, suspendido).
     *
     * @return LengthAwarePaginator<int, Caso>
     */
    public function list(?string $search = null, ?string $estado = null, int $perPage = 10): LengthAwarePaginator
    {
        $estadoBuscado = $search !== null ? CasoEstado::fromLabel($search) : null;

        return Caso::query()
            ->with(['cliente', 'abogados'])
            ->when($estado, static function ($query, string $estado): void {
                $query->where('estado', $estado);
            })
            ->when($search, static function ($query, string $search) use ($estadoBuscado): void {
                $query->where(static function ($query) use ($search, $estadoBuscado): void {
                    $query->where('numero_expediente', 'like', "%{$search}%")
                        ->orWhereHas('cliente', static function ($query) use ($search): void {
                            $query->where('cedula', 'like', "%{$search}%")
                                ->orWhere('nombre', 'like', "%{$search}%")
                                ->orWhere('apellido', 'like', "%{$search}%");
                        });

                    if ($estadoBuscado !== null) {
                        $query->orWhere('estado', $estadoBuscado);
                    }
                });
            })
            ->orderBy('numero_expediente')
            ->paginate($perPage);
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
