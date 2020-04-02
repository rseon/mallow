<?php

namespace App\Models;

use App\Models\Formatters\PriceFormatter;
use App\Models\Validators\EmailValidator;
use Rseon\Mallow\Model;
use Rseon\Mallow\Models\Traits\Cache;
use Rseon\Mallow\Models\Traits\Timestamps;
use Rseon\Mallow\Models\Traits\SoftDeletes;
use Rseon\Mallow\Models\Traits\Authenticable;

class User extends Model
{
    use SoftDeletes;
    use Timestamps;
    use Cache;
    use Authenticable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Required fields to validate the model
     *
     * @var array
     */
    protected $required = [
        'name', 'email',
    ];

    /**
     * These fields will not be shown in the model
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $cast = [

    ];

    /**
     * The attributes that should be validated.
     *
     * @var array
     */
    protected $validate = [
        'email' => EmailValidator::class
    ];
}