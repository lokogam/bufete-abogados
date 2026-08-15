<?php

namespace Database\Seeders;

use App\Models\Abogado;
use Illuminate\Database\Seeder;

class AbogadoSeeder extends Seeder
{
    /**
     * Datos de abogados de demostración.
     *
     * @return array<int, array<string, string>>
     */
    public static function datos(): array
    {
        return [
            ['cedula' => '2011112222', 'nombre' => 'Juan', 'apellido' => 'Pérez', 'email' => 'juan.perez@bufete.com', 'telefono' => '3116667788', 'especialidad' => 'Derecho Civil'],
            ['cedula' => '2022223333', 'nombre' => 'Ana', 'apellido' => 'Rodríguez', 'email' => 'ana.rodriguez@bufete.com', 'telefono' => '3127778899', 'especialidad' => 'Derecho Penal'],
            ['cedula' => '2033334444', 'nombre' => 'Luis', 'apellido' => 'Fernández', 'email' => 'luis.fernandez@bufete.com', 'telefono' => '3138889900', 'especialidad' => 'Derecho Laboral'],
            ['cedula' => '2044445555', 'nombre' => 'Carolina', 'apellido' => 'Silva', 'email' => 'carolina.silva@bufete.com', 'telefono' => '3149990011', 'especialidad' => 'Derecho Comercial'],
        ];
    }

    public function run(): void
    {
        foreach (self::datos() as $dato) {
            Abogado::updateOrCreate(['cedula' => $dato['cedula']], $dato);
        }
    }
}
