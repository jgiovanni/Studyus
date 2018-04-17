<?php namespace App\ModelFilters;

class QuestionFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [
        'answers' => []
    ];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = ['created_at', 'country'];


    public function task ($task)
    {
        return $this->where('task_id', $task);
    }
}
