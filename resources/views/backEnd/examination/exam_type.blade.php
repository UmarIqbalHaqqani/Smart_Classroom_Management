@extends('backEnd.master')
@section('title')
@lang('exam.exam_type')
@endsection

@push('css')
    <style>
        .exam-type-actions{
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .dtr-data .CRM_dropdown {
            margin-bottom: 10px;
        }

        @media (max-width: 480px){
            .dtr-data a.small {
                font-size: 10px!important;
                padding: 0px 8px
            }
        }
    </style>
@endpush
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.exam_type')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examination')</a>
                <a href="#">@lang('exam.exam_type')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">

        @if(isset($exam_type_edit))
         @if(userPermission('exam_type_store'))
                       
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('exam-type')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('common.add')
                </a>
            </div>
        </div>
        @endif
        @endif
        <div class="row">
           

            <div class="col-lg-4 col-xl-3">
                <div class="row">
                    <div class="col-lg-12">
                        @if(isset($exam_type_edit))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_type_update', 'method' => 'POST']) }}
                        @else
                         @if(userPermission('exam_type_store'))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_type_store', 'method' => 'POST']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="main-title">
                                <h3 class="mb-15">@if(isset($exam_type_edit))
                                        @lang('exam.edit_exam_type')
                                    @else
                                        @lang('exam.add_exam_type')
                                    @endif
                                  
                                </h3>
                            </div>
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label> @lang('exam.exam_name') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('exam_type_title') ? ' is-invalid' : '' }}" type="text" name="exam_type_title" autocomplete="off" value="{{isset($exam_type_edit)? $exam_type_edit->title : ''}}">
                                            <input type="hidden" name="id" value="{{isset($exam_type_edit)? $exam_type_edit->id: Request::old('exam_type_title')}}">
                                            @if ($errors->has('exam_type_title'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('exam_type_title') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>  

                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <label>@lang('exam.average_passing_examination')</label>
                                        <div class="primary_input">
                                            <input type="checkbox" id="averageExam" class="common-checkbox exam-checkbox" name="is_average" {{isset($exam_type_edit)? ($exam_type_edit->is_average == 1 ? 'checked' : '') : Request::old('is_average')}} {{old('is_average') == 'yes' ? 'checked' : ''}}>
                                            <label for="averageExam">@lang('common.yes')</label>
                                        </div>
                                    </div>
                                </div>  

                                <div class="row mt-20 averagePercentage d-none">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label> @lang('exam.average_mark')<span class="text-danger">*</span></label>
                                            <input class="primary_input_field form-control{{ $errors->has('average_mark') ? ' is-invalid' : '' }}" type="text" name="average_mark" autocomplete="off" value="{{isset($exam_type_edit)? $exam_type_edit->average_mark : Request::old('average_mark')}}">
                                            @error('average_mark')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
	                            @php 
                                  $tooltip = "";
                                  if(userPermission('exam_type_store') || userPermission('exam_type_edit')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp

                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($exam_type_edit))
                                                @lang('exam.update_exam_type')
                                            @else
                                                @lang('exam.save_exam_type')
                                            @endif
                                           

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-xl-9">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-12 text-right col-md-12 mb-20">
                            <a href="{{route('exam')}}" class="primary-btn small fix-gr-bg">
                                <span class="ti-plus pr-2"></span>
                                @lang('exam.exam_setup')
                            </a>
                        </div>
            
                    </div>
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-15 ">@lang('exam.exam_type_list')</h3>
                            </div>
                        </div>
                    </div>
    
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                  
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('exam.exam_name')</th>
                                        <th>@lang('exam.is_average_passing_exam')</th>
                                        <th>@lang('exam.average_mark')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
    
                                <tbody>
                                    @php $i=0; @endphp
                                    @foreach($exams_types as $exams_type)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{ @$exams_type->title}}</td>
                                        <td>{{ $exams_type->is_average == 1 ? __('common.yes') : __('common.no')}}</td>
                                        <td>{{ number_format($exams_type->average_mark, 2, '.', '') }}</td>
                                        <td class="exam-type-actions">
                                            <x-drop-down>
    
                                                            @if(userPermission('exam_type_edit'))
    
                                                            <a class="dropdown-item" href="{{route('exam_type_edit', [$exams_type->id])}}">@lang('common.edit')</a>
                                                            @endif
                                                            @if(userPermission('exam_type_delete'))
    
                                                            <a class="dropdown-item" data-toggle="modal" data-target="#deleteSubjectModal{{@$exams_type->id}}"  href="#">@lang('common.delete')</a>
                                                       @endif
                                                        </div>
                                                    </div>
                                                     <a class="primary-btn small tr-bg" style="white-space: nowrap" href="{{route('exam-marks-setup',$exams_type->id)}}">
                                                        <span class="pl ti-settings"></span> @lang('exam.exam_setup')
                                                    </a>
                                                </x-drop-down>
                                        </td>
                                    </tr>
                                     <div class="modal fade admin-query" id="deleteSubjectModal{{@$exams_type->id}}" >
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('exam.delete_exam_type')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
    
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>
    
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                        <a href="{{route('exam_type_delete', [@$exams_type->id])}}" class="text-light">
                                                        <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                         </a>
                                                    </div>
                                                </div>
    
                                            </div>
                                        </div>
                                    </div>
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
@push('script')
    <script>
        $( document ).ready(function() {
            if($("#averageExam").is(':checked')){
                $(this).val('yes');
                if($('.averagePercentage').hasClass('d-none')){
                    $('.averagePercentage').removeClass('d-none');
                }
            }

            $("#averageExam").on('click', function(){  
                if($(this).is(':checked')){
                    $(this).val('yes');
                    if($('.averagePercentage').hasClass('d-none')){
                        $('.averagePercentage').removeClass('d-none');
                    }
                }else{
                    $(this).val('');
                    $('.averagePercentage').addClass('d-none');
                }
            });
        });
    </script>
@endpush