@extends('backEnd.master')
@section('title')
    @lang('front_settings.add_testimonial')
@endsection
@push('css')
    <style>
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            font-size: 1.5em;
            justify-content: space-around;
            text-align: center;
            width: 5em;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #ccc;
            cursor: pointer;
        }

        .star-rating :checked~label {
            color: #f90;
        }

        article {
            background-color: #ffe;
            box-shadow: 0 0 1em 1px rgba(0, 0, 0, .25);
            color: #006;
            font-family: cursive;
            font-style: italic;
            margin: 4em;
            max-width: 30em;
            padding: 2em;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.add_testimonial')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.frontend_cms')</a>
                    <a href="#">@lang('front_settings.add_testimonial')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if (isset($add_testimonial))
                @if (userPermission('store_testimonial'))
                    <div class="row">
                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                            <a href="{{ route('testimonial_index') }}" class="primary-btn small fix-gr-bg">
                                <span class="ti-plus pr-2"></span>
                                @lang('common.add')
                            </a>
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        @if (isset($add_testimonial))
                            {{ Form::open([
                                'class' => 'form-horizontal',
                                'files' => true,
                                'route' => 'update_testimonial',
                                'method' => 'POST',
                                'enctype' => 'multipart/form-data',
                                'id' => 'add-income-update',
                            ]) }}
                        @else
                            @if (userPermission('store_testimonial'))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'store_testimonial',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                    'id' => 'add-income',
                                ]) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="main-title">
                                <h3 class="mb-15">
                                    @if (isset($add_testimonial))
                                        @lang('front_settings.edit_testimonial')
                                    @else
                                        @lang('front_settings.add_testimonial')
                                    @endif
                                </h3>
                            </div>
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">

                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.name') <span
                                                    class="text-danger"> *</span></label>

                                            <input
                                                class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" autocomplete="off"
                                                value="{{ isset($add_testimonial) ? @$add_testimonial->name : old('name') }}">
                                            <input type="hidden" name="id"
                                                value="{{ isset($add_testimonial) ? @$add_testimonial->id : '' }}">

                                            @if ($errors->has('name'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('name') }}
                                                </span>
                                            @endif
                                        </div>



                                    </div>
                                </div>
                                <div class="row no-gutters input-right-icon mt-15">
                                    <div class="col">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.designation') <span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('designation') ? ' is-invalid' : '' }}"
                                                type="text" name="designation" autocomplete="off"
                                                value="{{ isset($add_testimonial) ? @$add_testimonial->designation : old('designation') }}">


                                            @if ($errors->has('designation'))
                                                <span class="text-danger">
                                                    {{ $errors->first('designation') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-gutters input-right-icon mt-15">
                                    <div class="col">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.institution_name') <span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('institution_name') ? ' is-invalid' : '' }}"
                                                type="text" name="institution_name" autocomplete="off"
                                                value="{{ isset($add_testimonial) ? @$add_testimonial->institution_name : old('institution_name') }}">


                                            @if ($errors->has('institution_name'))
                                                <span class="text-danger">
                                                    {{ $errors->first('institution_name') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-15">

                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.image') <span
                                                    class="text-danger"> *</span></label>
                                            <div class="primary_file_uploader">
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('image') ? ' is-invalid' : '' }}"
                                                    type="text" id="placeholderFileOneName" name="image"
                                                    placeholder="{{ isset($add_testimonial) ? (@$add_testimonial->image != '' ? getFilePath3(@$add_testimonial->image) : trans('common.image') . ' *') : trans('common.image') . ' *' }}"
                                                    readonly>
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="addTestimonialImage">{{ __('common.browse') }}</label>
                                                    <input type="file" class="d-none" name="image" id="addTestimonialImage">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <img class="previewImageSize {{ @$add_testimonial->image ? '' : 'd-none' }}" src="{{ @$add_testimonial->image ? asset($add_testimonial->image) : '' }}" alt="" id="testimonialImageShow" height="100%" width="100%">
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.description')<span
                                                    class="text-danger"> *</span></label>
                                            <textarea class="primary_input_field form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" cols="0"
                                                rows="4"
                                                name="description">{{ isset($add_testimonial) ? @$add_testimonial->description : old('description') }}</textarea>


                                            @if ($errors->has('description'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('description') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('common.rating')<span
                                                    class="text-danger"> *</span></label>
                                            <div class="star-rating d-flex">
                                                <input type="radio"
                                                    id="5-stars"
                                                    name="rating" value="5"
                                                    {{ @$add_testimonial->star_rating == 5 ? "checked" : '' }} />
                                                <label for="5-stars"
                                                    class="star">&#9733;</label>
                                                <input type="radio"
                                                    id="4-stars"
                                                    name="rating" value="4"
                                                    {{ @$add_testimonial->star_rating == 4 ? "checked" : '' }} />
                                                <label for="4-stars"
                                                    class="star">&#9733;</label>
                                                <input type="radio"
                                                    id="3-stars"
                                                    name="rating" value="3"
                                                    {{ @$add_testimonial->star_rating == 3 ? "checked" : '' }} />
                                                <label for="3-stars"
                                                    class="star">&#9733;</label>
                                                <input type="radio"
                                                    id="2-stars"
                                                    name="rating" value="2"
                                                    {{ @$add_testimonial->star_rating == 2 ? "checked" : '' }} />
                                                <label for="2-stars"
                                                    class="star">&#9733;</label>
                                                <input type="radio"
                                                    id="1-star"
                                                    name="rating" value="1"
                                                    {{ @$add_testimonial->star_rating == 1 ? "checked" : '' }} />
                                                <label for="1-star"
                                                    class="star">&#9733;</label>
                                            </div>
                                            @if ($errors->has('rating'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('rating') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $tooltip = '';
                                    if (userPermission('store_testimonial')) {
                                        $tooltip = '';
                                    } else {
                                        $tooltip = 'You have no permission to add';
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">

                                        @if (Illuminate\Support\Facades\Config::get('app.app_sync'))
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                                title="Disabled For Demo "> <button
                                                    class="primary-btn fix-gr-bg  demo_view" style="pointer-events: none;"
                                                    type="button">@lang('front_settings.submit') </button></span>
                                        @else
                                            <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                                title="{{ @$tooltip }}">
                                                @if (isset($add_testimonial))
                                                    @lang('common.update')
                                                @else
                                                    @lang('common.add')
                                                @endif
                                            </button>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-15">@lang('front_settings.testimonial_list')</h3>
                            </div>
                        </div>
                    </div>
    
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
    
                                    <thead>
    
                                        <tr>
                                            <th>@lang('common.name')</th>
                                            <th>@lang('front_settings.designation')</th>
                                            <th>@lang('front_settings.institution_name')</th>
                                            <th>@lang('common.rating')</th>
                                            <th>@lang('front_settings.image')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>
    
                                    <tbody>
                                        @foreach ($testimonial as $value)
                                            <tr>
                                                <td>{{ @$value->name }}</td>
                                                <td>{{ @$value->designation }}</td>
                                                <td>{{ @$value->institution_name }}</td>
    
                                                <td>
                                                    <div class="star-rating">
                                                        <input type="radio"
                                                            id="5-stars{{ $value->id }}"
                                                            name="rating{{ $value->id }}" value="5"
                                                            {{ $value->star_rating == 5 ? 'checked' : '' }}
                                                            disabled />
                                                        <label for="5-stars{{ $value->id }}"
                                                            class="star">&#9733;</label>
    
                                                        <input type="radio"
                                                            id="4-stars{{ $value->id }}"
                                                            name="rating{{ $value->id }}" value="4"
                                                            {{ $value->star_rating == 4 ? 'checked' : '' }}
                                                            disabled />
                                                        <label for="4-stars{{ $value->id }}"
                                                            class="star">&#9733;</label>
    
                                                        <input type="radio"
                                                            id="3-stars{{ $value->id }}"
                                                            name="rating{{ $value->id }}" value="3"
                                                            {{ $value->star_rating == 3 ? 'checked' : '' }}
                                                            disabled />
                                                        <label for="3-stars{{ $value->id }}"
                                                            class="star">&#9733;</label>
    
                                                        <input type="radio"
                                                            id="2-stars{{ $value->id }}"
                                                            name="rating{{ $value->id }}" value="2"
                                                            {{ $value->star_rating == 2 ? 'checked' : '' }}
                                                            disabled />
                                                        <label for="2-stars{{ $value->id }}"
                                                            class="star">&#9733;</label>
    
                                                        <input type="radio"
                                                            id="1-star{{ $value->id }}"
                                                            name="rating{{ $value->id }}" value="1"
                                                            {{ $value->star_rating == 1 ? 'checked' : '' }}
                                                            disabled />
                                                        <label for="1-star{{ $value->id }}"
                                                            class="star">&#9733;</label>
                                                    </div>
                                                </td>
                                                <td><img src="{{ asset(@$value->image) }}" width="60px" height="50px">
                                                </td>
                                                <td>
                                                    <x-drop-down>
                                                        @if (userPermission('testimonial-details'))
                                                            <a href="{{ route('testimonial-details', @$value->id) }}"
                                                                class="dropdown-item small fix-gr-bg modalLink"
                                                                title="@lang('front_settings.testimonial_details')" data-modal-size="modal-lg">
                                                                @lang('common.view')
                                                            </a>
                                                        @endif
                                                        @if (userPermission('edit-testimonial'))
                                                            <a class="dropdown-item"
                                                                href="{{ route('edit-testimonial', @$value->id) }}">@lang('common.edit')</a>
                                                        @endif
    
                                                        @if (Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                            <span tabindex="0" data-toggle="tooltip"
                                                                title="Disabled For Demo"> <a href="#"
                                                                    class="dropdown-item small fix-gr-bg  demo_view"
                                                                    style="pointer-events: none;">@lang('common.delete')</a></span>
                                                        @else
                                                            @if (userPermission('for-delete-testimonial'))
                                                                <a href="{{ route('for-delete-testimonial', @$value->id) }}"
                                                                    class="dropdown-item small fix-gr-bg modalLink"
                                                                    title="@lang('front_settings.delete_testimonial')" data-modal-size="modal-md">
                                                                    @lang('common.delete')
                                                                </a>
                                                            @endif
                                                        @endif
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
    </section>
@endsection

@include('backEnd.partials.data_table_js')
@push('script')
    <script>
        $(document).on('change', '#addTestimonialImage', function(event) {
            $('#testimonialImageShow').removeClass('d-none');
            getFileName($(this).val(), '#placeholderFileOneName');
            imageChangeWithFile($(this)[0], '#testimonialImageShow');
        });
    </script>
@endpush
