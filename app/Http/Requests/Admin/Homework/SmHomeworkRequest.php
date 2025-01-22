<?php

namespace App\Http\Requests\Admin\Homework;

use Illuminate\Foundation\Http\FormRequest;

class SmHomeworkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $maxFileSize=generalSetting()->file_size*1024;
        $rules = [
            'marks' => "required|numeric|min:0",
            'description' => "required",
            'homework_date'=>["required", 'date'],
            'submission_date'=>["required", 'after:homework_date'],
            'homework_file' => "sometimes|nullable|mimes:pdf,doc,docx,txt,jpg,jpeg,png,mp4,ogx,oga,ogv,ogg,webm,mp3|max:".$maxFileSize,
        ];
        if (moduleStatusCheck('University')) {
            $rules += [
                'un_session_id' => ['required'],
                'un_department_id' => ['required'],
                'un_academic_id' => ['required'],
                'un_semester_id' => ['required'],
                'un_semester_label_id' => ['required'],
                'un_subject_id' => ['required']
            ];
        }else{
            if(!moduleStatusCheck('Lms')) {
                $rules += [
                    'class_id' => ['required'],
                    'section_id' => ['required'],
                    'subject_id' => ['required']
                ];
            }
        
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'class_id.required' => 'Class field is required.',
            'section_id.required' => 'Section field is required.',
            'subject_id.required' => 'Subject field is required.',
            'un_session_id.required' => 'Session field is required.',
            'un_department_id.required' => 'Department field is required.',
            'un_academic_id.required' => 'Academic field is required.',
            'un_semester_id.required' => 'Semester field is required.',
            'un_semester_label_id.required' => 'Semester Label field is required.',
            'un_subject_id.required' => 'Subject field is required.',
            'marks.required' => 'Marks field is required.',
            'marks.numeric' => 'Marks must be a number.',
            'marks.min' => 'Marks must be greater than 0.',
            'description.required' => 'Description field is required.',
            'homework_date.required' => 'Homework Date field is required.',
            'homework_date.date' => 'Homework Date must be a date.',
            'submission_date.required' => 'Submission Date field is required.',
            'submission_date.after_or_equal' => 'Submission Date must be a date after or equal to Homework Date.',
            'homework_file.mimes' => 'File type must be pdf,doc,docx,txt,jpg,jpeg,png,mp4,ogx,oga,ogv,ogg,webm,mp3.',
            'homework_file.max' => 'File size must be less than '.generalSetting()->file_size.' MB.'

        ];
    }
}
