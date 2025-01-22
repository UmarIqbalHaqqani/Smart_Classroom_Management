<?php

namespace App\Http\Requests\Admin\AdminSection;

use Illuminate\Foundation\Http\FormRequest;

class SmStudentCertificateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $maxFileSize =generalSetting()->file_size*1024;
        return [
            'name' => "required|max:50",
            'header_left_text' => "nullable",
            'date' => "nullable|date",
            'body' => "nullable",
            'footer_left_text' => "nullable",
            'footer_center_text' => "nullable",
            'footer_right_text' => "nullable",
            'student_photo' => "nullable",
            'layout' => "required",
            'body_font_size' => "required",
            'height' => "required",
            'width' => "required",
            'file' => "mimes:jpg,jpeg,png|max:".$maxFileSize
        ];

    }
}
