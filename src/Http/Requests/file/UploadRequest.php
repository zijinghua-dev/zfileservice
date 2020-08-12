<?php

namespace Zijinghua\Zfilesystem\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => [
                'required',
                'file',
                'max:10240'
            ],
            'file_md5' => [
                'required',
                'max:32'
            ],
            'use_type' => [
                'string'
            ],
            'resource_keyword' => [
                'required_with:use_type'
            ]
        ];
    }
}
