<?php

namespace App\Http\Requests\Admin\FeesCollection;

use Illuminate\Foundation\Http\FormRequest;

class SmFeesForwardSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        if (moduleStatusCheck('University')) {
            return [
                'un_semester_label_id' => 'required',
                'un_section_id' => 'required'
            ];
        } else {
            return [
                'class' => 'required',
                'section' => 'required'
            ];
        }
        
        
    }

    public function messages(){
        return [
            'un_semester_label_id.required' => 'The semester field is required.',
            'un_section_id.required' => 'The section field is required.',
            'class.required' => 'The class field is required.',
            'section.required' => 'The section field is required.'
        ];
    }
}
