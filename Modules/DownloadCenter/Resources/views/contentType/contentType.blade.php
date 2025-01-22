@extends('backEnd.master')
@section('title')
    @lang('downloadCenter.content_type')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('downloadCenter.content_type') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('downloadCenter.download_center')</a>
                    <a href="#">@lang('downloadCenter.content_type')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (isset($type))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'download-center.content-type-update', 'method' => 'POST']) }}
                            @else
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'download-center.content-type-save', 'method' => 'POST']) }}
                            @endif
                            <div class="white-box">
                                <div class="main-title">
                                    <h3 class="mb-15">
                                        @if (isset($type))
                                            @lang('downloadCenter.edit_type')
                                        @else
                                            @lang('downloadCenter.add_type')
                                        @endif
                                    </h3>
                                </div>
                                <div class="add-visitor">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label for="name">@lang('downloadCenter.name') <span
                                                        class="text-danger">*</span></label>
                                                <input
                                                    class="primary_input_field form-control{{ @$errors->has('name') ? ' is-invalid' : '' }}"
                                                    type="text" name="name" autocomplete="off" id="name"
                                                    value="{{ isset($type) ? $type->name : old('type') }}">
                                                <input type="hidden" name="type_id"
                                                    value="{{ isset($type) ? $type->id : '' }}">
                                                @if ($errors->has('name'))
                                                    <span class="text-danger">{{ @$errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-10">
                                            <div class="primary_input">
                                                <label for="description">@lang('downloadCenter.description')</label>
                                                <textarea class="primary_input_field form-control" cols="0" rows="3" name="description" id="description">{{ isset($type) ? $type->description : old('description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-20">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit">
                                                <span class="ti-check"></span>
                                                @if (isset($type))
                                                    @lang('downloadCenter.update')
                                                @else
                                                    @lang('downloadCenter.save')
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-15">@lang('downloadCenter.content_type_list')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table id="table_id" class="table Crm_table_active3" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('downloadCenter.name')</th>
                                                <th>@lang('downloadCenter.description')</th>
                                                <th>@lang('common.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($types)
                                                @foreach ($types as $type)
                                                    <tr>
                                                        <td>{{ $type->name }}</td>
                                                        <td>{{ $type->description }}</td>
                                                        <td>
                                                            <x-drop-down>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('download-center.content-type-edit', $type->id) }}">@lang('common.edit')</a>
                                                                <a class="dropdown-item" data-toggle="modal"
                                                                    data-target="#deleteTypeModal{{ @$type->id }}"
                                                                    href="#">@lang('common.delete')</a>
                                                            </x-drop-down>
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade admin-query"
                                                        id="deleteTypeModal{{ @$type->id }}">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">@lang('downloadCenter.delete_type')</h4>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="text-center">
                                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                                    </div>
                                                                    <div class="mt-40 d-flex justify-content-between">
                                                                        <button type="button" class="primary-btn tr-bg"
                                                                            data-dismiss="modal">@lang('common.cancel')</button>
                                                                        <a href="{{ route('download-center.content-type-delete', [@$type->id]) }}"
                                                                            class="text-light">
                                                                            <button class="primary-btn fix-gr-bg"
                                                                                type="submit">@lang('common.delete')</button>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </x-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
