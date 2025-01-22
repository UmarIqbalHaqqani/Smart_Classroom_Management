@extends('backEnd.master')
@section('title')
    @lang('behaviourRecords.incidents')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('behaviourRecords.incidents')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('behaviourRecords.dashboard')</a>
                    <a href="#">@lang('behaviourRecords.behaviour_records')</a>
                    <a href="#">@lang('behaviourRecords.incidents')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="white-box">
                    <div class="col-lg-12 text-md-right col-md-6 mb-30-lg col-6 mb-20 p-0">
                        <button class="primary-btn-small-input primary-btn small fix-gr-bg" type="button"
                            data-toggle="modal" data-target="#addIncident">
                            <span class="ti-plus pr-2"></span>
                            @lang('behaviourRecords.add')
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-15">@lang('behaviourRecords.incident_list')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('behaviourRecords.title')</th>
                                            <th>@lang('behaviourRecords.point')</th>
                                            <th width="65%">@lang('behaviourRecords.description')</th>
                                            <th>@lang('behaviourRecords.actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($incidents as $data)
                                            <tr>
                                                <td class="col-lg-2">{{ $data->title }}</td>
                                                <td class="col-lg-2">{{ $data->point }}</td>
                                                <td class="col-lg-6">{{ $data->description }}</td>
                                                <td class="col-lg-2">
                                                    <x-drop-down>
                                                        <a class="dropdown-item" data-toggle="modal"
                                                            data-target="#editIncident{{ @$data->id }}"
                                                            href="#">
                                                            @lang('common.edit')
                                                        </a>
                                                        <a class="dropdown-item" data-toggle="modal"
                                                            data-target="#deleteIncident{{ @$data->id }}"
                                                            href="#">
                                                            @lang('common.delete')
                                                        </a>
                                                    </x-drop-down>
                                                </td>
                                            </tr>

                                            <!-- Start Incident Edit Modal -->
                                            <div class="modal fade admin-query" id="editIncident{{ @$data->id }}">
                                                <div class="modal-dialog modal-dialog-centered large-modal">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('behaviourRecords.edit_incident')</h4>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                        </div>
                                                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'behaviour_records.incident_update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                                        <input type="hidden" name="id" value="{{ @$data->id }}">
                                                        <div class="modal-body">
                                                            <div class="container-fluid">
                                                                <form action="">
                                                                    <div class="row">
                                                                        <div class="col-lg-12">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <div class="primary_input">
                                                                                        <label class="primary_input_label"
                                                                                            for="">@lang('behaviourRecords.title')<span
                                                                                                class="text-danger">
                                                                                                *</span></label>
                                                                                        <input
                                                                                            class="primary_input_field read-only-input form-control"
                                                                                            type="text" name="title"
                                                                                            value="{{ @$data->title }}"
                                                                                            id="title">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-12 mt-25">
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="primary_input">
                                                                                        <label class="primary_input_label"
                                                                                            for="">@lang('behaviourRecords.point')<span
                                                                                                class="text-danger">
                                                                                                *</span></label>
                                                                                        <input
                                                                                            class="primary_input_field read-only-input form-control"
                                                                                            type="text" name="point"
                                                                                            value="{{ @$data->point }}"
                                                                                            id="point">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="primary_input sm_mb_20 mt-30"
                                                                                        style="position: relative; top: 12px;">
                                                                                        <input type="checkbox"
                                                                                            name="editNegativePoint"
                                                                                            id="editNegativePoint{{ @$data->id }}"
                                                                                            class="common-checkbox permission-checkAll"
                                                                                            {{ @$data->point < 0 ? 'checked' : '' }}
                                                                                            value="1">
                                                                                        <label
                                                                                            for="editNegativePoint{{ @$data->id }}">@lang('behaviourRecords.is_this_neagtive_incident')</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-12 mt-25">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <div class="primary_input">
                                                                                        <label class="primary_input_label"
                                                                                            for="">@lang('behaviourRecords.description')</label>
                                                                                        <textarea class="primary_input_field form-control" cols="0" rows="3" name="description" id="description">{{ @$data->description }}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-12 text-center mt-25">
                                                                            <div class="d-flex justify-content-between">
                                                                                <button type="button"
                                                                                    class="primary-btn tr-bg"
                                                                                    data-dismiss="modal">@lang('behaviourRecords.cancel')</button>
                                                                                <button
                                                                                    class="primary-btn fix-gr-bg submit"
                                                                                    id="save_button_query"
                                                                                    type="submit">@lang('behaviourRecords.save')</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        {{ Form::close() }}
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Incident Edit Modal -->
                                            <!-- Start Incident Delete Modal -->
                                            <div class="modal fade admin-query" id="deleteIncident{{ @$data->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('behaviourRecords.delete_incident')</h4>
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
                                                                <a href="{{ route('behaviour_records.incident_delete', [@$data->id]) }}"
                                                                    class="text-light">
                                                                    <button class="primary-btn fix-gr-bg"
                                                                        type="submit">@lang('common.delete')</button>
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Incident Delete Modal -->
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
    </section>

    <!-- Start Incident Add Modal -->
    <div class="modal fade admin-query" id="addIncident">
        <div class="modal-dialog modal-dialog-centered large-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('behaviourRecords.add_incident')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'behaviour_records.incident_create', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('behaviourRecords.title')<span
                                                        class="text-danger"> *</span></label>
                                                <input class="primary_input_field read-only-input form-control"
                                                    type="text" name="title" id="title">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-25">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('behaviourRecords.point')<span
                                                        class="text-danger"> *</span></label>
                                                <input class="primary_input_field read-only-input form-control"
                                                    type="number" min="0" name="point" id="point element_point">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="primary_input sm_mb_20 mt-30"
                                                style="position: relative; top: 12px;">
                                                <input type="checkbox" name="negativePoint" id="negativePoint"
                                                    class="common-checkbox permission-checkAll" value="1">
                                                <label for="negativePoint">@lang('behaviourRecords.is_this_neagtive_incident')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-25">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="primary_input">
                                                <label class="primary_input_label"
                                                    for="">@lang('behaviourRecords.description')</label>
                                                <textarea class="primary_input_field form-control" cols="0" rows="3"
                                                    name="description" id="description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center mt-25">
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="primary-btn tr-bg"
                                            data-dismiss="modal">@lang('behaviourRecords.cancel')</button>
                                        <button class="primary-btn fix-gr-bg submit" id="save_button_query"
                                            type="submit">@lang('behaviourRecords.save')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <!-- End Incident Add Modal -->
@endsection
@include('backEnd.partials.data_table_js')
