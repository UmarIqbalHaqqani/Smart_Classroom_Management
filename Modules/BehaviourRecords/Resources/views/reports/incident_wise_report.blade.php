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
            <div class="white-box mt-40">
                <div class="row">
                    <div class="col-lg-12 main-title student-details up_admin_visitor">
                        <h3 class="mb-15">@lang('behaviourRecords.incident_wise_report')</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table data-table" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('behaviourRecords.incidents')</th>
                                        <th>@lang('behaviourRecords.students')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($incidents as $incident)
                                        <tr>
                                            <td>{{ $incident->title }}</td>
                                            <td>
                                                <a
                                                    href="{{ route('behaviour_records.view_incident_wise_report_modal', [$incident->id]) }}">{{ count($incident->incidents->unique('student_id')) }}</a>
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
    </section>
@endsection
@include('backEnd.partials.data_table_js')
