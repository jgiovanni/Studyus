<?php

namespace App\Http\Transformers\v1;


use App\Task;
use App\Question;
use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [
        'questions', 'students', 'classrooms'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Task $homework
     * @return array
     */
    public function transform(Task $homework)
    {
        return array_merge($homework->toArray(), [
            // 'questions_count' => $homework->
        ]);
    }

    public function includeQuestions(Task $homework)
    {
        $questions = $homework->questions;
        return $this->collection($questions, new QuestionTransformer);
    }

    public function includeStudents(Task $homework)
    {
        $students = $homework->students;
        return $this->collection($students, new UserTransformer);
    }

    public function includeClassrooms(Task $homework)
    {
        $classrooms = $homework->classrooms;
        return $this->collection($classrooms, new ClassRoomTransformer());
    }


}