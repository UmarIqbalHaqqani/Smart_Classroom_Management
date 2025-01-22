<form method="GET" action="{{ route('frontend.indiviual-result') }}">
    <div class="row align-items-end">
        @csrf
        <div class="col-lg-5 col-md-4 col-sm-6">
            <div class="mb-2">@lang('edulia.exam') <span class="required">*</span> </div>
            <select id="academic_year_selector" class="w-100" name="exam">
                <option value="@lang('edulia.select')">@lang('edulia.select')</option>
                @foreach ($exam_types as $exam)
                    <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-5 col-md-4 col-sm-6">
            <div class="mb-2">@lang('edulia.admission_no') <span class="required">*</span></div>
            <div class="input-control">
                <input type="number" class="result_filter_input" name="admission_number"
                    placeholder='@lang('edulia.admission_no')' required>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <button type="submit" class="boxed_btn search_btn text-center">
                <i class="fa fa-search"></i>
                @lang('edulia.search')
            </button>
        </div>
    </div>
</form>
