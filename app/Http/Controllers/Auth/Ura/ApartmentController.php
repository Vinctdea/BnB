<?php

namespace App\Http\Controllers\Auth\Ura;

use App\Function\Helper;
use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $apartment = Apartment::with(['messages', 'sponsors', 'views'])->orderBy('id', 'desc')->where('user_id', $user->id)->get();
        return $apartment;
    }

    #tommy riga 26
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApartmentRequest $request)
    {

        // $data = $request->all();
        $data = json_decode($request->input('apartment'), true);


        $data['slug'] = Helper::generateSlug($data['title'], Apartment::class);
        $data['user_id'] =  auth()->user()->id;



        // Gestione dell'immagine caricata
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Ottieni l'immagine
            $image = $request->file('image');



            // Crea un nome unico per l'immagine
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Sposta l'immagine nella cartella public/images
            $image->move(public_path('storage/uploads'), $imageName);

            // Salva il percorso dell'immagine nella variabile $data
            $data['image'] = "storage/uploads/$imageName"; // Percorso relativo da salvare nel database
        } else {
            $data['image'] = 'storage/uploads/default/1.jpg';
        }

        $apartment = Apartment::create($data);
        $apartment->save();
        $apartment->services()->attach($data['services']);




        return response()->json($apartment, 200);
    }







    #riga61
    /**
     * Display the specified resource.
     */
    // public function show(Apartment $apartment)
    public function show($id)
    {
        $apartment = Apartment::find($id);
        return response()->json($apartment);
    }

    #vincenzo riga 78
    /**
     * Show the form for editing the specified resource.
     */

    #vincenzo 92
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Apartment $apartment)
    {

        $data = $request->all();
        dd($data['image']);
        $oldApartament = Apartment::find($data['id']);
        $data['user_id'] =  auth()->user()->id;

        $validator = Validator::make($data,
        [
            'title'=>'required|string|min:5|max:500',
            'number_rooms'=>'required|integer|min:1|max:7',
            'number_beds'=>'required|integer|min:1|max:5',
            'number_bathrooms'=>'required|integer|min:1|max:3',
            'square_meters'=>'required|integer|min:1|max:300',
            'address'=>'required|string|min:5|max:500'
        ],
        [
           'title.required' => 'Il titolo è obbligatorio.',
            'title.string' => 'Il titolo deve essere una stringa.',
            'title.min' => 'Il titolo deve contenere almeno :min caratteri.',
            'title.max' => 'Il titolo non può superare i :max caratteri.',

            'number_rooms.required' => 'Il numero di stanze è obbligatorio.',
            'number_rooms.integer' => 'Il numero di stanze deve essere un numero intero.',
            'number_rooms.min' => 'Il numero di stanze deve essere almeno :min.',
            'number_rooms.max' => 'Il numero di stanze non può superare :max.',

            'number_beds.required' => 'Il numero di letti è obbligatorio.',
            'number_beds.integer' => 'Il numero di letti deve essere un numero intero.',
            'number_beds.min' => 'Il numero di letti deve essere almeno :min.',
            'number_beds.max' => 'Il numero di letti non può superare :max.',

            'number_bathrooms.required' => 'Il numero di bagni è obbligatorio.',
            'number_bathrooms.integer' => 'Il numero di bagni deve essere un numero intero.',
            'number_bathrooms.min' => 'Il numero di bagni deve essere almeno :min.',
            'number_bathrooms.max' => 'Il numero di bagni non può superare :max.',

            'square_meters.required' => 'La metratura è obbligatoria.',
            'square_meters.integer' => 'La metratura deve essere un numero intero.',
            'square_meters.min' => 'La metratura deve essere almeno :min metri quadrati.',
            'square_meters.max' => 'La metratura non può superare i :max metri quadrati.',

            'address.required' => 'L\'indirizzo è obbligatorio.',
            'address.string' => 'L\'indirizzo deve essere una stringa.',
            'address.min' => 'L\'indirizzo deve contenere almeno :min caratteri.',
            'address.max' => 'L\'indirizzo non può superare i :max caratteri.',
        ]);

        if($validator->fails()){
            $errors = $validator->errors();
            return response()->json('errors');
        } else {

            if ($data['title'] != $apartment->title) {

                $data['slug'] = Helper::generateSlug($data['title'], Apartment::class);
            }

            $oldApartament->update($data);

            return $apartment;

        }



        // if ($oldApartament->image === $data['image'] && $oldApartament->original_name !== $data['original_name'] && ) {
        //     $image = Storage::put('uploads', $data['image']);
        //     $original_name = $request->file('image')->getClientOriginalName();
        //     $data['image'] = $image;
        //     $data['original_name'] = $original_name;
        // }else{
        //     $data['image'] = 'vuoto';
        //     $data['original_name'] = 'vuoto';
        // }



    }
















    #matte da riga 125
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($dashboard)
    {

        $apartment = Apartment::find($dashboard);
        //verifica se l'appartamento esiste e lo elimina
        if ($apartment) {
            $apartment->delete();  //elimina l'appartamento dal database
            return response()->json(['message' => 'Appartamento eliminato con successo'], 200);
        }

        return response()->json(['message' => 'Appartamento non trovato'], 404);
    }
}
