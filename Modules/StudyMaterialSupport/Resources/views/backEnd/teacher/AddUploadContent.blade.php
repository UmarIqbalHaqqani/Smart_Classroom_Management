@extends('backEnd.master')
@section('title') 
@lang('lang.upload_content') 
@endsection

@section('mainContent')
<link rel="stylesheet" href="{{asset('public/backEnd/summernote/')}}/summernote.css">
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.upload_content') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('lang.study_material')</a>
                <a href="#">@lang('lang.upload_content') </a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30 text-center">
                                    @if(isset($editData))
                                        @lang('common.edit')
                                    @else
                                    @endif
                                    @lang('lang.upload_content')
                                </h3>
                            </div>
                            @if(isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-upload-content',@$editData->id, 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="id" value="{{@$editData->id}}">
                                @else
                             @if(userPermission(89))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'save-upload-content', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                            <div class="row mb-10">
                                                <div class="col-lg-6 mb-30">
                                                    <div class="primary_input">
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('content_title') ? ' is-invalid' : '' }}"
                                                            type="text" name="content_title" autocomplete="off"
                                                            value="{{isset($editData)? @$editData->content_title:''}}">
                                                        <label> @lang('lang.content_title') <span class="text-danger"> *</span> </label>
                                                        
                                                        @if ($errors->has('content_title'))
                                                            <span class="text-danger" >
                                                        {{ $errors->first('content_title') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-30">
                                                    <select
                                                        class="primary_select  form-control{{ $errors->has('content_type') ? ' is-invalid' : '' }}"
                                                        name="content_type" id="content_type">
                                                        <option data-display="@lang('lang.content_type') *" value="">@lang('lang.content_type') *</option>
                                                        <option value="as" {{isset($editData) && @$editData->content_type == "as"? 'selected':''}}> @lang('lang.assignment')</option>                                               
                                                        <option value="sy" {{isset($editData) && @$editData->content_type == "sy"? 'selected':''}}>@lang('lang.syllabus')</option>
                                                        <option value="ot" {{isset($editData) && @$editData->content_type == "ot"? 'selected':''}}>@lang('lang.other_download')</option>
                                                    </select>
                                                    @if ($errors->has('content_type'))
                                                        <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('content_type') }}
                                                        </span>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="row mb-30">    
                                                <div class="col-lg-4 mb-30">
                                                    <select class="primary_select  form-control{{ $errors->has('content_type') ? ' is-invalid' : '' }}" name="available_for" id="contentType">
                                                        <option data-display="@lang('lang.available_for') *" value="">@lang('lang.available_for') *</option> 
                                                        <option value="admin" {{isset($editData) && (@$editData->available_for_admin ==1 && @$editData->class ==null)? 'selected':''}}>@lang('lang.admin')</option>
                                                        <option value="student" {{isset($editData) && @$editData->available_for_admin == 0  ? 'selected':''}}>@lang('lang.student')</option>
                                                        <option value="both" {{isset($editData) && (@$editData->available_for_admin == 1 && ($editData->available_for_all_classes ==1 || $editData->class !=null))? 'selected':''}}>@lang('lang.admin') & @lang('lang.student')</option>
                                                    </select>    
                                                    @if ($errors->has('available_for'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('available_for') }}</span>
                                                @endif                                   
                                                </div>
                                            
                                                <div class="col-lg-4 mb-20" id="adminClassDiv">
                                                    <div class="primary_input">
                                                        <select class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}" name="class" id="classSelectStudent">
                                                            <option data-display="@lang('common.select_class') *" value="">@lang('common.select') @lang('class') *</option>
                                                            @foreach($classes as $class)
                                                                <option value="{{@$class->id}}" {{isset($editData) && $editData->class == $class->id? 'selected':''}}>{{@$class->class_name}}</option>
                                                            @endforeach
                                                            <option value="all_classes">@lang('lang.available_for_all_classes')</option>
                                                        </select>
                                                        
                                                        @if ($errors->has('class'))
                                                            <span class="text-danger invalid-select" role="alert">
                                                                {{ $errors->first('class') }}
                                                        </span>
                                                        @endif
                                                    </div>    
                                                </div>
                                                <div class="col-lg-4" id="adminSectionDiv">
                                                    <div class="primary_input" id="sectionStudentDiv">
                                                        <select
                                                            class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                                            name="section" id="sectionSelectStudent">
                                                            <option data-display="@lang('common.select_section') " value="">@lang('common.section')  </option>
                                                            @if(isset($editData->section))
                                                                @foreach($sections as $section)
                                                                    <option value="{{$section->section_id}}" {{$editData->section == $section->section_id? 'selected': ''}}>{{$section->sectionName->section_name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <div class="pull-right loader loader_style" id="select_section_loader">
                                                            <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                                        </div>
                                                        
                                                        @if ($errors->has('section'))
                                                        <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('section') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-30">                                         
                                                <div class="col-lg-4 mb-20">
                                                                <div class="primary_input">
                                                                    <select
                                                                        class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}"
                                                                        name="subject" id="contentSubject">
                                                                        <option data-display="@lang('common.subject') "
                                                                                value="">@lang('common.select_subject') </option>
                                                                                
                                                                                    @foreach($subjects as $subject)
                                                                                        <option value="{{$subject->id}}" @isset($editData) {{$editData->subject_id == $subject->id? 'selected': ''}}   @endisset>{{$subject->subject_name}}</option>
                                                                                    @endforeach
                                                                              
                                                                    </select>
                                                                    
                                                                    @if ($errors->has('subject'))
                                                                        <span class="text-danger invalid-select" role="alert">
                                                                    {{ $errors->first('subject') }}
                                                                </span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        <div class="col-lg-4 mb-30">
                                                                <div class="primary_input" id="contentLessonDiv">
                                                                    <select
                                                                        class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                                                        name="lesson" id="contentLesson">
                                                                        <option data-display="@lang('lang.lesson') "
                                                                                value="">@lang('lang.lesson') 
                                                                        </option>
                                                                    @isset($editData)
                                                                        @foreach ($lessons as $lesson)
                                                                        <option value="{{$lesson->id}}" {{$editData->lesson_id == $lesson->id? 'selected': ''}}>{{$lesson->lesson_title}}</option>
                                                                            
                                                                        @endforeach
                                                                    @endisset
                                                                    </select>
                                                                    <div class="pull-right loader loader_style" id="select_lesson_loader">
                                                                        <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                                                    </div>
                                                                    
                                                                    @if ($errors->has('lesson'))
                                                                    <span class="text-danger invalid-select" role="alert">
                                                                    {{ $errors->first('lesson') }}
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                        </div> 
                                                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                                <div class="col-lg-4 no-gutters input-right-icon mb-30">

                                                    <div class="col">
                                                        <div class="primary_input">
                                                            <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('upload_date') ? ' is-invalid' : '' }}"  id="upload_date" type="text"
                                                                name="upload_date"  value="{{isset($editData)? date('m/d/Y', strtotime(@$editData->upload_date)): date('m/d/Y')}}">
                                                            <label class="primary_input_label" for="">@lang('lang.update_date') <span></span> </label>
                                                            
                                                            @if ($errors->has('upload_date'))
                                                                <span class="text-danger" >
                                                                {{ $errors->first('upload_date') }}
                                                                </span>
                                                            @endif
                                                        </div>
            
                                                    </div>
                                                    <div class="col-auto">
                                                        <button class="" type="button">
                                                            <i class="ti-calendar" id="apply_date_icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                    
                                    

                                            <div class="row mb-20">
                                                <div class="col-lg-12">
                                                    <label class="primary_input_label" for="">@lang('common.description') <span class="text-danger"> *</span> </label>
                                                    <div class="primary_input">                                               
                                                        <textarea class="primary_input_field a form-control" cols="0" rows="10" name="description" id="summernote">{{@$editData->description}}</textarea>
                                                      
                                                         
                                                            @if ($errors->has('description'))
                                                                <span class="error text-danger">{{ $errors->first('description') }}</span>
                                                            @endif
                                                    </div>
                                                </div>
                                            </div>
                                    
                               
                                            <div class="row  mb-20">
                                                <div class="col-lg-6">
                                                    <div class="primary_input">
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('source_url') ? ' is-invalid' : '' }}"
                                                            type="text" name="source_url" autocomplete="off"
                                                            value="{{isset($editData)? @$editData->source_url:''}}">
                                                        <label> @lang('lang.source_url')</label>
                                                        
                                                        @if ($errors->has('source_url'))
                                                            <span class="text-danger" >
                                                        {{ $errors->first('source_url') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row no-gutters input-right-icon mb-20">
                                                        <div class="col">
                                                            <div class="primary_input">
                                                                <input
                                                                    class="primary_input_field form-control {{ $errors->has('content_file') ? ' is-invalid' : '' }}"
                                                                    readonly="true" type="text"
                                                                    placeholder="{{isset($editData->upload_file) && @$editData->upload_file != ""? getFilePath3(@$editData->upload_file):trans('lang.file').''}}"
                                                                    id="placeholderUploadContent">
                                                                
                                                                @if ($errors->has('content_file'))
                                                                    <span class="text-danger" >
                                                                        {{ $errors->first('content_file') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                             <code>(jpg,png,jpeg,pdf,doc,docx,mp4,mp3 are allowed for upload)</code>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button class="primary-btn-small-input" type="button">
                                                                <label class="primary-btn small fix-gr-bg"
                                                                       for="upload_content_file">@lang('common.browse')</label>
                                                                    
                                                                <input type="file" class="d-none form-control" name="content_file"
                                                                       id="upload_content_file">
                                                            </button>
                                                            
                
                                                        </div>
                                                       
                                                    </div>
                                                </div>

                                            </div> 
                                            <div class="row mb-20">
                                                <div class="col-lg-12"> 
                                                    <div class="primary_input">
                                                        <label for="checkExamOn"> <strong>  @lang('onlineexam::onlineExam.online_exam')? </strong></label>
                                                        <input type="checkbox" style="margin:5px"  name="examOn"  @if(isset($editData))  {{$editData->g_online_exam !=null ? "checked":""}}   @endif id="checkExamOn" onclick="ExamOn()"/>
                                                       
                                                        
                                                    </div>
                                                 </div>
                                            </div>
                                            
                                            <div class="row mb-10"  @if(!isset($editData)) id="is_exam" @endisset>
                                               
                                                <div class="col-lg-6 mb-30" id="grouplist">
                                                    <select
                                                        class="primary_select  form-control{{ $errors->has('content_type') ? ' is-invalid' : '' }}"
                                                        name="group_id">
                                                          <option data-display="@lang('lang.select_group') *" value="">@lang('lang.select_group') *</option>

                                                        @foreach($groups as $group)
                                                        @if(isset($editData))
                                                           <option value="{{$group->id}}" {{ $editData->g_online_exam == $group->id? 'selected':''}}>{{$group->title}}</option>
                                                        @else
                                                        <option value="{{$group->id}}"  >{{$group->title}}</option>
                                                        @endif
        
                                                    @endforeach
                                                    </select>
                                                    @if ($errors->has('group'))
                                                    <span class="text-danger invalid-select" role="alert">
                                                        {{ $errors->first('group') }}</span>
                                                    @endif
                                                </div>

                                                <div class="col-lg-6 mb-30" id="groupName">
                                                    <div class="primary_input">
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('content_title') ? ' is-invalid' : '' }}"
                                                            type="text" name="group_name" autocomplete="off"
                                                            value="{{isset($editData)? @$editData->content_title:''}}">
                                                        <label> @lang('lang.group_name') <span class="text-danger"> *</span> </label>
                                                        
                                                        @if ($errors->has('group_name'))
                                                            <span class="text-danger" >
                                                        {{ $errors->first('group_name') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if(!isset($editData))
                                                <div class="col-lg-6 mb-30">
                                                    <div class="primary_input">
                                                        <input
                                                            class="primary_input_field form-control"
                                                            type="text" name="marks">
                                                        <label> @lang('exam.marks') <span class="text-danger"> *</span> </label>
                                                        
                                                        @if ($errors->has('marks'))
                                                            <span class="text-danger" >
                                                        {{ $errors->first('marks') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                 @endif
                                               @if(!isset($editData))
                                                  <div class="col-lg-6 mb-30" style="color:white">
                                                    <a class="btn primary-btn small  fix-gr-bg"
                                                            id="newGroup" onclick="newGroup()"  >  @lang('lang.new_group')</a>
                                                 </div>
                                                 @endif
                                                <div class="col-lg-6 mb-30">
                                                    <div class="primary_input">
                                                        <input
                                                            class="primary_input_field form-control{{ $errors->has('exam_title') ? ' is-invalid' : '' }}"
                                                            type="text" name="exam_title" autocomplete="off"
                                                            value="{{isset($editData)? @$online_exam->title:''}}">
                                                            <label class="primary_input_label" for="">@lang('onlineexam::onlineExam.exam_title') <span class="text-danger"> *</span></label>
                                                        
                                                        @if ($errors->has('exam_title'))
                                                            <span class="text-danger" >
                                                        {{ $errors->first('exam_title') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                      @php 
                                        $tooltip = "";
                                        if(userPermission(89) ){
                                                @$tooltip = "";
                                            }else{
                                                @$tooltip = "You have no permission to add";
                                            }
                                      @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                                <span class="ti-check"></span>
                                                @lang('lang.upload_content')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            
            
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.date_picker_css_js')

@section('script')  
<script src="{{asset('public/backEnd/summernote')}}/summernote.js"></script>
<script>
    $(document).ready(function(){
        $('#contentType').on('change',function(){
            var contentType=$(this).val();
            if($(this).val()=='admin'){
                $('#adminClassDiv').addClass('disabledbutton');
                $('#adminSectionDiv').addClass('disabledbutton');                
            }else{
                $('#adminClassDiv').removeClass('disabledbutton');
                $('#adminSectionDiv').removeClass('disabledbutton');  
            }
        });

        $('#classSelectStudent').on('change',function(){
            if($(this).val()=='all_classes'){
                $('#adminSectionDiv').addClass('disabledbutton');
            }else{
                $('#adminSectionDiv').removeClass('disabledbutton');  
            }
        })
    });
</script>
<script>
    
    $(document).ready(function() {
        $('#summernote').summernote();
        $('#is_exam').hide();
        $('#groupName').hide();
        $('#marks').hide();
    });

    function sendFile(files, editor = '#summernote') {
        const SET_DOMAIN="{{ url('/')}}";
        var formData = new FormData();
        $.each(files, function(i, v) {
            formData.append("files[" + i + "]", v);
        })

        $.ajax({
            url: SET_DOMAIN + '/api/upload/image',
            type: 'post',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success: function(response) {
                var $summernote = $(editor);
                $.each(response, function(i, v) {
                    $summernote.summernote('insertImage', v);
                })
            },
            error: function(data) {
                if (data.status === 404) {
                    toastr.error("What you are looking is not found", 'Opps!');
                    return;
                } else if (data.status === 500) {
                    toastr.error('Something went wrong. If you are seeing this message multiple times, please contact Spondon It author.', 'Opps');
                    return;
                } else if (data.status === 200) {
                    toastr.error('Something is not right', 'Error');
                    return;
                }
                let jsonValue = $.parseJSON(data.responseText);
                let errors = jsonValue.errors;
                if (errors) {
                    let i = 0;
                    $.each(errors, function(key, value) {
                        let first_item = Object.keys(errors)[i];
                        let error_el_id = $('#' + first_item);
                        if (error_el_id.length > 0) {
                            error_el_id.parsley().addError('ajax', {
                                message: value,
                                updateClass: true
                            });
                        }
                        toastr.error(value, 'Validation Error');
                        i++;
                    });
                } else {
                    toastr.error(jsonValue.message, 'Opps!');
                }

            }
        });
    }
  </script>
  <script>
      $('#summernote').summernote({
        callbacks: {
            onImageUpload: function(files) {
                sendFile(files, '#summernote')
            }
        },
        placeholder: 'Write here',
        tabsize: 2,
        height: 300,
        tooltip: false,
        codeviewFilter: true,
        codeviewIframeFilter: true
    });
  </script>

<script>
    function ExamOn() {
        
    var checkBox = document.getElementById("checkExamOn");
    var text = document.getElementById("is_exam");
            if (checkBox.checked == true){
                $('#is_exam').show();
            } else {
                $('#is_exam').hide();
            }
    }

    function newGroup(){
        $('#grouplist').toggle();
        $('#groupName').toggle();
        $('#marks').toggle();
    }
</script>
  @endsection


