@extends('backEnd.master')
@section('title') 
    @lang('communicate.event_list')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('communicate.event_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">@lang('communicate.event_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($editData))
            @if(userPermission("event-store"))
                <div class="row">
                    <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                        <a href="{{route('event')}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('common.add')
                        </a>
                    </div>
                </div>
            @endif
        @endif
        <div class="row">
              <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        @if(isset($editData))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('event-update',$editData->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                            @if(userPermission("event-store"))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'event', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="main-title">
                                <h3 class="mb-15">
                                    @if(isset($editData))
                                        @lang('communicate.edit_event')
                                    @else
                                        @lang('communicate.add_event')
                                    @endif
                                </h3>
                            </div>
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12 mb-15">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('communicate.event_title') <span class="text-danger"> *</span> </label>
                                            <input class="primary_input_field form-control{{ $errors->has('event_title') ? ' is-invalid' : '' }}" type="text" name="event_title" autocomplete="off" value="{{isset($editData)? $editData->event_title : old('event_title') }}">
                                            @error('event_title')
                                                <span class="text-danger" >
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12 mb-15">
                                        <label for="checkbox" class="mb-2">@lang('communicate.role') <span class="text-danger">*</span></label>
                                        <select multiple id="selectMultiUsers" class="multypol_check_select active position-relative" name="role_ids[]" style="width:300px">
                                            
                                           @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{@$editData &&  $editData->role_ids ? (in_array($role->id, json_decode($editData->role_ids))? 'selected' : '') : '' }}>{{ $role->name }}</option>
                                           @endforeach
                                        </select>
                                        @error('role_ids')
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-15">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('communicate.event_location') <span class="text-danger"> *</span> </label>
                                            <input class="primary_input_field form-control{{ $errors->has('event_location') ? ' is-invalid' : '' }}"
                                            type="text" name="event_location" autocomplete="off" value="{{isset($editData)? $editData->event_location : old('event_location') }}">
                                            @error('event_location')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                </div>

                                <div class="row mb-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input ">
                                            <label class="primary_input_label" for="from_date">{{ __('common.from_date') }} <span
                                                    class="text-danger">*</span></label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('from_date') ? ' is-invalid' : '' }}" id="event_from_date" type="text"
                                                            name="from_date" value="{{isset($editData)? date('m/d/Y', strtotime($editData->from_date)): date('m/d/Y')}}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" data-id="#from_date" type="button">
                                                        <label class="m-0 p-0" for="event_from_date">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="text-danger">{{$errors->first('from_date')}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('communicate.to_date')<span class="text-danger"> *</span> </label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('to_date') ? ' is-invalid' : '' }}" id="event_to_date" type="text"
                                                            name="to_date" value="{{isset($editData)? date('m/d/Y', strtotime($editData->to_date)): date('m/d/Y') }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" data-id="#from_date" type="button">
                                                        <label class="m-0 p-0" for="event_to_date">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="text-danger">{{$errors->first('to_date')}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.description') <span class="text-danger"> *</span> </label>
                                            <textarea class="primary_input_field form-control {{ $errors->has('event_des') ? ' is-invalid' : '' }}" cols="0" rows="4" name="event_des">{{isset($editData)? $editData->event_des: old('event_des')}}</textarea>
                                            @error('event_des')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.url')</label>
                                            <textarea class="primary_input_field form-control {{ $errors->has('url') ? ' is-invalid' : '' }}" cols="0" rows="4" name="url">{{isset($editData)? $editData->url: old('url')}}</textarea>
                                            @error('url')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <div class="primary_file_uploader">
                                                <input
                                                    class="primary_input_field form-control {{ $errors->has('upload_file_name') ? ' is-invalid' : '' }}"
                                                    readonly="true" type="text"
                                                    placeholder="{{ isset($editData->upload_file) && @$editData->upload_file != '' ? getFilePath3(@$editData->upload_file) : trans('study.file') . '' }}"
                                                    id="placeholderUploadContent">
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="upload_content_file">{{ __('common.browse') }}</label>
                                                    <input type="file" class="d-none" name="upload_file_name"
                                                        id="upload_content_file">
                                                </button>
                                                <code>(JPG,JPEG,PNG,GIF are allowed for upload)</code>
                                                @error('upload_file_name')
                                                    <span class="text-danger">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                  @php 
                                    $tooltip = "";
                                    if(userPermission("event-store")){
                                        $tooltip = "";
                                    }elseif(userPermission('event-edit') && isset($editData)){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{ @$tooltip}}">
                                            <span class="ti-check"></span>
                                            {{@$editData ? __('communicate.update') : __('communicate.save')}}
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
                                <h3 class="mb-15">@lang('communicate.event_list')</h3>
                            </div>
                        </div>
                    </div>
    
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="eventDataTable" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('common.sl')</th>
                                            <th>@lang('communicate.event_title')</th>
                                            <th>@lang('common.role')</th>
                                            <th>@lang('common.date')</th>
                                            <th>@lang('communicate.location')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')
@include('backEnd.partials.date_picker_css_js')
@include('backEnd.partials.multi_select_js')
@endsection
@push('script')  
    <script>
        $(document).ready(function() {
            $('#eventDataTable').DataTable({
                processing: true,
                serverSide: true,
                "ajax": $.fn.dataTable.pipeline( {
                    url: "{{route('get-all-event-list')}}",
                    pages: "{{generalSetting()->ss_page_load}}"
                } ),
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'name', name: 'name'},
                    {data: 'date', name: 'date'},
                    {data: 'location', name: 'location'},
                    {data: 'action', name: 'action', orderable: false, searchable: true},
                ],
                bLengthChange: false,
                bDestroy: true,
                language: {
                    search: "<i class='ti-search'></i>",
                    searchPlaceholder: window.jsLang('quick_search'),
                    paginate: {
                        next: "<i class='ti-arrow-right'></i>",
                        previous: "<i class='ti-arrow-left'></i>",
                    },
                },
                dom: "Bfrtip",
                buttons: [{
                    extend: "copyHtml5",
                    text: '<i class="fa fa-files-o"></i>',
                    title: $("#logo_title").val(),
                    titleAttr: window.jsLang('copy_table'),
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)'
                    },
                },
                    {
                        extend: "excelHtml5",
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: window.jsLang('export_to_excel'),
                        title: $("#logo_title").val(),
                        margin: [10, 10, 10, 0],
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "csvHtml5",
                        text: '<i class="fa fa-file-text-o"></i>',
                        titleAttr: window.jsLang('export_to_csv'),
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "pdfHtml5",
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        title: $("#logo_title").val(),
                        titleAttr: window.jsLang('export_to_pdf'),
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                        orientation: "landscape",
                        pageSize: "A4",
                        margin: [0, 0, 0, 12],
                        alignment: "center",
                        header: true,
                        customize: function(doc) {
                            doc.content[1].margin = [100, 0, 100, 0]; //left, top, right, bottom
                            doc.content.splice(1, 0, {
                                margin: [0, 0, 0, 12],
                                alignment: "center",
                                image: "data:image/png;base64," + $("#logo_img").val(),
                            });
                            doc.defaultStyle = {
                                font: 'DejaVuSans'
                            }
                        },
                    },
                    {
                        extend: "print",
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: window.jsLang('print'),
                        title: $("#logo_title").val(),
                        exportOptions: {
                            columns: ':visible:not(.not-export-col)'
                        },
                    },
                    {
                        extend: "colvis",
                        text: '<i class="fa fa-columns"></i>',
                        postfixButtons: ["colvisRestore"],
                    },
                ],
                columnDefs: [{
                    visible: false,
                }, ],
                responsive: true,
            });
        } );
    </script>
@endpush