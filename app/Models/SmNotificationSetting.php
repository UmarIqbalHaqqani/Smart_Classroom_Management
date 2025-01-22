<?php

namespace App\Models;

use App\SmClass;
use App\SmSection;
use App\SmSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmNotificationSetting extends Model
{
    use HasFactory;

    protected $casts = ['destination' => 'array', 'template' => 'array', 'subject' => 'array', 'recipient' => 'array', 'shortcode' => 'array'];

    public static function templeteData($body, $data)
    {
        $body = str_replace('[student_name]', @$data['student_name'], $body);
        $body = str_replace('[assignment]', @$data['assignment'], $body);
        $body = str_replace('[subject]', @$data['subject'], $body);
        $body = str_replace('[teacher_name]', @$data['teacher_name'], $body);
        $body = str_replace('[parent_name]', @$data['parent_name'], $body);
        $body = str_replace('[syllabus]', @$data['assignment'], $body);
        $body = str_replace('[other_downloads]', @$data['assignment'], $body);
        $body = str_replace('[lesson_plan]', @$data['lesson_plan'], $body);
        $body = str_replace('[admin_name]', @$data['admin_name'], $body);
        $body = str_replace('[subject]', @$data['subject'], $body);
        $body = str_replace('[date]', @$data['date'], $body);
        $body = str_replace('[book]', @$data['book'], $body);
        $body = str_replace('[exam_schedule]', @$data['exam_schedule'], $body);
        $body = str_replace('[vehicle_no]', @$data['vehicle_no'], $body);
        $body = str_replace('[route]', @$data['route'], $body);
        $body = str_replace('[dormitory]', @$data['dormitory'], $body);
        $body = str_replace('[room]', @$data['room'], $body);
        $body = str_replace('[name]', @$data['name'], $body);
        $body = str_replace('[from_date]', @$data['from_date'], $body);
        $body = str_replace('[to_date]', @$data['to_date'], $body);
        $body = str_replace('[event]', @$data['event'], $body);
        $body = str_replace('[notice]', @$data['notice'], $body);
        $body = str_replace('[grand_total]', @$data['grand_total'], $body);
        $body = str_replace('[total_paid]', @$data['total_paid'], $body);
        $body = str_replace('[total_due]', @$data['total_due'], $body);
        $body = str_replace('[item]', @$data['item'], $body);
        $body = str_replace('[quantity]', @$data['quantity'], $body);
        $body = str_replace('[amount]', @$data['amount'], $body);
        $body = str_replace('[fees]', @$data['fees'], $body);
        if (@$data['attendance_type'] == "P") {
            $body = str_replace('[attendance_type]', "Present", $body);
        } elseif (@$data['attendance_type'] == "L") {
            $body = str_replace('[attendance_type]', "Late", $body);
        } elseif (@$data['attendance_type'] == "A") {
            $body = str_replace('[attendance_type]', "Absent", $body);
        } elseif (@$data['attendance_type'] == "F") {
            $body = str_replace('[attendance_type]', "Half Day", $body);
        }

        if (@$data['class']) {
            $class = SmClass::find($data['class']);
            $body = str_replace('[class]', @$class->class_name, $body);
        }

        if (@$data['section']) {
            $section = SmSection::find($data['section']);
            $body = str_replace('[section]', @$section->section_name, $body);
        }

        if (@$data['class_id']) {
            $class = SmClass::find($data['class_id'], ['class_name']);
            $body = str_replace('[class]', @$class->class_name, $body);
        }

        if (@$data['section_id']) {
            $section = SmSection::find($data['section_id'], ['section_name']);
            $body = str_replace('[section]', @$section->section_name, $body);
        }

        if (@$data['subject_id']) {
            $subject = SmSubject::find($data['subject_id'], ['subject_name']);
            $body = str_replace('[subject]', @$subject->subject_name, $body);
        }
        return $body;
    }
}
