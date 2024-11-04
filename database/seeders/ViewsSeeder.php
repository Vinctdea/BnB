<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\View;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ViewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $apartments = Apartment::all(); // Mantieni la collezione completa con un nome chiaro
    $totalApartment = $apartments->count();
    $start = strtotime("2023-01-01 00:00:00");
    $end = strtotime("2024-10-28 17:45:33");

    for ($i = 0; $i < $totalApartment; $i++) { // Itera fino all'ultimo appartamento
        for ($j = 0; $j < 40; $j++) { // Crea 40 viste per ogni appartamento
            $new_view = new View;
            $currentApartment = $apartments[$i]; // Usa una variabile temporanea per ogni appartamento

            $new_view->apartment_id = $currentApartment->id;

            // Genera una data casuale nell'intervallo specificato
            $randomTimestamp = rand($start, $end);
            $new_view->date_view = date("Y-m-d H:i:s", $randomTimestamp);

            $new_view->save();
        }
    }
}

}
