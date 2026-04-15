<?php
// ════════════════════════════════════════════════════════════
//  BidController
// ════════════════════════════════════════════════════════════
namespace App\Http\Controllers;

use App\Models\{Bid, Project, Contract, Notification};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function store(Request $request, Project $project)
    {
        abort_if(Auth::user()->role !== 'freelancer', 403);
        abort_if($project->status !== 'open', 422, 'Ce projet n\'accepte plus de soumissions.');

        $exists = Bid::where('project_id', $project->id)
                     ->where('freelancer_id', Auth::id())->exists();
        abort_if($exists, 422, 'Vous avez déjà soumis une offre.');

        $data = $request->validate([
            'amount'        => 'required|numeric|min:5',
            'delivery_days' => 'required|integer|min:1|max:365',
            'cover_letter'  => 'required|string|min:50|max:2000',
        ]);

        $bid = Bid::create(array_merge($data, [
            'project_id'    => $project->id,
            'freelancer_id' => Auth::id(),
        ]));

        // Notify client
        Notification::create([
            'user_id' => $project->client_id,
            'type'    => 'new_bid',
            'title'   => 'Nouvelle offre reçue',
            'body'    => Auth::user()->name . ' a soumis une offre pour "' . $project->title . '"',
            'data'    => ['bid_id' => $bid->id, 'project_id' => $project->id],
        ]);

        return back()->with('success', 'Votre offre a été soumise !');
    }

    public function accept(Bid $bid)
    {
        $project = $bid->project;
        abort_if($project->client_id !== Auth::id(), 403);

        // Accept this bid
        $bid->update(['status' => 'accepted']);

        // Reject others
        $project->bids()->where('id', '!=', $bid->id)->update(['status' => 'rejected']);

        // Update project status
        $project->update(['status' => 'in_progress']);

        // Create contract
        $contract = Contract::create([
            'project_id'    => $project->id,
            'bid_id'        => $bid->id,
            'client_id'     => Auth::id(),
            'freelancer_id' => $bid->freelancer_id,
            'amount'        => $bid->amount,
            'delivery_date' => now()->addDays($bid->delivery_days),
        ]);

        // Notify freelancer
        Notification::create([
            'user_id' => $bid->freelancer_id,
            'type'    => 'bid_accepted',
            'title'   => 'Votre offre a été acceptée !',
            'body'    => 'Votre offre pour "' . $project->title . '" a été acceptée. Contrat #' . $contract->id . ' créé.',
            'data'    => ['contract_id' => $contract->id],
        ]);

        return back()->with('success', 'Offre acceptée. Contrat créé.');
    }

    public function destroy(Bid $bid)
    {
        abort_if($bid->freelancer_id !== Auth::id(), 403);
        $bid->update(['status' => 'withdrawn']);
        return back()->with('success', 'Offre retirée.');
    }
}

// ════════════════════════════════════════════════════════════
//  ContractController
// ════════════════════════════════════════════════════════════
class ContractController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $contracts = $user->isFreelancer()
            ? Contract::where('freelancer_id', $user->id)
            : Contract::where('client_id', $user->id);

        $contracts = $contracts->with(['project', 'client', 'freelancer'])->latest()->paginate(10);
        return view('dashboard.contracts', compact('contracts'));
    }

    public function show(Contract $contract)
    {
        abort_if(
            $contract->client_id !== Auth::id() && $contract->freelancer_id !== Auth::id(),
            403
        );
        $contract->load(['project', 'client', 'freelancer', 'bid', 'review', 'payment']);
        return view('contracts.show', compact('contract'));
    }

    public function complete(Contract $contract)
    {
        abort_if($contract->client_id !== Auth::id(), 403);
        $contract->update([
            'status'             => 'completed',
            'client_approved_at' => now(),
        ]);
        $contract->project->update(['status' => 'completed']);

        Notification::create([
            'user_id' => $contract->freelancer_id,
            'type'    => 'contract_completed',
            'title'   => 'Contrat terminé',
            'body'    => 'Le client a marqué le contrat comme terminé. Paiement en cours.',
            'data'    => ['contract_id' => $contract->id],
        ]);

        return back()->with('success', 'Contrat marqué comme terminé.');
    }

    public function dispute(Contract $contract)
    {
        $isParty = in_array(Auth::id(), [$contract->client_id, $contract->freelancer_id]);
        abort_if(!$isParty, 403);
        $contract->update(['status' => 'disputed']);
        return back()->with('info', 'Un litige a été ouvert. Notre équipe vous contactera.');
    }
}

// ════════════════════════════════════════════════════════════
//  MessageController
// ════════════════════════════════════════════════════════════

use App\Models\{Message, User};

class MessageController extends Controller
{
    public function inbox()
    {
        $userId = Auth::id();

        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(fn($m) => $m->sender_id === $userId ? $m->receiver_id : $m->sender_id)
            ->map(fn($msgs) => $msgs->first());

        return view('messages.inbox', compact('conversations'));
    }

    public function conversation(User $user)
    {
        $myId = Auth::id();

        Message::where('sender_id', $user->id)
               ->where('receiver_id', $myId)
               ->whereNull('read_at')
               ->update(['read_at' => now()]);

        $messages = Message::where(fn($q) =>
                $q->where('sender_id', $myId)->where('receiver_id', $user->id))
            ->orWhere(fn($q) =>
                $q->where('sender_id', $user->id)->where('receiver_id', $myId))
            ->with(['sender', 'receiver'])
            ->oldest()
            ->get();

        return view('messages.conversation', compact('messages', 'user'));
    }

    public function send(Request $request, User $user)
    {
        $data = $request->validate(['body' => 'required|string|max:2000']);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $user->id,
            'body'        => $data['body'],
            'project_id'  => $request->project_id,
        ]);

        return back()->with('success', 'Message envoyé.');
    }
}

// ════════════════════════════════════════════════════════════
//  ReviewController
// ════════════════════════════════════════════════════════════

use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, Contract $contract)
    {
        $user = Auth::user();
        $isClient     = $contract->client_id === $user->id;
        $isFreelancer = $contract->freelancer_id === $user->id;
        abort_if(!$isClient && !$isFreelancer, 403);
        abort_if($contract->status !== 'completed', 422, 'Contrat non terminé.');

        $type = $isClient ? 'client_to_freelancer' : 'freelancer_to_client';
        $existing = Review::where('contract_id', $contract->id)->where('type', $type)->exists();
        abort_if($existing, 422, 'Vous avez déjà laissé un avis.');

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create(array_merge($data, [
            'contract_id'  => $contract->id,
            'reviewer_id'  => $user->id,
            'reviewed_id'  => $isClient ? $contract->freelancer_id : $contract->client_id,
            'type'         => $type,
        ]));

        return back()->with('success', 'Avis soumis. Merci !');
    }
}

// ════════════════════════════════════════════════════════════
//  ProfileController
// ════════════════════════════════════════════════════════════

use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load(['reviews.reviewer', 'bids.project']);
        $completedContracts = Contract::where('freelancer_id', $user->id)
            ->where('status', 'completed')->count();
        return view('profile.show', compact('user', 'completedContracts'));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'bio'         => 'nullable|string|max:1000',
            'location'    => 'nullable|string|max:100',
            'website'     => 'nullable|url',
            'phone'       => 'nullable|string|max:20',
            'hourly_rate' => 'nullable|numeric|min:1',
            'skills'      => 'nullable|array',
            'avatar'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);
        return back()->with('success', 'Profil mis à jour.');
    }
}

// ════════════════════════════════════════════════════════════
//  DashboardController
// ════════════════════════════════════════════════════════════

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isClient()) {
            $stats = [
                'total_projects'    => $user->projects()->count(),
                'active_projects'   => $user->projects()->where('status', 'in_progress')->count(),
                'completed_projects'=> $user->projects()->where('status', 'completed')->count(),
                'total_bids'        => Bid::whereHas('project', fn($q) => $q->where('client_id', $user->id))->count(),
            ];
            $recentProjects = $user->projects()->with('bids')->latest()->take(5)->get();
            return view('dashboard.client', compact('stats', 'recentProjects'));
        }

        if ($user->isFreelancer()) {
            $stats = [
                'total_bids'      => $user->bids()->count(),
                'accepted_bids'   => $user->bids()->where('status', 'accepted')->count(),
                'active_contracts'=> Contract::where('freelancer_id', $user->id)->where('status', 'active')->count(),
                'total_earned'    => Contract::where('freelancer_id', $user->id)->where('status', 'completed')->sum('amount'),
            ];
            $recentBids      = $user->bids()->with('project')->latest()->take(5)->get();
            $openProjects    = Project::open()->with('category')->latest()->take(6)->get();
            return view('dashboard.freelancer', compact('stats', 'recentBids', 'openProjects'));
        }

        return redirect()->route('admin.dashboard');
    }
}

// ════════════════════════════════════════════════════════════
//  AdminController
// ════════════════════════════════════════════════════════════

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(fn($req, $next) =>
            Auth::user()?->isAdmin() ? $next($req) : abort(403)
        );
    }

    public function dashboard()
    {
        $stats = [
            'total_users'      => User::count(),
            'total_projects'   => Project::count(),
            'active_contracts' => Contract::where('status', 'active')->count(),
            'total_revenue'    => Contract::where('status', 'completed')->sum('amount') * 0.10,
        ];
        $recentUsers    = User::latest()->take(10)->get();
        $recentProjects = Project::with('client', 'category')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentProjects'));
    }

    public function users(Request $request)
    {
        $users = User::when($request->role, fn($q) => $q->where('role', $request->role))
                     ->when($request->q, fn($q) => $q->where('name', 'like', "%{$request->q}%"))
                     ->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'Statut utilisateur mis à jour.');
    }

    public function projects(Request $request)
    {
        $projects = Project::withTrashed()
            ->with('client', 'category')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(20);
        return view('admin.projects', compact('projects'));
    }
}
