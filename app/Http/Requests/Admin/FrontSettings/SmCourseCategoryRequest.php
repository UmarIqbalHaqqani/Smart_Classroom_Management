<?php

namespace App\Http\Requests\Admin\FrontSettings;

use App\SmCourseCategory;
use Illuminate\Foundation\Http\FormRequest;

class SmCourseCategoryRequest extends FormRequest
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
        $maxFileSize =generalSetting()->file_size*1024;
        $category = SmCourseCategory::find($this->id);
        $rules =  [
            'category_name' => 'required',
            'category_image' => 'required|max:'.$maxFileSize,
        ];

        return $rules;
    }
}
