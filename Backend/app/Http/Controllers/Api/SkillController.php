<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skills;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        return Skills::paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'skill_name' => 'required|string|max:255',
        ]);
        $skill = Skills::create($data);
        return response()->json($skill, 201);
    }

    public function show(Skills $skill)
    {
        return $skill;
    }

    public function update(Request $request, Skills $skill)
    {
        $data = $request->validate([
            'skill_name' => 'sometimes|string|max:255',
        ]);
        $skill->update($data);
        return $skill;
    }

    public function destroy(Skills $skill)
    {
        $skill->delete();
        return response()->noContent();
    }
}
