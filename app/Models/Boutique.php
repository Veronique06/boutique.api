<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    //
    protected $fillable = [
        'user_id',
        'nom',
        'description',
        'adresse',
        'telephone',
        'logo',
        'image_couverture',
        'lien_acces',
        'devise',
        'visible'
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Categorie::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    // Scopes pour filtrer les boutiques
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function scopeByDevise($query, $devise)
    {
        return $query->where('devise', $devise);
    }
}
