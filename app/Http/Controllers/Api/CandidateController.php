<?php

namespace App\Http\Controllers\Api;

use App\Models\Candidate;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Support\Facades\Storage;
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
        $candidate = Candidate::find ($id);
        if(!$candidate){
            return response()->json([
                'message' => 'Candidat(e) non trouvée'
            ]);
        }
        
        $validated = $request->validate([

        'photo' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'gender' => 'required|in:MASCULIN,FEMININ',
        'birth_date' => 'required|date',
        'height' => 'nullable|float',
        'weight' => 'nullable|float',
        'nationality ' =>'required|string',
        'description' =>'required|text',


        ]);

        if ($request->hasFile('photo')) {
            if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
                Storage::disk('public')->delete($candidate->photo);
            }
            $validated['photo'] = $request->file('photo')->store('candidates', 'public');
        }

        $candidate->update($validated);

        return response()->json([
            'message' => 'Candidat(e) mis à jour avec succès',
            'data' => $candidate
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $candidate = Candidate::find($id);
        if (!$candidate) {
            return response()->json(['message' => 'candidat(e) non trouvé(e)']);
        }

        $candidate->delete();
        return response()->json([
            'message' => 'Candidat(e) supprimé(e) avec succès'
        ]);

    }
}
