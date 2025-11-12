<?php

namespace App\Http\Controllers\Api\Voter;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index()
    {
        try {

            $candidates = Candidate::all();

            return response()->json([
                'success' => true,
                'message' => 'Liste des candidats récupérée avec succès',
                'data' => $candidates
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des candidats',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show(string $id)
    {
        $candidate = Candidate::find($id);


        if (!$candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Candidat non trouvée'
            ], 404);
        }

        try {

            return response()->json([
                'success' => true,
                'message' => 'Candidat trouvée avec succès',
                'data' => $candidate
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
