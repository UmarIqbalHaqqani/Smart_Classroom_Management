@extends('backEnd.master')
@section('title')
    @lang('behaviourRecords.incident_wise_report')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('behaviourRecords.incident_wise_report')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('behaviourRecords.dashboard')</a>
                    <a href="#">@lang('behaviourRecords.behaviour_records')</a>
                    <a href="#">@lang('behaviourRecords.incident_wise_report')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-20">
                <div class="col-lg-12 student-details up_admin_visitor">
                    <h3 class="mb-0">@lang('behaviourRecords.incident_wise_report')</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 mb-20">
                    <x-table>
                        <table id="table_id" class="table data-table" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('behaviourRecords.admission_no')
                                    </th>
                                    <th>@lang('behaviourRecords.student_name')
                                    </th>
                                    <th>@lang('behaviourRecords.class')(@lang('behaviourRecords.section'))
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studentRecords as $studentRecord)
                                    <tr>
                                        <td>{{ $studentRecord->studentRecord->studentDetail->admission_no }}</td>
                                        <td>
                                            <a target="_blank"
                                                href="{{ route('student_view', [$studentRecord->studentRecord->studentDetail->id]) }}">{{ $studentRecord->studentRecord->studentDetail->first_name }}
                                                {{ $studentRecord->studentRecord->studentDetail->last_name }}</a>
                                        </td>
                                        <td>{{ $studentRecord->studentRecord->class->class_name }}({{ $studentRecord->studentRecord->section->section_name }})
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-table>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
