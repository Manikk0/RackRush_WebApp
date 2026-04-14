<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Application user model used for authentication.
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Attributes allowed for mass assignment.
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    // Attributes hidden from arrays/JSON.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Attribute cast rules.
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
