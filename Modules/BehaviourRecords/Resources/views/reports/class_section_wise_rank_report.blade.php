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
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box">
                                <div class="row">
                                    <div class="col-lg-4 no-gutters">
                                        <div class="main-title">
                                            <h3 class="mb-15">@lang('behaviourRecords.class_section_wise_rank_report') </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <x-table>
                                            <table id="table_id" class="table data-table" cellspacing="0"
                                                width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('behaviourRecords.rank')</th>
                                                        <th>@lang('behaviourRecords.class')</th>
                                                        <th>@lang('behaviourRecords.students')</th>
                                                        <th>@lang('behaviourRecords.section')-(@lang('behaviourRecords.students'))</th>
                                                        <th>@lang('behaviourRecords.total_points')</th>
                                                        <th>@lang('behaviourRecords.actions')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($classes as $key => $class)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $class->class_name }}</td>
                                                            <td>{{ @$class->records_count }}</td>
                                                            <td>
                                                                @if (@$class->groupclassSections)
                                                                    @foreach ($class->groupclassSections as $section)
                                                                        {{ @$section->sectionName->section_name }}-({{ total_no_records($class->id, $section->sectionName->id) }}){{ !$loop->last ? ', ' : '' }}
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td>{{ $class->all_incident_sum_point ? $class->all_incident_sum_point : 0 }}
                                                            </td>
                                                            <td>
                                                                <x-drop-down>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('behaviour_records.view_class_section_wise_modal', [$class->id]) }}">@lang('common.view')</a>
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
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
