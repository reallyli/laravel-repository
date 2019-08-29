<?php

namespace Uniqueway\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface RepositoryInterface
 * @package Uniqueway\Repositories\Contracts
 */
interface RepositoryInterface
{
    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*']);

    /**
     * @param $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 1, $columns = ['*']);

    /**
     * @param array $data
     * @return bool
     */
    public function save(Model $model);

    /**
     * @param $id
     * @return mixed
     */
    public function delete(Model $model);

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*']);

    /**
     * @param $attribute
     * @param string $value
     * @param array $columns
     * @param bool $or
     * @return mixed
     * @author Taylor <boz14676@gmail.com>
     */
    public function findBy($attribute, $value = '', $columns = ['*'], $or = false);

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findAllBy($field, $value, $columns = ['*']);

    /**
     * @param $where
     * @param array $columns
     * @return mixed
     */
    public function findWhere($where, $columns = ['*']);

    /**
     * @param $field
     * @param $where
     * @param array $columns
     * @return mixed
     */
    public function findWhereIn($field, $values, $columns = ['*']);

    /**
     * @param string $relation
     * @param closure $closure
     *
     * @return $this
     */
    public function whereHas($relation, $closure);
}
