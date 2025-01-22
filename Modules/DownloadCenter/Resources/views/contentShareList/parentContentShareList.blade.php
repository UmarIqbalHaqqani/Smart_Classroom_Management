@extends('backEnd.master')
@section('title')
    @lang('downloadCenter.content_list')
@endsection
@push('css')
    <style>
        .content-modal-section-title {
            font-size: 18px;
            font-weight: 500;
            line-height: 1.1;
            color: inherit;
            margin-bottom: 10px;
            color: var(--base_color);
        }

        .content-modal-section-title.sidebar {
            margin-bottom: 10px;
        }

        .single-content-modal-info .row {
            row-gap: 10px;
        }

        .single-content-modal-info .content-type {
            color: var(--base_color);
            font-size: 14px;
        }

        ul.content-links li {
            border-top: 1px solid #dad6d6;

        }

        ul.content-links li:first-child {
            border-top: none;
        }

        ul.content-links li a {
            color: #828bb2;
            padding: 5px
        }

        .single-content-modal.modal .modal-dialog {
            max-width: 90%;
        }

        .single-content-modal.modal .modal-dialog .modal-body .row.content-container {
            row-gap: 20px;
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
            color: var(--base_color);
            cursor: pointer;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ $student_detail->full_name }} - @lang('downloadCenter.content_list') </h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('downloadCenter.content_list')</a>
                </div>
            </div>
        </div>
    </section>
    @if (isset($sharedContents))
        <section class="mt-20">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-lg-12 student-details up_admin_visitor">
                        <ul class="nav nav-tabs tabs_scroll_nav ml-0" role="tablist">
                            @foreach ($records as $key => $record)
                                <li class="nav-item">
                                    @if (moduleStatusCheck('University'))
                                        <a class="nav-link @if ($key == 0) active @endif "
                                            href="#tab{{ $key }}" role="tab"
                                            data-toggle="tab">{{ $record->semesterLabel->name }} (
                                            {{ $record->unSemester->name }} - {{ $record->unAcademic->name }} ) </a>
                                    @else
                                        <a class="nav-link @if ($key == 0) active @endif "
                                            href="#tab{{ $key }}" role="tab"
                                            data-toggle="tab">{{ $record->class->class_name }}
                                            ({{ $record->section->section_name }})
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach ($records as $key => $record)
                                <div role="tabpanel"
                                    class="tab-pane fade  @if ($key == 0) active show @endif"
                                    id="tab{{ $key }}">
                                    <div class="container-fluid p-0">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <x-table>
                                                            <table id="table_id" class="table Crm_table_active3"
                                                                cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>@lang('downloadCenter.sl')</th>
                                                                        <th>@lang('downloadCenter.title')</th>
                                                                        <th>@lang('downloadCenter.send_to')</th>
                                                                        <th>@lang('downloadCenter.share_date')</th>
                                                                        <th>@lang('downloadCenter.valid_upto')</th>
                                                                        <th>@lang('downloadCenter.shared_by')</th>
                                                                        <th>@lang('downloadCenter.description')</th>
                                                                        <th>@lang('common.action')</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($sharedContents as $key => $sharedContent)
                                                                        @php
                                                                            $send_type = '';
                                                                            if ($sharedContent->send_type == 'G') {
                                                                                $send_type = 'Group';
                                                                            } elseif ($sharedContent->send_type == 'I') {
                                                                                $send_type = 'Individual';
                                                                            } elseif ($sharedContent->send_type == 'C') {
                                                                                $send_type = 'Class';
                                                                            } else {
                                                                                $send_type = 'Public';
                                                                            }
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $key + 1 }}</td>
                                                                            <td>{{ $sharedContent->title }}</td>
                                                                            <td>{{ $send_type }}</td>
                                                                            <td>{{ $sharedContent->share_date }}</td>
                                                                            <td>{{ $sharedContent->valid_upto }}</td>
                                                                            <td>{{ $sharedContent->user->full_name }}</td>
                                                                            <td>{{ $sharedContent->description ? $sharedContent->description : 'No Description' }}
                                                                            </td>
                                                                            <td>
                                                                                <x-drop-down>
                                                                                    @if ($sharedContent->url)
                                                                                        <a class="modalLink dropdown-item"
                                                                                            title="@lang('downloadCenter.shared_content_link')"
                                                                                            href="{{ route('download-center.content-share-link-modal', [$sharedContent->id]) }}">
                                                                                            @lang('downloadCenter.link')
                                                                                        </a>
                                                                                    @endif
                                                                                    <a class="modalLink dropdown-item"
                                                                                        data-modal-size="large-modal"
                                                                                        title="@lang('downloadCenter.view_shared_content')"
                                                                                        href="{{ route('download-center.content-view-link-modal', [$sharedContent->id]) }}">
                                                                                        @lang('common.view')
                                                                                    </a>
                                                                                </x-drop-down>
                                                                            </td>
                                                                        </tr>
                                                                        {{-- Start Delete Modal --}}
                                                                        <div class="modal fade admin-query"
                                                                            id="deleteModal{{ @$sharedContent->id }}">
                                                                            <div class="modal-dialog modal-dialog-centered">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h4 class="modal-title">
                                                                                            @lang('downloadCenter.delete_shared_content')
                                                                                        </h4>
                                                                                        <button type="button"
                                                                                            class="close"
                                                                                            data-dismiss="modal">
                                                                                            &times;
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="text-center">
                                                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                                                        </div>
                                                                                        <div
                                                                                            class="mt-40 d-flex justify-content-between">
                                                                                            <button type="button"
                                                                                                class="primary-btn tr-bg"
                                                                                                data-dismiss="modal">
                                                                                                @lang('common.cancel')
                                                                                            </button>
                                                                                            <a href="{{ route('download-center.content-share-list-delete', [@$sharedContent->id]) }}"
                                                                                                class="text-light">
                                                                                                <button
                                                                                                    class="primary-btn fix-gr-bg"
                                                                                                    type="submit">
                                                                                                    @lang('common.delete')
                                                                                                </button>
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        {{-- End Delete Modal --}}
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
@include('backEnd.partials.data_table_js')
