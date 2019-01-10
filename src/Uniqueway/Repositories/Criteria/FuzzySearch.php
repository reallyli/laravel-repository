<?php

namespace Uniqueway\Repositories\Criteria;

use Uniqueway\Repositories\Contracts\RepositoryInterface as Repository;

class FuzzySearch extends Criteria
{
    /**
     * Search query
     *
     * @var string
     */
    protected $query;

    /**
     * Search attributes
     *
     * @var array
     */
    protected $attributes;

    /**
     * SearchCustomer constructor.
     *
     * @param string $query
     * @param array $attributes
     */
    public function __construct($query, array $attributes)
    {
        $query = trim($query);
        $query = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $query);
        $this->query = $query;
        $this->attributes = $attributes;
    }

    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        if (empty($this->query)) {
            return $model;
        }

        $where = 'where';
        foreach ($this->attributes as $attribute) {
            if ($attribute == 'id' && preg_match('/^\d+$/', $this->query)) {
                $model = $model->$where($attribute, '=', $this->query);
            } else {
                $pattern = '%' . $this->query . '%';
                $model = $model->$where($attribute, 'LIKE', $pattern);
            }

            $where = 'orWhere';
        }

        return $model;
    }
}
