<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\RoleEnums;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            return User::all();
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement, Réessayez !',
                'error' => $ex->getMessage()
            ], 500);
        }
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
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'role' => RoleEnums::ADMIN->value,
        ];

        try {

            $user = User::create($data);

            return response()->json([
                'success' => true,
                'message' => "Utilisateur créé avec succes",
                'data' => $user,
            ], 201);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement, Réessayez !',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {

            return User::find($id);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement, Réessayez !',
                'error' => $ex->getMessage()
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
        $user = User::find($id);

        if (!$user) {
            return [
                "message" => "Utilisateur non trouvé !",
            ];
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? $request->password : $user->password,
            'phone' => $request->phone,
        ];

        try {


            $user->update($data);
            return $request->all();

            return response()->json([
                'success' => true,
                'message' => "Utilisateur mise à jour avec succes",
                'data' => $user,
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement, Réessayez !',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $user = User::find($id);

            if (!$user) {
                return [
                    "message" => "Utilisateur non trouvé !",
                ];
            }

            if ($id != Auth::id() && $id != 1) {

                $user->delete();

                return [
                    "message" => "Utilisateur supprimé avec succès !",
                ];
            }

            return [
                "message"  => "Impossible de s'auto-supprimer"
            ];
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement, Réessayez !',
                'error' => $ex->getMessage()
            ], 500);
        }
    }


    public function trash()
    {
        try {

            $trashed = User::onlyTrashed()->get();

            return response()->json([
                'success' => true,
                'message' => "Corbeille",
                'data' => $trashed,
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement, Réessayez !',
                'error' => $ex->getMessage()
            ], 500);
        }
    }
}
