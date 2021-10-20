<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Locator implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('|^[A-Ra-r]{2}[0-9]{2}[A-Xa-x]{2}$|', $value);
    }
    public function message()
    {
        return __('Pole :attribute neobsahuje platný QTH lokátor.');
    }
}
