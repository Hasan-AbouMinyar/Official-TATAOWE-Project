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

Route::post('/login', function(Request $request) {
    $credentials = $request->only('email', 'password');

    if(!Auth::attempt($credentials)) {
        return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'تم تسجيل الدخول بنجاح',
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
        'message' => 'تم إنشاء الحساب بنجاح',
        'user' => $user,
        'token' => $token
    ], 201);
});

Route::middleware('auth:sanctum')->post('/logout', function(Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'تم تسجيل الخروج بنجاح'
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
    return response()->json($request->user());
});