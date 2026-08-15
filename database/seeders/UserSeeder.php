<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo@bufete.com'],
            [
                'name' => 'Usuario Demo',
                'password' => bcrypt('password'),
            ],
        );
    }
}
