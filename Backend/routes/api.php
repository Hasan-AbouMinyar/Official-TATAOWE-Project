<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ApplicationController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::apiResource('users', UserController::class);
Route::apiResource('skills', SkillController::class);
Route::apiResource('organizations', OrganizationController::class);
Route::apiResource('events', EventController::class);
Route::apiResource('applications', ApplicationController::class);

// Extra relationship endpoints
Route::get('users/{user}/skills', [UserController::class, 'skills']);
Route::get('users/{user}/organizations', [UserController::class, 'organizations']);
Route::get('users/{user}/applications', [UserController::class, 'applications']);
Route::get('users/{user}/events/applied', [UserController::class, 'appliedEvents']);
Route::get('organizations/{organization}/events', [OrganizationController::class, 'events']);
Route::get('events/{event}/applications', [EventController::class, 'applications']);
Route::get('events/{event}/users', [EventController::class, 'users']);

// Update application status (for organization owners)
Route::middleware('auth:sanctum')->patch('applications/{application}/status', function(Request $request, \App\Models\Application $application) {
    $validated = $request->validate([
        'status' => 'required|in:pending,accepted,rejected',
    ]);

    // Check if the authenticated user owns the organization that owns this event
    $user = $request->user();
    if (!$user || !$application->event || !$application->event->organization || $application->event->organization->user_id !== $user->id) {
        return response()->json(['message' => 'Unauthorized to update application status'], 403);
    }

    $application->update(['status' => $validated['status']]);

    return response()->json([
        'message' => 'Application status updated successfully',
        'application' => $application->load('user', 'event')
    ]);
});

// Event reviews endpoints
Route::get('events/{event}/reviews', function(\App\Models\Event $event) {
    return $event->reviews()->with('user:id,name,photo')->latest()->get();
});

Route::middleware('auth:sanctum')->post('events/{event}/reviews', function(Request $request, \App\Models\Event $event) {
    $validated = $request->validate([
        'comment' => 'nullable|string|max:1000',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    // Check if user already reviewed this event
    $existingReview = $event->reviews()->where('user_id', $request->user()->id)->first();
    
    if ($existingReview) {
        $existingReview->update($validated);
        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $existingReview->load('user:id,name,photo')
        ]);
    }

    $review = $event->reviews()->create([
        'user_id' => $request->user()->id,
        'comment' => $validated['comment'],
        'rating' => $validated['rating'],
    ]);

    return response()->json([
        'message' => 'Review added successfully',
        'review' => $review->load('user:id,name,photo')
    ], 201);
});

// Delete review endpoint - only owner can delete their own review
Route::middleware('auth:sanctum')->delete('reviews/{review}', function(Request $request, \App\Models\EventReview $review) {
    // Check if the authenticated user is the owner of this review
    if ($review->user_id !== $request->user()->id) {
        return response()->json([
            'message' => 'Unauthorized. You can only delete your own reviews.'
        ], 403);
    }

    $review->delete();

    return response()->json([
        'message' => 'Review deleted successfully'
    ]);
});

// Apply to event endpoint
Route::middleware('auth:sanctum')->post('events/{event}/apply', function(Request $request, \App\Models\Event $event) {
    $user = $request->user();
    
    // Check if registration period ended
    if (now()->isAfter($event->end_time)) {
        return response()->json([
            'message' => 'Registration period has ended'
        ], 422);
    }

    // Check if user already applied
    $existingApplication = \App\Models\Application::where('user_id', $user->id)
        ->where('event_id', $event->id)
        ->first();
    
    if ($existingApplication) {
        return response()->json([
            'message' => 'You have already applied to this event',
            'application' => $existingApplication
        ], 422);
    }

    $application = \App\Models\Application::create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'status' => 'pending',
    ]);

    return response()->json([
        'message' => 'Application submitted successfully',
        'application' => $application
    ], 201);
});

// Check if user applied to event
Route::middleware('auth:sanctum')->get('events/{event}/check-application', function(Request $request, \App\Models\Event $event) {
    $application = \App\Models\Application::where('user_id', $request->user()->id)
        ->where('event_id', $event->id)
        ->first();
    
    return response()->json([
        'applied' => !!$application,
        'application' => $application
    ]);
});

Route::post('/login', function(Request $request) {
    $credentials = $request->only('email', 'password');

    if(!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'user' => $user,
        'token' => $token
    ]);
});

Route::post('/register', function(Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'phoneNumber' => 'required|string|max:20|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'username' => $validated['username'],
        'phoneNumber' => $validated['phoneNumber'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Account created successfully',
        'user' => $user,
        'token' => $token
    ], 201);
});

Route::middleware('auth:sanctum')->post('/logout', function(Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logged out successfully'
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
    $user = $request->user();
    
    // Load counts for statistics
    $user->load('organizations', 'applications', 'appliedEvents');
    
    // Add counts to the response
    $userData = $user->toArray();
    $userData['stats'] = [
        'organizations_count' => $user->organizations()->count(),
        'applications_count' => $user->applications()->count(),
        'events_count' => $user->appliedEvents()->count(),
    ];
    
    return response()->json($userData);
});

Route::middleware('auth:sanctum')->put('/user/profile', function(Request $request) {
    $user = $request->user();
    
    $validated = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
        'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
        'phoneNumber' => 'sometimes|required|string|max:20|unique:users,phoneNumber,' . $user->id,
    ]);

    $user->update($validated);
    
    // Refresh user and load statistics
    $user = $user->fresh();
    $user->load('organizations', 'applications', 'appliedEvents');
    
    $userData = $user->toArray();
    $userData['stats'] = [
        'organizations_count' => $user->organizations()->count(),
        'applications_count' => $user->applications()->count(),
        'events_count' => $user->appliedEvents()->count(),
    ];

    return response()->json([
        'message' => 'Profile updated successfully',
        'user' => $userData
    ]);
});