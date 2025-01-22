@extends(config('pagebuilder.site_layout'),['edit' => false ])
@section(config('pagebuilder.site_section'))
{{headerContent()}}
<style>
    .cmt_rp_fornt{
        padding: 10px 21px;
        font-size: 12px;
        line-height: 0.8;
    }
    .cmt-gap{
        gap: 10px;
    }
</style>
    <section class="bradcrumb_area" style="background-image:url('{{asset($news->image)}}')">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{__('edulia.blog_details')}} <span><a href="{{url('/')}}">{{__('edulia.home')}}</a> / {{__('edulia.blog_details')}}</span></h1>
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
                            <div class="col-lg-12 col-md-12">
                                <div class="blog_details">
                                    <div class="blog_details_img"><img src="{{asset($news->image)}}" alt="{{$news->news_title}}"></div>
                                    <div class="blog_details_wrapper">
                                        <span class="blog_details_wrapper_date">{{$news->category->category_name}} . {{$news->publish_date != ""? dateConvert($news->publish_date):''}}</span>
                                        <h3>{{$news->news_title}}</h3>
                                        {!!$news->news_body!!}
                                    </div>
                                </div>

                                <div class="blog_comments">
                                    <h3>{{$news->newsComments->count()}} {{__('edulia.comments')}}</h3>
                                    @include('frontEnd.theme.edulia.news.comment_page', ['newsComments' => $news->newsComments, 'level' => 0])
                                </div>
                                <div class="blog_leave_comment normalComment">
                                    <h3>{{__('edulia.leave_a_comment')}}</h3>
                                    @if (!auth()->check())
                                        <p>{{__('edulia.Sing in to post your comment or singup if you don’t have any account.')}}</p>
                                    @endif
                                </div>
                                @php
                                    $gs = generalSetting();
                                @endphp
                                @if (auth()->check() && $news->is_global == 1 && $gs->is_comment == 1)
                                    @include('frontEnd.theme.edulia.news.comment_form')
                                @elseif(auth()->check() && $news->is_global == 0 && $news->is_comment == 1)
                                    @include('frontEnd.theme.edulia.news.comment_form')
                                @endif
                            </div>
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
    $(document).ready(function(){
        $(document).on('click', '.newsReplyBtn', function(e){
            e.preventDefault();
            var commentId = $(this).data('comment-id');
            $('.replyDiv_'+commentId).slideToggle();
            $('.normalComment').slideToggle();
            $('.replyDiv_'+commentId).find('.parentId').val(commentId);
        })
    })
</script>
@endpushonce