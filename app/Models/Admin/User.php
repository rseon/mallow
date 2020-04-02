<?php

namespace App\Models\Admin;

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

    const AUTH_SESSION_NAME = 'admin';
    const AUTH_IDENTIFIER = 'email';

    /**
     * Table name in database
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
        'password',
        'remember_token',
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

    /**
     * Validation messages
     *
     * @var array
     */
    protected $messages = [
        'name.required' => 'Merci de renseigner le nom',
        'email.required' => 'Merci de renseigner l\'email',
        'email.validate' => 'L\'email n\'est pas au bon format',
    ];
}