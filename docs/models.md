# Models

- **[Introduction](/models?id=introduction)**
- **[Defining model](/models?id=defining-model)**
    - [Table name](/models?id=table-name)
    - [Primary key](/models?id=primary-key)


## Introduction

Models are simple ActiveRecord implementation for working with your database.
Each database table has a corresponding "Model" which is used to interact with that table.
Models allow you to query for data in your tables, as well as insert new records into the table.

The simplest way to return records from database is an array but the funniest is an object to manipulate it easily.


## Defining model

Models are stored in the `app/Models` directory and must extend `Rseon\Mallow\Model` class.

```php
<?php

namespace App\Models;

use use Rseon\Mallow\Model;

class Flight extends Model
{
    //
}
```

### Table name

By convention, the "snake case" name of the class will be used as the table name unless another name is explicitly
specified. So, in this case Mallow will assume the `Flight` model stores records in the `flight` table.
You may specify a custom table by defining a `table` property on your model :

```php
<?php

namespace App\Models;

use use Rseon\Mallow\Model;

class Flight extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'my_flights';
}
```

### Primary key

Mallow will also assume that each table has a primary key column named `id`.
You may define a protected `$primary` property to override this convention:

```php
<?php

namespace App\Models;

use use Rseon\Mallow\Model;

class Flight extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primary = 'flight_id';
}
```


## Retrieving models




## Useful methods

Some methods inherits from `Rseon\Mallow\Model` :

```php
```
