<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Vote;
use FedaPay\Customer;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * Étape 1 : Créer une transaction FedaPay
     */
    public function initPayment(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'full_name' => 'required|string',
            'phone_number' => 'required|string',
            'vote_number' => 'required|integer|min:1',
        ]);


        $candidate = Candidate::with('competition')->findOrFail($request->candidate_id);

        if (!$candidate) {
            return response()->json([
                'success' => false,
                'error' => 'candidate non trouvé',
            ], 400);
        }

        $votePrice = $candidate->competition->vote_value;

        $amount = $request->vote_number * $votePrice;

        Fedapay::setApiKey('sk_sandbox_228oRPWbueWIryCFxrujSeUN');
        Fedapay::setEnvironment('sandbox');


        $client = Customer::create(array(
            'firstname' => $request->full_name,
            // "lastname" => "Doe",
            // "email" => "John.doe@gmail.com",
            "phone_number" => [
                "number" =>  $request->phone_number,
                "country" => 'tg' // 'bj' Benin code
            ]
        ));

        $transaction = Transaction::create([
            'description' => "Vote pour {$candidate->firstname} {$candidate->lastname}  ",
            'amount' => $amount,
            'currency' => ['iso' => 'XOF'],
            'callback_url' => route('fedapay.callback'),
            // 'callback_url' => "https://awless-ozell-fibriform.ngrok-free.dev",
            'customer' => [
                'id' => $client->id,
            ],

            'metadata' => [
                'candidate_id' => $candidate->id,
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'vote_number' => $request->vote_number,
                'competition_id' => $candidate->competition->id,
                'vote_value' => $votePrice,
            ],
        ]);

        return response()->json([
            'payment_url' => $transaction->generateToken()->url,
            'reference' => $transaction->reference,
            'amount' => $amount,
            'vote_value' => $votePrice,
        ]);
    }


    // Étape 2 : Callback FedaPay
    //  Créer le vote après paiement réussi


    public function fedapayCallback(Request $request)
    {
        $reference = $request->input('reference');
        $status = $request->input('status');
        $metadata = $request->input('metadata', []);

        if ($status === 'approved') {
            if (!Vote::where('payment_reference', $reference)->exists()) {
                Vote::create([
                    'candidate_id' => $metadata['candidate_id'],
                    'full_name' => $metadata['full_name'],
                    'phone_number' => $metadata['phone_number'],
                    'vote_number' => $metadata['vote_number'],
                    'amount' => $metadata['vote_number'] * $metadata['vote_value'],
                    'payment_reference' => $reference,
                    'payment_status' => 'paid',
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
