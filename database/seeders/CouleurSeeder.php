<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Couleur;

class CouleurSeeder extends Seeder
{
    public function run(): void
    {
        $couleurs = [
            // Couleurs de base
            ['nom' => 'Noir', 'code_hex' => '#000000'],
            ['nom' => 'Blanc', 'code_hex' => '#FFFFFF'],
            ['nom' => 'Rouge', 'code_hex' => '#FF0000'],
            ['nom' => 'Bleu', 'code_hex' => '#0000FF'],
            ['nom' => 'Vert', 'code_hex' => '#008000'],
            ['nom' => 'Jaune', 'code_hex' => '#FFFF00'],
            ['nom' => 'Orange', 'code_hex' => '#FFA500'],
            ['nom' => 'Rose', 'code_hex' => '#FFC0CB'],
            ['nom' => 'Violet', 'code_hex' => '#800080'],
            ['nom' => 'Marron', 'code_hex' => '#A52A2A'],
            ['nom' => 'Gris', 'code_hex' => '#808080'],

            // Nuances populaires
            ['nom' => 'Beige', 'code_hex' => '#F5F5DC'],
            ['nom' => 'Bordeaux', 'code_hex' => '#800020'],
            ['nom' => 'Marine', 'code_hex' => '#000080'],
            ['nom' => 'Turquoise', 'code_hex' => '#40E0D0'],
            ['nom' => 'Fuchsia', 'code_hex' => '#FF00FF'],
            ['nom' => 'Lime', 'code_hex' => '#00FF00'],
            ['nom' => 'Argent', 'code_hex' => '#C0C0C0'],
            ['nom' => 'Or', 'code_hex' => '#FFD700'],
            ['nom' => 'Transparent', 'code_hex' => null],
            ['nom' => 'Multicolore', 'code_hex' => null],
        ];

        foreach ($couleurs as $couleurData) {
            Couleur::create($couleurData);
        }

        $this->command->info('21 couleurs créées avec succès!');
    }
}
