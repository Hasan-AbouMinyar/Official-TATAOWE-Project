<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
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
        $application->update($data);
        return $application;
    }

    public function destroy(Application $application)
    {
        $application->delete();
        return response()->noContent();
    }
}
