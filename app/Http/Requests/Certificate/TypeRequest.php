<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class TypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:certificate_types,name,'.$this->id,
            'short_code' => 'required',
            'role_id' => 'required',
        ];
    }

    public function messages(){
        return [
            'name.required' => _trans('response.The name field is required'),
            'name.unique' => _trans('response.The name has already been taken'),
            'short_code.required' => _trans('response.The short code field is required'),
            'role_id.required' => _trans('response.Applicable for field is required'),
        ];
    }
}
