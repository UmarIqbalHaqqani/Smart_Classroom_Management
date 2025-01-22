@extends('backEnd.master')

@section('title')
    @lang('common.forum_topic')
@endsection

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">

<style>
    .col-xl-2 {
        padding: 10px;
        background: #fbfbfb;
    }
    .lession_lists {
        list-style-type: none;
        padding: 0; 
        margin: 0; 
    }

    .lession_lists .category-item {
        padding: 10px; 
        border-bottom: 1px solid #ccc;
        font-family: Arial, sans-serif;
    }

    .lession_lists .category-item a {
        text-decoration: none; 
        color: #333;
        display: block; 
    }

    .lession_lists .category-item.active a {
        font-weight: bold; 
        color: #00458f;
    }

    .lession_lists .category-item a:hover {
        color: var(--base_color);
    }
    .text-small {
        font-size: 10px;
        color: #999;
    }
</style>
@endpush

@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> @lang('common.forum_topic_list')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('common.forum')</a>
                    <a href="#">@lang('common.forum_topic_list')</a>
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

                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'user-forum-topics-search', 'method' => 'POST']) }}

                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">

                            <div class="col-lg-10 mt-30-md">
                                <input type="hidden" name="forum_title_id" value="{{$forum_title->id}}">
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
                    <div class="">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="fourm_area section_spacing4">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <div class="white-box">
                                                <div class="row">
                                                    <div class="col-lg-12 no-gutters">
                                                        <div class="main-title">
                                                            <h4 class="mb-15"> @lang('common.forum_topic')</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="lession_lists mb_30 mt_114">
                                                    @foreach ($forum_titles as $title)
                                                        <li class="category-item {{ $forum_title->id == $title->id ? 'active' : '' }}">
                                                            <a href="{{ route('user-forum-topic.index', $title->id) }}">{{ $title->title }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xl-9">
                                            <div class="white-box">
                                            <div class="row mb-3">
                                                <div class="col-lg-8 col-md-6 col-6">
                                                    <div class="main-title">
                                                        <h4 class="mb-15"> @lang('common.forum_topic')</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 text-md-right col-md-6 mb-30-lg col-6 text-right ">
                                                    <button class="primary-btn-small-input primary-btn small fix-gr-bg" type="button" data-toggle="modal" data-target="#addModalForumTopic">
                                                        <span class="ti-plus pr-2"></span> Add
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <x-table>
                                                <div class="table-responsive">
                                                    <table class="table fourm_table mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>@lang('common.topic')</th>
                                                                <th>@lang('common.category')</th>
                                                                <th>@lang('common.comment')</th>
                                                                <th>@lang('common.views')</th>
                                                                <th>@lang('common.action')</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($forum_topic_items as $forum)
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{ route('user-forum.view', $forum->id) }}">
                                                                            <div class="topic_name">
                                                                                <h6>{{ @$forum->title }}</h6> 
                                                                                
                                                                                <p class="text-small"> {{ @$forum->createdBy->full_name }} </p>

                                                                            </div>
                                                                        </a>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <span class="badge badge-secondary">{{ @$forum->forumTitle->forumCategory->title }}</span>
                                                                    </td>
                                                                    <td class="text-center">{{ @$forum->forumComments()->count() }}</td>
                                                                    <td class="text-center">{{ @$forum->total_views}}</td>
                                                                    <td>
                                                                        @php
                                                                            $routeList = [];
                                                                            $routeList[] = '<a class="dropdown-item" href="'. route('user-forum.view',$forum->id) .'">'. __('common.view') .'</a>';
                                                                            if(auth()->user()->id == $forum->created_by) {
                                                                                $routeList[] = '<a class="dropdown-item" data-toggle="modal" data-target="#editSectionModal'. $forum->id .'" href="#">'. __('common.edit') .'</a>';
                                                                                $routeList[] = '<a class="dropdown-item" data-toggle="modal" data-target="#deleteSectionModal'. $forum->id .'" href="#">'. __('common.delete') .'</a>';
                                                                            }
                                                                            $routeListHtml = implode('', $routeList);
                                                                        @endphp
                                                                        <x-drop-down-action-component :routeList="$routeList" />
                                                                    </td>
                                                                </tr>

                                                                <div class="modal fade" id="editSectionModal{{$forum->id}}" tabindex="-1" role="dialog" aria-labelledby="editModalForumTopicLabel{{$forum->id}}" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="editModalForumTopicLabel{{$forum->id}}">
                                                                                    @lang('common.edit_forum_topic')
                                                                                </h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'user-forum-topic.update', 'method' => 'POST']) }}
                                                                                <div class="row mb-3 mt-3">
                                                                                    <div class="col-12">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-12">
                                                                                                <div class="primary_input">
                                                                                                    <label for="title">@lang('common.title') <span class="text-danger"> *</span></label>
                                                                                                    <input class="primary_input_field form-control{{ @$errors->has('title') ? ' is-invalid' : '' }}" type="text" required name="title" autocomplete="off" id="title" value="{{ @$forum->title }}">
                                                                                                    <input type="hidden" name="forum_title_id" value="{{$forum_title->id}}">
                                                                                                    <input type="hidden" name="forum_topic_id" value="{{$forum->id}}">
                                                                                                    @if ($errors->has('title'))
                                                                                                        <span class="text-danger">{{ @$errors->first('title') }}</span>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-12 mt-3">
                                                                                                <div class="primary_input">
                                                                                                    <label for="description">@lang('common.description') <span class="text-danger">*</span></label>
                                                                                                    <textarea class="primary_input_field form-control{{ $errors->has('description') ? ' is-invalid' : '' }} lms_summernote" name="description" autocomplete="off" id="description">{{ @$forum->description }}</textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    @php
                                                                                        $edit = true;
                                                                                    @endphp
                                                                                    @isset($edit) 
                                                                                        @php
                                                                                            $role_ids = json_decode($forum->available_for);
                                                                                            $role_ids = is_array($role_ids) ? $role_ids : [];
                                                                                        @endphp
                                                                                    @endisset
                                                            
                                                                                    {{-- <div class="col-lg-3">
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
                                                                                    </div> --}}
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

                                                                <div class="modal fade admin-query" id="deleteSectionModal{{ @$forum->id }}">
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
                                                                                    <a class="primary-btn fix-gr-bg" href="{{ route('user-forum-topic.delete', [$forum->id]) }}"
                                                                                        class="text-light"> @lang('common.delete')
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">
                                                                        <h3>@lang('common.no_data_found')</h3>
                                                                    </td>
                                                                </tr>
                                                            @endforelse
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
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModalForumTopic" tabindex="-1" role="dialog" aria-labelledby="addModalForumTopicLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalForumTopicLabel">
                            @lang('common.add_forum_topic')
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'user-forum-topic.store', 'method' => 'POST']) }}
                        <div class="row mb-3 mt-3">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="primary_input">
                                            <label for="title">@lang('common.title') <span class="text-danger"> *</span></label>
                                            <input class="primary_input_field form-control{{ @$errors->has('title') ? ' is-invalid' : '' }}" type="text" required name="title" autocomplete="off" id="title" value="{{ old('title') }}">
                                            <input type="hidden" name="forum_title_id" value="{{$forum_title->id}}">
                                            @if ($errors->has('title'))
                                                <span class="text-danger">{{ @$errors->first('title') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="primary_input">
                                            <label for="description">@lang('common.description') <span class="text-danger">*</span></label>
                                            <textarea class="primary_input_field form-control{{ $errors->has('description') ? ' is-invalid' : '' }} lms_summernote" name="description" autocomplete="off" id="description">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-lg-3">
                                <label>@lang('common.available_for') <span class="text-danger">*</span></label>
                                <div id="role_container">
                                    @foreach($roles as $role)
                                        <div class="saas_role_checkbox">
                                            <input type="checkbox" id="role_{{$role->id}}" class="common-checkbox" value="{{$role->id}}" name="role[]">
                                            <label for="role_{{$role->id}}">{{$role->name}}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @if($errors->has('role'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('role') }}</strong>
                                    </span>
                                @endif
                            </div> --}}
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
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')

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
