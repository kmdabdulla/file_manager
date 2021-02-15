<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUpload extends FormRequest
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
            'files' => 'required_without:fileId',
            'fileId' => 'sometimes|numeric',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'fileId' => filter_var($this->fileId,FILTER_SANITIZE_NUMBER_INT),
        ]);
    }

    public function messages()
    {
        return [
            'files.required_without' => 'Please choose file(s) before uploading.',
        ];
    }

}
