@extends(config('pagebuilder.site_layout'), ['edit' => false])
@section(config('pagebuilder.site_section'))
    {{headerContent()}}
    <section class="bradcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{ __('edulia.notice_details') }} <span><a
                                    href="{{ url('/') }}">{{ __('edulia.home') }}</a> /
                                {{ __('edulia.notice_details') }}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_padding noticeboard">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 col-md-12">
                    <div class="noticeboard_details">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="noticeboard_details_wrapper">
                                    <h4>{{ $notice_detail->notice_title }}
                                    </h4>
                                    <span class="d-block mt-3 ">{{ date('d M, Y', strtotime($notice_detail->notice_date)) }}</span>

                                    <p>{{ $notice_detail->notice_message }}</p>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="noticeboard_inner">
                                    <div class="noticeboard_inner_head">
                                        <h5>{{ __('edulia.noticeboard') }}</h5>
                                    </div>
                                    <div class='noticeboard_inner_wrapper'>
                                        @foreach ($notices as $notice)
                                            <div class="noticeboard_inner_item">
                                                <a href="{{ route('frontend.notice-details', $notice->id) }}"
                                                    class="noticeboard_inner_item_title">{{ $notice->notice_title }}</a>
                                                <p>{{ __('edulia.published') }}:
                                                    {{ date('d M, Y', strtotime($notice->notice_date)) }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
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
        $(document).ready(function() {
            $(document).on('click', '.newsReplyBtn', function(e) {
                e.preventDefault();
                var commentId = $(this).data('comment-id');
                $('.replyDiv_' + commentId).slideToggle();
                $('.normalComment').slideToggle();
                $('.replyDiv_' + commentId).find('.parentId').val(commentId);
            })
        })
    </script>
@endpushonce
