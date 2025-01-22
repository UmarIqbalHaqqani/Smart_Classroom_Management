<style>
    .photo-gallery-image {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2; 
        -webkit-box-orient: vertical;
    }
</style>

<div class="row mb-minus-24">
    @php
        $photoGalleryCount = $photoGalleries->count();
        $isAdmin = auth()->check() && auth()->user()->role_id == 1;
        $count =  pagesetting('photo_gallery_count');
    @endphp
    @if ($photoGalleryCount < 1 && $isAdmin)
        <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank" href="{{ URL::to('/photo-gallery') }}">@lang('edulia.photo_gallery')</a></p>
    @else
    @foreach ($photoGalleries->take((int) $count) as $photoGallery)
        <div class="totalblog col-lg-{{ $column }} 
            @if ($column == '12' )
                col-md-12
                @elseif ($column == '6')
                col-md-12
                @elseif ($column == '4')
                col-md-6 col-sm-12
                @elseif ($column == '3')
                col-md-4 col-sm-6
                @elseif ($column == '2')
                col-md-3 col-sm-4 col-6
                @elseif ($column == '1')
                col-md-2 col-sm-3 col-6
            @endif
            " id="{{@$column}}">
                <a href='{{ route('frontend.gallery-details', $photoGallery->id) }}' class="gallery_item">
                    <div class="gallery_item_img">
                        <img src="{{ asset($photoGallery->feature_image) }}" alt="">
                    </div>
                    <div class="gallery_item_inner">
                        <h4 class="photo-gallery-image">{{ $photoGallery->name }}</h4>
                    </div>
                </a>
            </div>
        @endforeach
        <div id="dynamicLoadMoreData">

        </div>

        @if ((Request::is('/') || Request::is('home')) || Request::segment(1) == 'pages')

        @else
            @if (Request::is('gallery'))
                @if ($photoGalleryCount > $count)
                    <div class="row text-center">
                        <div class="col-md-12">
                            <div class="load_more section_padding_top">
                                <a href="#" class="site_btn load_more_photo_btn" data-skip="{{$count}}">{{ __('edulia.load_more') }}</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endif
    @endif
</div>

@pushonce(config('pagebuilder.site_script_var'))
    <script>

        $(document).on('click', '.load_more_photo_btn', function (e) {
            e.preventDefault();
            var skip = $(this).data('skip');
            var take = {{ $count }};
            var row_each_column = {{ $column }};

            $.ajax({
                url: "{{ route('frontend.load-more-photo-gallery-list') }}",
                method: "POST",
                data: {
                    skip: skip,
                    row_each_column : row_each_column,
                    take : take,
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.success) {
                        $('#dynamicLoadMoreData').append(response.html);

                        $('.load_more_photo_btn').data('skip', skip + take);

                        if (!response.has_more) {
                            $('.load_more_photo_btn').hide();
                        }
                    } else {
                        console.error('Failed to load more photos.');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                },
            });
        });

    </script>
@endpushonce