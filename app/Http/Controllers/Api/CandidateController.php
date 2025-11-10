<?php

namespace App\Http\Controllers\Api;

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
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $candidate =Candidate::find($id);
        if (!$candidate) {
            return response()->json(['message' => 'candidat(e) non trouvé(e)']);
        }

        $candidate->delete();
        return response()->json([
            'message' => 'Candidat(e) supprimé(e) avec succès'
        ]);

    }
}
