<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        return Organization::paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:organizations,email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'field' => 'nullable|string|max:255',
            'photo' => 'nullable|string',
        ]);
        $org = Organization::create($data);
        return response()->json($org, 201);
    }

    public function show(Organization $organization)
    {
        return $organization;
    }

    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:organizations,email,' . $organization->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'field' => 'nullable|string|max:255',
            'photo' => 'nullable|string',
        ]);
        $organization->update($data);
        return $organization;
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return response()->noContent();
    }

    public function events(Organization $organization)
    {
        return $organization->events;
    }
}
