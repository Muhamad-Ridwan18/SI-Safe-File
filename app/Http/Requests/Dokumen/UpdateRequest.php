<?php

namespace App\Http\Requests\Dokumen;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title'                => 'required',
            'file_path'                => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required'               => 'Nama dokumen tidak boleh kosong',
            'file_path.required'               => 'File dokumen tidak boleh kosong',
        ];
    }
}
