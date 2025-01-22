<?php

namespace Modules\DownloadCenter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentShareRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => "required",
            'shareDate' => "required",
            'validUpto' => "required|after:shareDate",
            'content_ids' => "required",
            'role' => "required_if:selectTab,==,G",
            'role_id' => "required_if:selectTab,==,I",
            'class_id' => "required_if:selectTab,==,C",
        ];
    }
    public function messages()
    {
        return [
            'role' => 'Role is required when recipients are Group',
            'role_id' => 'User is required when recipients are Individual',
            'class_id' => 'Class is required when recipients are Class',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
