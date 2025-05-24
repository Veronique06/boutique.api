<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;
use App\Models\ProduitImage;
use App\Models\Taille;
use App\Models\Couleur;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $produits = [
            // Produits pour TechStore Cotonou (boutique_id: 1)
            [
                'boutique_id' => 1,
                'categorie_id' => 2, // Smartphones
                'nom' => 'iPhone 15 Pro',
                'description' => 'Smartphone Apple dernière génération avec puce A17 Pro, écran Super Retina XDR et système photo avancé',
                'prix' => 850000.00,
                'stock' => 15,
                'visible' => true,
                'sku' => 'TEC-IPH-0001',
                'poids' => 0.187,
                'dimensions' => ['longueur' => 14.67, 'largeur' => 7.09, 'hauteur' => 0.83],
                'couleurs' => [1, 2, 3], // Noir, Blanc, Rouge
                'tailles' => [17], // Unique
            ],
            [
                'boutique_id' => 1,
                'categorie_id' => 2, // Smartphones
                'nom' => 'Samsung Galaxy S24',
                'description' => 'Smartphone Samsung haut de gamme avec IA intégrée et appareil photo de 200MP',
                'prix' => 680000.00,
                'stock' => 20,
                'visible' => true,
                'sku' => 'TEC-SAM-0002',
                'poids' => 0.196,
                'dimensions' => ['longueur' => 14.7, 'largeur' => 7.06, 'hauteur' => 0.79],
                'couleurs' => [1, 2, 4, 11], // Noir, Blanc, Bleu, Gris
                'tailles' => [17], // Unique
            ],
            [
                'boutique_id' => 1,
                'categorie_id' => 4, // Accessoires
                'nom' => 'AirPods Pro',
                'description' => 'Écouteurs sans fil avec réduction de bruit active et son spatial',
                'prix' => 180000.00,
                'stock' => 25,
                'visible' => true,
                'sku' => 'TEC-AIR-0003',
                'poids' => 0.061,
                'couleurs' => [2], // Blanc
                'tailles' => [17], // Unique
            ],

            // Produits pour Fashion Center (boutique_id: 2)
            [
                'boutique_id' => 2,
                'categorie_id' => 6, // Vêtements Homme
                'nom' => 'Chemise Classique Homme',
                'description' => 'Chemise en coton blanc, coupe classique, parfaite pour le bureau ou les occasions formelles',
                'prix' => 25000.00,
                'stock' => 30,
                'visible' => true,
                'sku' => 'FAS-CHE-0001',
                'poids' => 0.3,
                'couleurs' => [2, 4, 11], // Blanc, Bleu, Gris
                'tailles' => [2, 3, 4, 5], // S, M, L, XL
            ],
            [
                'boutique_id' => 2,
                'categorie_id' => 7, // Vêtements Femme
                'nom' => 'Robe Élégante',
                'description' => 'Robe cocktail pour occasions spéciales, tissu fluide et coupe flatteuse',
                'prix' => 45000.00,
                'stock' => 12,
                'visible' => true,
                'sku' => 'FAS-ROB-0002',
                'poids' => 0.4,
                'couleurs' => [1, 3, 4, 9], // Noir, Rouge, Bleu, Violet
                'tailles' => [1, 2, 3, 4], // XS, S, M, L
            ],
            [
                'boutique_id' => 2,
                'categorie_id' => 8, // Chaussures
                'nom' => 'Sneakers Unisexe',
                'description' => 'Baskets confortables pour tous les jours, semelle amortissante',
                'prix' => 35000.00,
                'stock' => 40,
                'visible' => true,
                'sku' => 'FAS-SNE-0003',
                'poids' => 0.8,
                'couleurs' => [1, 2, 3, 4], // Noir, Blanc, Rouge, Bleu
                'tailles' => [7, 8, 9, 10, 11, 12, 13, 14], // Pointures 36-43
            ],

            // Produits pour SuperMarché Marie (boutique_id: 3)
            [
                'boutique_id' => 3,
                'categorie_id' => 9, 
                'nom' => 'Bananes Bio',
                'description' => 'Bananes fraîches du Bénin, cultivées sans pesticides (vendu au kg)',
                'prix' => 800.00,
                'stock' => 100,
                'visible' => true,
                'sku' => 'SUP-BAN-0001',
                'poids' => 1.0,
                'couleurs' => [6], // Jaune
                'tailles' => [17], // Unique
            ],
            [
                'boutique_id' => 3,
                'categorie_id' => 3, 
                'nom' => 'Riz Jasmin Premium',
                'description' => 'Riz parfumé de qualité premium, grain long (sac de 5kg)',
                'prix' => 4500.00,
                'stock' => 50,
                'visible' => true,
                'sku' => 'SUP-RIZ-0002',
                'poids' => 5.0,
                'dimensions' => ['longueur' => 40, 'largeur' => 25, 'hauteur' => 8],
                'couleurs' => [2], // Blanc
                'tailles' => [17], // Unique
            ],
        ];

        foreach ($produits as $produitData) {
            // Extraire les données de relations
            $couleurs = $produitData['couleurs'] ?? [];
            $tailles = $produitData['tailles'] ?? [];

            // Supprimer les clés de relations des données principales
            unset($produitData['couleurs'], $produitData['tailles']);

            // Créer le produit
            $produit = Produit::create($produitData);

            // Associer les couleurs avec stock aléatoire
            if (!empty($couleurs)) {
                foreach ($couleurs as $couleurId) {
                    $produit->couleurs()->attach($couleurId, [
                        'stock_couleur' => rand(1, 10),
                        'prix_supplement' => 0
                    ]);
                }
            }

            // Associer les tailles avec stock aléatoire
            if (!empty($tailles)) {
                foreach ($tailles as $tailleId) {
                    $produit->tailles()->attach($tailleId, [
                        'stock_taille' => rand(1, 15),
                        'prix_supplement' => 0
                    ]);
                }
            }
        }
    }
}
