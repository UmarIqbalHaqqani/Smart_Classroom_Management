@extends(config('pagebuilder.site_layout'),['edit' => false ])
@section(config('pagebuilder.site_section'))
{{headerContent()}}
    <section class="bradcrumb_area" style="background-image:url('{{$newsPage->image != ""? $newsPage->image : '../img/client/common-banner1.jpg'}}')">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{__('edulia.news')}} <span><a href="{{url('/')}}">{{__('edulia.home')}}</a> / {{__('edulia.news')}}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section_padding blog">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 col-md-12">
                    <div class="blog_card">
                        <div class="row">
                            <div class="col-lg-8 col-md-7 all_news_new">
                                @foreach($news as $value)
                                    <div class="blog_card_wrapper">
                                        @if (file_exists($value->image))
                                            <div class="blog_card_wrapper_img"><img src="{{asset($value->image)}}" alt="{{$value->news_title}}"></div>
                                        @endif
                                        <div class="blog_card_wrapper_content">
                                            <a href="{{route('frontend.news-details',$value->id)}}" class='blog_card_wrapper_content_title'>{{$value->news_title}}</a>
                                            <p class="blog_card_wrapper_content_meta">{{dateConvert($value->publish_date)}}</p>
                                            <p>{!! $value->news_body !!}</p>
                                            <a href="{{route('frontend.news-details',$value->id)}}">+ {{__('edulia.read_more')}}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-lg-4 col-md-5">
                                <div class="blog_widget">
                                    <div class="blog_widget_search">
                                        <label for="#" class='blog_widget_search_icon'><i class="far fa-search"></i></label>
                                        <input type="text" class="input-control-input" id="newsFrontSearch" placeholder='{{__('edulia.search')}}…'>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <div class="events_loadmore">
                                    <a href="#" class="site_btn load_more_btn_news">{{__('edulia.load_more')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
{{footerContent()}}
@endsection
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        $("#newsFrontSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".blog_card_wrapper").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $(document).on('click', '.load_more_btn_news', function (e) {
            e.preventDefault();
            var totalNews = $('.news_count').length;
            $.ajax({
                url: "{{route('frontend.load-more-blog')}}",
                method: "POST",
                data: {
                    skip: totalNews,
                    _token: "{{csrf_token()}}",
                },
                success: function (response) {
                    var hideButtonNew = $('#hide-button-new-news').val();
                    var countCourseNew = $('#count-news-new-news').val();
                    for (var count  in response) count++;
                        $(".all_news_new").append(response);

                    if(countCourseNew  >= hideButtonNew){
                        $('.load_more_btn_news').hide();
                    }else{
                        $('.load_more_btn_news').show();
                    }
                }
            })
        })
    </script>
@endpushonce