@extends('backEnd.master')

@section('title')
    @lang('common.news')
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/') }}/Modules/News/public/assets/css/style.css" />
@endpush

@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> @lang('common.news_details')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('common.news')</a>
                    <a href="#">@lang('common.news_details')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">              
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 course__details">
                                <div class="text-center">
                                    <img class="img-fluid" src="{{asset($news->image)}}" alt="">
                                </div>
                                <div class="course__details_title mt-5">
                                    <div class="single__details">
                                        <div class="details_content">
                                            <span>@lang('common.category')</span>
                                            <h4 class="f_w_700">{{ @$news->newsCategory->title }}</h4>
                                        </div>
                                    </div>
                                    <div class="single__details">
                                        <div class="details_content">
                                            <span>@lang('common.title')</span>
                                            <h4 class="f_w_700">{{ @$news->title }}</h4>
                                        </div>
                                    </div>
                                    <div class="single__details">
                                        <div class="details_content text-center">
                                            <span>@lang('common.tags')</span>
                                            <div class="d-flex">
                                            @foreach ($news->tags as $tag)
                                                <button class="btn btn-sm btn-primary ml-2"><i class="fa fa-tags"></i> {{$tag->title}} </button>
                                            @endforeach
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="user-news-description justify-between">
                                    <p> {!! $news->description !!}</p>
                                </div>
                            </div>                           
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="course_review_wrapper">
                                    <div class="course_cutomer_reviews">
                                        <div class="details_title">
                                            <h4 class="font_22 f_w_700">@lang('common.comments')</h4>
                                        </div>
                                        <div class="customers_reviews">
                                            @foreach ($news->newsComments as $comment)
                                                <div class="single_reviews">
                                                    <div class="thumb">
                                                        @if ($comment->user->role_id == 2)
                                                            <img class="img-fluid rounded-circle"
                                                                src="{{ asset($comment->user->student->student_photo) }}"
                                                                alt="{{ @$comment->user->first_name }}">
                                                        @elseif($comment->user->role_id == 3)
                                                            <img class="img-fluid rounded-circle"
                                                                src="{{ asset($comment->user->parent->guardians_photo) }}"
                                                                alt="{{ @$comment->user->parent->first_name }}">
                                                        @else
                                                            <img class="img-fluid rounded-circle"
                                                                src="{{ !is_null($comment->user->staff->staff_photo) ? asset($comment->user->staff->staff_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                                                                alt="{{ @$comment->user->staff->first_name }}">
                                                        @endif
                                                    </div>
                                                    <div class="review_content">
                                                        <h4 class="f_w_700">{{ @$comment->user->full_name }}
                                                        </h4>
                                                        <div class="rated_customer d-flex align-items-center">
                                                            <span>{{ dateconvert($comment->created_at) }}</span>
                                                        </div>
                                                        <p>{{ $comment->comment }}</p>
                                                        <div class="replay_btn text-right">
                                                            <button class="primary-btn small fix-gr-bg" data-toggle="collapse" data-target="#mainComment{{$comment->id}}" aria-expanded="false" aria-controls="collapseExample">reply </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="comment_replay_wized">
                                                    <div class="col-lg-12 collapse" id="mainComment{{$comment->id}}">
                                                        <form action="{{ route('user-news-comment-reply.store') }}" method="post" class="">
                                                            @csrf
                                                            <input type="hidden" name="news_id" value="{{ @$news->id }}">
                                                            <input type="hidden" name="comment_id" value="{{ @$comment->id }}">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="section_title3 mb_20">
                                                                        <h3>{{ __('common.write_your_reply') }}</h3>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12">
                                                                    <div class="single_input mb_20">
                                                                        <textarea placeholder=" {{ __('common.write_your_answer') }}" name="reply" class="primary_textarea gray_input form-control"></textarea>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 mb_30 ">
                                                                    <button type="submit" class="w-100 text-center mb_10 cart_store primary-btn fix-gr-bg height_50"> {{ __('common.submit') }}</button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                    
                                                    @foreach ($comment->replies as $reply)
                                                        <div class="single_reviews">
                                                            <div class="thumb">
                                                                @if ($reply->user->role_id == 2)
                                                                    <img class="img-fluid rounded-circle" src="{{ asset($reply->user->student->student_photo) }}" alt="{{ @$reply->user->first_name }}">
                                                                @elseif($reply->user->role_id == 3)
                                                                    <img class="img-fluid rounded-circle" src="{{ asset($reply->user->parent->guardians_photo) }}" alt="{{ @$reply->user->parent->first_name }}">
                                                                @else
                                                                    <img class="img-fluid rounded-circle" src=" {{ !is_null($reply->user->staff->staff_photo) ? asset($reply->user->staff->staff_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" alt="{{ @$reply->user->staff->first_name }}">
                                                                @endif
                                                            </div>

                                                            <div class="review_content">
                                                                <h4 class="f_w_700">
                                                                    {{ @$reply->user->full_name }}</h4>
                                                                <div class="rated_customer d-flex align-items-center">
                                                                    <span>{{ dateconvert($reply->created_at) }}</span>
                                                                </div>
                                                                <p>{{ $reply->reply }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="conversition_box white-box">

                                    <div id="conversition_box"></div>

                                    <div class="row">
                                        <div class="col-lg-12 " id="mainComment0">
                                            <form action="{{ route('user-news-comment.store') }}" method="post" class="">
                                                @csrf
                                                <input type="hidden" name="news_id" value="{{ @$news->id }}">

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="section_title3 mb_20">
                                                            <h3>{{ __('common.leave_a_comment') }}</h3>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div class="single_input mb_25">
                                                            <textarea rows="4" placeholder="{{ __('common.leave_a_comment') }}" name="comment" class="form-control mb-25"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 mb_30">
                                                        <button type="submit" class="w-100 text-center mb_10 cart_store primary-btn fix-gr-bg height_50">
                                                            <i class="fa fa-comments"></i> {{ __('common.comment') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')