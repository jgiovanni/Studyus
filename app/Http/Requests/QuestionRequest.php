<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $required = [
            'body' => 'required',
            'task_id' => 'required|exists:tasks,id',
            'explanation' => 'nullable|string',
        ];

        if ($this->isMethod('post')) {
            $post = [
                'answers' => 'sometimes|array',
                'answers.*.correct' => 'boolean',
                'answers.*.body' => 'required|string',
            ];

            return array_merge($required, $post);
        }
        return $required;
    }
}
