<?php
/**
 * Created by PhpStorm.
 * User: emmanuel
 * Date: 17-12-20
 * Time: 下午1:42
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $model
*/
trait RepositoryTrait
{
    /**
     * @param array $where
     * @return $this
     */
    public function findWhereBetween(array $where)
    {
        $this->applyWhereBetweenConditions($where);
        return $this;
    }

    /**
     * @param $field
     * @param array $where
     * @param array $columns
     * @return $this
     * @authot magus.lee
     */
    public function findWhereIn($field,array $where,$columns = ['*'])
    {
        $this->applyWhereInConditions($field,$where);
        return $this;
    }

    public function where(array $where)
    {
        $this->model->where($where);
        return $this;
    }

    public function limit($value)
    {
        $this->model->limit($value);
        return $this;
    }
    /**
     * @param array $where
     */
    protected function applyWhereBetweenConditions(array $where)
    {
        foreach ($where as $field => $values) {
            $this->model = $this->model->whereBetween($field, $values);
        }
    }

    /**
     * @param $field
     * @param array $values
     * @author magus.lee
     */
    protected function applyWhereInConditions($field,array $values)
    {
        $this->model = $this->model->whereIn($field, $values);
    }

    /**
     * @param $page
     * @param $perPage
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function forPage($page, $perPage)
    {
        return $this->model->forPage($page, $perPage);
    }

    /**
     * @param string $columns
     * @return int
     * @author magus.lee
     */
    public function count($columns = '*'){
        return $this->model->count($columns);
    }
}