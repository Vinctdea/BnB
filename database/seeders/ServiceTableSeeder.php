<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $services=['wi-fi','piscina','sauna','navetta','massaggio'
        ];
        foreach($services as $service){
            $new_service = new Service();
            $new_service->name = $service;
            $new_service->save();
        }
    }
}
