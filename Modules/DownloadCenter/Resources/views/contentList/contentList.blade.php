@extends('backEnd.master')
@section('title')
    @lang('downloadCenter.content_list')
@endsection
@push('css')
    <style>
        button#btnsubmit {
            top: 35px;
        }

        .orline {
            margin: 0px auto;
            max-width: 100%;
        }

        .orline {
            position: relative;
        }

        .orline:before {
            content: "";
            position: absolute;
            background: #ccc;
            height: 1px;
            width: 100%;
            top: 10px;
            left: 0;
            right: 0;
            margin-left: auto;
            margin-right: auto;
        }

        .orline span {
            position: relative;
            left: 43%;
            background: white;
        }

        .primary_file_uploader button {
            top: 8px !important;
            right: 10px !important;
        }

        .common-checkbox.video-list~label::before,
        .common-checkbox.video-list~label::after {
            top: 10px !important;
            left: -26px !important;
        }

        .common-checkbox~label::before,
        .common-checkbox~label::after {
            top: 15px !important;
            left: -20px !important;
        }

        .common-checkbox~label {
            padding-left: 15px !important;
        }

        li.attached-content-item {
            border: 1px solid rgba(130, 139, 178, 0.3);
            padding: 10px 15px;
            margin-bottom: -1px;
        }

        li.attached-content-item:first-child {
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }

        li.attached-content-item:last-child {
            margin-bottom: 0;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        li.attached-content-item:hover {
            background-color: #f5f4f4bf;
        }

        .content-table tbody tr td:first-child {
            padding-left: 60px!important;
        }
        .content-table tbody tr td label{
            margin-top: 7px;
        }
        .dt-buttons {
            margin-bottom: -90px !important;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('downloadCenter.content') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('downloadCenter.download_center')</a>
                    <a href="{{ route('download-center.content-list') }}">@lang('downloadCenter.content')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box filter_card">
                        <div class="row">
                            <div class="col-lg-8 col-md-6 col-sm-6">
                                <div class="main-title mt_0_sm mt_0_md">
                                    <h3 class="mb-15">@lang('common.search')</h3>
                                </div>
                            </div>
                        </div>
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'download-center.content-list-search', 'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
                        <div class="col-lg-12">
                            <div class="primary_input sm_mb_20 ">
                                <label class="primary_input_label" for="">
                                    @lang('downloadCenter.name')
                                </label>
                                <input
                                    class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    type="text" placeholder="Name" name="name"
                                    value="{{ isset($name) ? $name : old('name') }}">
                            </div>
                            @if ($errors->has('name'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('name') }}
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-12 text-right mb-30">
                            <button type="submit" class="primary-btn small fix-gr-bg" id="btnsubmit">
                                <span class="ti-search pr-2"></span>
                                @lang('common.search')
                            </button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="row mt-40">
                <div class="col-lg-4 col-xl-3">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (isset($editContent))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'download-center.content-list-update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="content_id" value="{{ @$editContent->id }}">
                            @else
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'download-center.content-list-save', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            <div class="white-box">
                                <div class="main-title">
                                    <h3 class="mb-15">
                                        @if (isset($editContent))
                                            @lang('downloadCenter.edit_content')
                                        @else
                                            @lang('downloadCenter.add_content')
                                        @endif
    
                                    </h3>
                                </div>
                                <div class="add-visitor">
                                    @if (isset($editContent))
                                        <div class="row no-gutters input-right-icon mb-20">
                                            <div class="col-lg-12">
                                                <div class="primary_input">
                                                    <label>
                                                        @lang('downloadCenter.file_name')
                                                        <span class="text-danger"> *</span>
                                                    </label>
                                                    <input
                                                        class="primary_input_field form-control{{ $errors->has('file_name') ? ' is-invalid' : '' }}"
                                                        type="text"
                                                        name="file_name" autocomplete="off"
                                                        value="{{ isset($editContent) ? @$editContent->file_name : '' }}">
                                                </div>
                                                @if ($errors->has('file_name'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('file_name') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-lg-12 mb-30">
                                            <label class="primary_input_label" for="">
                                                {{ __('downloadCenter.content_type') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <select
                                                class="primary_select form-control{{ $errors->has('content_type_id') ? ' is-invalid' : '' }}"
                                                name="content_type_id" id="content_type_id">
                                                <option data-display="@lang('downloadCenter.content_type') *" value="">
                                                    @lang('downloadCenter.content_type')
                                                    *</option>
                                                @foreach (@$contentTpyes as $contentTpye)
                                                    <option value="{{ $contentTpye->id }}"
                                                        {{ isset($editContent) && $editContent->content_type_id == $contentTpye->id ? 'selected' : '' }}>
                                                        {{ $contentTpye->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('content_type_id'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('content_type_id') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($editContent == null)
                                        <div class="row no-gutters input-right-icon mb-20">
                                            <div class="col-lg-12">
                                                <div class="primary_input">
                                                    <label>@lang('downloadCenter.youtube_link')</label>
                                                    <input
                                                        class="primary_input_field form-control{{ $errors->has('youtube_link') ? ' is-invalid' : '' }}"
                                                        type="text"
                                                        name="youtube_link" autocomplete="off"
                                                        value="{{ isset($editContent) ? @$editContent->youtube_link : '' }}">
                                                </div>
                                                @if ($errors->has('youtube_link'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('youtube_link') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="orline">
                                            <span>
                                                @lang('downloadCenter.or')
                                            </span>
                                        </div>

                                        <div class="row mb-20">
                                            <div class="col-lg-12 mt-15">
                                                <div class="primary_input">
                                                    <div class="primary_file_uploader">
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('content_file') ? ' is-invalid' : '' }}"
                                                            readonly="true" type="text"
                                                            placeholder="{{ isset($editContent->upload_file) && @$editContent->upload_file != '' ? getFilePath3(@$editContent->upload_file) : trans('downloadCenter.file') . '' }}"
                                                            id="placeholderUploadContent">
                                                        <button class="" type="button">
                                                            <label class="primary-btn small fix-gr-bg"
                                                                for="upload_content_file">{{ __('common.browse') }}</label>
                                                            <input type="file" class="d-none" name="content_file"
                                                                id="upload_content_file">
                                                        </button>
                                                        <code>(jpg,png,jpeg,pdf,doc,docx,txt,xlsx,rar,zip are allowed for
                                                            upload)</code>
                                                    </div>
                                                </div>
                                                @if ($errors->has('content_file'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('content_file') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row mt-20">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg">
                                                <span class="ti-check"></span>
                                                @if (isset($editContent))
                                                    @lang('common.update')
                                                @else
                                                    @lang('common.save')
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
                <div class="col-lg-8 col-xl-9">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-15">@lang('downloadCenter.content_list')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <a class="primary-btn fix-gr-bg submit mt-0 shareContentData" id="shareContentModal"
                                    href="#">@lang('downloadCenter.share')</a>
                                <a class="primary-btn fix-gr-bg submit mt-0 shareContentData" id="generateUrlModal"
                                    href="#">@lang('downloadCenter.generate_url')</a>
                                <div class="mt-3">
                                <x-table>
                                    <table id="table_id" class="table Crm_table_active3 content-table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('downloadCenter.sl')</th>
                                                <th>@lang('downloadCenter.document')</th>
                                                <th>@lang('downloadCenter.content_type')</th>
                                                <th>@lang('downloadCenter.size')</th>
                                                <th>@lang('downloadCenter.uploaded_by')</th>
                                                <th>@lang('downloadCenter.created_on')</th>
                                                <th>@lang('downloadCenter.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (@$contents as $key => $content)
                                                @php
                                                    $fileSize = '';
                                                    $dataUnit = '';
                                                    if ($content->file_size < 1000000) {
                                                        $fileSize = $content->file_size / 1000;
                                                        $dataUnit = 'KB';
                                                    } else {
                                                        $fileSize = $content->file_size / 1000000;
                                                        $dataUnit = 'MB';
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" id="content{{ $content->id }}"
                                                            class="common-checkbox video-list contentDataCheckbox"
                                                            data-name="{{ $content->file_name }}"
                                                            name="content[]" value="{{ $content->id }}">
                                                        <label for="content{{ $content->id }}"></label>
                                                        {{ $key + 1 }}
                                                    </td>
                                                    <td class="text-break">{{ $content->file_name }}</td>
                                                    <td>{{ $content->contentType->name }}</td>
                                                    <td>{{ number_format($fileSize, 2, '.', ',') }} {{ $dataUnit }}</td>
                                                    <td>{{ $content->user->full_name }}</td>
                                                    <td>{{ $content->created_at }}</td>
                                                    <td>
                                                        <x-drop-down>
                                                            <a class="dropdown-item"
                                                                href="{{ route('download-center.content-list-edit', $content->id) }}">@lang('common.edit')</a>
                                                            <a class="dropdown-item" data-toggle="modal"
                                                                data-target="#deleteContentModal{{ @$content->id }}"
                                                                href="#">@lang('common.delete')</a>
                                                        </x-drop-down>
                                                    </td>
                                                </tr>
                                                <div class="modal fade admin-query"
                                                    id="deleteContentModal{{ @$content->id }}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">
                                                                    @lang('downloadCenter.delete_content')
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    &times;
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                                </div>
                                                                <div class="mt-40 d-flex justify-content-between">
                                                                    <button type="button" class="primary-btn tr-bg"
                                                                        data-dismiss="modal">
                                                                        @lang('common.cancel')
                                                                    </button>
                                                                    <a href="{{ route('download-center.content-list-delete', [@$content->id]) }}"
                                                                        class="text-light">
                                                                        <button class="primary-btn fix-gr-bg" type="submit">
                                                                            @lang('common.delete')
                                                                        </button>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
    <!-- Start Share Content Modal -->
    <div class="modal fade admin-query" id="shareContent">
        <div class="modal-dialog modal-dialog-centered large-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('downloadCenter.share_content')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'download-center.content-share-list-save', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'share-content-form']) }}
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">
                                                @lang('downloadCenter.title')
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input
                                                class="primary_input_field read-only-input form-control form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                type="text"
                                                name="title" id="title">
                                            <span class="text-danger" id="shareTitleError">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('downloadCenter.share_date')<span
                                                    class="text-danger"> *</span></label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input
                                                                class="primary_input_field primary_input_field date form-control"
                                                                id="shareDate" type="text" name="shareDate"
                                                                value="{{ old('shareDate') != '' ? old('shareDate') : date('m/d/Y') }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" data-id="#shareDate" type="button">
                                                        <label class="m-0 p-0" for="shareDate">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="shareDateError">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('downloadCenter.valid_upto')<span
                                                    class="text-danger"> *</span></label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input
                                                                class="primary_input_field primary_input_field date form-control"
                                                                id="validUpto" type="text" name="validUpto"
                                                                value="{{ old('validUpto') != '' ? old('validUpto') : date('m/d/Y') }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" data-id="#validUpto" type="button">
                                                        <label class="m-0 p-0" for="validUpto">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="validUptoDateError">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('downloadCenter.description')</label>
                                            <textarea class="primary_input_field form-control" cols="0" rows="3"
                                                name="description" id="description"></textarea>
                                            <span class="text-danger" id="descriptionError">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12 contentShowList">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row student-details mt_0_sm">
                                    <div class="col-lg-12">
                                        <label class="primary_input_label" for="">
                                            @lang('downloadCenter.send_to')
                                            <span class="text-danger"> *</span>
                                        </label>
                                        <ul class="nav nav-tabs mt_0_sm" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#group_video" selectTab="G"
                                                    role="tab"
                                                    data-toggle="tab">@lang('communicate.group')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" selectTab="I" href="#indivitual_video"
                                                    role="tab"
                                                    data-toggle="tab">@lang('communicate.individual')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" selectTab="C" href="#class_section_video"
                                                    role="tab" data-toggle="tab">
                                                   @if (moduleStatusCheck('University'))
                                                   @lang('university::un.semester')
                                                    @else 
                                                       @lang('common.class')
                                                   @endif 
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            <input type="hidden" name="selectTab" id="selectTab" value="G">
                                            <div role="tabpanel" class="tab-pane fade show active mt-2"
                                                id="group_video">
                                                <div class="white-box">
                                                    <div class="col-lg-12">
                                                        @foreach (@$roles as $role)
                                                            <div class="">
                                                                <input type="checkbox" id="role_{{ @$role->id }}"
                                                                    class="common-checkbox" value="{{ @$role->id }}"
                                                                    name="role[]">
                                                                <label
                                                                    for="role_{{ @$role->id }}">{{ @$role->name }}</label>
                                                            </div>
                                                        @endforeach
                                                        @if ($errors->has('role'))
                                                            <span class="text-danger invalid-select" role="alert">
                                                                {{ $errors->first('role') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="groupError">
                                                </span>
                                            </div>

                                            <div role="tabpanel" class="tab-pane fade mt-2" id="indivitual_video">
                                                <div class="white-box">
                                                    <div class="row mb-15">
                                                        <div class="col-lg-12">
                                                            <select
                                                                class="primary_select  form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}"
                                                                name="role_id" id="userByRoleContent">
                                                                <option data-display="@lang('communicate.role')"
                                                                    value="">
                                                                    @lang('communicate.role')</option>
                                                                @foreach ($roles as $value)
                                                                    @if (isset($editData))
                                                                        <option value="{{ @$value->id }}"
                                                                            {{ @$value->id == @$editData->role_id ? 'selected' : '' }}>
                                                                            {{ @$value->name }}</option>
                                                                    @else
                                                                        <option value="{{ @$value->id }}"
                                                                            {{ old($value->id) ? 'selected' : '' }}>
                                                                            {{ @$value->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                            @if ($errors->has('leave_type'))
                                                                <span class="text-danger invalid-select" role="alert">
                                                                    {{ $errors->first('leave_type') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-12 mt-15" id="selectStaffsDiv">
                                                            <label for="checkbox"
                                                                class="mb-2">@lang('common.name')</label>
                                                            <select multiple="multiple"
                                                                class="multypol_check_select active position-relative"
                                                                id="individualContentUser"
                                                                name="individual_content_user[]"
                                                                style="width:300px">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="individualError">
                                                </span>
                                            </div>

                                            <div role="tabpanel" class="tab-pane fade mt-2" id="class_section_video">
                                                <div class="white-box">
                                                    <div class="row mb-35">
                                                        @if (moduleStatusCheck('University'))
                                                           <div class="col-lg-12">
                                                            @includeIf('university::common.session_faculty_depart_academic_semester_level',['required' => ['USN','UF', 'UD', 'US', 'USL'] , 'hide' => ['USUB','UA'],'row' => 1, 'div' => 'col-lg-12', 'mt' =>'mt-0'])
                                                           </div>
                                                        @else 
                                                        <div class="col-lg-12">
                                                            <select
                                                                class="primary_select  form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}"
                                                                name="class_id" id="class_id_email_sms">
                                                                <option data-display="@lang('common.class')  *"
                                                                    value="">@lang('common.class') *</option>
                                                                @if (isset($classes))
                                                                    @foreach ($classes as $value)
                                                                        <option value="{{ @$value->id }}">
                                                                            {{ @$value->class_name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            @if ($errors->has('leave_type'))
                                                                <span class="text-danger invalid-select" role="alert">
                                                                    {{ $errors->first('leave_type') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-12 mt-30" id="selectSectionsDiv">
                                                            <label for="checkbox"
                                                                class="mb-2">@lang('common.section') <span class="text-danger">*</span></label>
                                                            <select multiple
                                                                class="multypol_check_select active position-relative"
                                                                id="selectMultiSections" name="section_ids[]"
                                                                style="width:300px"></select>
                                                        </div>
                                                        @endif 
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="classError">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 text-center mt-40">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg"
                                        data-dismiss="modal">@lang('downloadCenter.cancel')</button>
                                    <button class="primary-btn fix-gr-bg submit" id="save_button_qu"
                                        type="submit">@lang('downloadCenter.share')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <!-- End Share Content Modal -->

    <!-- Start Generate Url Modal -->
    <div class="modal fade admin-query" id="generateUrl">
        <div class="modal-dialog modal-dialog-centered large-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('downloadCenter.generate_url')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                {{ Form::open(['class' => 'form-horizontal', 'id' => 'generate-url-form', 'files' => true, 'route' => 'download-center.content-generate-url-save', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('downloadCenter.title')<span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field read-only-input form-control form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                type="text"
                                                name="title" id="title">
                                            <span class="text-danger" id="generateTitleError">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('downloadCenter.share_date')<span
                                                    class="text-danger"> *</span></label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input
                                                                class="primary_input_field primary_input_field date form-control"
                                                                id="shareDateUrl" type="text" name="shareDate"
                                                                value="{{ old('shareDate') != '' ? old('shareDate') : date('m/d/Y') }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" data-id="#shareDateUrl" type="button">
                                                        <label class="m-0 p-0" for="shareDateUrl">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="generateShareDateError">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('downloadCenter.valid_upto')<span
                                                    class="text-danger"> *</span></label>
                                            <div class="primary_datepicker_input">
                                                <div class="no-gutters input-right-icon">
                                                    <div class="col">
                                                        <div class="">
                                                            <input
                                                                class="primary_input_field primary_input_field date form-control"
                                                                id="validUptoUrl" type="text" name="validUpto"
                                                                value="{{ old('validUpto') != '' ? old('validUpto') : date('m/d/Y') }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <button class="btn-date" data-id="#validUptoUrl" type="button">
                                                        <label class="m-0 p-0" for="validUptoUrl">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="generateValidUptoError">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12 contentShowList">

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 text-center mt-40">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg"
                                        data-dismiss="modal">@lang('downloadCenter.cancel')</button>
                                    <button class="primary-btn fix-gr-bg submit" id="save_button_q"
                                        type="submit">@lang('downloadCenter.share')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <!-- End Generate Url Modal -->
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.multi_select_js')
@include('backEnd.partials.date_picker_css_js')
@push('script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.shareContentData', function(e) {
                e.preventDefault();
                var selectedData = [];
                $(".contentDataCheckbox:checked").each(function() {
                    selectedData[$(this).val()] = $(this).data('name');
                });
                if (selectedData.length == 0) {
                    $('#shareContent').modal('hide');
                    $('#generateUrl').modal('hide');
                    toastr.error("No content selected", "Error", {
                        timeOut: 5000,
                    });
                    console.log('hide');
                    return;
                }
                console.log(selectedData);
                $('.contentShowList').html('');
                if ($(this).attr('id') == 'shareContentModal') {
                    $('#shareContent').modal('show');
                } else if ($(this).attr('id') == 'generateUrlModal') {
                    $('#generateUrl').modal('show');
                }
                console.log('show');
                $.each(selectedData, function(i, value) {
                    if (value != undefined) {
                        $('.contentShowList').append(`
                                <div class="attached-content">
                                    <ul class="attached-content-list">
                                        <li class="attached-content-item">
                                            ${value}
                                        </li>
                                    </ul>
                                </div>
                                <input type="hidden" name="content_ids[]" value="${i}">
                            `)
                    }
                })
            });
        });
        $(document).ready(function() {
            $("#userByRoleContent").on("change", function() {
                $("#checkbox").prop("checked", false);
                var url = $("#url").val();

                var formData = {
                    id: $(this).val(),
                };
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "studStaffByRole",
                    success: function(data) {

                        $("#individualContentUser option").remove();
                        if (formData.id == 3) {
                            $.each(data, function(i, item) {
                                if (item.length) {
                                    $.each(item, function(i, parents) {
                                        $("#individualContentUser").append(
                                            $("<option>", {
                                                value: parents.user_id,
                                                text: parents
                                                    .fathers_name,
                                            })
                                        );
                                    });
                                }
                            });
                            $('#individualContentUser').multiselect('reset');
                        }

                        if (formData.id == 2) {
                            $.each(data, function(i, item) {
                                if (item.length) {
                                    $.each(item, function(i, students) {
                                        $("#individualContentUser").append(
                                            $("<option>", {
                                                value: students.user_id,
                                                text: students
                                                    .full_name,
                                            })
                                        );
                                    });

                                }
                            });
                            $('#individualContentUser').multiselect('reset');
                        }

                        if (formData.id != 2 && formData.id != 3) {
                            $.each(data, function(i, item) {
                                if (item.length) {
                                    $.each(item, function(i, staffs) {
                                        $("#individualContentUser").append(
                                            $("<option>", {
                                                value: staffs.user_id,
                                                text: staffs.full_name,
                                            })
                                        );
                                    });

                                }
                            });
                            $('#individualContentUser').multiselect('reset');
                        }

                    },
                    error: function(data) {
                        console.log("Error:", data);
                    },
                });
            });
        });
    </script>
    <script>
        $(document).on('submit', '#share-content-form', function(e) {
            e.preventDefault();
            let shareContent = $(this);
            const submit_url = shareContent.attr('action');
            const method = shareContent.attr('method');
            const formData = new FormData(shareContent[0]);
            let submitButton = $('#save_button_qu');
            submitButton.prop('disabled', false);

            $.ajax({
                url: submit_url,
                type: method,
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function(response) {
                    location.reload();
                    toastr.success("Save Successfully", "Successful", {
                        timeOut: 5000,
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON.errors.title);
                    $('#shareTitleError').html(xhr.responseJSON.errors.title);
                    $('#validUptoDateError').html(xhr.responseJSON.errors.validUpto);
                    $('#groupError').html(xhr.responseJSON.errors.role);
                    $('#individualError').html(xhr.responseJSON.errors.role_id);
                    $('#classError').html(xhr.responseJSON.errors.class_id);
                    submitButton.prop('disabled', false);
                }
            });
        });
    </script>
    <script>
        $(document).on('submit', '#generate-url-form', function(e) {
            e.preventDefault();
            $('#generateTitleError').html('');
            $('#generateValidUptoError').html('');

            let submitButton = $('#save_button_q');

            submitButton.prop('disabled', false);

            let shareContent = $(this);
            const submit_url = shareContent.attr('action');
            const method = shareContent.attr('method');
            const formData = new FormData(shareContent[0]);

            $.ajax({
                url: submit_url,
                type: method,
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'JSON',
                success: function(response) {
                    location.reload();
                    toastr.success("Save Successfully", "Successful", {
                        timeOut: 5000,
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON.errors.title);
                    $('#generateTitleError').html(xhr.responseJSON.errors.title);
                    $('#generateValidUptoError').html(xhr.responseJSON.errors.validUpto);

                    // Enable the submit button on error
                    submitButton.prop('disabled', false);
                }
            });
        });
    </script>
@endpush
