<?php

namespace App\Http\Controllers;

use App\Models\{Project, Category, Bid, Contract};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::with(['client', 'category', 'bids'])
            ->open()
            ->byCategory($request->category)
            ->search($request->q)
            ->when($request->budget_max, fn($q) => $q->where('budget_max', '<=', $request->budget_max))
            ->when($request->sort === 'oldest', fn($q) => $q->oldest(), fn($q) => $q->latest())
            ->paginate(12);

        $categories = Category::withCount('projects')->orderByDesc('projects_count')->get();

        return view('projects.index', compact('projects', 'categories'));
    }

    public function create()
    {
        $this->authorize('client-only');
        $categories = Category::all();
        return view('projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('client-only');

        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string|min:50',
            'category_id'     => 'required|exists:categories,id',
            'budget_min'      => 'required|numeric|min:5',
            'budget_max'      => 'required|numeric|gte:budget_min',
            'deadline'        => 'nullable|date|after:today',
            'required_skills' => 'nullable|array',
            'attachments.*'   => 'nullable|file|max:5120',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('project-attachments', 'public');
            }
        }

        $project = Project::create(array_merge($data, [
            'client_id'   => Auth::id(),
            'attachments' => $attachments,
        ]));

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projet publié avec succès !');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'category', 'bids.freelancer', 'contract']);

        $userBid = Auth::check()
            ? $project->bids()->where('freelancer_id', Auth::id())->first()
            : null;

        return view('projects.show', compact('project', 'userBid'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        $categories = Category::all();
        return view('projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string|min:50',
            'category_id'     => 'required|exists:categories,id',
            'budget_min'      => 'required|numeric|min:5',
            'budget_max'      => 'required|numeric|gte:budget_min',
            'deadline'        => 'nullable|date|after:today',
            'required_skills' => 'nullable|array',
        ]);

        $project->update($data);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projet mis à jour.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('projects.index')
            ->with('success', 'Projet supprimé.');
    }

    public function myProjects()
    {
        $projects = Auth::user()->projects()
            ->with(['bids', 'contract', 'category'])
            ->latest()
            ->paginate(10);

        return view('dashboard.client-projects', compact('projects'));
    }
}
