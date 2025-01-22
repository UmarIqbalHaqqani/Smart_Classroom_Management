@extends('backEnd.master')
@section('title') 
@lang('student.student_export')
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-20 up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('student.student_export')</h1>
            <div class="bc-pages">
                <a href="{{url('admin-dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('student.student_information')</a>
                <a href="#">@lang('student.student_export')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="col-lg-12 white-box">
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-title">
                        <h3 class="mb-15">
                            @lang('student.all_student_export')
                        </h3>
                    </div>
                    <div>
                        <div class="add-visitor">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="text-center">
                                        @if (userPermission('all-student-export-excel'))
                                            <a class="primary-btn small bg-success text-white border-0  tr-bg" href="{{route('all-student-export-excel')}}">
                                                @lang('student.export_to_csv')
                                            </a>  
                                        @endif
                                        @if (userPermission('all-student-export-pdf'))
                                            <a class="primary-btn small bg-success text-white border-0  tr-bg" href="{{route('all-student-export-pdf')}}">
                                                @lang('student.export_to_pdf')
                                            </a>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
