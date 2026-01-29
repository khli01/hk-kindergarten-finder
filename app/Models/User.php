<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified',
        'verification_token',
        'email_verified_at',
        'preferred_language',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Check if user's email is verified.
     */
    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified === true;
    }

    /**
     * Mark the user's email as verified.
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified' => true,
            'email_verified_at' => $this->freshTimestamp(),
            'verification_token' => null,
        ])->save();
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Get the user's suggestions.
     */
    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class);
    }

    /**
     * Get the user's favorite schools.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(FavoriteSchool::class);
    }

    /**
     * Get the kindergartens favorited by the user.
     */
    public function favoriteKindergartens(): BelongsToMany
    {
        return $this->belongsToMany(Kindergarten::class, 'favorite_schools')
            ->withPivot('notes')
            ->withTimestamps();
    }

    /**
     * Check if user has favorited a kindergarten.
     */
    public function hasFavorited(Kindergarten $kindergarten): bool
    {
        return $this->favorites()->where('kindergarten_id', $kindergarten->id)->exists();
    }
}
