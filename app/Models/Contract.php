<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    const STATUS_ACTIVE    = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DISPUTED  = 'disputed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'project_id', 'bid_id', 'client_id', 'freelancer_id',
        'amount', 'delivery_date', 'status',
        'client_approved_at', 'freelancer_approved_at',
        'terms', 'milestones',
    ];

    protected $casts = [
        'amount'                  => 'decimal:2',
        'delivery_date'           => 'date',
        'client_approved_at'      => 'datetime',
        'freelancer_approved_at'  => 'datetime',
        'milestones'              => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function bid()
    {
        return $this->belongsTo(Bid::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
