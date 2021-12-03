<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'title' => 'required|max:30',
           'description' => 'required',
           'due_date' => 'required|date'
        ];
    }

    /**
     * Get the validation failed validation.
     *
     * @return array
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
          'success' => false,
          'message' => 'Validation Errors',
          'data' => $validator->errors()
        ]));
    }

}
