@extends('backEnd.master')
@section('title')
    @lang('library.issued_Book_List')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('library.issued_Book_List')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('library.library')</a>
                    <a href="#">@lang('library.issued_Book_List')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-8 col-md-6">
                                <div class="main-title">
                                    <h3 class="mb-15 ">@lang('common.select_criteria')</h3>
                                </div>
                            </div>
                            <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg">
                                <a href="{{ route('addStaff') }}" class="primary-btn small fix-gr-bg">
                                </a>
                            </div>
                        </div>
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'search-issued-book', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <input type="hidden" id="book_id" name="book_id" value="{{@$book_id}}">
                            <input type="hidden" id="book_number" name="book_number" value="{{@$book_number}}">
                            <input type="hidden" id="subject_id" name="subject_id" value="{{@$subject_id}}">
                            <div class="col-lg-4">
                                <label class="primary_input_label" for="">@lang('library.book')</label>
                                <select class="primary_select  form-control" name="book_id" id="book_id">
                                    <option data-display="@lang('library.select_Book_Name')" value="">@lang('common.select') </option>
                                    @foreach ($books as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($book_id) ? ($book_id == $value->id ? 'selected' : '') : '' }}>
                                            {{ $value->book_title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mt-30-md">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">@lang('library.search_By_Book_ID')</label>
                                    <input class="primary_input_field" type="text" name="book_number"
                                        value="{{ isset($book_number) ? $book_number : '' }}">


                                </div>
                            </div>
                            <div class="col-lg-4 mt-30-md">
                                <label class="primary_input_label" for="">@lang('common.subject')</label>
                                <select class="primary_select  form-control" name="subject_id" id="subject_id">
                                    <option data-display="@lang('common.select_subjects')" value="">@lang('common.select') </option>
                                    @foreach ($subjects as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($subject_id) ? ($subject_id == $value->id ? 'selected' : '') : '' }}>
                                            {{ $value->subject_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-15">@lang('library.all_issued_book')</h3>
                                </div>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table id="table_id" class="table data-table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('library.book_title')</th>
                                                <th>@lang('library.book_no')</th>
                                                <th>@lang('library.isbn_no')</th>
                                                <th>@lang('library.member_name')</th>
                                                <th>@lang('library.author')</th>
                                                <th>@lang('library.subject')</th>
                                                <th>@lang('library.issue_date')</th>
                                                <th>@lang('library.return_date')</th>
                                                <th>@lang('common.status')</th>
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
@endsection

@include('backEnd.partials.data_table_js')
@include('backEnd.partials.server_side_datatable')
@push('script')  

<script>
   $(document).ready(function() {
       $('.data-table').DataTable({
                     processing: true,
                     serverSide: true,
                     "ajax": $.fn.dataTable.pipeline( {
                           url: "{{route('all-issed-book-ajax')}}",
                           data: { 
                               subject_id : $("#subject_id").val(), 
                               book_number : $("#book_number").val(), 
                               book_id : $("#book_id").val(), 
                            },
                           pages: "{{generalSetting()->ss_page_load}}" // number of pages to cache
                           
                       } ),
                       columns: [
                          
                            {data: 'books.book_title', name: 'books.book_title'},
                            {data: 'books.book_number', name: 'books.book_number'},
                            {data: 'books.isbn_no', name: 'books.isbn_no'},
                           {data: 'user.full_name', name: 'user.full_name'},
                           {data: 'books.author_name', name: 'books.author_name'},
                           {data: 'books.book_subject.subject_name', name: 'books.bookSubject.subject_name'},
                           {data: 'given_date', name: 'given_date'},
                           {data: 'due_date', name: 'due_date'},
                           {data: 'issue_status', name: 'issue_status', orderable: false, searchable: true},
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
<script>
    function deleteHomeWork(id){
        var modal = $('#deleteHomeWorkModal');
        modal.find('input[name=id]').val(id)
        modal.modal('show');
    }
</script>
@endpush
