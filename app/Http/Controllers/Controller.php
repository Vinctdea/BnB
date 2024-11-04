<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

//"title" => "dfasdf"ok
        //   "address" => "fdasd"ok
        //   "lat" => null ok
        //   "lon" => null ok
        //   "number_rooms" => 1 ok
        //   "number_beds" => 1 ok
        //   "number_bathrooms" => 1 ok
        //   "image" => null
        //   "square_meters" => 1 ok
