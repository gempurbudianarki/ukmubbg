<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('division')->latest('event_date');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location_venue', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->input('division_id'));
        }

        $events = $query->paginate(15)->withQueryString();
        $divisions = Division::all();

        return view('admin.events.index', compact('events', 'divisions'));
    }

    public function create()
    {
        $divisions = Division::all();
        return view('admin.events.form', [
            'event' => new Event(),
            'divisions' => $divisions,
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'nullable',
            'location_type' => 'required|in:offline,online,hybrid',
            'location_venue' => 'required|string|max:255',
            'registration_link' => 'nullable|url|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:upcoming,completed,cancelled',
        ]);

        if ($user->isDivisionAdmin()) {
            $validated['division_id'] = $user->division_id;
        }

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Agenda event berhasil ditambahkan!');
    }

    public function edit(Event $event)
    {
        $user = auth()->user();
        if ($user->isDivisionAdmin() && $event->division_id !== $user->division_id) {
            abort(403, 'Anda tidak berwenang mengedit agenda event divisi lain.');
        }

        $divisions = $user->isSuperAdmin() ? Division::all() : Division::where('id', $user->division_id)->get();
        return view('admin.events.form', [
            'event' => $event,
            'divisions' => $divisions,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $user = auth()->user();
        if ($user->isDivisionAdmin() && $event->division_id !== $user->division_id) {
            abort(403, 'Anda tidak berwenang memperbarui agenda event divisi lain.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'nullable',
            'location_type' => 'required|in:offline,online,hybrid',
            'location_venue' => 'required|string|max:255',
            'registration_link' => 'nullable|url|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:upcoming,completed,cancelled',
        ]);

        if ($user->isDivisionAdmin()) {
            $validated['division_id'] = $user->division_id;
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Agenda event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        $user = auth()->user();
        if ($user->isDivisionAdmin() && $event->division_id !== $user->division_id) {
            abort(403, 'Anda tidak berwenang menghapus agenda event divisi lain.');
        }

        $event->delete();
        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
