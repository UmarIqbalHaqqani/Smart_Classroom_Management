@extends(config('pagebuilder.layout'))

@push(config('pagebuilder.style_var'))
    <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/feather-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/pagebuilder/css/pages.css') }}">
    <style>
        .tb-switchbtn input::before {
            top: -1.5px !important;
        }

        .tb-checkaction,
        .tb-checkactionhome {
            background-color: #eceef4 !important;
            border: 0 !important;
            height: 20px !important;
        }

        .tb-switchbtn input::before {
            background: #ffffff !important;
            height: 16px !important;
            width: 16px !important;
            left: 2px !important;
            top: 2px !important;
            transition: 0.4s all ease-in-out !important;

        }

        .tb-switchbtn input:checked::before {
            background: linear-gradient(90deg, #7c32ff 0%, #a235ec 70%, #c738d8 100%) !important;
            right: 2px !important;
            left: auto !important;
        }

        .QA_section .QA_table .page-list-table thead tr th {
            padding-left: 30px !important;
        }

        .QA_section .QA_table .page-list-table thead tr th:nth-child(2) {
            padding-left: 0 !important;
        }

        .QA_section .QA_table.mt-30 {
            margin-top: 0 !important;
        }

        .tb-dhb-mainheading {
            padding-bottom: 0;
        }

        .page-list-table thead tr th:first-child {
            border-radius: 10px 0px 0 0 !important;
        }

        .page-list-table thead tr th:last-child {
            border-radius: 0px 10px 0 0 !important;
        }

        .page-list-table tbody tr:last-child td {
            border-bottom: 0;
        }
        .tk-skeletonwrap{
            filter: none;
        }
    </style>
@endpush
@extends('backEnd.master')
@section('title')
    {{ __('front_settings.page_list') }}
@endsection
@section(config('pagebuilder.section'))
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{ __('front_settings.page_list') }}</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.frontend_cms')</a>
                    <a href="#">{{ __('front_settings.page_list') }}</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4" id="add_edit_section">
                    <div class="row">
                        <div class="col-lg-12">
                            @component('pagebuilder::components.update-page', ['edit' => $edit, 'page' => $page ?? null])
                            @endcomponent
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="white-box">
                        <div class="tb-dhb-mainheading">
                            <div class="main-title">
                                <h3 class="mb-15">
                                    {{ __('front_settings.page_list') }}
                                </h3>
                            </div>
                        </div>
                        <div class="tb-disputetable" id="pages_list">
                            @component('pagebuilder::components.pages-skeleton')
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
            <div id="skeleton" class="pb-d-none">
                @component('pagebuilder::components.pages-skeleton')
                @endcomponent
            </div>
        </div>
    </section>
    {{-- delete modal start --}}
    <div class="modal fade admin-query" id="deletePageModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('common.delete')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                        <form id="page_form_delete_page">
                            <input type="hidden" name="pageIdForPageDelete" value="">
                            <button class="primary-btn fix-gr-bg" id="aoraPageDelete" type="submit">@lang('common.delete')</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- delete modal end --}}
@endsection

@include('backEnd.partials.data_table_js')
@push(config('pagebuilder.script_var'))
    <script>
        jQuery(".pb-preloader-outer").delay(100).fadeOut();

        jQuery(window).on("load", function() {
            getPages();
        });
        var status = 'draft';

        function getPages(page = 1) {
            $.ajax({
                type: 'GET',
                url: '{{ route('pagebuilder') }}',
                data: {
                    'page': page,
                    'sort': $('#filter_sort').val(),
                    'per_page': $('#filter_per_page').val(),
                    'search': $('#filter_search').val()
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        $('#pages_list').html(data.html);
                    }
                }
            });
        }

        function getPages(page = 1) {
            $.ajax({
                type: 'GET',
                url: '{{ route('pagebuilder') }}',
                data: {
                    'page': page,
                    'sort': $('#filter_sort').val(),
                    'per_page': $('#filter_per_page').val(),
                    'search': $('#filter_search').val()
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        $('#pages_list').html(data.html);
                    }
                }
            });
        }

        function printErrorMsg(msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display', 'block');
            $.each(msg, function(key, value) {
                $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
            });
        }

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
        });

        $(document).on('keyup', '#name', function() {
            let value = $(this).val();
            createSlug(value, '#slug');
        });

        $(document).on('click', '.goto-page', function() {
            getPages($(this).data('page'));
        });

        $(document).on('change', '#filter_sort', function() {
            getPages();
        });

        $(document).on('change', '#filter_per_page', function() {
            getPages();
        });

        let keyupTimer;
        $("#filter_search").keyup(function() {
            clearTimeout(keyupTimer);
            keyupTimer = setTimeout(function() {
                getPages();
            }, 300);
        });

        $(document).on('click', '#page_edit_btn', function() {
            var url = '{{ route('page.edit', 'page_id') }}';
            url = url.replace('page_id', $(this).data('page-id'))

            $.ajax({
                type: 'GET',
                url: url,
                data: {},
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        status = data.status;
                        $('#add_edit_section').html(data.html)
                    }
                }
            });
        });

        $(document).on('click', '.tb-checkaction', function(event) {
            let _this = $(this);
            if (_this.is(':checked')) {
                _this.parent().find('#tb-textdes').html("{{ __('pagebuilder::pagebuilder.active') }}");
                status = 'published';
            } else {
                _this.parent().find('#tb-textdes').html("{{ __('pagebuilder::pagebuilder.deactive') }}");
                status = 'draft';
            }
        });
        $(document).on('click', '.tb-checkactionhome', function(event) {
            let _this = $(this);
            if (_this.is(':checked')) {
                _this.parent().find('#tb-texthome').html("{{ __('pagebuilder::pagebuilder.yes') }}");
                home_page = 1;
            } else {
                _this.parent().find('#tb-texthome').html("{{ __('pagebuilder::pagebuilder.no') }}");
                home_page = 0;
            }
        });

        $(document).on('submit', '#page_form', function(event) {
            event.preventDefault();
            var update_text = '{{ __('common.updated_successfully') }}';
            if ($('#id').val() === undefined)
                var update_text = '{{ __('common.added_successfully') }}';

            var data = $('#page_form').serializeArray();
            data.push({
                'name': 'status',
                'value': status,
                '_token': '{{ csrf_token() }}'
            });
            $.ajax({
                type: 'POST',
                url: '{{ route('page.store') }}',
                data: {
                    data: data
                },
                async: false,
                dataType: 'json',
                success: function(data) {
                    if (data.success == 'demo') {
                        return;
                    }

                    if (data.success) {
                        getPages($('#current_page').val());
                        loadAddPage();
                        toastr.success(update_text, "Success", {
                            timeOut: 5000,
                        });
                    } else {
                        let message = '';
                        data.error.forEach(function(item) {
                            message += item + "<br />";
                        });
                       
                        toastr.error(message, 'Error', {
                            timeOut: 5000,
                        });
                    }
                }
            });
        });

        $(document).on('click', '.publish_status', function(event) {
            var statusValue = 'draft';
            if($(this).is(":checked")){
                statusValue = 'published';
            }
            var id = $(this).data('id');

            $.ajax({
                type: 'POST',
                url: '{{ route('page.status-update') }}',
                data: {
                    id: id,
                    status: statusValue
                },
                async: false,
                dataType: 'json',
                success: function(data) {
                    if (data.success == 'demo') {
                        return;
                    }
                    if (data.success) {
                        toastr.success('Status Updated Successfully', "Success", {
                            timeOut: 5000,
                        });
                    } else {
                        toastr.error(data.error, 'Error', {
                            timeOut: 5000,
                        });
                    }
                }
            });
        });

        $(document).on('click', '.goBack', function() {
            loadAddPage();
        });

        $(document).on('click', '.deletePage', function() {
            let pageId = $(this).data('page-id');
            deletePageModalFun(pageId);
        });

        $(document).on('submit', '#page_form_delete_page', function(event) {
            event.preventDefault();
            var pageId = $('input[name=pageIdForPageDelete]').val();
            var url = '{{ route('page.delete', 'pageId') }}';
            url = url.replace("pageId", pageId);
            $.ajax({
                type: 'DELETE',
                url: url,
                dataType: 'json',
                success: function(data) {
                    if (data.success == 'demo') {
                        return;
                    }
                    if (data.success) {
                        getPages($('#current_page').val());
                        $('#deletePageModal').modal('hide');
                        toastr.success("Delete Successfully", "Success", {
                            timeOut: 5000,
                        });
                    }
                    if (data.error) {
                        toastr.error("Cann't Be Deleted! Please Change Default Home Page First.",
                            "Failed", {
                                timeOut: 5000,
                            });
                    }
                }
            });
        });

        function loadAddPage() {
            $.ajax({
                type: 'GET',
                url: '{{ route('page.create') }}',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        $('#add_edit_section').html(data.html)
                        status = 'draft';
                    }
                }
            });
        }

        function deletePageModalFun(pageId) {
            var modal = $('#deletePageModal');
            modal.find('input[name=pageIdForPageDelete]').val(pageId);
            modal.modal('show');
        }
    </script>
@endpush
