<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;
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
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors du chargement des compétitions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try {
            $validator = Validator::make($request->all(),[
                'user_id' => 'required|exist:users,id',
                 'name' => 'required|string|max:225',
                  'description' => 'nullable|string',
                   'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:satrt_date',
                     'vote_value' => 'integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $competition = Competition::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Compétition créée avec succès',
                'data' => $competition,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la création',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $competition = Competition::with('user')->find($id);

            if (!$competition) {
                return response()->json([
                    'status' => false,
                    'message' => 'Compétition non trouvée'
                ], 404);
            }

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
        try {
            $competition = Competition::findOrFail($id);

            $validatedData = $request->validate([
                'user_id'     => 'required|exists:users,id',
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date'  => 'required|date',
                'end_date'    => 'required|date|after_or_equal:start_date',
                'vote_value'  => 'required|integer|min:0',
            ]);

            $competition->update($validatedData);

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
        try {

            $competition = Competition::findOrFail($id);
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
