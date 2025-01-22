@extends('backEnd.master')
@section('title')
    @lang('behaviourRecords.class_section_wise_rank_report')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('behaviourRecords.class_section_wise_rank_report')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('behaviourRecords.dashboard')</a>
                    <a href="#">@lang('behaviourRecords.behaviour_records')</a>
                    <a href="#">@lang('behaviourRecords.class_section_wise_rank_report')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-20">
                <div class="col-lg-12 student-details up_admin_visitor">
                    <div class="white-box">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4 no-gutters">
                                    <div class="main-title">
                                        <h3 class="mb-15">@lang('behaviourRecords.class_section_wise_rank_report') </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-20">
                                    <x-table>
                                        <div class="table-responsive">
                                        <table class="table table-alignment rank-report-alignment" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="8%">@lang('behaviourRecords.admission_no')
                                                    </th>
                                                    <th width="12%">@lang('behaviourRecords.student')
                                                    </th>
                                                    <th width="10%">@lang('behaviourRecords.class')(@lang('behaviourRecords.section'))
                                                    </th>
                                                    <th width="70%">
                                                        <table width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="15%">@lang('behaviourRecords.assign_incident')
                                                                    </th>
                                                                    <th width="75%">@lang('behaviourRecords.description')
                                                                    </th>
                                                                    <th width="10%" class="text-right">@lang('behaviourRecords.point')
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($class->records as $record)
                                                    @if (count($record->incidents))
                                                        <tr>
                                                            <td width="8%">{{ $record->studentDetail->admission_no }}
                                                            </td>
                                                            <td width="12%">
                                                                <a target="_blank"
                                                                    href="{{ route('student_view', [$record->studentDetail->id]) }}">{{ $record->studentDetail->first_name }}
                                                                    {{ $record->studentDetail->last_name }}</a>
                                                            </td>
                                                            <td width="10%">
                                                                {{ $record->class->class_name }}({{ $record->section->section_name }})
                                                            </td>
                                                            <td width="70%">
                                                                <table width="100%">
                                                                    <tbody>
                                                                        @foreach ($record->studentDetail->incidents as $incident)
                                                                            <tr>
                                                                                <td width="15%">
                                                                                    {{ $incident->incident->title }}
                                                                                </td>
                                                                                <td width="75%">
                                                                                    {{ $incident->incident->description }}
                                                                                </td>
                                                                                <td width="10%" class="text-center">
                                                                                    {{ $incident->incident->point }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                                    </x-table>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
