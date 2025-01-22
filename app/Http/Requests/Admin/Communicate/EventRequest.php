<?php

namespace App\Http\Requests\Admin\Communicate;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $maxFileSize=generalSetting()->file_size*1024;
        
        $rules = [
            'role_ids' => "required|array|min:1",
            'event_title' => "required",
            'from_date' => "required|date",
            'to_date' => "required|date|after_or_equal:from_date",
            'event_des' => "required",
            'event_location' => 'required',
            'url' => 'nullable',
            'upload_file_name' => "nullable|mimes:jpg,jpeg,png,gif|max:".$maxFileSize,
        ];

        if (!$this->id) {
            $rules['upload_file_name'] = "nullable|mimes:jpg,jpeg,png,gif|max:".$maxFileSize;
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'role_ids' => 'role',
            'event_des' => 'event description',
        ];
    }
}
