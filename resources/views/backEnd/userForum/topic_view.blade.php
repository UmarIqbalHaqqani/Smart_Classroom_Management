@extends('backEnd.master')

@section('title')
    @lang('common.forum_topic_details')
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/') }}/Modules/Forum/public/assets/css/style.css" />

    <style>
        .single__details .details_content .d-flex {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .single__details .details_content .d-flex button {
            flex: 0 1 calc(25% - 20px); 
            margin: 10px;
            box-sizing: border-box;
        }

        @media (max-width: 768px) {
            .single__details .details_content .d-flex button {
                flex: 0 1 calc(50% - 20px);
            }
        }

        @media (max-width: 576px) {
            .single__details .details_content .d-flex button {
                flex: 0 1 calc(100% - 20px); 
            }
        }

        .course__details {
            padding: 10px 15px;
            background: var(--bg_white);
            border-radius: 10px;
            box-shadow: none;
        }
        .f_s_20 {
            font-size: 16px;
        }
        .course_review_wrapper .course_cutomer_reviews .single_reviews {
            flex-direction: column;
        }
        .single_reviews .thumb {
            height: 67px!important;;
            width: 67px!important;
            flex: 67px 0 0!important;
            aspect-ratio: 1/1;
            margin-bottom: 0 !important;
            margin-right: 10px;
            line-heigth: 67px!important;
            }

        .single_reviews .thumb img{
            aspect-ratio: 1/1;
            height: 67px;
            width: 67px;
            object-fit: cover;
            object-position: center;
        }
        

        .course__details_title  .single__details{
            margin-bottom: 0!important;
        }
        .vote-section button{
            height: 32px;
            line-height: 1;
        }
        .vote-section button.btn-success{
            background: #38C172;
        }
        .vote-section button.btn-danger{
            background: #FF2020;
        }
        .vote-section i, .vote-section span {
            font-size: 12px;
        }

        .course_review_wrapper .course_cutomer_reviews .single_reviews .thumb {
            margin-right: 10px;
        }
        .review_content h4, .single_reviews h4 {
            font-size: 24px;
            text-transform: capitalize;
            color: #1F2B40;
            font-weight: 600;
        }

        .review_content span, .single_reviews span {
            font-size: 14px;
            font-weight: 600;
            color: #637083;
        }

        .course_review_wrapper .course_cutomer_reviews .single_reviews .review_content .rated_customer {
            margin-top: 0px
        }

        .course_review_wrapper .course_cutomer_reviews .single_reviews .thumb{
            line-height: 64px!important;
        }

        .single_reviews .small.fix-gr-bg{
            padding: 3px;
            aspect-ratio: 1/1;
            height: 32px;
            width: 32px;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .single_reviews .small.fix-gr-bg.type1{
            background: #7C32FF;
        }
        .single_reviews .small.fix-gr-bg.type2{
            background: #FF2020;
        }

        .single_reviews .small.fix-gr-bg i{
            margin: 0;
        }

                
        .reply_btn_el {
            background: linear-gradient(77.16deg, #660AFB 13.44%, #BF37FF 87.24%);
            border: 0;
            padding: 2px 10px !important;
            color: white;
            display: inline-block !important;
            width: auto !important;
            border-radius: 2px !important;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
        }
        #reply-upvote-count, #reply-downvote-count, #comment-upvote-count, #comment-downvote-count {
            font-size: 12px;
            color: inherit;
            font-weight: inherit;
            vertical-align: middle;
        }

        .img-fluid.rounded-circle {
            display: block;
        }
        .comment_replay_wized {
            margin-left: 50px;
            border: 1px solid #E9E7F7;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 2px;
        }
        .single_reviews {
            border: 1px solid #E9E7F7;
            padding: 20px;
            margin-bottom: ;
            border-bottom: 1px solid #E9E7F7 !important;
            padding-bottom: 20px!important;
            border-radius: 2px;
        }
        .comment_replay_wized .single_reviews {
            border: none !important;
            margin-bottom: 0!important;
            border-radius: 2px;
            padding: 0!important;
        }

        .course_review_wrapper .course_cutomer_reviews .single_reviews {
        margin-bottom: 30px;
        }
    </style>
    @endpush

@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> @lang('common.forum_topic_details')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('common.forum')</a>
                    <a href="#">@lang('common.forum_topic_details')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-9">              
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-12 course__details">
                                <div class="single_reviews mb-4">
                                    <div class="d-flex align-items-center gap-10">
                                        <div class="thumb" style="width: 45px">
                                            <img class="img-fluid rounded-circle"
                                                src="{{  asset('public/uploads/staff/demo/staff.jpg') }}"
                                                >
                                        </div>
                                        <div class="review_content">
                                            <h4 class="mb-0">{{ @$forum->createdBy->full_name }}
                                            </h4>
                                            <div class="rated_customer d-flex align-items-center">
                                                <span>{{ dateconvert(@$forum->created_at) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="course__details_title mb-4">
                                    <div class="single__details">
                                        <div class="details_content">
                                            <span>@lang('common.category')</span>
                                            <h4 class="">{{ @$forum->forumTitle->forumCategory->title }}</h4>
                                        </div>
                                    </div>
                                    <div class="single__details">
                                        <div class="details_content">
                                            <span>@lang('common.forum_title')</span>
                                            <h4 class="">{{ @$forum->forumTitle->title }}</h4>
                                        </div>
                                    </div>
                                    <div class="single__details">
                                        <div class="details_content">
                                            <span>@lang('common.forum_topic')</span>
                                            <h4 class="">{{ @$forum->title }}</h4>
                                        </div>
                                    </div>

                                    {{-- @php
                                        $roleMap = [
                                            2 => 'Teacher',
                                            3 => 'Admin',
                                            4 => 'Student',
                                            5 => 'Parent',
                                            6 => 'Accountant',
                                            7 => 'Receptionist',
                                            8 => 'Librarian',
                                            9 => 'Driver',
                                        ];
                                        $availableFor = json_decode($forum->available_for, true);
                                        $authUserId = Auth::user()->id;
                                    @endphp

                                    @if ($forum->created_by == $authUserId)
                                        <div class="single__details">
                                            <div class="details_content text-center">
                                                <span>@lang('common.available_for')</span>
                                                <div class="d-flex flex-wrap justify-content-center">
                                                    @foreach ($availableFor as $roleId)
                                                        <button class="btn btn-sm btn-primary m-2"><i class="fa fa-key"></i> {{ $roleMap[$roleId] ?? 'Other' }} </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif --}}
                                </div>
                                <div class="user-forum-description justify-between">
                                    <p> {!! $forum->description !!}</p>
                                </div>

                                @php
                                    $createdBy = $forum->created_by;
                                    $authUserId = auth()->user()->id;
                                @endphp

                                @if ($createdBy != $authUserId)
                                    <div class="vote-section mt-4">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <button class="btn btn-success btn-vote" id="upvote-button" onclick="handleVote('upvote', {{ $forum->id }})">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4.98828 12.2332L7.05495 13.8331C7.32161 14.0998 7.92161 14.2331 8.32161 14.2331H10.8549C11.6549 14.2331 12.5216 13.6331 12.7216 12.8331L14.3216 7.96648C14.6549 7.03315 14.0549 6.23315 13.0549 6.23315H10.3883C9.98828 6.23315 9.65495 5.89982 9.72161 5.43315L10.0549 3.29982C10.1883 2.69982 9.78828 2.03315 9.18828 1.83315C8.65495 1.63315 7.98828 1.89982 7.72161 2.29982L4.98828 6.36648"
                                                    stroke="white" stroke-width="1.5" stroke-miterlimit="10" />
                                                <path
                                                    d="M1.58594 12.2331V5.69977C1.58594 4.76644 1.98594 4.43311 2.91927 4.43311H3.58594C4.51927 4.43311 4.91927 4.76644 4.91927 5.69977V12.2331C4.91927 13.1664 4.51927 13.4998 3.58594 13.4998H2.91927C1.98594 13.4998 1.58594 13.1664 1.58594 12.2331Z"
                                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg> <span id="upvote-count">{{ $forum->upvotes }}</span>
                                            </button>
                                            <button class="btn btn-danger btn-vote ml-2" id="downvote-button" onclick="handleVote('downvote', {{ $forum->id }})">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.0119 3.7666L8.94519 2.1666C8.67852 1.89993 8.07852 1.7666 7.67852 1.7666H5.14519C4.34519 1.7666 3.47852 2.3666 3.27852 3.1666L1.67852 8.03327C1.34519 8.9666 1.94519 9.7666 2.94519 9.7666H5.61185C6.01185 9.7666 6.34519 10.0999 6.27852 10.5666L5.94519 12.6999C5.81185 13.2999 6.21185 13.9666 6.81185 14.1666C7.34519 14.3666 8.01185 14.0999 8.27852 13.6999L11.0119 9.63327"
                                                    stroke="white" stroke-width="1.5" stroke-miterlimit="10" />
                                                <path
                                                    d="M14.4115 3.76667V10.3C14.4115 11.2333 14.0115 11.5667 13.0781 11.5667H12.4115C11.4781 11.5667 11.0781 11.2333 11.0781 10.3V3.76667C11.0781 2.83333 11.4781 2.5 12.4115 2.5H13.0781C14.0115 2.5 14.4115 2.83333 14.4115 3.76667Z"
                                                    stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg> <span id="downvote-count">{{ $forum->downvotes }}</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>                           
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="course_review_wrapper">
                                    <div class="course_cutomer_reviews">
                                        <div class="details_title">
                                            <h4 class="font_22 ">@lang('common.comments')</h4>
                                        </div>
                                        <div class="customers_reviews">
                                            @foreach ($forum->forumComments as $comment)
                                                <div class="single_reviews">
                                                    <div class="d-flex gap-10">
                                                        <div class="thumb">
                                                            @if ($comment->user)
                                                                {{-- @if ($comment->user->role_id == 2)
                                                                    <img class="img-fluid rounded-circle"
                                                                        src="{{ asset(@$comment->user->student->student_photo) }}"
                                                                        alt="">
                                                                @elseif($comment->user->role_id == 3)
                                                                    <img class="img-fluid rounded-circle"
                                                                        src="{{ asset(@$comment->user->parent->guardians_photo) }}"
                                                                        alt="">
                                                                @else --}}
                                                                    <img class="img-fluid rounded-circle"
                                                                        src={{@profile() && file_exists(@profile()) ? asset(profile()) : asset('public/backEnd/assets/img/avatar.png')}}
                                                                        alt="">
                                                                {{-- @endif --}}
                                                            @endif
                                                        </div>

                                                        <div class="d-flex align-items-center gap-10">
                                                            <div>
                                                                <h4 class="mb-0">{{ @$comment->user->full_name }}
                                                                </h4>
                                                                <div class="rated_customer d-flex align-items-center">
                                                                    <span>{{ dateconvert($comment->created_at) }}</span>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                @php
                                                                    $createdBy = $comment->user_id;
                                                                    $authUserId = auth()->user()->id;
                                                                @endphp

                                                                @if ($createdBy == $authUserId)
                                                                    <div class="d-flex gap-10">
                                                                        <a class="dropdown-item small fix-gr-bg type1 p-1" data-toggle="modal" data-target="#editCommentModal{{$comment->id}}" href="#">
                                                                        <svg width="20" height="20" viewBox="0 0 20 20"
                                                                            fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M11.0514 3.00002L4.20976 10.2417C3.95142 10.5167 3.70142 11.0584 3.65142 11.4334L3.34309 14.1334C3.23476 15.1084 3.93476 15.775 4.90142 15.6084L7.58476 15.15C7.95976 15.0834 8.48475 14.8084 8.74309 14.525L15.5848 7.28335C16.7681 6.03335 17.3014 4.60835 15.4598 2.86668C13.6264 1.14168 12.2348 1.75002 11.0514 3.00002Z"
                                                                                stroke="white" stroke-width="1.5"
                                                                                stroke-miterlimit="10"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M9.91016 4.2085C10.2685 6.5085 12.1352 8.26683 14.4518 8.50016"
                                                                                stroke="white" stroke-width="1.5"
                                                                                stroke-miterlimit="10"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path d="M2.5 18.3335H17.5" stroke="white"
                                                                                stroke-width="1.5"
                                                                                stroke-miterlimit="10"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>

                                                                        </a>
                                                                        <a class="dropdown-item small fix-gr-bg type2 p-1" data-toggle="modal" data-target="#deleteCommentModal{{$comment->id}}" href="#">
                                                                        <svg width="20" height="20" viewBox="0 0 20 20"
                                                                            fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M17.5 4.98356C14.725 4.70856 11.9333 4.56689 9.15 4.56689C7.5 4.56689 5.85 4.65023 4.2 4.81689L2.5 4.98356"
                                                                                stroke="white" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M7.08203 4.1415L7.26536 3.04984C7.3987 2.25817 7.4987 1.6665 8.90703 1.6665H11.0904C12.4987 1.6665 12.607 2.2915 12.732 3.05817L12.9154 4.1415"
                                                                                stroke="white" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M15.7096 7.6167L15.168 16.0084C15.0763 17.3167 15.0013 18.3334 12.6763 18.3334H7.3263C5.0013 18.3334 4.9263 17.3167 4.83464 16.0084L4.29297 7.6167"
                                                                                stroke="white" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path d="M8.60938 13.75H11.3844"
                                                                                stroke="white" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path d="M7.91797 10.4165H12.0846"
                                                                                stroke="white" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>

                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="review_content">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <p class="my-4">{{ $comment->comment }}</p>

                                                                <div class="replay_btn">
                                                                    <button class="reply_btn_el small fix-gr-bg" data-toggle="collapse" data-target="#mainComment{{$comment->id}}" aria-expanded="false" aria-controls="collapseExample">reply </button>
                                                                </div>

                                                                @if ($createdBy != $authUserId)
                                                                    <div class="vote-section mt-2">
                                                                        <div class="d-flex justify-content-start align-items-center">
                                                                            <button class="btn btn-success btn-vote" id="comment-upvote-button" onclick="handleCommentVote('upvote', {{ $comment->id }})">
                                                                            <svg width="16" height="16"
                                                                                viewBox="0 0 16 16" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M4.98828 12.2332L7.05495 13.8331C7.32161 14.0998 7.92161 14.2331 8.32161 14.2331H10.8549C11.6549 14.2331 12.5216 13.6331 12.7216 12.8331L14.3216 7.96648C14.6549 7.03315 14.0549 6.23315 13.0549 6.23315H10.3883C9.98828 6.23315 9.65495 5.89982 9.72161 5.43315L10.0549 3.29982C10.1883 2.69982 9.78828 2.03315 9.18828 1.83315C8.65495 1.63315 7.98828 1.89982 7.72161 2.29982L4.98828 6.36648"
                                                                                    stroke="white" stroke-width="1.5"
                                                                                    stroke-miterlimit="10" />
                                                                                <path
                                                                                    d="M1.58594 12.2331V5.69977C1.58594 4.76644 1.98594 4.43311 2.91927 4.43311H3.58594C4.51927 4.43311 4.91927 4.76644 4.91927 5.69977V12.2331C4.91927 13.1664 4.51927 13.4998 3.58594 13.4998H2.91927C1.98594 13.4998 1.58594 13.1664 1.58594 12.2331Z"
                                                                                    stroke="white" stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                            </svg> <span id="comment-upvote-count">{{ $comment->upvotes }}</span>
                                                                            </button>
                                                                            <button class="btn btn-danger btn-vote ml-2" id="comment-downvote-button" onclick="handleCommentVote('downvote', {{ $comment->id }})">
                                                                            <svg width="16" height="16"
                                                                                viewBox="0 0 16 16" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M11.0119 3.7666L8.94519 2.1666C8.67852 1.89993 8.07852 1.7666 7.67852 1.7666H5.14519C4.34519 1.7666 3.47852 2.3666 3.27852 3.1666L1.67852 8.03327C1.34519 8.9666 1.94519 9.7666 2.94519 9.7666H5.61185C6.01185 9.7666 6.34519 10.0999 6.27852 10.5666L5.94519 12.6999C5.81185 13.2999 6.21185 13.9666 6.81185 14.1666C7.34519 14.3666 8.01185 14.0999 8.27852 13.6999L11.0119 9.63327"
                                                                                    stroke="white" stroke-width="1.5"
                                                                                    stroke-miterlimit="10" />
                                                                                <path
                                                                                    d="M14.4115 3.76667V10.3C14.4115 11.2333 14.0115 11.5667 13.0781 11.5667H12.4115C11.4781 11.5667 11.0781 11.2333 11.0781 10.3V3.76667C11.0781 2.83333 11.4781 2.5 12.4115 2.5H13.0781C14.0115 2.5 14.4115 2.83333 14.4115 3.76667Z"
                                                                                    stroke="white" stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                            </svg> <span id="comment-downvote-count">{{ $comment->downvotes }}</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="comment_replay_wized">
                                                    <div class="col-lg-12 collapse" id="mainComment{{$comment->id}}">
                                                        <form action="{{ route('user-forum-comment-reply.store') }}" method="post" class="">
                                                            @csrf
                                                            <input type="hidden" name="forum_topic_id" value="{{ @$forum->id }}">
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
                                                            <div class="d-flex align-items-center gap-10">
                                                                <div class="thumb">
                                                                    @if ($reply->user)
                                                                        {{-- @if ($reply->user->role_id == 2)
                                                                            <img class="img-fluid rounded-circle" src="{{ asset(@$reply->user->student->student_photo) }}" alt="">
                                                                        @elseif($reply->user->role_id == 3)
                                                                            <img class="img-fluid rounded-circle" src="{{ asset(@$reply->user->parent->guardians_photo) }}" alt="">
                                                                        @else --}}
                                                                        {{-- @endif --}}
                                                                            <img class="img-fluid rounded-circle" src=" {{ @profile() && file_exists(@profile()) ? asset(profile()) : asset('public/backEnd/assets/img/avatar.png') }}" alt="">
                                                                    @endif
                                                                    
                                                                </div>

                                                                <div class="d-flex align-items-center gap-10">
                                                                    <div>
                                                                        <h4 class="mb-0">
                                                                            {{ @$reply->user->full_name }}</h4>
                                                                        <div class="rated_customer d-flex align-items-center">
                                                                            <span>{{ dateconvert($reply->created_at) }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        @php
                                                                            $createdBy = $reply->user_id;
                                                                            $authUserId = auth()->user()->id;
                                                                        @endphp

                                                                        @if ($createdBy == $authUserId)
                                                                            <div class="d-flex gap-10">
                                                                                <a class="dropdown-item small fix-gr-bg type1 p-1" data-toggle="modal" data-target="#editReplyModal{{$reply->id}}" href="#">
                                                                                <svg width="20" height="20"
                                                                                    viewBox="0 0 20 20" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M11.0514 3.00002L4.20976 10.2417C3.95142 10.5167 3.70142 11.0584 3.65142 11.4334L3.34309 14.1334C3.23476 15.1084 3.93476 15.775 4.90142 15.6084L7.58476 15.15C7.95976 15.0834 8.48475 14.8084 8.74309 14.525L15.5848 7.28335C16.7681 6.03335 17.3014 4.60835 15.4598 2.86668C13.6264 1.14168 12.2348 1.75002 11.0514 3.00002Z"
                                                                                        stroke="white"
                                                                                        stroke-width="1.5"
                                                                                        stroke-miterlimit="10"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round" />
                                                                                    <path
                                                                                        d="M9.91016 4.2085C10.2685 6.5085 12.1352 8.26683 14.4518 8.50016"
                                                                                        stroke="white"
                                                                                        stroke-width="1.5"
                                                                                        stroke-miterlimit="10"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round" />
                                                                                    <path d="M2.5 18.3335H17.5"
                                                                                        stroke="white"
                                                                                        stroke-width="1.5"
                                                                                        stroke-miterlimit="10"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round" />
                                                                                </svg>

                                                                                </a>
                                                                                <a class="dropdown-item small fix-gr-bg type2 p-1" data-toggle="modal" data-target="#deleteReplyModal{{$reply->id}}" href="#">
                                                                                <svg width="20" height="20"
                                                                                    viewBox="0 0 20 20" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M17.5 4.98356C14.725 4.70856 11.9333 4.56689 9.15 4.56689C7.5 4.56689 5.85 4.65023 4.2 4.81689L2.5 4.98356"
                                                                                        stroke="white"
                                                                                        stroke-width="1.5"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round" />
                                                                                    <path
                                                                                        d="M7.08203 4.1415L7.26536 3.04984C7.3987 2.25817 7.4987 1.6665 8.90703 1.6665H11.0904C12.4987 1.6665 12.607 2.2915 12.732 3.05817L12.9154 4.1415"
                                                                                        stroke="white"
                                                                                        stroke-width="1.5"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round" />
                                                                                    <path
                                                                                        d="M15.7096 7.6167L15.168 16.0084C15.0763 17.3167 15.0013 18.3334 12.6763 18.3334H7.3263C5.0013 18.3334 4.9263 17.3167 4.83464 16.0084L4.29297 7.6167"
                                                                                        stroke="white"
                                                                                        stroke-width="1.5"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round" />
                                                                                    <path d="M8.60938 13.75H11.3844"
                                                                                        stroke="white"
                                                                                        stroke-width="1.5"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round" />
                                                                                    <path d="M7.91797 10.4165H12.0846"
                                                                                        stroke="white"
                                                                                        stroke-width="1.5"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round" />
                                                                                </svg>

                                                                                </a>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="review_content">
                                                                <p class="my-4">{{ $reply->reply }}</p>

                                                                @if ($createdBy != $authUserId)
                                                                    <div class="vote-section">
                                                                        <div class="d-flex justify-content-start align-items-center">
                                                                            <button class="btn btn-success btn-vote" id="reply-upvote-button" onclick="handleReplyVote('upvote', {{ $reply->id }})">
                                                                            <svg width="16" height="16"
                                                                                viewBox="0 0 16 16" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M4.98828 12.2332L7.05495 13.8331C7.32161 14.0998 7.92161 14.2331 8.32161 14.2331H10.8549C11.6549 14.2331 12.5216 13.6331 12.7216 12.8331L14.3216 7.96648C14.6549 7.03315 14.0549 6.23315 13.0549 6.23315H10.3883C9.98828 6.23315 9.65495 5.89982 9.72161 5.43315L10.0549 3.29982C10.1883 2.69982 9.78828 2.03315 9.18828 1.83315C8.65495 1.63315 7.98828 1.89982 7.72161 2.29982L4.98828 6.36648"
                                                                                    stroke="white" stroke-width="1.5"
                                                                                    stroke-miterlimit="10" />
                                                                                <path
                                                                                    d="M1.58594 12.2331V5.69977C1.58594 4.76644 1.98594 4.43311 2.91927 4.43311H3.58594C4.51927 4.43311 4.91927 4.76644 4.91927 5.69977V12.2331C4.91927 13.1664 4.51927 13.4998 3.58594 13.4998H2.91927C1.98594 13.4998 1.58594 13.1664 1.58594 12.2331Z"
                                                                                    stroke="white" stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                            </svg> <span id="reply-upvote-count">{{ $reply->upvotes }}</span>
                                                                            </button>
                                                                            <button class="btn btn-danger btn-vote ml-2" id="reply-downvote-button" onclick="handleReplyVote('downvote', {{ $reply->id }})">
                                                                            <svg width="16" height="16"
                                                                                viewBox="0 0 16 16" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M11.0119 3.7666L8.94519 2.1666C8.67852 1.89993 8.07852 1.7666 7.67852 1.7666H5.14519C4.34519 1.7666 3.47852 2.3666 3.27852 3.1666L1.67852 8.03327C1.34519 8.9666 1.94519 9.7666 2.94519 9.7666H5.61185C6.01185 9.7666 6.34519 10.0999 6.27852 10.5666L5.94519 12.6999C5.81185 13.2999 6.21185 13.9666 6.81185 14.1666C7.34519 14.3666 8.01185 14.0999 8.27852 13.6999L11.0119 9.63327"
                                                                                    stroke="white" stroke-width="1.5"
                                                                                    stroke-miterlimit="10" />
                                                                                <path
                                                                                    d="M14.4115 3.76667V10.3C14.4115 11.2333 14.0115 11.5667 13.0781 11.5667H12.4115C11.4781 11.5667 11.0781 11.2333 11.0781 10.3V3.76667C11.0781 2.83333 11.4781 2.5 12.4115 2.5H13.0781C14.0115 2.5 14.4115 2.83333 14.4115 3.76667Z"
                                                                                    stroke="white" stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                            </svg> <span id="reply-downvote-count">{{ $reply->downvotes }}</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="modal fade" id="editReplyModal{{$reply->id}}" tabindex="-1" role="dialog" aria-labelledby="editModalReplyLabel{{$reply->id}}" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="editModalReplyLabel{{$reply->id}}">
                                                                                @lang('common.edit_reply')
                                                                            </h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'user-topic-comment-reply.update', 'method' => 'POST']) }}
                                                                            <div class="row mb-3 mt-3">
                                                                                <div class="col-12">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-12">
                                                                                            <div class="primary_input">
                                                                                                <input type="hidden" name="forum_topic_id" value="{{ $forum->id }}">
                                                                                                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                                                                                <input type="hidden" name="reply_id" value="{{ $reply->id }}">
                                                                                                <label for="reply{{ $comment->id }}">@lang('common.comment') <span class="text-danger">*</span></label>
                                                                                                <textarea class="primary_input_field form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" rows="5" name="reply" autocomplete="off" id="reply{{ $reply->id }}">{!! $reply->reply !!}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                    
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('common.close')</button>
                                                                            <button type="submit" class="primary-btn fix-gr-bg submit">
                                                                                <span class="ti-check"></span>
                                                                                @lang('common.save')
                                                                            </button>
                                                                        </div>
                                                                        {{ Form::close() }}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal fade admin-query" id="deleteReplyModal{{ @$reply->id }}">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">@lang('common.delete_reply')</h4>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal">&times;</button>
                                                                        </div>
                
                                                                        <div class="modal-body">
                                                                            <div class="text-center">
                                                                                <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                                            </div>
                
                                                                            <div class="mt-40 d-flex justify-content-between">
                                                                                <button type="button" class="primary-btn tr-bg"
                                                                                    data-dismiss="modal">@lang('common.cancel')</button>
                                                                                <a class="primary-btn fix-gr-bg" href="{{ route('user-forum-comment-reply.delete', [$reply->id]) }}"
                                                                                    class="text-light"> @lang('common.delete')
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div class="modal fade" id="editCommentModal{{$comment->id}}" tabindex="-1" role="dialog" aria-labelledby="editModalCommentLabel{{$comment->id}}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalCommentLabel{{$comment->id}}">
                                                                    @lang('common.edit_comment')
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'user-topic-comment.update', 'method' => 'POST']) }}
                                                                <div class="row mb-3 mt-3">
                                                                    <div class="col-12">
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <div class="primary_input">
                                                                                    <input type="hidden" name="forum_topic_id" value="{{ $forum->id }}">
                                                                                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                                                                    <label for="description{{ $comment->id }}">@lang('common.comment') <span class="text-danger">*</span></label>
                                                                                    <textarea class="primary_input_field form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" rows="5" name="comment" autocomplete="off" id="description{{ $comment->id }}">{!! $comment->comment !!}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                        
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('common.close')</button>
                                                                <button type="submit" class="primary-btn fix-gr-bg submit">
                                                                    <span class="ti-check"></span>
                                                                    @lang('common.save')
                                                                </button>
                                                            </div>
                                                            {{ Form::close() }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade admin-query" id="deleteCommentModal{{ @$comment->id }}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">@lang('common.delete_comment')</h4>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal">&times;</button>
                                                            </div>
    
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                                </div>
    
                                                                <div class="mt-40 d-flex justify-content-between">
                                                                    <button type="button" class="primary-btn tr-bg"
                                                                        data-dismiss="modal">@lang('common.cancel')</button>
                                                                    <a class="primary-btn fix-gr-bg" href="{{ route('user-forum-comment.delete', [$comment->id]) }}"
                                                                        class="text-light"> @lang('common.delete')
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="conversition_box">

                                    <div id="conversition_box"></div>

                                    <div class="row">
                                        <div class="col-lg-12 " id="mainComment0">
                                            <form action="{{ route('user-forum-comment.store') }}" method="post" class="">
                                                @csrf
                                                <input type="hidden" name="forum_topic_id" value="{{ @$forum->id }}">

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
                <div class="col-lg-3">
                    <div class="white-box">
                        <div class="fourm_cat_boxes mb_20 mt-20">
                            <h4 class="cat_title f_s_20  mb-2">{{ __('common.recent_discussion')}}</h4>
                            <div class="discussion_lists">
                                @foreach ($recent_forum as  $r_forum)
                                    <div class="single_discussion py-2">
                                        <h5><a href="{{route('user-forum.view',$r_forum->id)}}">{{ @$r_forum->title }}</a></h5>
                                        <p>{{ dateconvert(@$r_forum->created_at)  }}</p>
                                        <hr >
                                    </div>
                                @endforeach
                            </div>
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
        let userVote = null;
        let userCommentVote = null;
        let userReplyVote = null;
    
        function handleVote(type, forumId) {
            const upvoteButton = document.getElementById('upvote-button');
            const downvoteButton = document.getElementById('downvote-button');
            const upvoteCount = document.getElementById('upvote-count');
            const downvoteCount = document.getElementById('downvote-count');
    
            if (!upvoteButton || !downvoteButton || !upvoteCount || !downvoteCount) {
                console.error('Vote elements not found');
                return;
            }
    
            upvoteButton.disabled = true;
            downvoteButton.disabled = true;
    
            $.ajax({
                url: '{{ route('user-forum-vote.store') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    forumId: forumId,
                    voteType: type
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
    
                        upvoteCount.innerText = response.upvotes;
                        downvoteCount.innerText = response.downvotes;
    
                        userVote = type;
                    } else if (response.error) {
                        toastr.error(response.error);
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred. Please try again.');
                },
                complete: function() {
                    upvoteButton.disabled = false;
                    downvoteButton.disabled = false;
                }
            });
        }

        function handleCommentVote(type, commentId) {
            const commentUpvoteButton = document.getElementById('comment-upvote-button');
            const commentDownvoteButton = document.getElementById('comment-downvote-button');
            const commentUpvoteCount = document.getElementById('comment-upvote-count');
            const commentDownvoteCount = document.getElementById('comment-downvote-count');
    
            if (!commentUpvoteButton || !commentDownvoteButton || !commentUpvoteCount || !commentDownvoteCount) {
                console.error('Vote elements not found');
                return;
            }
    
            commentUpvoteButton.disabled = true;
            commentDownvoteButton.disabled = true;
    
            $.ajax({
                url: '{{ route('user-forum-comment-vote.store') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    commentId: commentId,
                    voteType: type
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
    
                        commentUpvoteCount.innerText = response.upvotes;
                        commentDownvoteCount.innerText = response.downvotes;
    
                        userCommentVote = type;
                    } else if (response.error) {
                        toastr.error(response.error);
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred. Please try again.');
                },
                complete: function() {
                    commentUpvoteButton.disabled = false;
                    commentDownvoteButton.disabled = false;
                }
            });
        }

        function handleReplyVote(type, replyId) {
            const replyUpvoteButton = document.getElementById('reply-upvote-button');
            const replyDownvoteButton = document.getElementById('reply-downvote-button');
            const replyUpvoteCount = document.getElementById('reply-upvote-count');
            const replyDownvoteCount = document.getElementById('reply-downvote-count');
    
            if (!replyUpvoteButton || !replyDownvoteButton || !replyUpvoteCount || !replyDownvoteCount) {
                console.error('Vote elements not found');
                return;
            }
    
            replyUpvoteButton.disabled = true;
            replyDownvoteButton.disabled = true;
    
            $.ajax({
                url: '{{ route('user-forum-reply-vote.store') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    replyId: replyId,
                    voteType: type
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
    
                        replyUpvoteCount.innerText = response.upvotes;
                        replyDownvoteCount.innerText = response.downvotes;
    
                        userReplyVote = type;
                    } else if (response.error) {
                        toastr.error(response.error);
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred. Please try again.');
                },
                complete: function() {
                    replyUpvoteButton.disabled = false;
                    replyDownvoteButton.disabled = false;
                }
            });
        }
    </script>
@endpush