<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;
use App\Models\Boutique;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Catégories pour TechStore Cotonou (boutique_id: 1)
            [
                'boutique_id' => 1,
                'nom' => 'Non catégorisé',
                'description' => 'Catégorie par défaut pour les produits sans catégorie',
            ],
            [
                'boutique_id' => 1,
                'nom' => 'Smartphones',
                'description' => 'Téléphones intelligents de toutes marques',
            ],
            [
                'boutique_id' => 1,
                'nom' => 'Ordinateurs',
                'description' => 'Ordinateurs portables et de bureau',
            ],
            [
                'boutique_id' => 1,
                'nom' => 'Accessoires',
                'description' => 'Accessoires pour appareils électroniques',
            ],

            // Catégories pour Fashion Center (boutique_id: 2)
            [
                'boutique_id' => 2,
                'nom' => 'Non catégorisé',
                'description' => 'Catégorie par défaut pour les produits sans catégorie',
            ],
            [
                'boutique_id' => 2,
                'nom' => 'Vêtements Homme',
                'description' => 'Mode masculine',
            ],
            [
                'boutique_id' => 2,
                'nom' => 'Vêtements Femme',
                'description' => 'Mode féminine',
            ],
            [
                'boutique_id' => 2,
                'nom' => 'Chaussures',
                'description' => 'Chaussures pour homme et femme',
            ],

            // Catégories pour SuperMarché Marie (boutique_id: 3)
            [
                'boutique_id' => 3,
                'nom' => 'Non catégorisé',
                'description' => 'Catégorie par défaut pour les produits sans catégorie',
            ],
            [
                'boutique_id' => 3,
                'nom' => 'Fruits et Légumes',
                'description' => 'Produits frais du jardin',
            ],
            [
                'boutique_id' => 3,
                'nom' => 'Épicerie',
                'description' => 'Produits d\'épicerie générale',
            ],
            [
                'boutique_id' => 3,
                'nom' => 'Boissons',
                'description' => 'Boissons diverses',
            ],
        ];

        foreach ($categories as $categorieData) {
            Categorie::create($categorieData);
        }
    }
}
