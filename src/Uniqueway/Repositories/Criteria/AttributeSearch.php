<?php

namespace Uniqueway\Repositories\Criteria;

use Uniqueway\Repositories\Contracts\RepositoryInterface as Repository;

class AttributeSearch extends Criteria
{
    /**
     * Search params
     *
     * @var array
     */
    protected $params;

    /**
     * @param $params
     */
    public function __construct($params)
    {
        $this->params = collect($params)->filter(function ($param) {
            return ! is_null($param);
        });
    }

    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        foreach ($this->params as $key => $val) {
            list($attribute, $operator, $value) = $this->extractFactors($key, $val);

            $model = $model->where($attribute, $operator, $value);
        }

        return $model;
    }

    protected function extractFactors($key, $val)
    {
        $attribute = $key;
        $operator = '=';
        $value = $val;

        if (ends_with($attribute, '_lt')) {
            $operator = '<';
            $attribute = substr($attribute, 0, strrpos($attribute, '_lt'));
        } elseif (ends_with($attribute, '_gt')) {
            $operator = '>';
            $attribute = substr($attribute, 0, strrpos($attribute, '_gt'));
        } elseif (ends_with($attribute, '_lte')) {
            $operator = '<=';
            $attribute = substr($attribute, 0, strrpos($attribute, '_lte'));
        } elseif (ends_with($attribute, '_gte')) {
            $operator = '>=';
            $attribute = substr($attribute, 0, strrpos($attribute, '_gte'));
        } elseif (ends_with($attribute, '_not')) {
            $operator = '<>';
            $attribute = substr($attribute, 0, strrpos($attribute, '_not'));
        } elseif (ends_with($attribute, '_query')) {
            $operator = 'LIKE';
            $attribute = substr($attribute, 0, strrpos($attribute, '_query'));
            $value = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $value);
            $value = '%' . $value . '%';
        }

        return [$attribute, $operator, $value];
    }
}
