@push('css')
    <style>
        .QA_table {
            margin-top: 5px !important;
        }
    </style>
@endpush
@if (@$student_records)
    <div class="white-box">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="main-title">
                    <h3 class="mb-15">@lang('hr.teachers_list')</h3>
                </div>
            </div>
            <div class="col-lg-12 student-details up_admin_visitor">
                <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">
                    @foreach ($student_records as $key => $record)
                        <li class="nav-item">
                            <a class="nav-link @if ($key == 0) active @endif "
                                href="#teacherTab{{ $key }}" role="tab"
                                data-toggle="tab">{{ moduleStatusCheck('University') ? $record->unSemesterLabel->name : $record->class->class_name }}
                                ({{ $record->section->section_name }})
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach ($student_records as $key => $record)
                        <div role="tabpanel"
                            class="tab-pane teacher-table fade @if ($key == 0) active show @endif"
                            id="teacherTab{{ $key }}">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="15%">@lang('hr.teacher_name')</th>
                                            <th width="10%">@lang('common.subject')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($record->StudentTeacher as $value)
                                            <tr>
                                                <td>
                                                    <img src="{{ file_exists(@$value->teacher->staff_photo) ? asset(@$value->teacher->staff_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                                                        class="img img-thumbnail" style="width: 60px; height: auto;">
                                                    {{ @$value->teacher != '' ? @$value->teacher->full_name : '' }}
                                                </td>
                                                <td>{{ $value->subject->subject_name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
