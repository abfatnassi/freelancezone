<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, ProjectController, BidController,
    ContractController, MessageController, ReviewController,
    ProfileController, DashboardController, AdminController
};

// ── Public ──────────────────────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

// Auth
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// Projects (public browse)
Route::get('/projects',          [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

// Profiles
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

// ── Authenticated ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/settings/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/settings/profile',   [ProfileController::class, 'update'])->name('profile.update');

    // Projects (CRUD for clients)
    Route::get('/projects/create',          [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects',                [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit',  [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}',       [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}',    [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/my-projects',              [ProjectController::class, 'myProjects'])->name('projects.mine');

    // Bids
    Route::post('/projects/{project}/bids',     [BidController::class, 'store'])->name('bids.store');
    Route::post('/bids/{bid}/accept',           [BidController::class, 'accept'])->name('bids.accept');
    Route::delete('/bids/{bid}',                [BidController::class, 'destroy'])->name('bids.destroy');

    // Contracts
    Route::get('/contracts',                    [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/{contract}',         [ContractController::class, 'show'])->name('contracts.show');
    Route::post('/contracts/{contract}/complete',[ContractController::class, 'complete'])->name('contracts.complete');
    Route::post('/contracts/{contract}/dispute', [ContractController::class, 'dispute'])->name('contracts.dispute');

    // Messages
    Route::get('/messages',                     [MessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('/messages/{user}',              [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages/{user}',             [MessageController::class, 'send'])->name('messages.send');

    // Reviews
    Route::post('/contracts/{contract}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

// ── Admin ───────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/',              [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',         [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
    Route::get('/projects',      [AdminController::class, 'projects'])->name('projects');
});
