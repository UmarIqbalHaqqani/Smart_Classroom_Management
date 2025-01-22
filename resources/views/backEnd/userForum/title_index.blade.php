@extends('backEnd.master')

@section('title')
    @lang('common.forum')
@endsection

@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">

    {{-- <style>
        .couse_wizged .thumb {
            position: relative;
            overflow: hidden;
        }

        .couse_wizged .thumb img {
            transform: scale(1);
            transition: 0.3s;
        }

        .couse_wizged .thumb .prise_tag {
            position: absolute;
            width: 60px;
            height: 30px;
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            top: 20px;
            right: 20px;
            border-radius: 5%;
            background: #fff;
            color: #fb1159;
            display: flex;
            flex-direction: column;
            line-height: auto;
            justify-content: center;
            align-items: center;
        }

        .couse_wizged .course_content {
            background-color: #f7f6f6;
            padding: 25px 20px;
        }
    </style> --}}
@endpush

@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> @lang('common.forum_list')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('common.forum')</a>
                    <a href="#">@lang('common.forum_list')</a>
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
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="main-title">
                                    <h3 class="mb-15 ">@lang('common.select_criteria')</h3>
                                </div>
                            </div>
                        </div>

                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'user-forum-title-search', 'method' => 'POST']) }}

                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">

                            <div class="col-lg-5 mt-30-md">
                                <select
                                    class="primary_select form-control{{ @$errors->has('forum_category_id') ? ' is-invalid' : '' }}"
                                    name="forum_category_id">
                                    <option data-display="@lang('common.select_forum_category')" value="">@lang('common.select_forum_category')</option>
                                    @foreach ($forum_categories as $category)
                                        <option value="{{ @$category->id }}"
                                            {{ isset($category_id) ? ($category_id == $category->id ? 'selected' : '') : '' }}>
                                            {{ @$category->title }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('forum_category_id'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ @$errors->first('forum_category_id') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-5 mt-30-md">
                                <select
                                    class="primary_select form-control{{ @$errors->has('forum_title_id') ? ' is-invalid' : '' }}"
                                    name="forum_title_id">
                                    <option data-display="@lang('common.select_forum_title')" value="">@lang('common.select_forum_title')</option>
                                    @foreach ($forum_titles as $title)
                                        <option value="{{ @$title->id }}"
                                            {{ isset($forum_title_id) ? ($forum_title_id == $title->id ? 'selected' : '') : '' }}>
                                            {{ @$title->title }}</option>
                                    @endforeach
                                </select>
    
                                @if ($errors->has('forum_title_id'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ @$errors->first('forum_title_id') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-2 mt-10 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_admin_visitor mt-25">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-15">  @lang('common.forum_list')</h3>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <div class="table-responsive">
                                        <table id="table_id" class="table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th> @lang('common.forum_title')</th>
                                                    <th> @lang('common.category')</th>
                                                    <th> @lang('common.topics')</th>
                                                    <th> @lang('common.comment')</th>
                                                    <th> @lang('common.action')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($forum_items as $forum)
                                                    <tr>
                                                        <td>
                                                            <a href="{{route('user-forum-topic.index',$forum->id)}}">
                                                                <div class="topic_name">
                                                                    {{ @$forum->title }}
                                                                </div>
                                                            </a>
                                                        </td>
                                                    
                                                        <td>
                                                            <div class="category_mark d-flex align-items-center ">
                                                                <span class="squire_bulet"> </span> {{ @$forum->forumCategory->title }}
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ @$forum->forumTopics()->count()}}
                                                        </td>
                                                        <td class="text-center">
                                                            @php
                                                                $totalComments = 0;
                                                                foreach ($forum->forumTopics as $topic) {
                                                                    $totalComments += $topic->forumComments()->count();
                                                                }
                                                            @endphp

                                                            {{ $totalComments }}
                                                        </td>
                                                        <td>
                                                            <x-drop-down>
                                                                <a class="dropdown-item" href="{{route('user-forum-topic.index',$forum->id)}}"> @lang('common.topic_list')</a>
                                                                <a class="dropdown-item" href=""> @lang('common.my_topics')</a>
                                                            </x-drop-down>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </x-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
