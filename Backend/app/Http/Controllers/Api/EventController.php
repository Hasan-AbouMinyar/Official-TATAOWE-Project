<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return Event::with('organization')->paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'location' => 'nullable|string|max:255',
            'requiredSkills' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);
        $event = Event::create($data);
        return response()->json($event, 201);
    }

    public function show(Event $event)
    {
        return $event->load('organization');
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after_or_equal:start_time',
            'location' => 'nullable|string|max:255',
            'requiredSkills' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);
        $event->update($data);
        return $event;
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->noContent();
    }

    public function applications(Event $event)
    {
        return $event->applications;
    }

    public function users(Event $event)
    {
        return $event->users;
    }
}
