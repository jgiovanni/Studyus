<?php

namespace App\Http\Transformers\v1;


use App\Question;
use League\Fractal\TransformerAbstract;

class QuestionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [
        'homework', 'answers', 'responses'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Question $question
     * @return array
     */
    public function transform(Question $question)
    {
        return $question->toArray();
    }

    public function includeAnswers(Question $question)
    {
        $answers = $question->answers;
        return $this->collection($answers, new AnswerTransformer);
    }

    public function includeResponses(Question $question)
    {
        $responses = $question->responses();
        return $this->collection($responses, new AnswerTransformer);
    }

}