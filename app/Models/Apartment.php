<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apartment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function services(){

        return $this->belongsToMany(Service::class);
    }

    public function messages(){

        return $this->hasMany(Message::class);
    }

    public function sponsors(){
        return $this->belongsToMany(Sponsor::class, 'apartment_sponsor')
                    ->withPivot('end_at');
    }

    public function views(){
        return $this->hasMany(View::class);
    }
    public function user()  {
        return $this->belongsTo(User::class);
    }

    protected $fillable =
    [
        'title',
        'user_id',
        'slug',
        'image',
        'original_name',
        'number_rooms',
        'number_beds',
        'number_bathrooms',
        'square_meters',
        'address',
        'lat',
        'lon',
        'is_visible'
    ];
}
