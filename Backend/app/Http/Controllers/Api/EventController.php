<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Notifications\EventCreated;
use App\Notifications\EventUpdated;
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
        
        // Send notification to organization owner
        if ($event->organization && $event->organization->user) {
            $event->organization->user->notify(new EventCreated($event));
        }
        
        // Return event with full photo URL
        if ($event->photo) {
            $event->photo = asset('storage/' . $event->photo);
        }
        
        return response()->json($event, 201);
    }

    public function show(Event $event)
    {
        $event->load(['organization.user:id', 'reviews.user:id,name,photo']);
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
        
        // Send notification to organization owner about update
        if ($event->organization && $event->organization->user) {
            $event->organization->user->notify(new EventUpdated($event));
        }
        
        // Notify all applicants about event update
        $event->applications()->with('user')->get()->each(function($application) use ($event) {
            if ($application->user) {
                $application->user->notify(new EventUpdated($event));
            }
        });
        
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
        // Check if the authenticated user belongs to the organization that owns this event
        $user = auth('sanctum')->user();
        if (!$user || !$event->organization || $event->organization->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to view applications for this event'], 403);
        }

        return $event->applications()
            ->with(['user' => function($query) {
                $query->select('id', 'name', 'email', 'phoneNumber', 'username', 'photo');
            }])
            ->latest()
            ->get()
            ->map(function($application) {
                return [
                    'id' => $application->id,
                    'status' => $application->status,
                    'created_at' => $application->created_at,
                    'updated_at' => $application->updated_at,
                    'user' => [
                        'id' => $application->user->id,
                        'name' => $application->user->name,
                        'email' => $application->user->email,
                        'phone' => $application->user->phoneNumber,
                        'username' => $application->user->username,
                        'photo' => $application->user->photo ? asset('storage/' . $application->user->photo) : null,
                    ]
                ];
            });
    }

    public function users(Event $event)
    {
        return $event->users;
    }
}
