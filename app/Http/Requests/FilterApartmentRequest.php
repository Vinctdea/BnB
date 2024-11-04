<?php

namespace App\Http\Requests;

use App\Models\Apartment;
use Illuminate\Foundation\Http\FormRequest;

class FilterApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'query.lat' => 'required|numeric',
            'query.lon' => 'required|numeric',
            'query.distance' => 'required|numeric|min:1',

        ];
    }



    public function filtredApartment()
    {

        // Ottieni i valori dalla query usando la dot notation
        $lat = $this->input('query.lat');
        $lon = $this->input('query.lon');
        $distanceKm = $this->input('query.distance');

        // Costante per la conversione della latitudine
        $kmPerGradoLat = 111;

        // Calcolo della variazione in gradi di latitudine e longitudine
        $deltaLatitudine = $distanceKm / $kmPerGradoLat;
        $deltaLongitudine = $distanceKm / ($kmPerGradoLat * cos(deg2rad($lat)));

        // Limiti massimi e minimi per latitudine e longitudine
        $latitudineMassima = $lat + $deltaLatitudine;
        $latitudineMinima = $lat - $deltaLatitudine;
        $longitudineMassima = $lon + $deltaLongitudine;
        $longitudineMinima = $lon - $deltaLongitudine;

        // Query di base per la zona
        $query = Apartment::with('sponsors')
                      ->whereBetween('lat', [$latitudineMinima, $latitudineMassima])
                      ->whereBetween('lon', [$longitudineMinima, $longitudineMassima]);

        // Applicazione dei filtri opzionali, verificando se sono presenti nella query
        if ($this->filled('query.number_rooms')) {
            $query->where('number_rooms', '>=', $this->input('query.number_rooms'));
        }

        if ($this->filled('query.number_beds')) {
            $query->where('number_beds', '>=', $this->input('query.number_beds'));
        }

        if ($this->filled('query.square_meters')) {
            $query->where('square_meters', '>=', $this->input('query.square_meters'));
        }
        if ($this->filled('query.services')) {
            // Recupera l'array dei servizi dalla query e forza a essere un array
            $serviceArray = $this->input('query.services');

            // Se services è una stringa singola, la trasformiamo in un array
            if (is_string($serviceArray)) {
                $serviceArray = explode(',', $serviceArray);  // Converte '1,2,3' in ['1', '2', '3']
            }

            // Filtra gli appartamenti che hanno tutti i servizi specificati
            foreach ($serviceArray as $serviceId) {
                $query->whereHas('services', function($q) use ($serviceId) {
                    $q->where('service_id', $serviceId);
                });
            }
        }




        // Restituisci i risultati della query finale
        return $query->get();
    }
}
