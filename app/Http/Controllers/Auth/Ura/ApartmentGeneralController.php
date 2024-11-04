<?php

namespace App\Http\Controllers\Auth\Ura;

use App\Function\Helper;
use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentGeneralController extends Controller
{
    public function update(Request $request){
        $data = json_decode($request->input('apartment'), true);
        $id = $request->input('id');
        $image = $request->file('image');


        $data['slug'] = Helper::generateSlug($data['title'], Apartment::class);
        $data['user_id'] =  auth()->user()->id;


        if($image){
            // elimina la vecchia
            // Crea un nome unico per l'immagine
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Sposta l'immagine nella cartella public/images
            $image->move(public_path('storage/uploads'), $imageName);

            // Salva il percorso dell'immagine nella variabile $data
            $data['image'] = "storage/uploads/$imageName"; // Percorso relativo da salvare nel database
        }

        $apartment = Apartment::find($id);
        $apartment->update($data);
        $apartment->services()->sync($data['services']);

        return response()->json('success', 200);



    }
}
