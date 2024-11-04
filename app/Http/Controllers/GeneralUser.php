<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterApartmentRequest;
use App\Models\Apartment;
use App\Models\Message;
use App\Models\Service;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GeneralUser extends Controller
{
    public function allApartment(){

        $allApartment = Apartment::with('services', 'messages', 'sponsors')->get();


        return response()->json($allApartment);

    }

    public function services(){
        return response()->json(Service::all());
    }

    public function messages()
    {
        return response()->json(Message::all());
    }

    public function filtredApartment(FilterApartmentRequest $request)
    {

        $apartments = $request->filtredApartment();

        return response()->json($apartments);
    }

    public function view(Request $request){

        $myFakeIp = env('FAKE_IP');
        $ipUser = $request->ip();
        $apartment_id = $request->apartment;


    if ($myFakeIp === $ipUser) {
        return response()->json(false, 200);
    } else {

        $apartment = Apartment::findOrFail($apartment_id);


        $apartment->views()->create([
            'IP_address' => Hash::make($ipUser),
            'date_view' => now()
        ]);

        return response()->json(true, 200);
    }



    }
    public function apartment(Request $request) {
        $id = $request->id;
        $apartment = Apartment::with('services', 'messages', 'sponsors')->find($id);

        return response()->json($apartment);
    }





}
