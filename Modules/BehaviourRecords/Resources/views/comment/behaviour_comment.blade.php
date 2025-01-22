@extends('backEnd.master')
@section('title')
    @lang('behaviourRecords.behabiour_record_comment')
@endsection
@push('css')
    <style>
        .profile-single-comment {
            display: flex;
            gap: 20px;
            margin-bottom: 20px
        }

        .profile-single-comment img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            aspect-ratio: 1 / 1;
        }

        .profile-comment .comment {
            font-size: 14px;
            color: #fff;
            line-height: 26px;
            font-weight: 400;
            background: #828bb2;
            border-radius: 0px 30px 30px 30px;
            padding: 10px 20px;
        }

        .profile-single-comment.reply .profile-comment .comment {
            background: #7c32ff;
            border-radius: 30px 0px 30px 30px;
        }

        .profile-single-comment.reply .profile-comment .profile-comment-time {
            text-align: right
        }

        .profile-single-comment.reply {
            flex-direction: row-reverse;
        }

        .profile-comment-input {
            gap: 10px;
        }

        .single-incident .card {
            border-radius: 45px;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('behaviourRecords.behabiour_record_comment')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('behaviourRecords.dashboard')</a>
                    <a href="#">@lang('behaviourRecords.student_list')</a>
                    <a href="#">@lang('behaviourRecords.student_details')</a>
                    <a href="#">@lang('behaviourRecords.behabiour_record_comment')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-20">
                <div class="col-lg-12 student-details up_admin_visitor">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4 no-gutters">
                                    <div class="main-title">
                                        <h3 class="mb-4">@lang('behaviourRecords.behabiour_record_comment') </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row white-box">
                                <div class="col-lg-12">
                                    <div class="single-incident mb-5">
                                        <h3 class="mb-2">@lang('behaviourRecords.incident_details')</h3>
                                        <div class="card col-lg-12">
                                            <div class="card-body">
                                                <h5 class="card-title">@lang('behaviourRecords.student_name'):
                                                    {{ $incident->studentRecord->studentDetail->full_name }}</h5>
                                                <h5 class="card-title">
                                                    @lang('behaviourRecords.incident_title'):
                                                    {{ $incident->incident->title }}
                                                </h5>
                                                <h5 class="card-title">@lang('behaviourRecords.incident_point'):
                                                    {{ $incident->incident->point }}
                                                </h5>
                                                <h5 class="card-title">@lang('behaviourRecords.incident_description'):</h5>
                                                <p class="card-text">{{ $incident->incident->description }}</p>
                                                <input type="hidden" name="" id="studentId"
                                                    value="{{ $incident->id }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="col-lg-12" id="incident_comment_list">

                                        </div>
                                        <div class="profile-comment-input d-flex">
                                            <input type="text" class="primary_input_field" name="comment" id="comment">
                                            <input type="hidden" id="incident_id" value="{{ $incident->id }}">
                                            <button type="submit"
                                                class="primary-btn small fix-gr-bg commentSave">@lang('behaviourRecords.send')</button>
                                        </div>
                                        <span id="comment_error" class="text-danger"></span>
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
@push('script')
    <script>
        function getIncidentComment() {
            $.ajax({
                type: "GET",
                url: "{{ route('behaviour_records.get_incident_comment', $incident->id) }}",
                dataType: "html",
                success: function(response) {
                    $('#incident_comment_list').html(response);
                },
                error: function(error) {
                    toastr.error(error.message, 'Error');
                }
            });
        }
        getIncidentComment();

        $(document).on('click', '.commentSave', function(e) {
            var incident_id = $('#incident_id').val();
            var comment = $('input[name=comment]').val();
            let data = {
                incident_id: incident_id,
                comment: comment,
            }
            $.ajax({
                type: "POST",
                data: data,
                url: "/behaviour_records/incident_comment_save",
                dataType: "json",
                success: function(response) {
                    $('#comment_error').html('');
                    toastr.success(response.message, 'Success');
                    $('#comment').val(null);
                    getIncidentComment();
                },
                error: function(error) {
                    $('#comment_error').html(error.responseJSON.errors.comment);
                }
            });
        })
    </script>
@endpush
