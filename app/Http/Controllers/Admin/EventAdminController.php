<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventAdminController extends Controller
{
    public function index()
    {
        $events = Event::with('division')->latest('event_date')->paginate(15);
        return view('admin.events.index', compact('events'));
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

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Agenda event berhasil ditambahkan!');
    }

    public function edit(Event $event)
    {
        $divisions = Division::all();
        return view('admin.events.form', [
            'event' => $event,
            'divisions' => $divisions,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Event $event)
    {
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

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Agenda event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
