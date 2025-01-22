<form class="tb-themeform" id="page_form">
    <div class="white-box">
        <div class="main-title">
            <h3 class="mb-15">
                {{ $edit ? __('pagebuilder::pagebuilder.update_page') : __('pagebuilder::pagebuilder.add_page') }}
            </h3>
        </div>
        <div class="add-visitor">
            @csrf
            <fieldset>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="primary_input">
                            <label class="primary_input_label">{{ __('pagebuilder::pagebuilder.page_name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                class="primary_input_field form-control @error('name') tk-invalid @enderror"
                                name="name"
                                value="{{ $page->name ?? null }}" id="name"
                                placeholder="{{ __('pagebuilder::pagebuilder.page_name') }} *">
                            @if ($edit)
                                <input type="hidden" name="id" id="id" value="{{ $page->id ?? null }}" />
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mt-20">
                    <div class="col-lg-12">
                        <div class="primary_input">
                            <label class="primary_input_label">{{ __('pagebuilder::pagebuilder.page_title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                class="primary_input_field form-control @error('title') tk-invalid @enderror"
                                name="title"
                                id="title" value="{{ $page->title ?? null }}"
                                placeholder="{{ __('pagebuilder::pagebuilder.page_title') }} *">
                        </div>
                    </div>
                </div>

                <div class="row mt-20">
                    <div class="col-lg-12">
                        <div class="primary_input">
                            <label
                                class="primary_input_label">{{ __('pagebuilder::pagebuilder.page_description') }}</label>
                            <textarea class="primary_input_field form-control" name="description" id="description"
                                placeholder="{{ __('pagebuilder::pagebuilder.page_description') }}">{{ $page->description ?? null }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row mt-20">
                    <div class="col-lg-12">
                        <div class="primary_input">
                            <label class="primary_input_label">{{ __('pagebuilder::pagebuilder.page_slug') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                class="primary_input_field form-control @error('slug') tk-invalid @enderror"
                                name="slug"
                                id="slug" value="{{ $page->slug ?? null }}"
                                placeholder="{{ __('pagebuilder::pagebuilder.page_slug') }} *">
                        </div>
                    </div>
                </div>
                <div class="row mt-20">
                    <div class="col-lg-12">
                        <div class="primary_input d-flex justify-content-between">
                            <label class="primary_input_label">{{ __('pagebuilder::pagebuilder.make_home_page') }}</label>
                            <div class="tb-switchbtn">
                                <label for="home_page" id="tb-texthome" class="primary_input_label">
                                    {{ ($page->home_page ?? null) == 1
                                        ? __('pagebuilder::pagebuilder.yes')
                                        : __('pagebuilder::pagebuilder.no') }}
                                </label>
                                <input type="checkbox" class="tb-checkactionhome" name="home_page" id="home_page"
                                    {{ ($page->home_page ?? null) == 1 ? 'checked' : '' }} />
                            </div>
                        </div>
                    </div>
                </div>
                @if ($edit)
                    <div class="row mt-20">
                        <div class="col-lg-12">
                            <div class="primary_input d-flex justify-content-between">
                                <label class="primary_input_label">{{ __('pagebuilder::pagebuilder.status') }}:</label>
                                <div class="tb-switchbtn">
                                    <label for="tb-pagestatus" id="tb-textdes" class="primary_input_label">
                                        {{ ($page->status ?? null) == 'published'
                                            ? __('pagebuilder::pagebuilder.active')
                                            : __('pagebuilder::pagebuilder.deactive') }}
                                    </label>
                                    <input type="checkbox" class="tb-checkaction" name="status" id="status"
                                        {{ ($page->status ?? null) == 'published' ? 'checked' : '' }} />
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row mt-40">
                    <div class="col-lg-12 text-center">
                        <button class="primary-btn fix-gr-bg" type="submit"
                            id="form_submit_btn">{{ $edit ? __('pagebuilder::pagebuilder.update_page') : __('pagebuilder::pagebuilder.add_page') }}</button>

                        @if ($edit)
                            <button class="primary-btn fix-gr-bg goBack" type="button"
                                id="form_submit_btn">{{ __('pagebuilder::pagebuilder.back') }}</button>
                        @endif
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</form>
