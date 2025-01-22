<div class="white-box">
    @if (@$my_leaves)
    <div class="row">
        <div class="col-lg-12">
            <div class="row ">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title mb-15">
                        <h3>@lang('leave.leave_types')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <x-table>
                        <table id="table_id" class="leave_table table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('common.type')</th>
                                    <th>@lang('leave.remaining_days')</th>
                                    <th>@lang('leave.extra_taken')</th>
                                    <th>@lang('leave.leave_taken')</th>
                                    <th>@lang('leave.leave_days')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($my_leaves as $my_leave)
                                    @php
                                        $approved_leaves = App\SmLeaveRequest::approvedLeave($my_leave->id);
                                        $remaining_days = $my_leave->days - $approved_leaves;
                                        $extra_days = $remaining_days < 0 ? $approved_leaves - $my_leave->days : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $my_leave->leaveType != '' ? $my_leave->leaveType->type : '' }}</td>
                                        <td>{{ $remaining_days >= 0 ? $remaining_days : 0 }}</td>

                                        <td>{{ $remaining_days < 0 ? $approved_leaves - $my_leave->days : 0 }}</td>
                                        <td>{{ $approved_leaves }}</td>
                                        <td>{{ $my_leave->days }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
@endif

</div>