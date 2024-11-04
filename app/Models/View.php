<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id',
        'IP_address',
        'date_view'
    ];

    public function apartment(){
        return $this->belongsTo(Apartment::class);
    }
}
