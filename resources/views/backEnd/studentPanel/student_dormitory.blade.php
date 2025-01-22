@extends('backEnd.master')
@section('title')
@lang('dormitory.dormitory')
@endsection

@push('css')
<style>

    @media (min-width: 991px) and (max-width: 1200px){
        .dataTables_filter label{
            left: 50%!important
        }
    }

    @media (max-width: 767px){
        .dataTables_filter label{
            top: -25px!important;
            width: 100%;
        }
    }

    @media screen and (max-width: 640px) {
        div.dt-buttons {
            display: none;
        }

        .dataTables_filter label{
            top: -60px!important;
            width: 100%;
            float: right;
        }
        .main-title{
            margin-bottom: 40px
        }
    }
</style>
@endpush

@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('dormitory.dormitory')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="{{route('student_dormitory')}}">@lang('dormitory.dormitory')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        @if(isset($room_list))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('room-list')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('common.add')
                </a>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-15"> @lang('dormitory.dormitory_room_list')</h3>
                            </div>
                        </div>
                    </div>
    
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
    
                                <thead>
                                    <tr>
                                        <th>@lang('dormitory.dormitory')</th>
                                        <th>@lang('dormitory.room_number') </th>
                                        <th>@lang('dormitory.room_type')</th>
                                        <th>@lang('dormitory.no_of_bed')</th>
                                        <th>@lang('dormitory.cost_per_bed')</th>
                                        <th>@lang('common.status')</th>
                                    </tr>
                                </thead>
    
                                <tbody>
                                    @foreach($room_lists as $values)
                                    @php @$rowCount=0; @endphp
                                        @foreach($values as $room_list)
                                        <tr>
                                            @if(@$rowCount==0)
                                            <td rowspan="{{@$values->count()}}">{{@$room_list->dormitory != ""? @$room_list->dormitory->dormitory_name:''}}</td>
                                            @endif
                                            @php @$rowCount=@$rowCount+1; @endphp
                                            <td>{{@$room_list->name}}</td>
                                            <td>{{@$room_list->roomType != ""? @$room_list->roomType->type: ''}}</td>
                                            <td>{{@$room_list->number_of_bed}}</td>
                                            <td>{{@$room_list->cost_per_bed}}</td>
                                            <td>
                                                @if(@$student_detail->room_id == @$room_list->id)
                                                    <button class="primary-btn small fix-gr-bg">
                                                       @lang('dormitory.assigned')                                                 
                                                    </button>
                                                 @else
                                                  
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
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
</section>
@endsection
@include('backEnd.partials.data_table_js')