<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'phoneNumber' => 'required|string|max:20|unique:users,phoneNumber',
            'email' => 'required|email|unique:users,email',
            'photo' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
            'phoneNumber' => 'sometimes|string|max:20|unique:users,phoneNumber,' . $user->id,
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|string',
            'password' => 'sometimes|string|min:6',
        ]);
        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user->update($data);
        return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }

    // Relationships
    public function skills(User $user)
    {
        return $user->skills;
    }

    public function organizations(User $user)
    {
        $organizations = $user->organizations;
        
        // Add full URL for logo
        $organizations->transform(function($org) {
            if ($org->logo) {
                $org->logo_url = asset('storage/' . $org->logo);
            }
            return $org;
        });
        
        return $organizations;
    }

    public function applications(User $user)
    {
        return $user->applications()
            ->with(['event.organization'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function appliedEvents(User $user)
    {
        return $user->appliedEvents;
    }
}
