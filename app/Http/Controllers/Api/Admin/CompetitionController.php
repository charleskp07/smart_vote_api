<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $competitions = Competition::with('user')->get();

            return response()->json([
                'status' => true,
                'message' => 'Liste des compétitions récupérée avec succès',
                'data' => $competitions
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors du chargement des compétitions',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = [
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'vote_value' => $request->vote_value,
        ];


        try {

            $competition = Competition::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Compétition créée avec succès',
                'data' => $competition
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
        $competition = Competition::with('user')->find($id);


        if (!$competition) {
            return response()->json([
                'status' => false,
                'message' => 'Compétition non trouvée'
            ], 404);
        }

        try {

            return response()->json([
                'status' => true,
                'message' => 'Compétition trouvée avec succès',
                'data' => $competition
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $competition = Competition::findOrFail($id);

        $data = [
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'vote_value' => $request->vote_value,
        ];

        try {

            $competition->update($data);

            return response()->json([
                'message' => 'Compétition mise à jour avec succès !',
                'data'    => $competition->load('user')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => 'Erreur lors de la mise à jour de la compétition.',
                'details' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $competition = Competition::findOrFail($id);

        try {

            $competition->delete();

            return response()->json([
                'message' => 'Compétition supprimée avec succès !'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erreur lors de la suppression de la compétition.',
                'details' => $th->getMessage(),
            ], 500);
        }
    }
}
