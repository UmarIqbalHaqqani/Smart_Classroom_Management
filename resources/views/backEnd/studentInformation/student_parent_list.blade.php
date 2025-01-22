@extends('backEnd.master')
@section('title')
    @lang('student.parent_list')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('student.parent_list')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('student.dashboard')</a>
                    <a href="#">@lang('student.student_information')</a>
                    <a href="{{ route('parent-list') }}">@lang('student.parent_list')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-20">
                <div class="col-lg-12 student-details up_admin_visitor">
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('student.select_criteria') </h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'parent-list-search', 'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
                            <div class="white-box">
                                <div class="row">
                                    @include('backEnd.common.search_criteria', [
                                        'div' => 'col-lg-3',
                                        'visiable' => ['class', 'section'],
                                    ])
                                    <div class="col-lg-3 mt-30-md">
                                        <label class="primary_input_label" for="">@lang('student.parent_name')</label>
                                        <input class="primary_input_field" type="text" name="parent_name">
                                    </div>
                                    <div class="col-lg-3 mt-30-md">
                                        <label class="primary_input_label" for="">@lang('student.student_name')</label>
                                        <input class="primary_input_field" type="text" name="student_name">
                                    </div>
                                    <div class="col-lg-12 mt-20 text-right">
                                        <button type="submit" class="primary-btn small fix-gr-bg">
                                            <span class="ti-search pr-2"></span>
                                            @lang('common.search')
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <div class="row mt-40">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4 no-gutters">
                                    <div class="main-title">
                                        <h3 class="mb-0">@lang('student.parent_list') </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <x-table>
                                        <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>@lang('common.sl')</th>
                                                    <th>@lang('student.parent_name')</th>
                                                    <th>@lang('student.child_name')</th>
                                                    <th>@lang('student.class')(@lang('student.section'))</th>
                                                    <th>@lang('common.email')</th>
                                                    <th>@lang('common.phone')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (@$parents as $key => $data)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            {{ @$data->parents->guardians_name ? @$data->parents->guardians_name : @$data->parents->fathers_name }}
                                                        </td>
                                                        <td>
                                                            <a
                                                                target="_blank"href="{{ route('student_view', [@$data->id]) }}">
                                                                {{ @$data->full_name }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            {{ $data->studentRecord->class->class_name }}({{ $data->studentRecord->section->section_name }})
                                                        </td>
                                                        <td>{{ @$data->parents->guardians_email }}</td>
                                                        <td>
                                                            {{ @$data->parents->guardians_mobile ? @$data->parents->guardians_mobile : @$data->parents->fathers_mobile }}
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
    </section>
@endsection
@include('backEnd.partials.data_table_js')
