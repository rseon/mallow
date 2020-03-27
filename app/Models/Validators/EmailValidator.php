<?php

namespace App\Models\Validators;

use Rseon\Mallow\Models\Validator;

class EmailValidator implements Validator
{
    /**
     * Validate the value
     *
     * @param $value
     * @return mixed
     */
    public static function validate($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}