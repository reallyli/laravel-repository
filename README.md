# Laravel Repository


Laravel Repository is a package for Laravel 5.7 which is used to abstract the database layer. This makes applications much easier to maintain.


**The main differnece between this and others is:**
* use eloquent model as an entity
* put the business logic into eloquent model
* always use repository to persist eloquent model, `$repository->save($model)`;
* don't call `$model->save, $model->update, $model->delete`, etc... persistence method directly, always use `$repository->save($model)`

## Installation

Run the following command from you terminal:


 ```bash
 composer require tlikai/laravel-repositories
 ```

or add this to require section in your composer.json file:

 ```
 "bosnadev/repositories": "^1.0"
 ```

then run ```composer update```


## Usage

First, create your repository class. Note that your repository class MUST extend ```Uniqueway\Repositories\Eloquent\Repository``` and implement model() method

```php
<?php namespace App\Repositories;

use Uniqueway\Repositories\Contracts\RepositoryInterface;
use Uniqueway\Repositories\Eloquent\Repository;

class FilmsRepository extends Repository {

    public function model() {
        return 'App\Film';
    }
}
```

By implementing ```model()``` method you telling repository what model class you want to use. Now, create ```App\Film``` model:

```php
<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model {

    protected $primaryKey = 'film_id';

    protected $table = 'film';

    protected $casts = [
        "rental_rate"       => 'float'
    ];
}
```

And finally, use the repository in the controller:

```php
<?php namespace App\Http\Controllers;

use App\Repositories\FilmsRepository as Film;

class FilmsController extends Controller {

    private $film;

    public function __construct(Film $film) {

        $this->film = $film;
    }

    public function index() {
        return \Response::json($this->film->all());
    }
}
```

## Available Methods

The following methods are available:

##### Uniqueway\Repositories\Contracts\RepositoryInterface

```php
public function all($columns = array('*'))
public function lists($value, $key = null)
public function paginate($perPage = 1, $columns = array('*'))
public function save($model)
public function delete($model)
public function find($id, $columns = array('*'))
public function findBy($field, $value, $columns = array('*'))
public function findAllBy($field, $value, $columns = array('*'))
public function findWhere($where, $columns = array('*'))
public function findWhereIn($field, $values, $columns = ['*'])
public function whereHas($relation, $closure)
```

##### Uniqueway\Repositories\Contracts\CriteriaInterface

```php
public function apply($model, Repository $repository)
```

### Example usage

Find film by id;

```php
$this->film->find($id);
```

Create a new film:

```php
$film = new Film;
$film->fill($attributes);
$this->film->save($film);
```

Update existing film:

```php
$film = $this->film->find($id);
$film->fill($attributes);
$this->film->save($film);
```

Delete film:

```php
$film = $this->film->find($id);
$this->film->delete($film);
```

```

Find one film by column

```php
$this->film->findBy('title', $title);
```

Find all film by column
```php
$this->film->findAllBy('author_id', $author_id);
```

Get all rows by multiple fields
```php
$this->film->findWhere([
    'author_id' => $author_id,
    ['year','>',$year]
]);
```

Put domain logic into model, use repository perist it
```php
<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model {

    protected $primaryKey = 'film_id';

    protected $table = 'film';

    protected $casts = [
        "rental_rate"       => 'float'
    ];

    public function publish()
    {
        $this->status = 'publish';
    }
}
?>

<?php namespace App\Http\Controllers;

use App\Repositories\FilmsRepository as Film;

class FilmsController extends Controller {

    private $film;

    public function __construct(Film $film) {

        $this->film = $film;
    }

    public function markAsPublish(Request $request, $id) {
        $film = $this->film->find($id);
        $film->publish();
        $this->film->save($film);
        return \Response::ok();
    }
}
?>
```

## Criteria

Criteria is a simple way to apply specific condition, or set of conditions to the repository query. Your criteria class MUST extend the abstract ```Uniqueway\Repositories\Criteria\Criteria``` class.

Here is a simple criteria:

```php
<?php namespace App\Repositories\Criteria\Films;

use Uniqueway\Repositories\Criteria\Criteria;
use Uniqueway\Repositories\Contracts\RepositoryInterface as Repository;

class LengthOverTwoHours extends Criteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $model = $model->where('length', '>', 120);
        return $model;
    }
}
```

Now, inside you controller class you call pushCriteria method:

```php
<?php namespace App\Http\Controllers;

use App\Repositories\Criteria\Films\LengthOverTwoHours;
use App\Repositories\FilmsRepository as Film;

class FilmsController extends Controller {

    /**
     * @var Film
     */
    private $film;

    public function __construct(Film $film) {

        $this->film = $film;
    }

    public function index() {
        $this->film->pushCriteria(new LengthOverTwoHours());
        return \Response::json($this->film->all());
    }
}
```


## Inspired by

* [prettus/l5-repository](https://github.com/prettus/l5-repository)
* [bosnadev/repository](https://github.com/bosnadev/repository)
