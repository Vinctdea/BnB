<?php

namespace App\Http\Controllers\Auth\Ura;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderRequest;
use App\Models\Apartment;
use App\Models\Sponsor;
use Braintree\Gateway;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function generate(Request $request, Gateway $gateway){
        // dd($request');
        $token = $gateway->clientToken()->generate();
        $data = [
            'token' => $token
        ];

        return response()->json($data, 200);
    }
    public function makePayment(OrderRequest $request, Gateway $gateway){

        $sponsor = Sponsor::find($request->sponsor);
        $result = $gateway->transaction()->sale([
            'amount' => $sponsor->price,
            'paymentMethodNonce' => $request->token,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        if($result->success){
            switch ($request->sponsor) {
                case 1:
                    $endDate = Carbon::now()->addHours(24);  // 24 ore
                    break;
                case 2:
                    $endDate = Carbon::now()->addHours(72);  // 72 ore
                    break;
                case 3:
                    $endDate = Carbon::now()->addHours(144); // 144 ore
                    break;

            };


            $apartment = Apartment::find($request->apartment);
            $sponsor = Sponsor::find($request->sponsor);
            $apartment->sponsors()->attach($sponsor->id, ['end_at' => $endDate]);

            $data = [
                'success' => true,
                'message' => 'Transazione eseguita con successo'

            ];
            return response()->json($data, 200);

        }else{
            $data = [
                'success' => false,
                'message' => 'Transazione Fallita!!!'
            ];
            return response()->json($data, 401);
        }
    }

}
