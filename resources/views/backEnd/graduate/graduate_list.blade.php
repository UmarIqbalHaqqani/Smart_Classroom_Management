@extends('backEnd.master')
    @section('title')
        @lang('student.graduate_list')
    @endsection
    <style>
        table.dataTable thead th {
            padding-left: 40px !important;
        }
        table.dataTable thead .sorting::after {
            content: '\e62a';
            font-family: 'themify';
            position: absolute;
            top: 14px !important;
            -webkit-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
        }
    </style>
@section('mainContent')
    <section class="sms-breadcrumb mb-40">
        <div class="container-fluid">
            <div class="row justify-content-between white-box">
                <h1>@lang('student.graduate_list')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('student.graduate_list')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="white-box">
            <div class="row">
                  <div class="col-lg-4 col-md-6">
                      <div class="main-title">
                          <h3 class="mb-30">@lang('common.select_criteria')</h3>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-12">
                      @if(session()->has('message-success') != "")
                          @if(session()->has('message-success'))
                          <div class="alert alert-success">
                              {{ session()->get('message-success') }}
                          </div>
                          @endif
                      @endif
                      <div>
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'alumni.graduate-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'parent-registration']) }}
                                <div class="row">
                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                        @if(moduleStatusCheck('University'))
                                            @includeIf('university::common.session_faculty_depart_academic_semester_level',  ['hide'=>['USUB','UA','US','USL','USEC'],'required'=> ['US'],'div'=>'col-lg-4'])
                                        @else
                                            @include('backEnd.common.search_criteria', [
                                                'div' => 'col-lg-3',
                                                'visiable' => ['academic', 'class', 'section',],
                                            ])
                                            <div class="col-lg-3 mt-0">
                                                <div class="primary_input sm_mb_20 ">
                                                    <label class="primary_input_label" for="">@lang('student.search_by_name')</label>
                                                    <input class="primary_input_field" type="text" placeholder="Name" name="name"
                                                        value="{{ isset($name) ? $name : old('name') }}">
                                                </div>
                                            </div>
                                        @endif
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" id="academic_id" value="{{ @$academic_year }}">
                            <input type="hidden" id="class" value="{{ @$class_id }}">
                            <input type="hidden" id="section" value="{{ @$section }}">
                            <input type="hidden" id="name" value="{{ @$name }}">
                            <input type="hidden" id="un_session" value="{{ @$data['un_session_id'] }}">
                            <input type="hidden" id="un_academic" value="{{ @$data['un_academic_id'] }}">
                            <input type="hidden" id="un_faculty" value="{{ @$data['un_faculty_id'] }}">
                            <input type="hidden" id="un_department" value="{{ @$data['un_department_id'] }}">
                            <input type="hidden" id="un_semester_label" value="{{ @$data['un_semester_label_id'] }}">
                            <input type="hidden" id="un_section" value="{{ @$data['un_section_id'] }}">
                        {{ Form::close() }}
                    </div>
                  </div>
                </div>
              </div>
              @if(( $graduates)) 
                <div class="row mt-40 white-box">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('student.graduate_list')</h3>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table class="school-table school-table-data data-table" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('student.admission_no')</th>
                                                <th>@lang('common.name')</th>
                                                @if (moduleStatusCheck('University'))
                                                    <th>@lang('university::un.session')</th>
                                                    <th>@lang('student.fac_dept')</th>
                                                @else
                                                    <th>@lang('admin.class_Sec')</th>
                                                @endif
                                                <th>@lang('common.gender')</th>
                                                <th>@lang('common.mobile')</th>
                                                <th>@lang('student.date_of_birth')</th>
                                                <th>@lang('common.action')</th>
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
            @endif 
        </div>

        <div class="modal fade admin-query" id="delete_university_department_modal" >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('university::un.delete_department')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="text-center">
                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                            <h5 class="text-danger">( @lang('university::un.department_delete_confirmation') )</h5>
                        </div>

                        <div class="mt-40 d-flex justify-content-between">
                            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                            {{ Form::open(['url' => '#', 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
@include('backEnd.partials.server_side_datatable')

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": $.fn.dataTable.pipeline({
                    url: "{{ url('graduates/graduate-search-datatable') }}",
                    data: {
                        academic_year: $('#academic_id').val(),
                        class: $('#class').val(),
                        section: $('#section').val(),
                        name: $('#name').val(),
                        un_session_id: $('#un_session').val(),
                        un_academic_id: $('#un_academic').val(),
                        un_faculty_id: $('#un_faculty').val(),
                        un_department_id: $('#un_department').val(),
                        un_semester_label_id: $('#un_semester_label').val(),
                        un_section_id: $('#un_section').val(),
                    },
                    pages: "{{ generalSetting()->ss_page_load }}"
                }),
                columns: [{
                        data: 'admission_no',
                        name: 'admission_no'
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        searchable: true,
                    },
                    {
                        data: 'class_sec',
                        name: 'class_sec',
                        searchable:true,
                    },
                    {
                        data: 'gender',
                        name: 'gender',
                        orderable: false,
                    },{
                        data: 'mobile',
                        name: 'mobile',
                        orderable: false,

                    },
                    {
                        data: 'date_of_birth',
                        name: 'date_of_birth'
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
    </script>
    <script>
        $(document).on('click', '.delete_university_department_modal', function(){
            let href = $(this).attr('href');
            $('#delete_university_department_modal').find('form').attr('action', href);
        })
    </script>
@endpush
