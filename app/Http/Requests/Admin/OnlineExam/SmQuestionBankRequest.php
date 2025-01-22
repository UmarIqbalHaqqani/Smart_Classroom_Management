<?php

namespace App\Http\Requests\Admin\OnlineExam;

use Illuminate\Foundation\Http\FormRequest;

class SmQuestionBankRequest extends FormRequest
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
        $maxFileSize = generalSetting()->file_size * 1024;

        $rules = [
            'group' => 'required',
            'question' => 'required',
            'question_type' => 'required',
            'marks' => 'required',
            'number_of_option' => 'required_if:question_type,M',
            'answer_type' => 'required_if:question_type,MI',
            'question_image' => 'required_if:question_type,MI|mimes:jpg,jpeg,png|max:' . $maxFileSize,
            'number_of_optionImg' => 'required_if:question_type,MI',
            'trueOrFalse' => 'required_if:question_type,T|in:T,F',
            'suitable_words' => 'required_if:question_type,F',
        ];
        
        if (moduleStatusCheck('University')) {      // University Module
            $rules['un_semester_label_id'] = 'required';
            if ($this->id) {
                $rules['un_section_id'] = 'required';
            } else {
                $rules['un_section_ids'] = 'required';
            }
        } else {                                    // School Module or General
            $rules['class'] = 'required';
            $rules['section'] = 'required';
            $rules['section.*'] = 'required';
        }
        return $rules;
    }
}
