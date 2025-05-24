<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Couleur extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'code_hex'];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'produit_couleurs')
            ->withPivot(['stock_couleur', 'prix_supplement'])
            ->withTimestamps();
    }
}
