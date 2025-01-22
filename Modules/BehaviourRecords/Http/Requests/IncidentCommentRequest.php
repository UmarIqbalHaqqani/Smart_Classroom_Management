<?php

namespace Modules\BehaviourRecords\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncidentCommentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'comment' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
