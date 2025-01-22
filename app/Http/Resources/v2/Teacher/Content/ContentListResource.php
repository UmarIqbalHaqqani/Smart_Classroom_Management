<?php

namespace App\Http\Resources\v2\Teacher\Content;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($this->available_for_admin == 1) {
            $avaiable = app('translator')->get('study.all_admins');
        } elseif ($this->available_for_all_classes == 1) {
            $avaiable = app('translator')->get('study.all_classes_student');
        } elseif ($this->classes != "" && $this->sections != "") {
            $avaiable = app('translator')->get('study.all_students_of') . " " . $this->classes->class_name . '->' . $this->sections->section_name;
        } elseif ($this->classes != "" && $this->section == null) {
            $avaiable = app('translator')->get('study.all_students_of') . " " . $this->classes->class_name . '->' . app('translator')->get('study.all_sections');
        } else {
            $avaiable = app('translator')->get('study.all_students_of');
        }

        if (@$this->content_type == 'as')
            $content_type = __('study.assignment');
        elseif (@$this->content_type == 'st')
            $content_type = __('study.study_material');
        elseif (@$this->content_type == 'sy')
            $content_type = __('study.syllabus');
        else
            $content_type = __('study.other_download');
        return [
            'id'            => (int)$this->id,
            'content_title' => (string)$this->content_title,
            'content_type'  => (string)$content_type,
            'upload_date'   => (string)$this->upload_date,
            'available_for' => (string)$avaiable,
            'upload_file'   => $this->upload_file ? (string)asset($this->upload_file) : (string)null
        ];
    }
}
