<?php

namespace Database\Factories;

use App\Enums\CasoEstado;
use App\Models\Caso;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Caso>
 */
class CasoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaInicio = fake()->dateTimeBetween('-2 years', '-6 months');

        return [
            'numero_expediente' => fake()->unique()->numerify('EXP-####-####'),
            'cliente_id' => Cliente::factory(),
            'fecha_inicio' => $fechaInicio,
            'fecha_finalizacion' => null,
            'estado' => CasoEstado::EnTramite,
            'descripcion' => fake()->sentence(),
        ];
    }

    /**
     * Caso ya archivado, con fecha de finalización.
     */
    public function archivado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => CasoEstado::Archivado,
            'fecha_finalizacion' => fake()->dateTimeBetween($attributes['fecha_inicio'], 'now'),
        ]);
    }
}
