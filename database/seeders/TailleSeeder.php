<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Taille;

class TailleSeeder extends Seeder
{
    public function run(): void
    {
        $tailles = [
            // Tailles vêtements standard
            ['nom' => 'Extra Small', 'code' => 'xs', 'description' => 'Taille très petite'],
            ['nom' => 'Small', 'code' => 's', 'description' => 'Petite taille'],
            ['nom' => 'Medium', 'code' => 'm', 'description' => 'Taille moyenne'],
            ['nom' => 'Large', 'code' => 'l', 'description' => 'Grande taille'],
            ['nom' => 'Extra Large', 'code' => 'xl', 'description' => 'Très grande taille'],
            ['nom' => 'XXL', 'code' => 'xxl', 'description' => 'Taille extra large'],

            // Tailles chaussures (pointures européennes)
            ['nom' => '36', 'code' => '36', 'description' => 'Pointure 36'],
            ['nom' => '37', 'code' => '37', 'description' => 'Pointure 37'],
            ['nom' => '38', 'code' => '38', 'description' => 'Pointure 38'],
            ['nom' => '39', 'code' => '39', 'description' => 'Pointure 39'],
            ['nom' => '40', 'code' => '40', 'description' => 'Pointure 40'],
            ['nom' => '41', 'code' => '41', 'description' => 'Pointure 41'],
            ['nom' => '42', 'code' => '42', 'description' => 'Pointure 42'],
            ['nom' => '43', 'code' => '43', 'description' => 'Pointure 43'],
            ['nom' => '44', 'code' => '44', 'description' => 'Pointure 44'],
            ['nom' => '45', 'code' => '45', 'description' => 'Pointure 45'],

            // Tailles spécifiques
            ['nom' => 'Unique', 'code' => 'unique', 'description' => 'Taille unique'],
            ['nom' => 'Personnalisé', 'code' => 'custom', 'description' => 'Taille personnalisée'],
        ];

        foreach ($tailles as $tailleData) {
            Taille::create($tailleData);
        }

        $this->command->info('18 tailles créées avec succès!');
    }
}
