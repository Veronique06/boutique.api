<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    //

    protected $fillable = [
        'boutique_id',
        'nom',
        'description'
    ];

    // Relations
    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
