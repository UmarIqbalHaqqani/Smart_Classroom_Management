<div class="white-box">
    <div class="row">
        <div class="col-lg-6 col-7">
            <div class="main-title">
                <h3 class="mb-15">@lang('alumni::al.upcoming_events')</h3>
            </div>
        </div>
        <div class="col-lg-12">
            <table class="school-table-style w-100">
                <thead>
                    <tr>
                        <th>@lang('dashboard.title')</th>
                        <th>@lang('common.date')</th>
                        <th class="d-flex justify-content-around">@lang('common.actions')</th>
                    </tr>
                </thead>

                <tbody>
                    @php $role_id = Auth()->user()->role_id; @endphp
                    @if (isset($smEvents)) 
                        @foreach ($smEvents as $u_event)
                            <tr>
                                <td>{{ $u_event->event_title }}</td>
                                <td> @if ($u_event->from_date){{ dateConvert($u_event->from_date) }} @endif - @if($u_event->to_date) {{ dateConvert($u_event->to_date)}} @endif </td>

                                <td class="d-flex justify-content-around">
                                    <a href="{{ route('alumni-view-event', $u_event->id) }}" title="@lang('alumni::al.view_event')"
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