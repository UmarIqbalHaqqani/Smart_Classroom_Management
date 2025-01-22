<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ @$certificate->name }} ({{ @$certificate->width . 'mm' }} X {{ @$certificate->height . 'mm' }})
    </title>
    <link rel="stylesheet" href="{{ asset('Modules/Certificate/Resources/assets/css/editor.css') }}">
    <link rel="stylesheet" href="{{ asset('Modules/Certificate/Resources/assets/css/paper/normalize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Modules/Certificate/Resources/assets/css/paper/paper.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/bootstrap.min.css') }}" />
    <style>
        @font-face {
            font-family: 'Pinyon Script';
            src: url('{{ asset('public/backEnd/fonts/pinyonscript-regular-webfont.woff') }}') format('woff2'),
                url('{{ asset('public/backEnd/fonts/pinyonscript-regular-webfont.woff') }}') format('woff');
            font-weight: normal;
            font-style: normal;

        }

        /* Apply the font to Summernote */
        .edu_summernote {
            font-family: '{{ $certificate->body_font_family }}', sans-serif;
            /* Fallback to sans-serif if the font is not available */
        }
    </style>
    @php

        function mmToPx($mm, $dpi = 96) {
            // Assuming 1 inch is equal to 25.4 millimeters
            // and 1 inch is approximately equal to 96 pixels (in web development)
            $inch = $mm / 25.4;
            $px = $inch * $dpi;
            return $px;
        }

        function pxToMm($px, $dpi = 96) {
            // Assuming 1 inch is equal to 25.4 millimeters
            // and 1 inch is approximately equal to 96 pixels (in web development)
            $inch = $px / $dpi;
            $mm = $inch * 25.4;
            return $mm;
        }

       
        $style_width = $certificate->width . 'mm';
        $style_height = $certificate->height . 'mm';
        $style_width = str_replace('.', '', $style_width);
        $style_height = str_replace('.', '', $style_height);

        $body_font_size = $certificate->body_font_size;
        

        $page_height= mmToPx(floatval($certificate->height));
        $page_width= mmToPx(floatval($certificate->width));
        //mm to px 

        //first element should 15% margin top of page_height


        $first_element_margin_top= intval($page_height)* 0.05;
        $first_element_margin_top= $first_element_margin_top.'px';

        $student_image_size= intval($page_height)* 0.1;
        $student_image_size= $student_image_size.'px';

        $second_element_margin_top= intval($page_height)* 0.2;
        $second_element_margin_top= $second_element_margin_top.'px';

        $certificate_height = floatval($certificate->height);
        $certificate_width = floatval($certificate->width);
        $half_of_a4_sheet = 297 / 2;
        $half_of_a4_sheet_height = 210 / 2;
        $half_of_certificate_height = $certificate_height / 2;
        //check is possible to print 2 certificate in a page
        if ($certificate_height <= $half_of_a4_sheet_height && $certificate_width <= $half_of_a4_sheet) {
            $is_possible_to_print_2_certificate_in_a_page = true;
        } else {
            $is_possible_to_print_2_certificate_in_a_page = false;
        }
    @endphp

 
    <style>
        body {
            font-family: 'dejavu sans', 'Poppins', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }


        .DivBody {
            /* height: 100vh; */
            border: 1px solid white !important;
            margin-top: 0px;
        }
        .first_element{
            margin-left: 0%;
            margin-top: {{ $first_element_margin_top }};
        }
        .second_element{
            display: flex;
            margin-top: {{ $second_element_margin_top }};
        }

        img {
            position: absolute;
        }



        body {
            /* padding:0px !important; */
            margin: 0px !important;
        }

        /* style added  */
        .DivBody {
            position: relative;
        }

        .position_bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .certificate_body_inner {
            position: relative;
            height: auto;
        }

        .position_bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .postion_header_certificate {
            position: absolute;
            /* top: 18%; */
            width: calc(100% - 20%);
            left: 0;
            margin: auto;
            right: 0;
            margin-top: -18%;
        }

        .postion_footer_certificate {
            position: absolute;
            /* bottom: 55%; */
            width: calc(100% - 20%);
            left: 0;
            margin: auto;
            right: 0;
        }

        .certificate_body_table {
            margin-top: {{ $half_of_certificate_height - 10 }}mm;
        }

        .setup_height_width {
            /* width: @php echo $style_width @endphp
            ;
            */ height: {{ $style_height }};
            width: {{ $style_width }};
        }

        /* Module css */

        @page {
            size: A4
        }

        .edu_summernote {
            font-size: {{ $body_font_size }};
        }

        .student-meta-img {
            position: absolute;
            /* top: 50px;
            left: 30px; */
            border-radius: 6px;
            width: 105px;
        }

        .img-100 {
            max-width: {{$student_image_size}};
            height: auto;
            border-radius: 6px;
        }
        .certificate:last-child {
            page-break-after: auto;
        }
        .certificate{
                margin: 0 auto;
            }
        /* remove blank page in print */
        @page {
            size: auto;
            margin: 0;
        }

        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            @page {
                size: A4
            }

            .page {
                margin: -2px;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                /* background: initial; */
                /* page-break-after: always; */
                vertical-align: middle;
            }

            .certificate {
                width: {{ $style_width }};
                height: {{ $style_height }};

                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                vertical-align: middle;

            }


            .DivBody {
                margin-bottom: 1px;
            }
            .certificate{
                margin: 0 auto;
            }

            .page_break {
                page-break-after: always;
            }
       
        }
    </style>

</head>


<body class="A4">
    <div class="certificate setup_height_width">
        @foreach ($students as $key => $student)
            <div class="DivBody setup_height_width">
                <img class="position_bg setup_height_width" src="{{ asset($certificate->file) }}">
                <div class="certificate_body_inner">
                    <div style="margin: 0 auto; padding: 15px;">
                        <div style="display: flex; justify-content: center;">
                            <div style="flex: 1;"></div>
                            <div style="flex: 8;">
                                <div class="first_element">
                                    <div style="flex: 6;">
                                        @if($certificate->student_photo == 1)
                                        <img class="student-meta-img img-100"
                                            src="{{ file_exists(@$student->student->student_photo) ? asset($student->student->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                                            alt="">
                                        @endif
                                    </div>
                                </div>
                                <div class="second_element" style="">
                                    <div style="flex: 6;">{{ @$certificate->header_left_text }}</div>
                                    <div style="flex: 6; text-align: right; white-space: nowrap;">@lang('common.date'):
                                        {{ @$certificate->date }}</div>
                                </div>
                                <div class="edu_summernote" style="text-align: center; margin-top: 7%;">
                                    {{ isset($student['student_id']) ? App\SmStudentCertificate::certificateBody($certificate->body, 2, $student['student_id']) : '' }}
                                </div>
                                <div style="text-align: center; margin-top: 20%; display:flex;">
                                    <div
                                        style="flex: 4; border-top: 1px solid #000; margin-top: 20px; margin-right: 5px;">
                                        {{ @$certificate->footer_left_text }}
                                    </div>
                                    <div
                                        style="flex: 4; border-top: 1px solid #000; margin-top: 20px; margin-right: 5px;">
                                        {{ @$certificate->footer_center_text }}
                                    </div>
                                    <div style="flex: 4; border-top: 1px solid #000; margin-top: 20px;">
                                        {{ @$certificate->footer_right_text }}
                                    </div>
                                </div>
                            </div>
                            <div style="flex: 1;"></div>
                        </div>
                    </div>

                </div>
            </div>
            @if($is_possible_to_print_2_certificate_in_a_page)
                @if($key % 2 != 0)
                    <div class="page_break"></div>
                @endif
            @elseif($certificate->layout == 1)
                <div class="page_break"></div>
            @endif
        @endforeach
    </div>




    <script src="{{ asset('https://infix_edu.test/public/backEnd/vendors/js/jquery-3.2.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>

    <script>
        //jquery save .this-template save as image in public folder
        let count_certificate = $(".DivBody").length;
        let time_for_each_certificate = 1000 * count_certificate;

        $(window).on('load', function() {
            window.print();
        });
    </script>
</body>

</html>
