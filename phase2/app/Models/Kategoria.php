<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategoria extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'image',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Kategoria::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Kategoria::class, 'parent_id');
    }

    public function produkty()
    {
        return $this->hasMany(Produkt::class, 'category_id');
    }
}
