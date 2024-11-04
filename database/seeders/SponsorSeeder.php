<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name =['bronzo', 'argento', 'oro'];
        $price = [2.99, 5.99, 9.99];
        $duration = [24, 72, 144];

        for ($i=0; $i < count($name); $i++) {

            $new_sponsor = new Sponsor();
            $new_sponsor->category = $name[$i];
            $new_sponsor->price = $price[$i];
            $new_sponsor->duration = $duration[$i];
            $new_sponsor->save();

        }
    }
}
