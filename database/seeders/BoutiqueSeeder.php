<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Boutique;

class BoutiqueSeeder extends Seeder
{
    public function run(): void
    {
        $boutiques = [
            [
                'user_id' => 1,
                'nom' => 'TechStore Cotonou',
                'description' => 'Votre boutique de référence pour tous vos besoins technologiques à Cotonou',
                'adresse' => 'Rue des Champs Élysées, Cotonou',
                'telephone' => '+229 97 12 34 56',
                'logo' => null,
                'image_couverture' => null,
                'lien_acces' => 'https://techstore-cotonou.com',
                'devise' => 'FCFA',
                'visible' => true,
            ],
            [
                'user_id' => 1,
                'nom' => 'Fashion Center',
                'description' => 'Mode et accessoires tendance pour homme et femme',
                'adresse' => 'Avenue Steinmetz, Cotonou',
                'telephone' => '+229 97 65 43 21',
                'logo' => null,
                'image_couverture' => null,
                'lien_acces' => 'https://fashion-center.bj',
                'devise' => 'FCFA',
                'visible' => true,
            ],
            [
                'user_id' => 2,
                'nom' => 'SuperMarché Marie',
                'description' => 'Alimentation générale et produits frais',
                'adresse' => 'Marché Dantokpa, Cotonou',
                'telephone' => '+229 96 78 90 12',
                'logo' => null,
                'image_couverture' => null,
                'lien_acces' => null,
                'devise' => 'FCFA',
                'visible' => true,
            ],
        ];

        foreach ($boutiques as $boutiqueData) {
            Boutique::create($boutiqueData);
        }

    }
}
