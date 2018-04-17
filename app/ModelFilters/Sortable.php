<?php
/**
 * Created by PhpStorm.
 * User: jerezb
 * Date: 2017-07-19
 * Time: 9:48 AM
 */

namespace App\ModelFilters;


trait Sortable
{

    /**
     * Default sortable fields.
     *
     * @var array
     */
    public $sortable = [];

    /**
     * Sort by field.
     *
     * @param $field
     * @return mixed
     */
    public function sort($field)
    {
        $param = preg_split('/\|+/', $field);
        $column = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($column, $this->sortable) )
            return $this->orderBy($column, $direction);

        return $this;
    }
}