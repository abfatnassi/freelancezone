<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// ── Message ──────────────────────────────────────────────────────────────────
class Message extends Model {
    use HasFactory;
    protected $fillable = ['sender_id','receiver_id','project_id','body','read_at','attachment'];
    protected $casts    = ['read_at' => 'datetime'];

    public function sender()   { return $this->belongsTo(User::class, 'sender_id'); }
    public function receiver() { return $this->belongsTo(User::class, 'receiver_id'); }
    public function project()  { return $this->belongsTo(Project::class); }
}

// ── Review ───────────────────────────────────────────────────────────────────
class Review extends Model {
    use HasFactory;
    protected $fillable = ['contract_id','reviewer_id','reviewed_id','rating','comment','type'];
    protected $casts    = ['rating' => 'integer'];

    public function reviewer() { return $this->belongsTo(User::class, 'reviewer_id'); }
    public function reviewed() { return $this->belongsTo(User::class, 'reviewed_id'); }
    public function contract() { return $this->belongsTo(Contract::class); }
}

// ── Category ─────────────────────────────────────────────────────────────────
class Category extends Model {
    use HasFactory;
    protected $fillable = ['name','slug','description','icon','parent_id'];

    public function parent()   { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children() { return $this->hasMany(Category::class, 'parent_id'); }
    public function projects() { return $this->hasMany(Project::class); }
}

// ── Tag ───────────────────────────────────────────────────────────────────────
class Tag extends Model {
    use HasFactory;
    protected $fillable = ['name','slug'];
    public function projects() { return $this->belongsToMany(Project::class); }
}

// ── Payment ───────────────────────────────────────────────────────────────────
class Payment extends Model {
    use HasFactory;
    const STATUS_PENDING   = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REFUNDED  = 'refunded';

    protected $fillable = [
        'contract_id','payer_id','payee_id','amount',
        'platform_fee','net_amount','status',
        'payment_method','transaction_id','paid_at',
    ];
    protected $casts = [
        'amount'       => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_amount'   => 'decimal:2',
        'paid_at'      => 'datetime',
    ];

    public function contract() { return $this->belongsTo(Contract::class); }
    public function payer()    { return $this->belongsTo(User::class, 'payer_id'); }
    public function payee()    { return $this->belongsTo(User::class, 'payee_id'); }
}

// ── Notification ──────────────────────────────────────────────────────────────
class Notification extends Model {
    use HasFactory;
    protected $fillable = ['user_id','type','title','body','data','read_at'];
    protected $casts    = ['read_at' => 'datetime', 'data' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
}
