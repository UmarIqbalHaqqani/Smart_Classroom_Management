<html>
    <title>@lang('student.view_transcript')</title>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                font-size: 14px;
                margin: 0;
                padding: 0;
            }
    
            table {
                border-collapse: collapse;
            }
    
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                margin: 0;
                color: #101010;
            }
    
            .invoice_wrapper {
                /* max-width: 1200px; */
                max-width: 100%;
                margin: auto;
                background: #fff;
                padding: 20px;
            }
    
            .table_exam {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
            }
    
            .border_none {
                border: 0px solid transparent;
                border-top: 0px solid transparent !important;
            }
    
            .invoice_part_iner {
                background-color: #fff;
            }
    
            .table_border thead {
                background-color: #F6F8FA;
            }
    
            .table_exam td,
            .table_exam th {
                padding: 5px 0;
                vertical-align: top;
                border-top: 0 solid transparent;
                color: #101010;
            }
    
            .table_exam td,
            .table_exam th {
                padding: 5px 0;
                vertical-align: top;
                border-top: 0 solid transparent;
                color: #101010;
            }
    
            .table_border tr {
                border-bottom: 1px solid #101010 !important;
            }
    
            .table_exam th p span,
            td p span {
                color: #212E40;
            }
    
            .table_exam th {
                color: #101010;
                border: 1px solid #101010 !important;
            }
    
            p {
                font-size: 14px;
                color: #101010;
            }
    
            h5 {
                font-size: 12px;
                font-weight: 500;
            }
    
            h6 {
                font-size: 10px;
                font-weight: 300;
            }
    
            .mt_40 {
                margin-top: 40px;
            }
    
            .table_style th,
            .table_style td {
                padding: 20px;
            }
    
            .invoice_info_table td {
                font-size: 10px;
                padding: 0px;
            }
    
            .text_right {
                text-align: right;
            }
    
            .virtical_middle {
                vertical-align: middle !important;
            }
    
            .logo_img {
                max-width: 120px;
            }
    
            .logo_img img {
                width: 100%;
            }
    
            .border_bottom {
                border-bottom: 1px solid #000;
            }
    
            .line_grid {
                display: grid;
                grid-template-columns: 110px auto;
                grid-gap: 10px;
            }
    
            .line_grid span {
                display: flex;
                justify-content: space-between;
            }
    
            .line_grid2 {
                display: grid;
                grid-template-columns: auto 110px;
                grid-gap: 10px;
            }
    
            .line_grid2 span {
                display: flex;
                justify-content: space-between;
            }
    
            p {
                margin: 0;
            }
            .mt_30{
                margin-top: 30px;
            }
    
            .font_18 {
                font-size: 18px;
            }
    
            .mb-0 {
                margin-bottom: 0;
            }
    
            .mb_30 {
                margin-bottom: 10px !important;
            }
    
            .border_table {}
    
            .border_table thead tr th {
                padding: 0;
                text-align: center !important;
            }
    
            .border_table tbody tr td {
                border: 1px solid #101010 !important;
                text-align: center;
                padding: 0;
            }
    
            .table_exam td,
            th {
                color: #101010;
                font-weight: 500;
                padding: 5px;
    
            }
    
            table {
                width: 100%;
            }
    
            .d_flex {
                display: flex;
            }
    
            .gap_20 {
                grid-gap: 20px;
            }
    
            .border_space {
                border-spacing: 3px;
                border-collapse: inherit;
            }
    
            .border_table tbody tr td.border-0,
            .border_table thead tr th.border-0 {
                border: 0 !important;
            }
    
            .text_right {
                text-align: right !important;
            }
    
            .seasonText {
                display: flex;
                align-items: center;
                grid-gap: 100px;
            }
    
            .seasonText p {
                font-weight: 600;
                text-transform: capitalize;
            }
    
            .f_w_600 {
                font-weight: 600;
            }
    
            .border_1px {
                border: 1px solid #000;
            }
            .table_exam thead th{
                color: #000;
            }
            .table_exam tr td, .table_exam tr th{
                font-weight: 600 !important;
            }
    
            hr {
                border-top: 2.5px solid #000;
            }
            
            .container{
                width: 100%;
                padding: 0;
                max-width: 100%;
            }
            .fail-color{
                background-color: yellow;
            }
        </style>
    </head>
<body >
    <section class="student-details mt-40 ">
        <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        @if(@$header)
                            <h3 class="mb-30">@lang('university::un.exam_report')</h3>
                        @endif
                    </div>
                </div>
            </div>

            @php
                $admission= $studentRecordDetails->first();
                $graduation= $studentRecordDetails->orderBy('id', 'desc')->first();
                $attempted = 0;
                $fail = 0;
                $cumulativeAverage = 0;
                $semesterResult = collect();
            @endphp
        
                <div class="invoice_wrapper">
                    <!-- invoice print part here -->
                    <div class="invoice_print mb_30">
                        <div class="container">
                            <div class="invoice_part_iner">
                                <table class="table mb_30">
                                    <thead>
                                        <td>
                                            <div class="transcript_print d_flex gap_20">
                                                <div class="transcript_print_left flex_fill d_flex gap_20">
                                                    <div class="logo_img">
                                                        <img src="{{asset(generalSetting()->logo)}}" alt="{{generalSetting()->school_name }}">
                                                    </div>
                                                    <div class="">
                                                        <h4>{{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}}</h4>
                                                        <h5>{{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}}</h5>
                                                        <h5>{{isset(generalSetting()->email)?generalSetting()->email:'hello@aorasoft.com'}}</h5>
                                                        <h5>{{isset(generalSetting()->phone)?generalSetting()->phone:'+96897002784'}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </thead>
                                </table>
                                <hr>

                                <table class="table_exam border_table mb_30 border_space">
                                    <thead>
                                        <tr>
                                            <th>@lang('library.full_name')</th>
                                            <th>{{$studentDetails->full_name}}</th>
                                            <th>@lang('admin.gender')</th>
                                            <th>{{@$studentDetails->gender->base_setup_name}}</th>
                                            <th>@lang('student.date_of_birth')</th>
                                            <th>{{dateConvert(@$studentDetails->date_of_birth)}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>@lang('student.roll_no')</td>
                                            <td>{{$studentDetails->roll_no}}</td>
                                            @if (moduleStatusCheck('University'))
                                            <td>@lang('student.admission')</td>
                                            <td>{{@$admission->unSemesterLabel->name}} ({{@$admission->unAcademic->name}})</td>
                                            <td>@lang('university::un.graduation')</td>
                                            <td>{{@$graduation->unSemesterLabel->name}} ({{@$graduation->unAcademic->name}})</td>
                                            @endif
                                        </tr>
                                        @if (moduleStatusCheck('University'))
                                        <tr>
                                            <td>@lang('university::un.program')</td>
                                            <td>{{@$studentRecordDetails->first()->unDepartment->name}}</td>
                                            <td>@lang('university::un.total_credit_hours')</td>
                                            <td>{{number_format(studentSubjectCredit($studentDetails->id), 2, '.', '')}}</td>
                                            <td>@lang('university::un.cumulative_avg')</td>
                                            <td class="cum-avg"></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @if (moduleStatusCheck('University'))
                                @foreach($studentRecords as $key=> $record) 
                                <table class="table_exam border_table mb_30 mt_30 border_space border_1px">
                                    <thead>
                                        <tr>
                                            <th>@lang('university::un.course_no')</th>
                                            <th>@lang('university::un.course_title')</th>
                                            <th>@lang('university::un.attempted')</th>
                                            <th>@lang('university::un.passed')</th>
                                            <th>@lang('exam.grade')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($record->unAcademic->unSemesterLabels as $unSemesterLabel)
                                            @if(isMarkRegister($unSemesterLabel->id))
                                                @php
                                                    $subjects = studentSubject($unSemesterLabel->id, $unSemesterLabel->un_academic_id, $studentDetails->id);
                                                    $totalSubject = count($subjects);
                                                    $semesterWiseAttempted = 0;
                                                    $passed = 0;
                                                    $markWithCredit = 0;
                                                    $result = 0;
                                                @endphp
                                                <tr>
                                                    <td colspan="5" class="border-0 f_w_600"><strong>{{$unSemesterLabel->name}} ({{$unSemesterLabel->academicYearDetails->name}})</strong></td>
                                                </tr>
                                                @foreach($subjects as $subject)
                                                    @php
                                                        $mark = unStudentFullMark($unSemesterLabel->id, $unSemesterLabel->un_academic_id, $subject->subject->id, $studentDetails->id);
                                                        $attempted += $subject->subject->number_of_hours;
                                                        $semesterWiseAttempted += $subject->subject->number_of_hours;
                                                        $failPass = failPassStatus($mark, $subject->subject->pass_mark, $unSemesterLabel->id, $unSemesterLabel->un_academic_id, $subject->subject->id);
                                                        $result += $mark;
                                                        if($failPass){
                                                            $fail += $subject->subject->number_of_hours;
                                                        }else{
                                                            $passed += $subject->subject->number_of_hours;
                                                        }
                                                        $markWithCredit += $mark * $subject->subject->number_of_hours;
                                                    @endphp
                                                    <tr>
                                                        <td class="border-0">{{$subject->subject->subject_code}}</td>
                                                        <td class="border-0 ">{{$subject->subject->subject_name}}</td>
                                                        <td class="border-0 ">{{$subject->subject->number_of_hours}}</td>
                                                        <td class="border-0 ">{{(@$failPass) ? 0 : $subject->subject->number_of_hours}}</td>
                                                        <td class="border-0">{{$mark}}</td>
                                                    </tr>
                                                @endforeach

                                                @php
                                                   $semesterAverage = null;
                                                   if($markWithCredit && $semesterWiseAttempted){
                                                     $semesterAverage = $markWithCredit / $semesterWiseAttempted;
                                                   }
                                                    $semesterResult->push(collect([
                                                        'avg'=>$semesterAverage,
                                                        'attempted'=>$semesterWiseAttempted,
                                                        'result'=> $semesterAverage* $semesterWiseAttempted,
                                                    ]));
                                                    $cumulativeAverage = $semesterResult->sum('result')/ $semesterResult->sum('attempted');
                                                    //$cumulativeAverage = 1;
                                                @endphp
                                                    <tr>
                                                        <td class="border-0"></td> 
                                                        <td class="border-0 text_right">@lang('university::un.semester_credit_hours')</td>
                                                        <td class="border-0">{{$semesterWiseAttempted}}</td>
                                                        <td class="border-0">{{$passed}}</td>
                                                        <td class="border-0"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="border-0"></td>
                                                        <td class="border-0"></td>
                                                        <td class="border-0 text_right">@lang('university::un.semester_average'): {{number_format($semesterAverage, 2, '.', '')}}</td>
                                                        <td class="border-0"></td>
                                                        <td class="border-0"></td>
                                                    </tr>
                                                    <tr>

                                                        <td class="border-0" colspan="5">
                                                            <div class="seasonText">
                                                                <p>@lang('university::un.cumulative_credit_hours') 
                                                                    (
                                                                        @lang('university::un.attempted'): {{$attempted}},
                                                                        @lang('university::un.passed'): {{$attempted - @$fail}},
                                                                        @lang('university::un.earned'): {{$attempted - @$fail}}
                                                                    )
                                                                </p>
                                                                <p>@lang('university::un.cumulative_average'): {{number_format($cumulativeAverage, 2, '.', '')}}</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="seasonText">
                            @if (moduleStatusCheck('Alumni'))
                                <p class="pl-2">{{@$graduate->unAlumni->notes}}</p>
                            @endif
                        </div>
                    </div>
                    
                </div>
        
        </div>
    </section>
</body>
<script src="{{ asset('public/vendor/spondonit/js/jquery-3.6.0.min.js') }}"></script>
<script>
    $('.cum-avg').text("{{number_format($cumulativeAverage, 2, '.', '')}}");
    $(document).ready(function(){
        window.print();
});
</script>

</html>
