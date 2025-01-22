@extends('backEnd.master')
@section('title')
@lang('transport.transport')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('transport.transport')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="">@lang('transport.transport')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">

        <div class="row">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('transport.transport_route_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 mb-30">
                        <!-- Start Student Meta Information -->
                        <div class="student-meta-box">
                            <div class="student-meta-top"></div>
                            <img class="student-meta-img img-100"
                                src="{{ file_exists($student_detail->student_photo) ? asset($student_detail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                                alt="">
                            <div class="white-box radius-t-y-0">
                                <div class="single-meta mt-50">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('student.student_name')
                                        </div>
                                        <div class="value">
                                            {{$student_detail->first_name.' '.$student_detail->last_name}}
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('student.admission_no')
                                        </div>
                                        <div class="value">
                                            {{$student_detail->admission_no}}
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('student.class')
                                        </div>
                                        <div class="value">
                                            @if($student_detail->defaultClass!="")
                                            {{@$student_detail->defaultClass->class->class_name}}
                                            {{-- ({{@$academic_year->year}}) --}}
                                            @elseif ($student_detail->studentRecord !="")
                                            {{@$student_detail->studentRecord->class->class_name}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('student.section')
                                        </div>
                                        <div class="value">

                                            @if($student_detail->defaultClass!="")
                                            {{@$student_detail->defaultClass->section->section_name}}

                                            @elseif ($student_detail->studentRecord !="")
                                            {{@$student_detail->studentRecord->section->section_name}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('common.gender')
                                        </div>
                                        <div class="value">
                                            {{$student_detail->gender !=""?$student_detail->gender->base_setup_name:""}}
                                        </div>
                                    </div>
                                </div>
                                @if (moduleStatusCheck('BehaviourRecords'))
                                <div class="single-meta">
                                    <div class="d-flex justify-content-between">
                                        <div class="name">
                                            @lang('behaviourRecords.behaviour_records_point')
                                        </div>
                                        <div class="value">
                                            @php
                                            $totalBehaviourPoints = 0;
                                            if (@$studentBehaviourRecords) {
                                            foreach ($studentBehaviourRecords as $studentBehaviourRecord) {
                                            $totalBehaviourPoints += $studentBehaviourRecord->point;
                                            }
                                            }
                                            @endphp
                                            {{ $totalBehaviourPoints }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- End Student Meta Information -->

                    </div>
                    <div class="col-lg-9">
                        <div class="white-box">
                            <div class="mt-40">
                                <x-table>
                                    <table id="table_id" class="table" cellspacing="0" width="100%">

                                        <thead>
                                            <tr>
                                                <th>@lang('transport.route')</th>
                                                <th>@lang('transport.vehicle')</th>
                                                <th>@lang('common.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($routes as $route)
                                            <tr>
                                                <td valign="top">{{@$route->route->title}}</td>
                                                <td class="pl-2">
                                                    @php
                                                    @$vehicles = explode(",",@$route->vehicle_id);
                                                    @endphp
                                                    @foreach($vehicles as $vehicle)

                                                    @php @$vehicle = App\SmVehicle::find(@$vehicle);
                                                    @endphp
                                                    {{@$vehicle->vehicle_no}}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <x-drop-down>
                                                        <a class="dropdown-item modalLink" title="Transport Details"
                                                            data-modal-size="modal"
                                                            href="{{route('student_transport_view_modal', [@$route->route->id, @$vehicle->id])}}">@lang('common.view')</a>
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
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')