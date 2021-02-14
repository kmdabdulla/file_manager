<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLogin extends FormRequest
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
                'email' => 'bail|required|email',
                'name' => 'bail|required_with:password_confirmation|string|max:255',
                'password' => 'required',
                'password_confirmation' => 'sometimes|same:password',
            ];

    }

    protected function prepareForValidation()
    {
        $this->merge([
            'email' => filter_var(strtolower($this->email), FILTER_SANITIZE_EMAIL),
            'name' => filter_var($this->name,FILTER_SANITIZE_STRING),
            'password' => filter_var($this->password,FILTER_SANITIZE_STRING)
        ]);
    }

    public function messages()
    {
        return [
            'name.required_with' => 'Name is required.',
        ];
    }
}
