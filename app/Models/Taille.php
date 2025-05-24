<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taille extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'code', 'description'];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'produit_tailles')
            ->withPivot(['stock_taille', 'prix_supplement'])
            ->withTimestamps();
    }
}
