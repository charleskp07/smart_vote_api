<?php

namespace App\Http\Controllers\Api\Admin;

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
        try {

            $candidates = Candidate::with('competition')->get();

            return response()->json([
                'status' => true,
                'message' => 'Liste des candidats récupérée avec succès',
                'data' => $candidates
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors du chargement des candidats',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $file = $request->file('photo');

        if ($file)
            $path = $file->store('candidates/photo', 'public');

        $data = [
            'competition_id' => $request->competition_id,
            'photo' => $file ? $path : null,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'height' => $request->height,
            'weight' => $request->weight,
            'nationality' => $request->nationality,
            'accumulated_vote' => $request->accumulated_vote ? $request->accumulated_vote : 0,
            'description' => $request->description,
        ];


        try {

            $candidate = Candidate::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Candidat créée avec succès',
                'data' => $candidate
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la création',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $candidate = Candidate::find($id);


        if (!$candidate) {
            return response()->json([
                'status' => false,
                'message' => 'Candidat non trouvée'
            ], 404);
        }

        try {

            return response()->json([
                'status' => true,
                'message' => 'Candidat trouvée avec succès',
                'data' => $candidate
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la récupération',
                'error' => $e->getMessage()
            ], 500);
        }
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
        $candidate = Candidate::find($id);

        if (!$candidate) {
            return response()->json([
                'message' => 'Candidat(e) non trouvée'
            ]);
        }

        $data = [
            'competition_id' => $request->competition_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'height' => $request->height,
            'weight' => $request->weight,
            'nationality' => $request->nationality,
            'accumulated_vote' => $request->accumulated_vote ? $request->accumulated_vote : $candidate->accumulated_vote,
            'description' => $request->description,
        ];

        if (isset($path))
            $data['photo'] = $path;


        try {

            $candidate->update($data);

            return response()->json([
                'message' => 'Candidat mise à jour avec succès !',
                'data'    => $candidate->load('competition')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => 'Erreur lors de la mise à jour de la Candidat.',
                'details' => $th->getMessage(),
            ], 500);
        }
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

        try {

            $candidate->delete();

            return response()->json([
                'message' => 'candidat(e) supprimée avec succès !'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erreur lors de la suppression de la candidat(e).',
                'details' => $th->getMessage(),
            ], 500);
        }
    }
}
