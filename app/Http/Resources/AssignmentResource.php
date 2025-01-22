<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $avaiable = '';
        if ($this->available_for_admin == 1) {
            $avaiable .= app('translator')->get('study.all_admins') . ', ';
        }
        if ($this->available_for_all_classes == 1) {
            $avaiable .= app('translator')->get('study.all_classes_student') . ', ';
        }
        if ($this->classes != "" && $this->sections != "") {
            $avaiable .= (app('translator')->get('study.all_students_of') . " " . $this->classes->class_name . '->' . @$this->sections->section_name) . ', ';
        }

        if ($this->classes != "" && $this->section == null) {
            $avaiable .= (app('translator')->get('study.all_students_of') . " " . $this->classes->class_name . '->' . app('translator')->get('study.all_sections')) . ', ';
        }

        if (moduleStatusCheck('University')) {
            $avaiable .= app('translator')->get('study.all_students_of') . " " . @$this->semesterLabel->name  . '(' . @$this->unSection->section_name . '-' . @$this->undepartment->name . ')';
        }
        $file_path = $this->upload_file ? (string)asset('/') . $this->upload_file : '';

        return [
            'id'            => (int)$this->id,
            'upload_date'   => (string)$this->upload_date,
            'content_title' => (string)$this->content_title,
            'available_for' => (string)@$avaiable,
            'upload_file'   => @$file_path,
            'description'   => (string)$this->description,
        ];
    }
}
