<?php

namespace App\Http\Controllers\Api;

use App\Models\Candidate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidates = Candidate::all();
        return response()->json($candidates);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|MASCULIN , FEMININ',
            'birth_date' => 'required|date',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'nationality' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('candidates', 'public');
            $data['photo'] = asset('storage/' . $path);
        }

        $candidate = Candidate::create($data);

        return response()->json($candidate, 201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $candidate = Candidate::find($id);
        if (is_null($candidate)) {
            return response()->json(['message' => 'Candidat introuvable'], 404);
        }
        return response()->json($candidate);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
