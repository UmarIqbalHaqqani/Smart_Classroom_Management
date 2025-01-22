@extends('backEnd.master')
@section('title')
    @lang('front_settings.expert_staff')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.expert_staff')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.frontend_cms')</a>
                    <a href="{{ route('expert-teacher') }}">@lang('front_settings.expert_staff')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        @if (isset($add_expert_teacher))
                            {{ Form::open([
                                'class' => 'form-horizontal',
                                'files' => true,
                                'route' => 'expert-teacher-update',
                                'method' => 'POST',
                                'enctype' => 'multipart/form-data',
                            ]) }}
                            <input type="hidden" value="{{ @$add_expert_teacher->id }}" name="id">
                        @else
                            @if (userPermission('expert-teacher-store'))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'expert-teacher-store',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                            @endif
                        @endif
                        <div class="white-box">
                        <div class="main-title">
                            <h3 class="mb-15">
                                @if (isset($add_expert_teacher))
                                    @lang('front_settings.edit_expert_staff')
                                @else
                                    @lang('front_settings.add_expert_staff')
                                @endif
                            </h3>
                        </div>
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12 mb-15">
                                        <label class="primary_input_label" for="">@lang('hr.role')</label>
                                        <select
                                            class="primary_select  form-control{{ $errors->has('member_type') ? ' is-invalid' : '' }}"
                                            name="member_type" id="member_type">
                                            <option data-display=" @lang('leave.select_role')" value="">
                                                @lang('leave.select_role')</option>
                                            @foreach ($roles as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('member_type'))
                                            <span class="text-danger">
                                                {{ $errors->first('member_type') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mb-15" id="selectStaffsDiv">
                                        <label class="primary_input_label" for="">@lang('hr.staff') <span
                                                class="text-danger"> *</span></label>
                                        <select
                                            class="primary_select  form-control{{ $errors->has('staff_id') ? ' is-invalid' : '' }}"
                                            name="staff" id="selectStaffs">
                                            <option data-display="@lang('common.name') *" value="">@lang('common.name') *
                                            </option>
                                            @foreach ($expertTeachers as $value)
                                                @if($value->satff)
                                                <option value="{{ $value->satff_id }}">{{ $value->satff->full_name }}
                                                </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12 text-center">
                                        @if(config('app.app_sync'))
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >@lang('common.add')</button></span>
                                        @else
                                            <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                                title="{{ @$tooltip }}">
                                                @if (isset($add_expert_teacher))
                                                    @lang('common.update')
                                                @else
                                                    @lang('common.add')
                                                @endif
                                            </button>
                                        @endif
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
                            <h3 class="mb-15">@lang('front_settings.expert_staff_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table id="table_id" class="table expertTeacher" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('front_settings.name')</th>
                                        <th>@lang('front_settings.designation')</th>
                                        <th>@lang('front_settings.image')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expertTeachers as $key => $value)
                                        <tr e_id="{{ $value->id }}">
                                            <td><span class="mr-2" style="cursor: grab"><i
                                                        class="ti-menu"></i></span>{{ $key + 1 }}</td>
                                            <td>{{ @$value->staff->full_name }}</td>
                                            <td>{{ @$value->staff->designations->title }}</td>
                                            <td><img src="{{ @$value->staff->staff_photo ? asset(@$value->staff->staff_photo) : asset('public/uploads/staff/staff.jpg') }}"
                                                    width="50px" height="50px">
                                            </td>
                                            <td>
                                                <x-drop-down>
                                                    <a href="{{ route('expert-teacher-delete-modal', @$value->id) }}"
                                                        class="dropdown-item small fix-gr-bg modalLink"
                                                        title="@lang('front_settings.delete_expert_staff')" data-modal-size="modal-md">
                                                        @lang('common.delete')
                                                    </a>
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
    </section>
@endsection

@include('backEnd.partials.data_table_js')
@push('script')
    <script type="text/javascript">
        datableArrange('.expertTeacher', 'sm_expert_teachers');
    </script>
@endpush
