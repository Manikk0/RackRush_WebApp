<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Product image model.
class ObrazokProduktu extends Model
{
    use HasFactory;

    // Database table name.
    protected $table = 'product_images';

    // JSON / API responses include a ready-to-use browser URL for <img src>.
    protected $appends = [
        'image_url',
    ];

    // Mass-assignable columns.
    protected $fillable = [
        'product_id',
        'url',
        'order',
    ];

    // Owning product relation.
    public function produkt()
    {
        return $this->belongsTo(Produkt::class, 'product_id');
    }

    // Full URL or path for <img src> (root-relative /storage/... works on 127.0.0.1 aj localhost).
    public function getImageUrlAttribute(): string
    {
        $raw = (string) ($this->attributes['url'] ?? '');

        return self::publicUrlForStoredPath($raw);
    }

    // Shared helper so controllers (cart) can reuse the same rules as Blade.
    public static function publicUrlForStoredPath(?string $raw): string
    {
        if ($raw === null || $raw === '') {
            return asset('assets/grapes_white_tray.png');
        }

        $normalized = str_replace('\\', '/', $raw);

        if (str_contains($normalized, '..')) {
            return asset('assets/grapes_white_tray.png');
        }

        if (str_starts_with($normalized, 'assets/')) {
            return asset($normalized);
        }

        return '/storage/'.ltrim($normalized, '/');
    }
}
