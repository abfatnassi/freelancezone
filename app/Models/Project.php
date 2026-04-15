<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_OPEN       = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_CANCELLED  = 'cancelled';

    protected $fillable = [
        'client_id', 'title', 'description', 'category_id',
        'budget_min', 'budget_max', 'deadline', 'status',
        'required_skills', 'attachments', 'is_featured',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'attachments'     => 'array',
        'deadline'        => 'date',
        'is_featured'     => 'boolean',
        'budget_min'      => 'decimal:2',
        'budget_max'      => 'decimal:2',
    ];

    /* ── Relationships ── */

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function acceptedBid()
    {
        return $this->hasOne(Bid::class)->where('status', Bid::STATUS_ACCEPTED);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /* ── Scopes ── */

    public function scopeOpen($q)
    {
        return $q->where('status', self::STATUS_OPEN);
    }

    public function scopeFeatured($q)
    {
        return $q->where('is_featured', true);
    }

    public function scopeByCategory($q, $categoryId)
    {
        return $q->when($categoryId, fn($q) => $q->where('category_id', $categoryId));
    }

    public function scopeSearch($q, $term)
    {
        return $q->when($term, fn($q) => $q->where(function ($q) use ($term) {
            $q->where('title', 'like', "%$term%")
              ->orWhere('description', 'like', "%$term%");
        }));
    }

    /* ── Helpers ── */

    public function getBudgetRangeAttribute(): string
    {
        return '$' . number_format($this->budget_min) . ' – $' . number_format($this->budget_max);
    }
}
