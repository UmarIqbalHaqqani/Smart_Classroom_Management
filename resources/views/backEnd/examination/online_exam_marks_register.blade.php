@extends('backEnd.master')
@section('title')
    @lang('exam.marking')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('exam.examinations')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('exam.examinations')</a>
                    <a href="{{ route('online-exam') }}">@lang('exam.online_exam')</a>
                    <a href="{{ route('online_exam_marks_register', [@$online_exam_question->id]) }}">@lang('exam.marking')</a>
                </div>
            </div>
        </div>
    </section>
    @if (isset($studentRecords))
        <section class="mt-20">
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-6 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('exam.marking')</h3>
                        </div>
                    </div>
                </div>
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'online_exam_marks_store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'marks_register_store']) }}
                <input type="hidden" name="exam_id" value="{{ @$online_exam_question->id }}">
                <input type="hidden" name="subject_id" value="{{ @$online_exam_question->subject_id }}">
                <div class="white-box">
                <div class="row">
                    <div class="col-lg-12">

                        <table class="table school-table-style" cellspacing="0" width="100%">
                            <thead>
                                <tr>


                                    <th>@lang('student.admission_no')</th>
                                    <th>@lang('common.student')</th>
                                    @if (moduleStatusCheck('University'))
                                        <th> @lang('university::un.semester_label') (@lang('common.section'))</th>
                                    @else
                                        <th>@lang('common.class_Sec')</th>
                                    @endif
                                    <th>@lang('exam.exam')</th>
                                    <th>@lang('common.subject')</th>
                                    <th>@lang('exam.marking')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($studentRecords as $student)
                                    <tr>
                                        <td>{{ @$student->studentDetail->admission_no }}</td>
                                        <td>{{ @$student->studentDetail->full_name }}</td>
                                        @if (moduleStatusCheck('University'))
                                            <td>{{ @$student->unSemesterLabel != '' ? @$student->unSemesterLabel->name : '' }}
                                                ({{ @$student->section != '' ? @$student->section->section_name : '' }})</td>
                                        @else
                                            <td>{{ @$student->class != '' ? @$student->class->class_name : '' }}
                                                ({{ @$student->section != '' ? @$student->section->section_name : '' }})</td>
                                        @endif

                                        <td>{{ @$online_exam_question->title }}</td>
                                        <td>{{ @$online_exam_question->subject != '' ? $online_exam_question->subject->subject_name : '' }}
                                        </td>

                                        <td>
                                            @if (in_array(@$student->student_id, @$present_students))
                                                <a class="primary-btn small bg-success text-white border-0"
                                                    href="{{ route('online_exam_marking', [@$online_exam_question->id, @$student->student_id]) }}">
                                                    @lang('exam.view_answer_marking')
                                                </a>
                                            @else
                                                <a class="primary-btn small bg-warning text-white border-0" href="#">
                                                    @lang('student.absent')
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <td colspan="6" class="text-center py-3">No data found</td>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </section>
    @endif

@endsection
