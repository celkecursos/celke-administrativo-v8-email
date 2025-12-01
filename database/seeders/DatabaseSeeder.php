<?php

namespace Database\Seeders;

use App\Models\ExamBookletType;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeds que devem rodar em produção
        if (App::environment() === 'production') {
            $this->call([
                
            ]);
        }

        // Seeds que devem rodar em qualquer ambiente
        if (App::environment() !== 'production') {
            $this->call([
                
            ]);
        }
    }
}
