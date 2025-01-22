@extends('backEnd.master')
    @section('title')
        @lang('common.my_topics')
    @endsection
@section('mainContent')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">

    <style>
        .mt-38 {
            margin-top: 38px;
        }
    </style>
@endpush

<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('common.my_topics')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('common.forum')</a>
                <a href="#">@lang('common.my_topics')</a>
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

                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'user-forum-mytopic-search', 'method' => 'POST']) }}

                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">

                        <div class="col-lg-3 mt-30-md">
                            <label for="forum_category_id"> {{ __('common.forum_category') }}</label>
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

                        <div class="col-lg-3 mt-30-md">
                            <label for="forum_title_id"> {{ __('common.forum_title') }}</label>
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

                        <div class="col-lg-3 mt-30-md">
                            <label for="forum_topic_id"> {{ __('common.forum_topic') }}</label>
                            <select
                                class="primary_select form-control{{ @$errors->has('forum_topic_id') ? ' is-invalid' : '' }}"
                                name="forum_topic_id">
                                <option data-display="@lang('common.select_forum_topic')" value="">@lang('common.select_forum_topic')</option>
                                @foreach ($forum_topics as $topic)
                                    <option value="{{ @$topic->id }}"
                                        {{ isset($forum_topic_id) ? ($forum_topic_id == $topic->id ? 'selected' : '') : '' }}>
                                        {{ @$topic->title }}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('forum_topic_id'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ @$errors->first('forum_topic_id') }}
                                </span>
                            @endif
                        </div>

                        <div class="col-lg-2 mt-38 text-right">
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

<section class="admin-visitor-area up_st_admin_visitor mt-25">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-15">  @lang('common.my_topic_list')</h3>
                            </div>
                        </div>
                    </div>
    
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th> @lang('common.forum_category')</th>
                                        <th> @lang('common.forum_title')</th>
                                        <th> @lang('common.forum_topic')</th>
                                        <th> @lang('common.comment')</th>
                                        <th> @lang('common.view')</th>
                                        <th> @lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($forums as $my_topic)
                                    <tr>
                                        <td>
                                            <span class="badge badge-secondary">{{@$my_topic->forumTitle->forumCategory->title}}</span>
                                        </td>
                                        <td>{{@$my_topic->forumTitle->title }}</td>
                                        <td>{{@$my_topic->title }}</td>
                                        <td>{{@$my_topic->forumComments()->count() }}</td>
                                        <td>{{@$my_topic->total_views }}</td>
                                        <td>
                                            @php
                                                $routeList = [];
                                                $routeList[] = '<a class="dropdown-item" href="'. route('user-forum.view',$my_topic->id) .'">'. __('common.view') .'</a>';
                                                $routeList[] = '<a class="dropdown-item" data-toggle="modal" data-target="#editSectionModal'. $my_topic->id .'" href="#">'. __('common.edit') .'</a>';
                                                $routeList[] = '<a class="dropdown-item" data-toggle="modal" data-target="#deleteSectionModal'. $my_topic->id .'" href="#">'. __('common.delete') .'</a>';
                                                $routeListHtml = implode('', $routeList);
                                            @endphp
                                            <x-drop-down-action-component :routeList="$routeList" />
                                        </td>
                                    </tr>
                                    
                                    <div class="modal fade" id="editSectionModal{{$my_topic->id}}" tabindex="-1" role="dialog" aria-labelledby="editModalForumTopicLabel{{$my_topic->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalForumTopicLabel{{$my_topic->id}}">
                                                        @lang('common.edit_forum_topic')
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'user-forum-topic.update', 'method' => 'POST']) }}
                                                    <div class="row mb-3 mt-3">
                                                        <div class="col-9">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="primary_input">
                                                                        <label for="title">@lang('common.title') <span class="text-danger"> *</span></label>
                                                                        <input class="primary_input_field form-control{{ @$errors->has('title') ? ' is-invalid' : '' }}" type="text" required name="title" autocomplete="off" id="title" value="{{ @$my_topic->title }}">
                                                                        <input type="hidden" name="forum_title_id" value="{{$my_topic->forumTitle->id}}">
                                                                        <input type="hidden" name="forum_topic_id" value="{{$my_topic->id}}">
                                                                        @if ($errors->has('title'))
                                                                            <span class="text-danger">{{ @$errors->first('title') }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="primary_input">
                                                                        <label for="description">@lang('common.description') <span class="text-danger">*</span></label>
                                                                        <textarea class="primary_input_field form-control{{ $errors->has('description') ? ' is-invalid' : '' }} lms_summernote" name="description" autocomplete="off" id="description">{!! @$my_topic->description !!}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @php
                                                            $edit = true;
                                                        @endphp
                                                        @isset($edit) 
                                                            @php
                                                                $role_ids = json_decode($my_topic->available_for);
                                                                $role_ids = is_array($role_ids) ? $role_ids : [];
                                                            @endphp
                                                        @endisset
                                
                                                        <div class="col-lg-3">
                                                            <label>@lang('common.available_for') <span class="text-danger">*</span></label>
                                                            <div id="role_container">
                                                                @foreach($roles as $rl)
                                                                    @php
                                                                        $custom_id = $rl->id.rand(10,1000);
                                                                    @endphp
                                                                    <div class="saas_role_checkbox">
                                                                        <input type="checkbox" id="role_{{$custom_id}}" class="common-checkbox" value="{{$rl->id}}" name="role[]"
                                                                        @isset($role_ids) @if(in_array($rl->id, $role_ids)) checked @endif @endisset>
                                                                        <label for="role_{{$custom_id}}">{{$rl->name}}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            @if($errors->has('role'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('role') }}</strong>
                                                                </span>
                                                            @endif
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

                                    <div class="modal fade admin-query" id="deleteSectionModal{{ @$my_topic->id }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('common.delete_forum_topic')</h4>
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
                                                        <a class="primary-btn fix-gr-bg" href="{{ route('user-forum-topic.delete', [$my_topic->id]) }}"
                                                            class="text-light"> @lang('common.delete')
                                                        </a>
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
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

    <script>
        $('.lms_summernote').summernote({
            height: 200,
            tabsize: 2,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    </script>
@endpush