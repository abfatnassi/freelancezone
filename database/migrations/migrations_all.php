<?php
// ════════════════════════════════════════════════════════════
//  DATABASE MIGRATIONS  –  FreelanceZone Platform
//  Run: php artisan migrate --seed
// ════════════════════════════════════════════════════════════

// ── 2024_01_01_000001_create_users_table.php ─────────────────
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['client', 'freelancer', 'admin'])->default('client');
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->json('skills')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('users'); }
};

/*
────────────────────────────────────────────
  2024_01_01_000002_create_categories_table
────────────────────────────────────────────
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('icon')->nullable();
    $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
    $table->timestamps();
});

────────────────────────────────────────────
  2024_01_01_000003_create_projects_table
────────────────────────────────────────────
Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('category_id')->constrained()->nullOnDelete();
    $table->string('title');
    $table->longText('description');
    $table->decimal('budget_min', 10, 2);
    $table->decimal('budget_max', 10, 2);
    $table->date('deadline')->nullable();
    $table->enum('status', ['open','in_progress','completed','cancelled'])->default('open');
    $table->json('required_skills')->nullable();
    $table->json('attachments')->nullable();
    $table->boolean('is_featured')->default(false);
    $table->softDeletes();
    $table->timestamps();
});

────────────────────────────────────────────
  2024_01_01_000004_create_bids_table
────────────────────────────────────────────
Schema::create('bids', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
    $table->foreignId('freelancer_id')->constrained('users')->cascadeOnDelete();
    $table->decimal('amount', 10, 2);
    $table->integer('delivery_days');
    $table->text('cover_letter');
    $table->enum('status', ['pending','accepted','rejected','withdrawn'])->default('pending');
    $table->unique(['project_id', 'freelancer_id']);
    $table->timestamps();
});

────────────────────────────────────────────
  2024_01_01_000005_create_contracts_table
────────────────────────────────────────────
Schema::create('contracts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
    $table->foreignId('bid_id')->constrained()->cascadeOnDelete();
    $table->foreignId('client_id')->constrained('users');
    $table->foreignId('freelancer_id')->constrained('users');
    $table->decimal('amount', 10, 2);
    $table->date('delivery_date');
    $table->enum('status', ['active','completed','disputed','cancelled'])->default('active');
    $table->text('terms')->nullable();
    $table->json('milestones')->nullable();
    $table->timestamp('client_approved_at')->nullable();
    $table->timestamp('freelancer_approved_at')->nullable();
    $table->timestamps();
});

────────────────────────────────────────────
  2024_01_01_000006_create_messages_table
────────────────────────────────────────────
Schema::create('messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
    $table->text('body');
    $table->string('attachment')->nullable();
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
});

────────────────────────────────────────────
  2024_01_01_000007_create_reviews_table
────────────────────────────────────────────
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
    $table->foreignId('reviewer_id')->constrained('users');
    $table->foreignId('reviewed_id')->constrained('users');
    $table->tinyInteger('rating')->unsigned();
    $table->text('comment')->nullable();
    $table->enum('type', ['client_to_freelancer','freelancer_to_client']);
    $table->timestamps();
});

────────────────────────────────────────────
  2024_01_01_000008_create_payments_table
────────────────────────────────────────────
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
    $table->foreignId('payer_id')->constrained('users');
    $table->foreignId('payee_id')->constrained('users');
    $table->decimal('amount', 10, 2);
    $table->decimal('platform_fee', 10, 2)->default(0);
    $table->decimal('net_amount', 10, 2);
    $table->enum('status', ['pending','completed','refunded'])->default('pending');
    $table->string('payment_method')->default('stripe');
    $table->string('transaction_id')->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();
});

────────────────────────────────────────────
  2024_01_01_000009_create_notifications_table
────────────────────────────────────────────
Schema::create('notifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('type');
    $table->string('title');
    $table->text('body');
    $table->json('data')->nullable();
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
});

────────────────────────────────────────────
  2024_01_01_000010_create_tags_tables
────────────────────────────────────────────
Schema::create('tags', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->timestamps();
});

Schema::create('project_tag', function (Blueprint $table) {
    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
    $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
    $table->primary(['project_id', 'tag_id']);
});
*/
