<?php

namespace App\Models\Admin;

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
    const AUTH_IDENTIFIER = 'username';

    /**
     * Table name in database
     *
     * @var string
     */
    protected $table = 'users';
}