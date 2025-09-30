<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Notifications\NewApplicationReceived;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return Application::with(['user','event'])->paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'status' => 'sometimes|in:pending,accepted,rejected',
        ]);
        $application = Application::create($data);
        
        // Send notification to event owner (organization owner)
        if ($application->event && $application->event->organization && $application->event->organization->user) {
            $application->event->organization->user->notify(new NewApplicationReceived($application));
        }
        
        return response()->json($application->load(['user','event']), 201);
    }

    public function show(Application $application)
    {
        return $application->load(['user','event']);
    }

    public function update(Request $request, Application $application)
    {
        $data = $request->validate([
            'status' => 'sometimes|in:pending,accepted,rejected',
        ]);
        
        $oldStatus = $application->status;
        $application->update($data);
        
        // Send notification to applicant if status changed
        if (isset($data['status']) && $oldStatus !== $data['status']) {
            $application->user->notify(new ApplicationStatusChanged($application, $data['status']));
            
            // Send notification to organization owner as confirmation
            if ($application->event && $application->event->organization && $application->event->organization->user) {
                $application->event->organization->user->notify(new ApplicationStatusChanged($application, $data['status'], true));
            }
        }
        
        return $application;
    }

    public function destroy(Application $application)
    {
        $application->delete();
        return response()->noContent();
    }
}
