<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Category model for product grouping.
class Kategoria extends Model
{
    use HasFactory;

    // Database table name.
    protected $table = 'categories';

    // Mass-assignable columns.
    protected $fillable = [
        'name',
        'image',
        'parent_id',
    ];

    // Parent category relation.
    public function parent()
    {
        return $this->belongsTo(Kategoria::class, 'parent_id');
    }

    // Child categories relation.
    public function children()
    {
        return $this->hasMany(Kategoria::class, 'parent_id');
    }

    // Products relation.
    public function produkty()
    {
        return $this->hasMany(Produkt::class, 'category_id');
    }
}
