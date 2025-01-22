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
            font-size: 11px;
            vertical-align: middle;
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

        hr {
            margin: 0px;
        }
        .white-box.subject-attendance-table {
            padding: 30px !important;
        }
    </style>
@endpush
@if (isset($subjectAttendance))
    <div class="col-lg-12 mt-40">
        <div class="white-box">
            <div class="row">
                <div class="col-lg-6 no-gutters">
                    <div class="main-title mb-0">
                        <h3 class="mb-15">@lang('student.monthly_subject_attendance_report')({{ date('F') }})
                        </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="subject-attendance-table">
                        <div class="lateday d-flex flex-wrap">
                            <div class="mr-3 mb-10">@lang('student.present'): <span class="text-success">P</span></div>
                            <div class="mr-3 mb-10">@lang('student.late'): <span class="text-warning">L</span></div>
                            <div class="mr-3 mb-10">@lang('student.absent'): <span class="text-danger">A</span></div>
                            <div class="mr-3 mb-10">@lang('student.half_day'): <span class="text-info">F</span></div>
                            <div>@lang('student.holiday'): <span class="text-dark">H</span></div>
                        </div>
                        <div class="table-responsive">
                            <table id="table_id1" class="table table-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="6%">@lang('common.name')</th>
                                        <th width="6%">@lang('student.admission_no')</th>
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
                                    @php
                                        $last_key_number = array_key_last([$subjectAttendance]);
                                    @endphp
                                    @php $total_attendance = 0; @endphp
                                    @php $count_absent = 0; @endphp
                                    <tr>
                                        <td>
                                            @php $student = 0; @endphp
                                            @foreach ($subjectAttendance as $value)
                                                @php $student++; @endphp
                                                @if ($student == 1)
                                                    {{ @$value->student->full_name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @php $student = 0; @endphp
                                            @foreach ($subjectAttendance as $value)
                                                @php $student++; @endphp
                                                @if ($student == 1)
                                                    {{ @$value->student->admission_no }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @php $p = 0; @endphp
                                            @foreach ($subjectAttendance as $value)
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
                                            @foreach ($subjectAttendance as $value)
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
                                            @foreach ($subjectAttendance as $value)
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
                                            @foreach ($subjectAttendance as $value)
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
                                            @foreach ($subjectAttendance as $value)
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
                                            @endphp
                                            {{ $total_present . '/' . $total_attendance }}
                                            <hr>
                                            @php
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
                                            @endphp
                                            <td width="3%" class="{{ $i <= 18 ? 'all' : 'none' }}">
                                                @php
                                                    $date_present = 0;
                                                    $date_absent = 0;
                                                    $date_total_class = 0;
                                                @endphp
                                                @foreach ($subjectAttendance as $key => $value)
                                                    @if (strtotime($value->attendance_date) == strtotime($date))
                                                        @php
                                                            if ($value->attendance_type == 'P' || $value->attendance_type == 'F' || $value->attendance_type == 'L') {
                                                                $date_present++;
                                                            } else {
                                                                $date_absent++;
                                                            }
                                                            $date_total_class = $date_present + $date_absent;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                @if ($date_total_class != 0)
                                                    {{ $date_present . '/' . $date_total_class }}
                                                    <hr>
                                                    @php
                                                        if ($date_absent == 0) {
                                                            echo '100%';
                                                        } else {
                                                            if ($date_present != 0) {
                                                                $date_percentage = ($date_present / $date_total_class) * 100;
                                                                echo @number_format((float) $date_percentage, 2, '.', '') . '%';
                                                            } else {
                                                                echo '0%';
                                                            }
                                                        }
                                                    @endphp
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
