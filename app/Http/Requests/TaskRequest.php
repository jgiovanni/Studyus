<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
        return [
            'public' => 'boolean',
            'name' => 'required|string',
//            'description' => 'string',
            'user_id' => '',
            'closed_at' => 'nullable|date',
            'due_at' => 'nullable|date',
            'opened_at' => 'nullable|date',
        ];
    }
}
