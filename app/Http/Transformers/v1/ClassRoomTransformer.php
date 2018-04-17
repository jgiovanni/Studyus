<?php

namespace App\Http\Transformers\v1;


use App\ClassRoom;
use League\Fractal\TransformerAbstract;

class ClassRoomTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [
        'students', 'instructors'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param ClassRoom $classroom
     * @return array
     */
    public function transform(ClassRoom $classroom)
    {
        return array_merge($classroom->toArray(), [
//            'students_count' => $classroom->students->count()
        ]);
    }

    public function includeInstructors(ClassRoom $classRoom)
    {
        $instructors = $classRoom->instructors;
        return $this->collection($instructors, new UserTransformer);
    }

    public function includeStudents(ClassRoom $classRoom)
    {
        $students = $classRoom->students;
        return $this->collection($students, new UserTransformer);
    }

}