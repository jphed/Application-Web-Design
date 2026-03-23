<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosRolesSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tickets.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'rol' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'gerente@tickets.com'],
            [
                'name' => 'Gerente General',
                'password' => Hash::make('password'),
                'rol' => 'gerente',
            ]
        );

        User::updateOrCreate(
            ['email' => 'usuario@tickets.com'],
            [
                'name' => 'Juan Pérez',
                'password' => Hash::make('password'),
                'rol' => 'usuario',
            ]
        );
    }
}
