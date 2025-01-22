@extends('backEnd.master')
@section('title')
    @lang('front_settings.photo_gallery')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.photo_gallery')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('front_settings.frontend_cms')</a>
                    <a href="{{ route('photo-gallery') }}">@lang('front_settings.photo_gallery')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="row row-gap-24">
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        @if (isset($add_photo_gallery))
                            {{ Form::open([
                                'class' => 'form-horizontal',
                                'files' => true,
                                'route' => 'photo-gallery-update',
                                'method' => 'POST',
                                'enctype' => 'multipart/form-data',
                            ]) }}
                            <input type="hidden" value="{{ @$add_photo_gallery->id }}" name="id">
                        @else
                            @if (userPermission('photo-gallery-store'))
                                {{ Form::open([
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'route' => 'photo-gallery-store',
                                    'method' => 'POST',
                                    'enctype' => 'multipart/form-data',
                                ]) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="main-title">
                                <h3 class="mb-15">
                                    @if (isset($add_photo_gallery))
                                        @lang('front_settings.edit_photo_gallery')
                                    @else
                                        @lang('front_settings.photo_gallery')
                                    @endif
                                </h3>
                            </div>
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.name') <span
                                                    class="text-danger"> *</span></label>
                                            <input
                                                class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                type="text" name="name" autocomplete="off"
                                                value="{{ isset($add_photo_gallery) ? @$add_photo_gallery->name : old('name') }}">
                                            @if ($errors->has('name'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('name') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Summernote CSS and JS -->
                                <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css" rel="stylesheet">
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>

                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.description') <span class="text-danger"> *</span></label>
                                            <textarea class="primary_input_field form-control{{ $errors->has('description') ? ' is-invalid' : '' }} newsSummerNote" cols="0"
                                                rows="4" name="description" id="address">{{ isset($add_photo_gallery) ? @$add_photo_gallery->description : old('description') }}</textarea>
                                            @if ($errors->has('description'))
                                                <span class="text-danger d-block">
                                                    {{ $errors->first('description') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('front_settings.feature_image') <span
                                                    class="text-danger"> *</span></label>
                                            <div class="primary_file_uploader">
                                                <input
                                                    class="primary_input_field form-control{{ $errors->has('feature_image') ? ' is-invalid' : '' }}"
                                                    type="text" id="placeholderFileOneName" name="feature_image"
                                                    placeholder="{{ isset($add_photo_gallery) ? (@$add_photo_gallery->feature_image != '' ? getFilePath3(@$add_photo_gallery->feature_image) : trans('front_settings.feature_image') . ' *') : trans('front_settings.feature_image') . ' *' }}"
                                                    id="placeholderUploadContent" readonly>
                                                <button class="" type="button">
                                                    <label class="primary-btn small fix-gr-bg"
                                                        for="addPhotoGalleryImage">{{ __('common.browse') }}</label>
                                                    <input type="file" class="d-none" name="feature_image"
                                                        id="addPhotoGalleryImage">
                                                </button>
                                            </div>
                                            @if ($errors->has('feature_image'))
                                                <span class="text-danger">
                                                    {{ $errors->first('feature_image') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <img class="previewImageSize {{ @$add_photo_gallery->feature_image ? '' : 'd-none' }}" src="{{ @$add_photo_gallery->feature_image ? asset($add_photo_gallery->feature_image) : '' }}" alt="" id="photoGalleryImageShow" height="100%" width="100%">
                                    </div>
                                </div>
                                <div class="row mt-40 align-items-center">
                                    <div class="col-lg-10 col-9">
                                        <div class="main-title mt-0">
                                            <h5>@lang('front_settings.add_gallery_photos') </h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-3 text-right">
                                        <a href="javascript:void(0)" class="primary-btn icon-only fix-gr-bg"
                                            id="addRowBtn">
                                            <span class="ti-plus"></span>
                                        </a>
                                    </div>
                                </div>
                                <div id="galleryImages">
                                    @if (@$add_photo_gallery)
                                        @foreach (@$add_photo_galleries as $galleryImage)
                                            <div class="row mt-10 gallery-div-row">
                                                <div class="col-lg-10">
                                                    <div class="primary_input">
                                                        <label
                                                            class="primary_input_label">@lang('front_settings.gallery_image')<span></span></label>
                                                        <div class="primary_file_uploader">
                                                            <input
                                                                class="primary_input_field form-control {{ $errors->has('gallery_image') ? ' is-invalid' : '' }} file-upload-multi-placeholder"
                                                                readonly="true" type="text"
                                                                id="placeholderInputUpdate{{ @$galleryImage->id }}"
                                                                placeholder="{{ isset($galleryImage->gallery_image) && @$galleryImage->gallery_image != '' ? getFilePath3(@$galleryImage->gallery_image) : 'Upload File' }}">
                                                            <button class="" type="button">
                                                                <label class="primary-btn small fix-gr-bg"
                                                                    for="upload_update_file{{ @$galleryImage->id }}">@lang('common.browse')</label>
                                                                <input type="file"
                                                                    class="d-none form-control file-upload-multi"
                                                                    name="gallery_image[{{ @$galleryImage->id }}][image]"
                                                                    id="upload_update_file{{ @$galleryImage->id }}">
                                                                <input type="hidden"
                                                                    name="gallery_image[{{ @$galleryImage->id }}][image]"
                                                                    value="{{ @$galleryImage->gallery_image }}">
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 mt-35">
                                                    <button class="primary-btn icon-only fix-gr-bg remove-gallery-image"
                                                        type="button">
                                                        <span class="ti-trash"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        @if(config('app.app_sync'))
                                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >@lang('common.add')</button></span>
                                        @else
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip"
                                            title="{{ @$tooltip }}">
                                            @if (isset($add_photo_gallery))
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

            <div class="col-lg-8">
                <div class="white-box">
                    <div class="row">
                        <div class="col-xl-4 col-sm-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-15">@lang('front_settings.photo_gallery_list')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table photoGallery" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('common.sl')</th>
                                            <th>@lang('front_settings.name')</th>
                                            <th>@lang('front_settings.image')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($photoGalleries as $key => $value)
                                            <input type="hidden" id="url" value="{{URL::to('/')}}">
                                            <input type="hidden" id="photo_gallery_id" value="{{ @$value->id }}">
                                            <tr e_id="{{$value->id}}">
                                                <td><span class="mr-2" style="cursor: grab"><i class="ti-menu"></i></span>{{ $key + 1 }}</td>
                                                <td>{{ @$value->name }}</td>
                                                <td><img src="{{ asset(@$value->feature_image) }}" width="60px"
                                                        height="50px">
                                                </td>
                                                <td>
                                                    <x-drop-down>
                                                        <a class="dropdown-item" data-toggle="modal"
                                                            data-target="#photogallery{{ @$value->id }}"
                                                            onclick="getPhotoGallery({{ @$value->id }})"
                                                            href="#">
                                                            @lang('common.view')
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('photo-gallery-edit', @$value->id) }}">@lang('common.edit')</a>
                                                        <a href="{{ route('photo-gallery-delete-modal', @$value->id) }}"
                                                            class="dropdown-item small fix-gr-bg modalLink"
                                                            title="@lang('front_settings.delete_photo_gallery')" data-modal-size="modal-md">
                                                            @lang('common.delete')
                                                        </a>
                                                    </x-drop-down>
                                                </td>
                                            </tr>
                                            <div class="modal fade admin-query" id="photogallery{{ @$value->id }}">
                                                <div class="modal-dialog modal-dialog-centered large-modal">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('front_settings.view_images')</h4>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="col-lg-12" id="photo_gallery_list">

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
    </section>
@endsection

@include('backEnd.partials.data_table_js')
@push('script')

    <script>
        $(document).ready(function() {
            $('.newsSummerNote').summernote({
                height: 200,
                placeholder: 'Enter description here...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture', 'video']]
                ]
            });
        });
    </script>
    
    <script type="text/javascript">
        function getPhotoGallery(galleryId) {
            var url = $('#url').val();
            $.ajax({
                type: "GET",
                data: {
                    galleryId: galleryId
                },
                url: url+"/photo-gallery-view-modal/" + galleryId,
                dataType: "html",
                success: function(response) {
                    $('#photo_gallery_list').html(response);
                },
                error: function(error) {
                    toastr.error(error.message, 'Error');
                }
            });
        }
        var totalData = 0;
        $(document).ready(function() {
            $(document).on('click', '#addRowBtn', function(event) {
                event.preventDefault();
                $('#galleryImages').append(
                    `<div class="row mt-10 gallery-div-row">
                    <div class="col-lg-10">
                        <div class="primary_input">
                            <label class="primary_input_label">@lang('front_settings.gallery_image')<span></span></label>
                            <div class="primary_file_uploader">
                                <input
                                    class="primary_input_field form-control {{ $errors->has('gallery_image') ? ' is-invalid' : '' }} file-upload-multi-placeholder"
                                    readonly="true" type="text"
                                    id="placeholderInputUpdate${totalData}"
                                    placeholder="@lang('front_settings.upload_file')">
                                <button class="" type="button">
                                    <label class="primary-btn small fix-gr-bg"
                                        for="upload_update_file${totalData}">@lang('common.browse')</label>
                                    <input type="file"
                                        class="d-none form-control file-upload-multi" data-id="${totalData}"
                                        name="gallery_image[${totalData}][image]"
                                        id="upload_update_file${totalData}">
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 mt-35">
                        <button class="primary-btn icon-only fix-gr-bg remove-gallery-image"
                            type="button">
                            <span class="ti-trash"></span>
                        </button>
                    </div>
                </div>`
                );
                totalData += 1;
            });

            $(document).on('click', '.remove-gallery-image', function(e) {
                e.preventDefault();
                $(this).parents('.gallery-div-row').remove();
            });
        });

        $(document).on('change', '.file-upload-multi', function(e) {
            let fileName = e.target.files[0].name;
            $(this).parent().parent().find('.file-upload-multi-placeholder').attr('placeholder', fileName);
        });

        datableArrange('.photoGallery', 'sm_photo_galleries');
        
        $(document).on('change', '#addPhotoGalleryImage', function(event) {
            $('#photoGalleryImageShow').removeClass('d-none');
            getFileName($(this).val(), '#placeholderFileOneName');
            imageChangeWithFile($(this)[0], '#photoGalleryImageShow');
        });
    </script>
@endpush
