<?php
namespace Zijinghua\Zfilesystem\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
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
            'filename_prefix' => [
                'string'
            ],
            'filename_extension' => [
                'required_with:filename_prefix',
                'string'
            ],
        ];
    }

    public function messages()
    {
        $message =  [
            'filename_prefix.required' => '请传入文件前缀',
            'filename_extension.required_with' => '请传入文件后缀'
        ];
        return $message;
    }
}
