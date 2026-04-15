<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_CLIENT    = 'client';
    const ROLE_FREELANCER = 'freelancer';
    const ROLE_ADMIN     = 'admin';

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'bio', 'avatar', 'skills', 'hourly_rate',
        'location', 'website', 'phone', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'skills'            => 'array',
        'is_active'         => 'boolean',
    ];

    /* ── Relationships ── */

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'freelancer_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewed_id');
    }

    public function givenReviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'freelancer_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /* ── Helpers ── */

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isFreelancer(): bool
    {
        return $this->role === self::ROLE_FREELANCER;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff';
    }
}
