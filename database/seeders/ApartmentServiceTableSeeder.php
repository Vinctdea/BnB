<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $apartmentLength = count(Apartment::all());
        for ($i = 0; $i < $apartmentLength; $i++) {

            $apartment = Apartment::inRandomOrder()->first();

            $service_id = Service::inRandomOrder()->first()->id;

            $apartment->services()->syncWithoutDetaching([$service_id]);
        }
    }
}
