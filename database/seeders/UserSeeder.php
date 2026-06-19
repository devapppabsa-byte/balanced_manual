<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Departamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = Departamento::all();

        if ($departamentos->isEmpty()) {
            $this->command->warn('No hay departamentos registrados. Ejecuta primero un seeder de departamentos.');
            return;
        }

        foreach ($departamentos as $depto) {
            $slug = Str::slug($depto->nombre, '_');

            User::updateOrCreate(
                ['email' => $slug . '@balanced.com'],
                [
                    'name' => 'Usuario ' . $depto->nombre,
                    'password' => bcrypt('12345678'),
                    'puesto' => 'Usuario de ' . $depto->nombre,
                    'tipo_usuario' => 'normal',
                    'id_departamento' => $depto->id,
                ]
            );

            $this->command->info("Usuario creado: {$slug}@balanced.com / 12345678 -> {$depto->nombre}");
        }
    }
}
