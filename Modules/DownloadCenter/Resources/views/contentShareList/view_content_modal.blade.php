@php
    $send_type = '';
    if ($viewContent->send_type == 'G') {
        $send_type = 'Group';
    } elseif ($viewContent->send_type == 'I') {
        $send_type = 'Individual';
    } elseif ($viewContent->send_type == 'C') {
        $send_type = 'Class';
    } else {
        $send_type = 'Public';
    }
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="single-content-modal-info">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="content-modal-section-title">
                            {{ $viewContent->title }}
                        </div>
                    </div>
                </div>
                <div class="row content-container">
                    <div class="col-lg-4">
                        <span class="content-type">
                            @lang('downloadCenter.upload_date'):
                        </span>
                        <span class="content-value">
                            {{ date('Y-m-d', strtotime($viewContent->created_at)) }}
                        </span>
                    </div>
                    <div class="col-lg-4">
                        <span class="content-type">
                            @lang('downloadCenter.valid_upto'):
                        </span>
                        <span class="content-value">
                            {{ $viewContent->valid_upto }}
                        </span>
                    </div>
                    <div class="col-lg-4">
                        <span class="content-type">
                            @lang('downloadCenter.share_date'):
                        </span>
                        <span class="content-value">
                            {{ $viewContent->share_date }}
                        </span>
                    </div>
                    <div class="col-lg-4">
                        <span class="content-type">
                            @lang('downloadCenter.shared_by'):
                        </span>
                        <span class="content-value">
                            {{ $viewContent->user->full_name }}
                        </span>
                    </div>
                    <div class="col-lg-4">
                        <span class="content-type">
                            @lang('downloadCenter.send_to'):
                        </span>
                        <span class="content-value">
                            {{ $send_type }}
                        </span>
                    </div>
                    <div class="col-lg-4">
                    </div>
                    <div class="col-lg-12">
                        <span class="content-type">
                            @lang('downloadCenter.description'):
                        </span>
                        <span class="content-value">
                            {{ $viewContent->description ? $viewContent->description : 'No Description' }}
                        </span>
                    </div>
                    <div class="col-lg-12">
                        <div class="content-modal-section-title">
                            @lang('downloadCenter.attachments')
                        </div>
                        <div class="attached-content">
                            <ul class="attached-content-list">
                                @foreach ($contents as $content)
                                    <li class="attached-content-item">
                                        @if (@$content->youtube_link)
                                            <a href="{{ $content->youtube_link }}"
                                                target="_blank">{{ $content->file_name }}</a>
                                        @else
                                            <a href="{{ url("$content->upload_file") }}"
                                                download>{{ $content->file_name }}
                                                <span class="pl ti-download"></span></a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="single-content-modal-sidebar">
                <div class="content-modal-section-title sidebar">
                    @lang('downloadCenter.shared_groups_users')
                </div>
                <ul class="content-links">
                    @if (@$viewContent->send_type == 'G')
                        @foreach (@$roles as $role)
                            <li>{{ $role->name }}</li>
                        @endforeach
                    @endif
                    @if (@$viewContent->send_type == 'I')
                        @foreach (@$individuals as $individual)
                            <li>{{ $individual->full_name }}</li>
                        @endforeach
                    @endif
                    @if (@$viewContent->send_type == 'C')
                        @foreach (@$classSections as $classSection)
                            <li>{{ $classSection->className->class_name }}({{ $classSection->sectionName->section_name }})
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
