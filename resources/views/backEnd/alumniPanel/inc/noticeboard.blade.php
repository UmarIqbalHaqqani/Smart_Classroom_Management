@if (userPermission('notice-board'))
<section class="">
    <div class="container-fluid p-0">
        <div class="white-box">
            <div class="row">
                <div class="col-lg-6 col-7">
                    <div class="main-title">
                        <h3 class="mb-15">@lang('communicate.notice_board')</h3>
                    </div>
                </div>


                <div class="col-lg-12">
                    <table class="school-table-style w-100">
                        <thead>
                            <tr>
                                <th>@lang('common.date')</th>
                                <th>@lang('dashboard.title')</th>
                                <th class="d-flex justify-content-around">@lang('common.actions')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $role_id = Auth()->user()->role_id; @endphp
                            @if (isset($totalNotices)) 
                                @foreach ($totalNotices as $notice)
                                    <tr>
                                        <td> @if ($notice->publish_on){{ dateConvert($notice->publish_on) }} @endif </td>
                                        <td>{{ $notice->notice_title }}</td>
                                        <td class="d-flex justify-content-around">
                                            <a href="{{ route('view-notice', $notice->id) }}" title="@lang('common.view_notice')"
                                                class="primary-btn small tr-bg modalLink" data-modal-size="modal-lg">
                                                <span class="ti-eye"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endif