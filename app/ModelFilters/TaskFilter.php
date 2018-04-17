<?php namespace App\ModelFilters;

class TaskFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */

    public $relations = [

    ];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = ['opened_at', 'closed_at', 'created_at', 'public'];
    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = ['name', 'description'];

}
