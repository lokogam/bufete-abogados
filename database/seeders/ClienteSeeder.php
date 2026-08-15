<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Datos de clientes de demostración.
     *
     * @return array<int, array<string, string>>
     */
    public static function datos(): array
    {
        return [
            ['cedula' => '1012345678', 'nombre' => 'Carlos', 'apellido' => 'Gómez', 'email' => 'carlos.gomez@mail.com', 'telefono' => '3101112233', 'direccion' => 'Calle 1 # 2-3, Bogotá'],
            ['cedula' => '1019876543', 'nombre' => 'María', 'apellido' => 'López', 'email' => 'maria.lopez@mail.com', 'telefono' => '3202223344', 'direccion' => 'Carrera 4 # 5-6, Medellín'],
            ['cedula' => '1023456789', 'nombre' => 'Andrés', 'apellido' => 'Ramírez', 'email' => 'andres.ramirez@mail.com', 'telefono' => '3003334455', 'direccion' => 'Avenida 7 # 8-9, Cali'],
            ['cedula' => '1034567890', 'nombre' => 'Laura', 'apellido' => 'Torres', 'email' => 'laura.torres@mail.com', 'telefono' => '3154445566', 'direccion' => 'Calle 10 # 11-12, Barranquilla'],
            ['cedula' => '1045678901', 'nombre' => 'Jorge', 'apellido' => 'Martínez', 'email' => 'jorge.martinez@mail.com', 'telefono' => '3015556677', 'direccion' => 'Carrera 13 # 14-15, Cartagena'],
        ];
    }

    public function run(): void
    {
        foreach (self::datos() as $dato) {
            Cliente::updateOrCreate(['cedula' => $dato['cedula']], $dato);
        }
    }
}
