<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Division;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $divisions = Division::all();
        $upcomingEvents = Event::with('division')
            ->where('status', 'upcoming')
            ->orderBy('event_date')
            ->get();

        $pastEvents = Event::with('division')
            ->where('status', 'completed')
            ->orderByDesc('event_date')
            ->take(6)
            ->get();

        return view('events.index', compact('upcomingEvents', 'pastEvents', 'divisions'));
    }
}
