<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BoutiqueSeeder::class,
            CategorieSeeder::class,
            TailleSeeder::class,        
            CouleurSeeder::class,       
            ProduitSeeder::class,      
        ]);
    }
}
