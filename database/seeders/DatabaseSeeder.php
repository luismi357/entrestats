<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;       // <-- IMPORTANTE
use App\Models\GrupoMuscular;            // si lo usas



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EjerciciosPorGrupoMuscularSeeder::class,
        ]);
    }
}
