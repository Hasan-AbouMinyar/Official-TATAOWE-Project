<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'field' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('organizations/logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Use address as location if location not provided
        if (!isset($data['location']) && isset($data['address'])) {
            $data['location'] = $data['address'];
        }

        $org = Organization::create($data);
        
        // Return full URL for logo
        if ($org->logo) {
            $org->logo = asset('storage/' . $org->logo);
        }
        
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
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'field' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($organization->logo && Storage::disk('public')->exists($organization->logo)) {
                Storage::disk('public')->delete($organization->logo);
            }
            
            $logoPath = $request->file('logo')->store('organizations/logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Use address as location if location not provided
        if (!isset($data['location']) && isset($data['address'])) {
            $data['location'] = $data['address'];
        }

        $organization->update($data);
        
        // Return full URL for logo
        if ($organization->logo) {
            $organization->logo = asset('storage/' . $organization->logo);
        }
        
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
