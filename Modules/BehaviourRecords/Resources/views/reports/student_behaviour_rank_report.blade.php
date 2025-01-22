@extends('backEnd.master')
@section('title')
    @lang('behaviourRecords.student_behaviour_rank_report')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('behaviourRecords.student_behaviour_rank_report')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('behaviourRecords.dashboard')</a>
                    <a href="#">@lang('behaviourRecords.behaviour_records')</a>
                    <a href="#">@lang('behaviourRecords.student_behaviour_rank_report')</a>
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
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'behaviour_records.student_behaviour_rank_report_search', 'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
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
                                        'div' => 'col-lg-3',
                                        'required' => ['academic'],
                                        'visiable' => ['academic', 'class', 'section'],
                                    ])
                                    <div class="col-lg-2 mt-30-md" id="select_type_div">
                                        <label class="primary_input_label" for="">@lang('behaviourRecords.type')
                                            <span
                                                class="text-danger"> </span></label>
                                        <select class="primary_select " id="select_type" name="type" {{ old('type') ? 'selected' : '' }}>
                                            <option data-display="@lang('behaviourRecords.select_type')" value="">
                                                @lang('behaviourRecords.select_type')</option>
                                            <option value="lesser_than_or_equal">
                                                @lang('behaviourRecords.less_than_or_equal')</option>
                                            <option value="greater_than_or_equal">
                                                @lang('behaviourRecords.great_than_or_equal')</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-1 mt-30-md" id="point_div">
                                        <label class="primary_input_label" for="">@lang('behaviourRecords.point')
                                            <span
                                                class="text-danger"> </span></label>
                                        <input class="primary_input_field" type="number" name="point" autocomplete="off"
                                            value="{{ old('point') }}">
                                    </div>
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
                    @isset($students)
                        <div class="row mt-40">
                            <div class="col-lg-12">
                                <div class="white-box">
                                    <div class="row">
                                        <div class="col-lg-4 no-gutters">
                                            <div class="main-title">
                                                <h3 class="mb-15">@lang('behaviourRecords.student_behaviour_rank_list') </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <x-table>
                                                <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('behaviourRecords.rank')</th>
                                                            <th>@lang('behaviourRecords.admission_no')</th>
                                                            <th>@lang('behaviourRecords.student_name')</th>
                                                            <th>@lang('behaviourRecords.class')(@lang('behaviourRecords.section'))</th>
                                                            <th>@lang('behaviourRecords.gender')</th>
                                                            <th>@lang('behaviourRecords.phone')</th>
                                                            <th>@lang('behaviourRecords.total_points')</th>
                                                            <th>@lang('behaviourRecords.actions')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($students as $key => $data)
                                                            @php
                                                                $incident = 0;
                                                                foreach ($data->incidents as $student_point) {
                                                                    $incident += $student_point->incident->point;
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $data->admission_no }}</td>
                                                                <td>
                                                                    <a target="_blank"
                                                                        href="{{ route('student_view', [$data->id]) }}">{{ $data->first_name }}
                                                                        {{ $data->last_name }}</a>
                                                                </td>
                                                                <td>
                                                                    @foreach ($data->studentRecords as $studentRecord)
                                                                        {{ $studentRecord->class->class_name }}({{ $studentRecord->section->section_name }})
                                                                    @endforeach
                                                                </td>
                                                                <td>{{ $data->gender->base_setup_name }}</td>
                                                                <td>{{ $data->mobile }}</td>
                                                                <td>{{ $data->incidents_sum_point }}</td>
                                                                <td>
                                                                    <x-drop-down>
                                                                        <a class="dropdown-item modalLink"
                                                                            data-modal-size="large-modal"
                                                                            title="Behaviour Rank-{{ $data->full_name }}"
                                                                            href="{{ route('behaviour_records.view_behaviour_rank_modal', [$data->id]) }}">@lang('common.view')</a>
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
@push('script')
    <script>
        $(document).ready(function() {
            $("#point_div").hide();
            $("#select_type").change(function() {
                if ($(this).val() == "lesser_than_or_equal" || $(this).val() == "greater_than_or_equal") {
                    $("#point_div").show();
                } else {
                    $("#point_div").hide();
                }
            });
        })
    </script>
@endpush
