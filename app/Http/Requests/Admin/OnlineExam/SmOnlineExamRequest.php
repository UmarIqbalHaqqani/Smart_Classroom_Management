<?php

namespace App\Http\Requests\Admin\OnlineExam;

use Illuminate\Foundation\Http\FormRequest;

class SmOnlineExamRequest extends FormRequest
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
        if (moduleStatusCheck('University')) {
            $rules = [
                'title' => 'required',
                'un_semester_label_id' => 'required',
                'un_section_ids' => 'required_if:id,null',
                'un_subject_id' => 'required',
                'date' => 'required|date',
                'end_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'percentage' => 'required',
                'instruction' => 'required'
            ];
            if ($this->id){
                $rules['un_section_id'] = 'required';
            }
        } else {
            $rules = [
                'title' => 'required',
                'class' => 'required',
                'section' => 'required|array',
                'subject' => 'required',
                'date' => 'required|date',
                'end_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'percentage' => 'required',
                'instruction' => 'required'
            ];
            if ($this->id){
                $rules['section'] = 'required';
            }
        }
        

        return $rules;
    }
}
