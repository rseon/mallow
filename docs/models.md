# Models

> TODO : methods, how to...

- **[Introduction](/models?id=introduction)**
- **[Defining model](/models?id=defining-model)**
    - [Table name](/models?id=table-name)
    - [Primary key](/models?id=primary-key)
- **[Retrieving models](/models?id=retrieving-models)**
- **[Useful methods](/models?id=useful-methods)**
    - [Static methods](/models?id=static-methods)
    - [Instance methods](/models?id=instance-methods)


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

By convention, the lowercase name of the class will be used as the table name unless another name is explicitly
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

You can retrieve a specific model from three ways :
- Instanciation : `new User(1)`
- Method `find` : `User::find(1)`
- Method `findOrFail` : `User::findOrFail(1)` (throws an exception if not found)


## Useful methods

Some methods inherits from `Rseon\Mallow\Model`.
These examples are for a `App\Models\User`.


### Static methods

All these methods returns the model as object (or array of objects).

```php
// Returns an array of users
User::all(array $conditions = [], array $sort = [], int|array $limit = null, string|array $only_fields = '*'): array

// Find a model by its primary or by condition
User::find(int|array $primary): User

// Same as find but throw an eception if model not found
User::findOrFail(int|array $primary): User

// Transform an array of data on object (or array of objects)
User::model(array $data = null): User|array
```

### Instance methods

Theses methods returns only arrays.
You can transform them as object using `User::model($data)`.

Moreover, some methods are part of the `Rseon\Mallow\Database`, the database abstraction layer used by the models.

```php
$User = new User;

// Delete
$User->delete(array $conditions): int

// Returns all rows of the query
$User->fetchAll(string $sql, array $params = []): array

// Returns one rows of the query
$User->fetchRow(string $sql, array $params = []): array

// Get if model was found
$User->found(): bool

// Returns all users
$User->getAll(array $conditions = [], array $sort = [], int|array $limit = null, string|array $only_fields = '*'): array

// Get attributes of the model
$User->getAttributes(): array

// Returns one user
$User->getRow(array $conditions = [], string|array $only_fields = '*'): array

// Returns only column
$User->getValue(string|array $column, array $conditions = []): mixed|array

// Insert
$User->insert(array $data): int

// Compare two models
$User->is($model2): bool
$User->isNot($model2): bool

// Insert or update a model
$User->save(): User

// Call a routine
$User->routine(string $name, array $params = []): array

// Update
$User->update(array $data, array $conditions): int
```
