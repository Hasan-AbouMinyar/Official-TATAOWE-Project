<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['organization', 'reviews' => function($query) {
            $query->select('event_id', 'rating');
        }])
        ->withCount('applications')
        ->latest() // ← إضافة ترتيب من الأحدث للأقدم
        ->get()
        ->map(function($event) {
            $event->average_rating = round($event->reviews->avg('rating'), 1);
            $event->total_reviews = $event->reviews->count();
            unset($event->reviews);
            
            // Return full photo URL
            if ($event->photo) {
                $event->photo = asset('storage/' . $event->photo);
            }
            
            // Return full logo URL for organization
            if ($event->organization && $event->organization->logo) {
                $event->organization->logo = asset('storage/' . $event->organization->logo);
            }
            
            return $event;
        });

        return response()->json([
            'data' => $events,
            'current_page' => 1,
            'per_page' => $events->count(),
            'total' => $events->count(),
            'last_page' => 1
        ]);
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('events', 'public');
            $data['photo'] = $photoPath;
        }

        $event = Event::create($data);
        
        // Return event with full photo URL
        if ($event->photo) {
            $event->photo = asset('storage/' . $event->photo);
        }
        
        return response()->json($event, 201);
    }

    public function show(Event $event)
    {
        $event->load(['organization', 'reviews.user:id,name,photo']);
        $event->average_rating = round($event->reviews->avg('rating'), 1);
        $event->total_reviews = $event->reviews->count();
        $event->applications_count = $event->applications()->count();
        
        // Return full photo URL
        if ($event->photo) {
            $event->photo = asset('storage/' . $event->photo);
        }
        
        // Return full logo URL for organization
        if ($event->organization && $event->organization->logo) {
            $event->organization->logo = asset('storage/' . $event->organization->logo);
        }
        
        return $event;
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Handle photo upload and delete old photo
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($event->photo && Storage::disk('public')->exists($event->photo)) {
                Storage::disk('public')->delete($event->photo);
            }
            
            $photoPath = $request->file('photo')->store('events', 'public');
            $data['photo'] = $photoPath;
        }

        $event->update($data);
        
        // Return event with full photo URL
        if ($event->photo) {
            $event->photo = asset('storage/' . $event->photo);
        }
        
        return $event;
    }

    public function destroy(Event $event)
    {
        // Delete photo if exists
        if ($event->photo && Storage::disk('public')->exists($event->photo)) {
            Storage::disk('public')->delete($event->photo);
        }
        
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
