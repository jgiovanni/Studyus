<?php

namespace App\Http\Transformers\v1;


use App\Answer;
use League\Fractal\TransformerAbstract;

class AnswerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [
        'question'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Answer $answer
     * @return array
     */
    public function transform(Answer $answer)
    {
        return $answer->toArray();
    }

    public function includeQuestion(Answer $answer)
    {
        $question = $answer->question;
        return $this->collection($question, new QuestionTransformer);
    }



}