<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Apartment;
use Illuminate\Support\Facades\Validator;
use App\Mail\NewContact;
use Illuminate\Support\Facades\Mail;

class NewMessageController extends Controller
{
    public function store(Request $request, $apartment_id)
    {
        $apartment = Apartment::find($apartment_id);

        // dd($apartment->title);
        $data = $request->all();

        $success = true;

        $validator = Validator::make($data,
            [
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required',
            ],
            [
                'name.required' => 'Il campo nome è obbligatorio',
                'name.string' => 'Il campo nome deve essere una stringa',
                'name.max' => 'Il campo nome deve avere al massimo :max caratteri',
                'surname.required' => 'Il campo cognome è obbligatorio',
                'surname.string' => 'Il campo cognome deve essere una stringa',
                'surname.max' => 'Il campo cognome deve avere al massimo :max caratteri',
                'email.required' => 'Il campo email è obbligatorio',
                'email.email' => 'Il campo email deve essere un indirizzo email valido',
                'email.max' => 'Il campo email deve avere al massimo :max caratteri',
                'message.required' => 'Il campo messaggio è obbligatorio',
            ]
        );

        // dd($validator->errors());
        if($validator->fails()){
            $success = false;
            $errors = $validator->errors();

            return response()->json(compact('success', 'errors'));

        } else {

            $new_message = new Message();
            $new_message->fill($data);
            $new_message->apartment_id = $apartment->id;
            $new_message->apartment_title = $apartment->title;
            $new_message->save();

            // invio la mail
            Mail::to($new_message->email)->send(new NewContact($new_message));

            return response()->json(compact('success'));
        }

    }
}
