<?php

namespace App\Http\Controllers\Api\Voter;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index()
    {
        try {

            $competitions = Competition::all();

            return response()->json([
                'success' => true,
                'message' => 'Liste des compétitions récupérée avec succès',
                'data' => $competitions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des compétitions',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show(string $id)
    {
        $competition = Competition::find($id)->with("candidates")->where('id', $id)->get();


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
}
