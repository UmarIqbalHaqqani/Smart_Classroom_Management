@extends(config('pagebuilder.site_layout'), ['edit' => false])
@section(config('pagebuilder.site_section'))
    {{ headerContent() }}
    @push(config('pagebuilder.site_style_var'))
        <style>
            .archive_widget_item_keywords li {
                cursor: default;
            }
        </style>
    @endpush
    @php
        $gs = generalSetting();
    @endphp
    <section class="bradcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>
                            {{ __('edulia.archive_list') }}
                            <span>
                                <a href="{{ url('/') }}">{{ __('edulia.home') }}</a> /{{ __('edulia.archive_list') }}
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section_padding archive">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 col-md-12">
                    <div class="archive_card">
                        <div class="row">
                            <div class="col-lg-8 col-md-7">
                                <div class="col-lg-12 col-md-12" id="dynamicLoadMoreData">
                                    @forelse ($archives->paginate(5) as $item)
                                        <div class="archive_card_wrapper no-img searchArchiveContent">
                                            <div class="archive_card_wrapper_content">
                                                <a href="{{ route('frontend.news-details', $item->id) }}"
                                                    class='archive_card_wrapper_content_title'>{{ $item->news_title }}</a>
                                                <p class="archive_card_wrapper_content_meta">
                                                    {{ dateConvert($item->publish_date) }} /
                                                    {{ $item->category->category_name }}</p>
                                                <p>{!! $item->news_body !!}</p>
                                                <a href="{{ route('frontend.news-details', $item->id) }}">+
                                                    {{ __('edulia.read_more') }}</a>
                                                <input type="hidden" name="createdYear" id="createdYear"
                                                    value="{{ $item->created_at->format('Y') }}">
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center text-danger">
                                            <div class="archive_card_wrapper_content">
                                                <p>{{ __('edulia.no_data_found') }}</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="archive_btns">
                                        <a href="#"
                                            class="boxed_btn load_more_archive_btn">{{ __('edulia.load_more') }}</a>
                                    </div>
                                </div>
                            </div>
                            @if ($gs->blog_search == 1)
                                <div class="col-lg-4 col-md-5">
                                    <div class="archive_widget">
                                        <div class="archive_widget_search">
                                            <label for="#" class='archive_widget_search_icon'><i
                                                    class="far fa-search"></i></label>
                                            <input type="text" class="input-control-input"
                                                placeholder='{{ __('edulia.search') }}' id="archiveAllContentSearch">
                                        </div>
                                        <div class="archive_widget_item">
                                            <h5>Archive in Year</h5>
                                            @foreach ($archiveYears as $key => $item)
                                                <label for="#" class="checkbox archive_year">
                                                    <input type="checkbox" class="checkbox-input archive_year_value"
                                                        value="{{ $key }}">
                                                    <span class="checkbox-title">Archive in {{ $key }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <div class="archive_widget_item border-padding-none ">
                                            <h5>Keywords</h5>
                                            <ul class="archive_widget_item_keywords">
                                                @foreach ($archiveCategories as $archiveCategory)
                                                    <li>{{ $archiveCategory->category_name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{ footerContent() }}
@endsection
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        $("#archiveAllContentSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".searchArchiveContent").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $(document).ready(function() {
            if ($('.text-danger').length > 0) {
                $('.load_more_archive_btn').hide();
            }
        });

        $(document).on('click', '.load_more_archive_btn', function(e) {
            e.preventDefault();
            var totalBlog = $('.searchArchiveContent').length;
            var year = [];
            $(".archive_year_value:checked").each(function(i) {
                year[i] = $(this).val();
            });
            if (year.length == 0) {
                ajaxRequest(totalBlog, null);
            } else {
                ajaxRequest(totalBlog, year);
            }
        })
        $(document).on('click', '.archive_year', function(e) {
            var year = [];
            $(".archive_year_value:checked").each(function(i) {
                year[i] = $(this).val();
            });
            $.ajax({
                url: "{{ route('frontend.archive-year-filter') }}",
                method: "GET",
                data: {
                    year: year,
                    data_count: year.length,
                },
                success: function(response) {
                    $('#dynamicLoadMoreData').empty();
                    $('#dynamicLoadMoreData').append(response.html);
                }
            })
        })

        function ajaxRequest(totalBlog, selectedYear = null) {
            $.ajax({
                url: "{{ route('frontend.load-more-archive-list') }}",
                method: "POST",
                data: {
                    skip: totalBlog,
                    year: selectedYear,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (totalBlog == response.total_data) {
                        $('.load_more_archive_btn').hide();
                    } else {
                        $('#dynamicLoadMoreData').append(response.html);
                    }

                    if ($('.text-danger').length > 0) {
                        $('.load_more_archive_btn').hide();
                    }
                }
            })
        }
    </script>
@endpushonce
