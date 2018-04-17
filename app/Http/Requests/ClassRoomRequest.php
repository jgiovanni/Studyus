<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassRoomRequest extends FormRequest
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
        $data =  [
            'name' => 'required|string',
            'grades' => 'required|array',
            'subject' => 'required|string',
        ];

        if ($this->method() == 'POST') {
            $data['instructors'] = 'required|array';
            $data['instructors.*.permissions'] = 'required|string';
            $data['instructors.*.user_id'] = 'required|string';
        }

        return $data;
    }
}
