@extends('backEnd.master')
@section('title')
    @lang('behaviourRecords.student_incident_report')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('behaviourRecords.student_incident_report')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('behaviourRecords.dashboard')</a>
                    <a href="#">@lang('behaviourRecords.behaviour_records')</a>
                    <a href="#">@lang('behaviourRecords.student_incident_report')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-20">
                <div class="col-lg-12 student-details up_admin_visitor">
                    <div class="row">
                        <div class="col-lg-12">
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'behaviour_records.student_incident_report_search', 'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
                            <div class="white-box">
                                <div class="row">
                                    <div class="col-lg-8 col-md-6">
                                        <div class="main-title">
                                            <h3 class="mb-15">@lang('behaviourRecords.select_criteria') </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @include('backEnd.common.search_criteria', [
                                        'div' => 'col-lg-4',
                                        'required' => ['academic', 'class', 'section'],
                                        'visiable' => ['academic', 'class', 'section'],
                                    ])
                                    <div class="col-lg-12 mt-20 text-right">
                                        <button type="submit" class="primary-btn small fix-gr-bg">
                                            <span class="ti-search pr-2"></span>
                                            @lang('behaviourRecords.search')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    @isset($student_records)
                        <div class="row mt-40">
                            <div class="col-lg-12">
                                <div class="white-box">
                                    <div class="row">
                                        <div class="col-lg-4 no-gutters">
                                            <div class="main-title">
                                                <h3 class="mb-15">@lang('behaviourRecords.student_incident_list') </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <x-table>
                                                <table id="table_id" class="table" cellspacing="0"
                                                    width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('behaviourRecords.admission_no')</th>
                                                            <th>@lang('behaviourRecords.student_name')</th>
                                                            <th>@lang('behaviourRecords.class')(@lang('behaviourRecords.section'))</th>
                                                            <th>@lang('behaviourRecords.gender')</th>
                                                            <th>@lang('behaviourRecords.phone')</th>
                                                            <th>@lang('behaviourRecords.total_incidents')</th>
                                                            <th>@lang('behaviourRecords.total_points')</th>
                                                            <th>@lang('behaviourRecords.actions')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($student_records as $key => $data)
                                                            @php
                                                                $incident = 0;
                                                                foreach ($data->student->incidents as $student_point) {
                                                                    $incident += $student_point->incident->point;
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $data->student->admission_no }}</td>
                                                                <td>
                                                                    <a target="_blank"
                                                                        href="{{ route('student_view', [$data->student->id]) }}">{{ $data->student->first_name }}
                                                                        {{ $data->student->last_name }}</a>
                                                                </td>
                                                                <td>{{ $data->class->class_name }}({{ $data->section->section_name }})
                                                                </td>
                                                                <td>{{ $data->student->gender->base_setup_name }}</td>
                                                                <td>{{ $data->student->mobile }}</td>
                                                                <td>{{ $data->incidents_count }}</td>
                                                                <td>{{ $data->incidents_sum_point ? $data->incidents_sum_point : 0 }}</td>
                                                                <td>
                                                                    <x-drop-down>
                                                                        <a class="dropdown-item modalLink"
                                                                            data-modal-size="large-modal"
                                                                            title="Student All Incident-{{ $data->student->full_name }}"
                                                                            href="{{ route('behaviour_records.view_student_all_incident_modal', [$data->student->id]) }}">@lang('common.view')</a>
                                                                    </x-drop-down>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </x-table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
