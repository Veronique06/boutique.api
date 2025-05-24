<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'boutique_id',
        'categorie_id',
        'nom',
        'description',
        'prix',
        'stock',
        'images',
        'visible',
        'tailles_disponibles',
        'couleurs_disponibles',
        'sku',
        'poids',
        'dimensions'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'poids' => 'decimal:3',
        'visible' => 'boolean',
        'images' => 'array',
        'tailles_disponibles' => 'array',
        'couleurs_disponibles' => 'array',
        'dimensions' => 'array',
    ];

    // Relations
    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function produitImages()
    {
        return $this->hasMany(ProduitImage::class)->orderBy('ordre');
    }

    public function imagePrincipale()
    {
        return $this->hasOne(ProduitImage::class)->where('image_principale', true);
    }

    public function tailles()
    {
        return $this->belongsToMany(Taille::class, 'produit_tailles')
            ->withPivot(['stock_taille', 'prix_supplement'])
            ->withTimestamps();
    }

    public function couleurs()
    {
        return $this->belongsToMany(Couleur::class, 'produit_couleurs')
            ->withPivot(['stock_couleur', 'prix_supplement'])
            ->withTimestamps();
    }

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

}
