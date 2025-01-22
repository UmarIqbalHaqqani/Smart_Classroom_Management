<div class="white-box">
    <div class="row">
        <div class="col-lg-4 no-gutters">
            <div class="main-title">
                <h3 class="mb-15">@lang('teacherEvaluation.teacher_pending_evaluation_report') </h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="primary_input ">
                <label class="primary_input_label"
                    for="">@lang('common.class')<span class="text-danger">
                        *</span></label>
                <select
                    class="primary_select  form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}"
                    name="class_id" id="classSelectStudentHomeWork">
                    <option data-display="@lang('common.select_class') *"
                        value="">@lang('common.select')</option>
                    @foreach ($classes as $key => $value)
                        <option value="{{ $value->id }}"
                            {{ old('class_id') != '' ? 'selected' : '' }}>
                            {{ $value->class_name }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('class_id'))
                    <span class="text-danger">{{ $errors->first('class_id') }}
                    </span>
                @endif
            </div>
        </div>
        <div class="col-lg-4">
            <div class="primary_input " id="subjectSelecttHomeworkDiv">
                <label class="primary_input_label" for="">@lang('common.subject')
                    <span class="text-danger"></span></label>
                <select
                    class="primary_select  form-control{{ $errors->has('subject_id') ? ' is-invalid' : '' }}"
                    name="subject_id" id="subjectSelect">
                    <option data-display="@lang('teacherEvaluation.select_subject')" value="">
                        @lang('common.subject')</option>
                </select>
                <div class="pull-right loader loader_style"
                    id="select_subject_loader">
                    <img class="loader_img_style"
                        src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                        alt="loader">
                </div>
            </div>
        </div>
        <div class="col-lg-4" id="selectSectionsDiv">
            <label for="checkbox" class="mb-2">@lang('common.section') <span
                    class="text-danger"></span></label>
            <select id="selectSectionss" name="section_id[]" multiple="multiple"
                class="multypol_check_select active position-relative form-control{{ $errors->has('section_id') ? ' is-invalid' : '' }}">
            </select>
        </div>
        <div class="col-lg-4 mt-30-md" id="select_teacher_div">
            <label class="primary_input_label" for="">@lang('teacherEvaluation.teacher')
                <span
                    class="text-danger"> </span></label>
            <select class="primary_select " id="select_teacher" name="teacher_id">
                <option data-display="@lang('teacherEvaluation.select_teacher')" value="">
                    @lang('teacherEvaluation.select_teacher')</option>
            </select>
            <div class="pull-right loader loader_style"
                id="select_teacher_loader">
                <img class="loader_img_style"
                    src="{{ asset('public/backEnd/img/demo_wait.gif') }}"
                    alt="loader">
            </div>
        </div>
        <div class="col-lg-4 mt-30-md" id="select_submitted_by_div">
            <label class="primary_input_label" for="">@lang('teacherEvaluation.submitted_by')
                <span
                    class="text-danger"> </span></label>
            <select class="primary_select " id="select_submitted_by"
                name="submitted_by">
                <option data-display="@lang('teacherEvaluation.select_submitted_by')" value="">
                    @lang('teacherEvaluation.select_submitted_by')</option>
                <option data-display="@lang('teacherEvaluation.parent')" value="3">
                    @lang('teacherEvaluation.parent')</option>
                <option data-display="@lang('teacherEvaluation.student')" value="2">
                    @lang('teacherEvaluation.student')</option>
            </select>
        </div>
        <div class="col-lg-12 mt-20 text-right">
            <button type="submit" class="primary-btn small fix-gr-bg">
                <span class="ti-search pr-2"></span>
                @lang('teacherEvaluation.search')
            </button>
        </div>
    </div>
</div>
