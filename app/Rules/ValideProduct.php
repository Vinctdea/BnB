<?php

namespace App\Rules;

use App\Models\Sponsor;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValideProduct implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        ##passa il valore id, se esite passa il valore true
        if(!Sponsor::find($value)){
           // Se non esiste, chiama la closure $fail con un messaggio di errore
           $fail('Il valore selezionato per ' . $attribute . ' non è valido.');
        }
    }


}
