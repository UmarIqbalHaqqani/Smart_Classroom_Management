<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ExamTypeStoreRequest extends FormRequest 
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exam_type_title' => ['required', Rule::unique('sm_exam_types', 'title')->where('academic_id', getAcademicId())->where('school_id', auth()->user()->school_id)->ignore($this->id)],
            'average_mark' => 'required_if:is_average,yes' 
        ];
    }
}
