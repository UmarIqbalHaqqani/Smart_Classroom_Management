@extends('backEnd.master')
@push('css')
    <style>
        .white-box.modal-white-box {
            padding: 20px 10px !important;
        }

        button.primary-btn.small.fix-gr-bg.assignViewSave {
            margin-top: 15%;
        }
    </style>
@endpush
@section('title')
    @lang('behaviourRecords.assign_incident')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('behaviourRecords.assign_incident')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('behaviourRecords.dashboard')</a>
                    <a href="#">@lang('behaviourRecords.behaviour_records')</a>
                    <a href="#">@lang('behaviourRecords.assign_incident')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'behaviour_records.assign_incident_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'infix_form']) }}
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box filter_card">
                        <div class="row">
                            <div class="col-lg-8 col-md-6">
                                <div class="main-title">
                                    <h3 class="mb-15">@lang('behaviourRecords.select_criteria') </h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            @if (moduleStatusCheck('University'))
                                @includeIf(
                                    'university::common.session_faculty_depart_academic_semester_level',
                                    ['mt' => 'mt-30', 'hide' => ['USUB'], 'required' => ['USEC']]
                                )
                                <div class="col-lg-4 mt-25">
                                    <div class="primary_input ">
                                        <label class="primary_input_label" for="">@lang('student.search_by_name')</label>
                                        <input class="primary_input_field" type="text" placeholder="Name" name="name"
                                            value="{{ isset($name) ? $name : '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-25">
                                    <div class="primary_input md_mb_20">
                                        <label class="primary_input_label" for="">@lang('student.search_by_roll_no')</label>
                                        <input class="primary_input_field" type="text" placeholder="Roll" name="roll_no"
                                            value="{{ isset($roll_no) ? $roll_no : '' }}">
                                    </div>
                                </div>
                            @else
                                @include('backEnd.common.search_criteria', [
                                    'mt' => 'mt-0',
                                    'div' => 'col-lg-4',
                                    'required' => ['academic'],
                                    'visiable' => ['academic', 'class', 'section'],
                                    'selected' => ['section_id' => @$data['section_id'], 'class_id' => @$data['class_id'], 'academic_year' => @$data['academic_year']],
                                ])
                                <div class="col-lg-4 mt-0">
                                    <div class="primary_input sm_mb_20 ">
                                        <label class="primary_input_label" for="">@lang('student.search_by_name')</label>
                                        <input class="primary_input_field" type="text" placeholder="Name" name="name"
                                            value="{{ isset($name) ? $name : old('name') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-0">
                                    <div class="primary_input sm_mb_20 ">
                                        <label class="primary_input_label" for="">@lang('student.search_by_roll')</label>
                                        <input class="primary_input_field" type="text" placeholder="Roll" name="roll_no"
                                            value="{{ isset($roll_no) ? $roll_no : old('roll_no') }}">
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg" id="btnsubmit">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="academic_id" value="{{ @$academic_year }}">
            <input type="hidden" id="class" value="{{ @$class_id }}">
            <input type="hidden" id="section" value="{{ @$section }}">
            <input type="hidden" id="roll" value="{{ @$roll_no }}">
            <input type="hidden" id="name" value="{{ @$name }}">
            <input type="hidden" id="un_session" value="{{ @$data['un_session_id'] }}">
            <input type="hidden" id="un_academic" value="{{ @$data['un_academic_id'] }}">
            <input type="hidden" id="un_faculty" value="{{ @$data['un_faculty_id'] }}">
            <input type="hidden" id="un_department" value="{{ @$data['un_department_id'] }}">
            <input type="hidden" id="un_semester_label" value="{{ @$data['un_semester_label_id'] }}">
            <input type="hidden" id="un_section" value="{{ @$data['un_section_id'] }}">
            {{ Form::close() }}
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-15">@lang('behaviourRecords.assign_incident_list') </h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('behaviourRecords.admission_no')</th>
                                                <th>@lang('behaviourRecords.student_name')</th>
                                                <th>@lang('behaviourRecords.class')</th>
                                                <th>@lang('behaviourRecords.gender')</th>
                                                <th>@lang('behaviourRecords.phone')</th>
                                                <th>@lang('behaviourRecords.total_points')</th>
                                                <th>@lang('behaviourRecords.total_incidents')</th>
                                                <th>@lang('behaviourRecords.actions')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
    <div class="modal fade admin-query" id="assignViewIncident">
        <div class="modal-dialog modal-dialog-centered large-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('behaviourRecords.assign_view_incident')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12 mb-20">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('behaviourRecords.select_incident') </h3>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-40">
                            <div class="col-lg-9" id="selectIncidentsDiv" style="margin-top: -25px;">
                                <label for="checkbox" class="mb-20 mt-20 primary_input_label">@lang('behaviourRecords.incidents')</label>
                                <select multiple="multiple" id="selectIncidents" name="incident_ids[]"
                                    class="multypol_check_select active position-relative">
                                    @foreach ($incidents as $incident)
                                        <option data-point="{{ $incident->point }}" value="{{ $incident->id }}">
                                            {{ $incident->title }}({{ $incident->point }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 text-right">
                                <input type="hidden" name="assign_view_incident" id="assign_view_incident_id">
                                <input type="hidden" name="record_id" id="record_id">
                                <button class="primary-btn small fix-gr-bg assignViewSave"
                                    type="button">@lang('common.assign')</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('behaviourRecords.assign_incident_list') </h3>
                                </div>
                            </div>
                        </div>
                        <div class="row white-box modal-white-box mt-20">
                            <div class="col-lg-12" id="assign_incident_list">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')
@include('backEnd.partials.multi_select_js')

@push('script')
    <script>
        function assignViewIncident(id) {
            var modal = $('#assignViewIncident');
            var record_id = $('.record' + id).data('record');
            modal.find('input[name=assign_view_incident]').val(id);
            modal.find('input[name=record_id]').val(record_id);
            modal.modal('show');
            getAssignIncident(id);
        }

        function getAssignIncident(studentId) {
            $.ajax({
                type: "POST",
                data: {
                    studentId: studentId
                },
                url: "{{ route('behaviour_records.get_student_incident') }}",
                dataType: "html",
                success: function(response) {
                    $('#assign_incident_list').html(response);
                },
                error: function(error) {
                    toastr.error(error.message, 'Error');
                }
            });
        }
        $(document).ready(function() {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": $.fn.dataTable.pipeline({
                    url: "{{ route('behaviour_records.assign_incident_datatable')}}",
                    data: {
                        academic_year: $('#academic_id').val(),
                        class: $('#class').val(),
                        section: $('#section').val(),
                        roll_no: $('#roll').val(),
                        name: $('#name').val(),
                        un_session_id: $('#un_session').val(),
                        un_academic_id: $('#un_academic').val(),
                        un_faculty_id: $('#un_faculty').val(),
                        un_department_id: $('#un_department').val(),
                        un_semester_label_id: $('#un_semester_label').val(),
                        un_section_id: $('#un_section').val(),
                    },
                    pages: "{{ generalSetting()->ss_page_load }}" // number of pages to cache
                }),
                columns: [{
                        data: 'admission_no',
                        name: 'admission_no'
                    },
                    {
                        data: 'full_name',
                        name: 'sm_students.full_name'
                    },
                    {
                        data: 'class_sec',
                        name: 'class_sec'
                    },
                    {
                        data: 'gender.base_setup_name',
                        name: 'gender.base_setup_name'
                    },
                    {
                        data: 'mobile',
                        name: 'sm_students.mobile'
                    },
                    {
                        data: 'incidents_sum_point',
                        name: 'incidents_sum_point',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'incidents_count',
                        name: 'incidents_count',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
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
        });
        $(document).on('click', '.assignViewSave', function(e) {
            let student_id = $('#assign_view_incident_id').val();
            var incident_id = $('#selectIncidents').val();
            var record_id = $('#record_id').val();
            let data = {
                incident_ids: incident_id,
                student_id: student_id,
                record_id: record_id,
            }
            $.ajax({
                type: "POST",
                data: data,
                url: "{{ route('behaviour_records.assign_incident_save') }}",
                dataType: "json",
                success: function(response) {
                    if ($('#selectIncidents').val() == '') {
                        toastr.error('Empty Submission', 'Error');
                    } else {
                        $('#selectIncidents').multiselect('reset');
                        toastr.success('Operation Successful', 'Success');
                        getAssignIncident(student_id);
                    }
                },
                error: function(error) {
                    toastr.error('Operation Failed', 'Error');
                }
            });
        })

        function assignViewDelete(id) {
            var assign_incident_id = id;
            let student_id = $('#assign_view_incident_id').val();
            var deleteIncidentUrlTemplate = "{{ route('behaviour_records.assign_incident_delete', ['id' => ':id']) }}";
            let url = deleteIncidentUrlTemplate.replace(':id', assign_incident_id);
            $.ajax({
                type: "DELETE",
                data: assign_incident_id,
                url: url ,
                dataType: "json",
                success: function(response) {
                    toastr.success(response.message, 'Success');
                    getAssignIncident(student_id);
                },
                error: function(error) {
                    toastr.error(error.message, 'Error');
                }
            });
        }
    </script>
@endpush
