<?php

namespace Database\Factories;

use App\Models\Abogado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Abogado>
 */
class AbogadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cedula' => fake()->unique()->numerify('##########'),
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'telefono' => fake()->numerify('3########'),
            'especialidad' => fake()->randomElement([
                'Derecho Civil',
                'Derecho Penal',
                'Derecho Laboral',
                'Derecho Comercial',
                'Derecho de Familia',
            ]),
        ];
    }
}
