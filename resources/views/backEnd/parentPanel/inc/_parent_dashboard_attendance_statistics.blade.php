@push('css')
    <style>
        #table_id1 {
            border: 1px solid var(--border_color);
        }

        #table_id1 td {
            border: 1px solid var(--border_color);
            text-align: center;
        }

        #table_id1 th {
            border: 1px solid var(--border_color);
            text-align: center;
        }

        .main-wrapper {
            display: block;
            width: auto;
            align-items: stretch;
        }

        .main-wrapper {
            display: block;
            width: auto;
            align-items: stretch;
        }

        #main-content {
            width: auto;
        }

        #table_id1 td {
            border: 1px solid var(--border_color);
            text-align: center;
            padding: 7px;
            flex-wrap: nowrap;
            white-space: nowrap;
            font-size: 11px
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #828bb2;
            height: 5px;
            border-radius: 0;
        }

        .table-responsive::-webkit-scrollbar {
            width: 5px;
            height: 5px
        }

        .table-responsive::-webkit-scrollbar-track {
            height: 5px !important;
            background: #ddd;
            border-radius: 0;
            box-shadow: inset 0 0 5px grey
        }

        .attendance td {
            padding: .3rem !important;
            font-size: 12px !important;
        }

        .dataTables_filter {
            margin-top: 30px;
        }
        .white-box.attendance-table {
            padding: 30px !important;
        }
    </style>
@endpush
@if (isset($attendance))
    <div class="col-lg-12">
        <div class="white-box">
            <div class="row mb-15">
                <div class="col-lg-12 no-gutters d-flex align-items-center justify-content-between">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('parent.monthly_attendance_report')({{ date('F') }})
                        </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="attendance-table">
                        <div class="lateday d-flex">
                            <div class="mr-3">@lang('parent.present'): <span class="text-success">P</span></div>
                            <div class="mr-3">@lang('parent.late'): <span class="text-warning">L</span></div>
                            <div class="mr-3">@lang('parent.absent'): <span class="text-danger">A</span></div>
                            <div class="mr-3">@lang('parent.half_day'): <span class="text-info">F</span></div>
                            <div>@lang('parent.holiday'): <span class="text-dark">H</span></div>
                        </div>
                        <div class="table-responsive">
                            <table id="table_id1" class="display school-table table-responsive attendance" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%">P</th>
                                        <th width="3%">L</th>
                                        <th width="3%">A</th>
                                        <th width="3%">F</th>
                                        <th width="3%">H</th>
                                        <th width="2%">%</th>
                                        @for ($i = 1; $i <= $days; $i++)
                                            <th width="3%" class="{{ $i <= 18 ? 'all' : 'none' }}">
                                                {{ $i }} <br>
                                                @php
                                                    $date = $year . '-' . $month . '-' . $i;
                                                    $day = date('D', strtotime($date));
                                                    echo $day;
                                                @endphp
                                            </th>
                                        @endfor
                                    </tr>
                                </thead>
    
                                <tbody>
                                    @php
                                        $total_grand_present = 0;
                                        $total_late = 0;
                                        $total_absent = 0;
                                        $total_holiday = 0;
                                        $total_halfday = 0;
                                    @endphp
                                    @php $total_attendance = 0; @endphp
                                    @php $count_absent = 0; @endphp
                                    <tr>
                                        <td>
                                            @php $p = 0; @endphp
                                            @foreach ($attendance as $value)
                                                @if ($value->attendance_type == 'P')
                                                    @php
                                                        $p++;
                                                        $total_attendance++;
                                                        $total_grand_present++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            {{ $p }}
                                        </td>
                                        <td>
                                            @php $l = 0; @endphp
                                            @foreach ($attendance as $value)
                                                @if ($value->attendance_type == 'L')
                                                    @php
                                                        $l++;
                                                        $total_attendance++;
                                                        $total_late++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            {{ $l }}
                                        </td>
                                        <td>
                                            @php $a = 0; @endphp
                                            @foreach ($attendance as $value)
                                                @if ($value->attendance_type == 'A')
                                                    @php
                                                        $a++;
                                                        $count_absent++;
                                                        $total_attendance++;
                                                        $total_absent++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            {{ $a }}
                                        </td>
    
                                        <td>
                                            @php $f = 0; @endphp
                                            @foreach ($attendance as $value)
                                                @if ($value->attendance_type == 'F')
                                                    @php
                                                        $f++;
                                                        $total_attendance++;
                                                        $total_halfday++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            {{ $f }}
                                        </td>
                                        <td>
                                            @php $h = 0; @endphp
                                            @foreach ($attendance as $value)
                                                @if ($value->attendance_type == 'H')
                                                    @php
                                                        $h++;
                                                        $total_attendance++;
                                                        $total_holiday++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            {{ $h }}
                                        </td>
                                        <td>
                                            @php
                                                $total_present = $total_attendance - $count_absent;
                                                if ($count_absent == 0) {
                                                    echo '100%';
                                                } else {
                                                    $percentage = ($total_present / $total_attendance) * 100;
                                                    echo number_format((float) $percentage, 2, '.', '') . '%';
                                                }
                                            @endphp
                                        </td>
                                        @for ($i = 1; $i <= $days; $i++)
                                            @php
                                                $date = $year . '-' . $month . '-' . $i;
                                                $y = 0;
                                            @endphp <td
                                                width="3%" class="{{ $i <= 18 ? 'all' : 'none' }}">
                                                @foreach ($attendance as $value)
                                                    @if (strtotime($value->attendance_date) == strtotime($date))
                                                        {{ $value->attendance_type }}
                                                    @endif
                                                @endforeach
                                        @endfor
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" id="total-attendance"
                            value="{{ $total_grand_present . '-' . $total_absent . '-' . $total_late . '-' . $total_halfday . '-' . $total_holiday }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
