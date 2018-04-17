<?php namespace App\ModelFilters;

class ClassRoomFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [
        'students' => ['students'],
        'instructors' => ['instructors'],
    ];

    public $searchable = ['name', 'subject'];
}
