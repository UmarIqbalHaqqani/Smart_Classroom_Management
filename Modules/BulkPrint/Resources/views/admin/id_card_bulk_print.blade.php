<!DOCTYPE html>
<html>

<head>
    <title>
        @if ($role_id == 2)
            @lang('student.student_id_card')
        @elseif ($role_id == 3)
            @lang('parent.parent_id')
        @else
            @lang('hr.staff_id')
        @endif
    </title>
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/vendors/css/bootstrap.css" />
    <style media="print">
        @import url("https://fonts.googleapis.com/css?family=Poppins:300,400,400i,500,600");

        td {
            border-right: 1px solid #ddd;
            border-left: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        table tr td {
            border: 0 !important;
        }
    </style>
    <style>
         .id_card {
            display: grid !important;grid-template-columns: repeat(2,1fr) !important;grid-gap: 10px;justify-content: center;
        }

        input#button {
            margin: 21px 0;
            margin-left: 8%;
            background: #8733f9;
            display: inline-block;
            color: #f1f2f7;
            letter-spacing: 1px;
            font-family: "Poppins", sans-serif;
            font-size: 12px;
            font-weight: 500;
            line-height: 40px;
            padding: 0px 20px;
            outline: none !important;
            text-align: center;
            cursor: pointer;
            text-transform: uppercase;
            border: 0;
            border-radius: 5px;
            position: relative;
            overflow: hidden;
            -webkit-transition: all 0.4s ease 0s;
            -moz-transition: all 0.4s ease 0s;
            -o-transition: all 0.4s ease 0s;
            transition: all 0.4s ease 0s;
        }

        td {
            font-size: 11px;
            padding: 0 12px;
            line-height: 18px;
        }
        body#abc {
            max-width: 1000px;
            margin: auto;
            /* background: #F0F0F0; */
        }
        table {
            width: 100%;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body id="abc">
    <input type="button" onclick="printDiv('abc')" id="button" class="primary-btn small fix-gr-bg" value="print" />

    <div class="id_card" id="id_card" style="display: grid !important;grid-template-columns: repeat(3,1fr) !important;grid-gap: {{$gridGap}}px;justify-content: center;">
        @php
            $roleId= json_decode($id_card->role_id);
            
        @endphp
        @foreach($s_students as $staff_student)
            @if(!in_array(3,$roleId))
                
                @php
                    $id_role = $staff_student->role_id == 2 ? 'student':'staff';
                    if(!file_exists(public_path('qr_codes/'.$id_role.'-'.$staff_student->id.'-qrcode.png')))
                    {
                        generateQRCode($id_role.'-'.$staff_student->id);
                    }
                    $school_name = generalSetting()->school_name;
                @endphp
                @if($id_card->page_layout_style=='horizontal') 
                <div id="vertical"  style="overflow: auto; margin: 0; padding: 0; font-family: 'Poppins', sans-serif;  font-size: 12px; line-height:1.02 ;">
                    <div class="vertical__card" style="line-height:1.02; background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->background_img) : asset('public/backEnd/id_card/img/horizontal_bg.png') }}); width: {{!empty($id_card->pl_width) ? $id_card->pl_width : 86}}mm; height: {{!empty($id_card->pl_height) ? $id_card->pl_height : 54}}mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative;">
                        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding: 12px">
                            
                            <!-- TODO: Institute name new section -->
                            @if(!empty($school_name))
                                <div class="card-institute-name" style="position: absolute;right: 11px;">
                                    <p style="line-height:1.02; font-size: 10px; font-weight: 700; margin: 0; padding: 0; text-align: center; font-size: 10px; font-weight: 700; text-align: right; line-heigtht: 9px; color: #000000;">{{ $school_name }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="vertical_card_body" style="line-height:1.02; padding-top: {{!empty($id_card->t_space) ? $id_card->t_space : 2.5}}mm; padding-bottom: {{!empty($id_card->b_space) ? $id_card->b_space : 2.5}}mm; align-items: center; gap: 14px; margin-left: 11px; margin-right: 0px; display: flex;">
                            <div style="width: 50%; background: #ffffff; padding: 12px; border-radius: 5px; border-top-left-radius: 10px;">
                                <div class="thumb vSize vSizeX photo vImg vRoundImg {{ $id_card->photo != 1 ? 'd-none':''}}" style="
                            @if (@$id_card->user_photo_style=='round')
                                {{"border-radius : 50%;"}}
                            @endif
                            background-image: url(
                                @if($role_id==2)
                                    {{ @$staff_student->student_photo != "" ? asset(@$staff_student->student_photo) : (@$id_card->profile_image != "" ? asset(@$id_card->profile_image) : asset('public/backEnd/id_card/img/thumb.png')) }}
                                @else
                                    {{ @$staff_student->staff_photo != "" ? asset(@$staff_student->staff_photo) : (@$id_card->profile_image != "" ? asset(@$id_card->profile_image) : asset('public/backEnd/id_card/img/thumb.png')) }} 
                                @endif
                                ); line-height:1.02; width: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 18.5}}mm; height: {{!empty($id_card->user_photo_height) ? $id_card->user_photo_height : 18.5}}mm; flex-basis: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 18.5}}mm; flex-grow: 0; flex-shrink: 0; margin-right: 0px; background-size: cover; background-position: center center; border: 3px solid #ffffff; margin-top: -35px; margin-left: -12px;"></div>

                                @if($id_card->student_name==1)
                                    <div id="vName">
                                        <h3 style="line-height:1.5; margin-top: 0; margin-bottom: 0px; font-size:12px; font-weight:700 ; color: #000F28; font-family: Poppins;">{{$staff_student->full_name}}</h3>
                                    </div>
                                @endif
                                @if($id_card->admission_no==1)
                                    <div id="vAdmissionNumber">
                                        @if($role_id==2)
                                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">@lang('student.admission_no') : {{$staff_student->admission_no}}</h4>
                                        @else 
                                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">@lang('hr.staff_id') : {{$staff_student->staff_no}}</h4>
                                        @endif
                                    </div>
                                @endif
                                @if ($id_card->staff_department == 1 && !in_array(2,$roleId) && !in_array(3,$roleId) )
                                <div id="vStaffDepartment" >
                                    <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">Department :  {{ !empty($staff_student->departments) ? $staff_student->departments->name:'' }} </h4>
                                </div>  
                                @endif
                                @if ($id_card->staff_department == 1 && !in_array(2,$roleId) && !in_array(3,$roleId) )
                                <div id="vStaffDesignation" >
                                    <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">Designation : {{ !empty($staff_student->designations ) ? $staff_student->designations->title:"" }} </h4>
                                </div>
                                @endif
                                @if($id_card->class==1 &&  $role_id==2 && !moduleStatusCheck('University'))
                                    <div id="vClass">
                                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">@lang('common.class') :
                                            @foreach($staff_student->getClassRecord as $record)
                                                {{ $record->class->class_name}} ({{$record->section->section_name}})
                                                {{ ($loop->iteration > 1 && !$loop->last) ? ',' :'' }}
                                            @endforeach
                                        </h4>
                                    </div>
                                @endif
                                @if($role_id==2 && moduleStatusCheck('University') && moduleStatusCheck('QRCodeAttendance') == false)
                                    @if($id_card->un_session==1)
                                        <div id="vSession">
                                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">@lang('university::un.session') : @foreach($staff_student->studentRecords as $record)
                                                {{ $record->unSession->name}} 
                                                {{ ($loop->iteration > 1 && !$loop->last) ? ',' :'' }}
                                                @endforeach</h3>
                                        </div>
                                    @endif
                                    @if($id_card->un_faculty==1 && moduleStatusCheck('QRCodeAttendance') == false)
                                        <div id="vFaculty">
                                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">@lang('university::un.faculty') : @foreach($staff_student->studentRecords as $record)
                                                {{ $record->unFaculty->name}} 
                                                {{ ($loop->iteration > 1 && !$loop->last) ? ',' :'' }}
                                                @endforeach</h3>
                                        </div>
                                    @endif
                                    @if($id_card->un_department==1 && moduleStatusCheck('QRCodeAttendance') == false)
                                        <div id="vDepartment">
                                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">@lang('university::un.department') : @foreach($staff_student->studentRecords as $record)
                                                {{ $record->unDepartment->name}} 
                                                {{ ($loop->iteration > 1 && !$loop->last) ? ',' :'' }}
                                                @endforeach</h3>
                                        </div>
                                    @endif
                                    @if($id_card->un_academic==1 && moduleStatusCheck('QRCodeAttendance') == false)
                                        <div id="vAcademic">
                                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">@lang('university::un.academic') : @foreach($staff_student->studentRecords as $record)
                                                {{ $record->unAcademic->name}} 
                                                {{ ($loop->iteration > 1 && !$loop->last) ? ',' :'' }}
                                                @endforeach</h3>
                                        </div>
                                    @endif

                                    @if($id_card->un_semester==1 && moduleStatusCheck('QRCodeAttendance') == false)
                                        <div id="vSemester">
                                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">@lang('university::un.semester') : @foreach($staff_student->studentRecords as $record)
                                                {{ $record->unSemester->name}} 
                                                {{ ($loop->iteration > 1 && !$loop->last) ? ',' :'' }}
                                                @endforeach</h3>
                                        </div>
                                    @endif
                                @endif

                                <div class="singnature_img signPhoto vSign {{ $id_card->signature_status != 1 ? 'd-none':''}}" style="background-image: url({{ $id_card->signature != "" ? asset($id_card->signature) : asset('public/backEnd/id_card/img/Signature.png') }}); line-height:1.02; width: 50px; flex: 50px 0 0; height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center; margin-top: 6px">
                                </div>
                            </div>
                        
                                

                            <!-- TODO: New section for qr code -->
                            @if(moduleStatusCheck('QRCodeAttendance')) 
                            <div class="card_qr_code" style="border: 2px solid #6D36E9; width: 120px; height: 120px; aspect-ratio: 1/1; margin: auto; padding: 2px; margin-top: -8px">
                                <img src="{{asset('public/qr_codes/'.$id_role.'-'.$staff_student->id.'-qrcode.png')}}" style="width: 100%; height: 100%; aspect-ratio: 1/1; object-fit: cover; object-position: center center;" alt="qr code">
                            </div>
                            @else    

                            <table style="border: 1px solid #B4B4B4; width: 50%; margin-right: 11px">
                                <tbody>
                                @if($id_card->dob==1)
                                    <tr id="vDob">
                                        <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                            @lang('common.date_of_birth')
                                        </td>
                                        <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                            {{@dateConvert($staff_student->date_of_birth)}}
                                        </td>
                                    </tr>
                                @endif

                                @if($id_card->blood==1 && $role_id==2)
                                    <tr id="vBloodGroup">
                                        <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                            @lang('student.blood_group')
                                        </td>
                                        <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                            {{@$staff_student->bloodGroup!=""?@$staff_student->bloodGroup->base_setup_name:""}}
                                        </td>
                                    </tr>
                                @endif

                                @if($id_card->father_name ==1)
                                    <tr id="vFatherName">
                                        <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                            @lang('student.father_name')
                                        </td>
                                        <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                            @if($role_id==2) {{@$staff_student->parents !=""?@$staff_student->parents->fathers_name:""}}@else {{$staff_student->fathers_name}} @endif
                                        </td>
                                    </tr>
                                @endif

                                @if($id_card->mother_name==1)
                                    <tr id="vMotherName">
                                        <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                            @lang('student.mother_name')
                                        </td>
                                        <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                            @if($role_id==2) {{@$staff_student->parents !=""?@$staff_student->parents->mothers_name:""}} @else {{$staff_student->mothers_name}} @endif
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table> 

                            @endif
                            <!-- TODO: the below should not be commented -->
                            
                        </div>
                        <div class="horizontal_card_footer" style="line-height:1.02;">

                        <div class="card_text_head " style="line-height:1.02; width: 100%;"> 
                            @if($id_card->student_address==1)
                                <div class="card_text_left vAddress" style="background: #000F28; border: 0.5px solid #ffffff; border-radius: 46px; margin-left: 8px; padding: 2px 10px; width: calc(100% - 35%); position: absolute; bottom: 8px;">
                                    <h3 style="line-height:1.5; margin-top: 0; font-size:8px; margin-bottom: 0; font-weight:500; color: #ffffff;">{{generalSetting()->address}} </h3>
                                </div>
                            @endif
                        </div>
                        <!-- TODO: new logo position -->

                        <div class="logo__img logoImage vLogo" style="line-height:1.02; width: 57px; background-image: url({{$id_card->logo !=''? asset($id_card->logo) : asset('public/backEnd/img/logo.png')}});background-size: cover; height: 23px;background-position: center center; background-repeat: no-repeat; position: absolute; margin-left: auto; right: 8px; bottom: 8px;"></div>
                        </div>
                    </div>
                </div>

                @endif
                @if($id_card->page_layout_style=='vertical')
                    
                <div id="horizontal" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif; font-weight: 500;  font-size: 12px; line-height:1.02 ; color: #000">
                    <div class="horizontal__card" style="line-height:1.02; background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->background_img) : asset('public/backEnd/id_card/img/vertical_bg.png') }}); width: {{!empty($id_card->pl_width) ? $id_card->pl_width : 57.15}}mm; height: {{!empty($id_card->pl_height) ? $id_card->pl_height : 88.89999999999999}}mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative; background-color: #fff; display: flex; flex-direction: column;">
                        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding:6px 5px; padding-left: 10px; gap: 16px">
                            <div class="logo__img logoImage hLogo" style="line-height:1.02; width: 80px; background-image: url({{$id_card->logo !=''? asset($id_card->logo) : asset('public/backEnd/img/logo.png')}});height: 23px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
                            <!-- TODO: Institute name new section -->
                            @if(!empty($school_name))
                                <div class="card-institute-name" style="border-radius: 46px; background: #ffffff; padding: 4px 10px;">
                                    <p style="line-height:1.02; font-size: 10px; font-weight: 600; margin: 0; padding: 0; text-align: center; font-size: 8px; font-weight: 500; line-heigtht: 9px; color: #000000;"> {{ $school_name }} </p>
                                </div>
                            @endif
                        </div>

                        <div class="horizontal_card_body" style="line-height:1.02; display:flex; padding-top:{{!empty($id_card->t_space) ? $id_card->t_space : 2.5}}mm ; padding-bottom: {{!empty($id_card->b_space) ? $id_card->b_space : 2.5}}mm ; padding-right: {{!empty($id_card->r_space) ? $id_card->r_space : 3}}mm ; padding-left: {{!empty($id_card->l_space) ? $id_card->l_space : 3}}mm ; flex-direction: column; padding-top: 0;">

                        <div class="thumb hRoundImg hSize photo hImg hRoundImg {{ $id_card->photo != 1 ? 'd-none':''}}" style="
                            @if (@$id_card->user_photo_style=='round')
                                {{"border-radius : 50%;"}}
                            @endif
                            background-image: url(
                                @if($role_id==2)
                                    {{ @$staff_student->student_photo != "" ? asset(@$staff_student->student_photo) : (@$id_card->profile_image != "" ? asset(@$id_card->profile_image) : asset('public/backEnd/id_card/img/thumb.png')) }}
                                @else
                                    {{ @$staff_student->staff_photo != "" ? asset(@$staff_student->staff_photo) : (@$id_card->profile_image != "" ? asset(@$id_card->profile_image) : asset('public/backEnd/id_card/img/thumb.png')) }} 
                                @endif
                                );background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: {{!empty($id_card->user_photo_width) ? $id_card->user_photo_width : 16.41}}mm; flex: 62px 0 0; height: {{!empty($id_card->user_photo_height) ? $id_card->user_photo_height : 16.41}}mm; border: 1px solid #fff; margin: auto;"></div>
                                <!-- TODO: Default color changed to 16.41.06mm may need to update backend setting -->

                                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: center; width: 100%; margin-top:10px; margin-bottom:10px">
                                    <div class="card_text_left hId" style="text-align: center">
                                        @if($id_card->student_name==1)
                                            <div id="hName">
                                                <h4 style="line-height:1.5; margin-top: 0; margin-bottom: 0px; font-size:12px; font-weight:700 ; color: #000F28; font-family: Poppins; text-align: center;">{{ $staff_student->full_name !=''? $staff_student->full_name :''}}</h4>
                                            </div>
                                        @endif
                                        @if($id_card->admission_no==1 )
                                            <div id="hAdmissionNumber">
                                                @if($role_id==2)
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">@lang('student.admission_no') : {{$staff_student->admission_no}}</h3>
                                                @else 
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">@lang('hr.staff_id') : {{$staff_student->staff_no}}</h3>
                                                @endif
                                            </div>
                                        @endif
                                       
                                        @if ($id_card->staff_department == 1  && !in_array(2,$roleId) && !in_array(3,$roleId))
                                        <div id="hStaffDepartment" >
                                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">Department :  {{ !empty($staff_student->departments) ?$staff_student->departments->name:'' }} </h4>
                                        </div>  
                                        @endif
                                        @if ($id_card->staff_designation == 1  && !in_array(2,$roleId) && !in_array(3,$roleId))
                                        <div id="hStaffDesignation" >
                                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">Designation :  {{ !empty($staff_student->designations) ? $staff_student->designations->title:'' }} </h4>
                                        </div>
                                        @endif
                                        
                                        
                                        @if($id_card->class==1 && $role_id==2)
                                            
                                            @if(!moduleStatusCheck('University'))
                                                <div id="hClass">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">@lang('common.class') :
                                                        @foreach($staff_student->getClassRecord as $record)
                                                            {{ $record->class->class_name}} ({{$record->section->section_name}})
                                                            {{ ($loop->iteration > 1 && !$loop->last) ? ',' :'' }}
                                                        @endforeach
                                                    </h3>
                                                </div>
                                            @endif

                                            @if($id_card->un_faculty==1 && moduleStatusCheck('QRCodeAttendance') == false)
                                                <div id="hFaculty">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('university::un.faculty') : @foreach($staff_student->studentRecords as $record)
                                                        {{ $record->unFaculty->name}} ,
                                                        @endforeach</h3>
                                                </div>
                                            @endif
                                            @if($id_card->un_department==1 &&  moduleStatusCheck('QRCodeAttendance') == false)
                                                <div id="hDepartment">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('university::un.department') : @foreach($staff_student->studentRecords as $record)
                                                        {{ $record->unDepartment->name}} ,
                                                        @endforeach</h3>
                                                </div>
                                            @endif
                                            @if($id_card->un_academic==1 && moduleStatusCheck('QRCodeAttendance') == false)
                                                <div id="hAcademic">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('university::un.academic') : @foreach($staff_student->studentRecords as $record)
                                                        {{ $record->UnAcademic->name}} ,
                                                        @endforeach</h3>
                                                </div>
                                            @endif

                                            @if($id_card->un_semester==1 && moduleStatusCheck('QRCodeAttendance') == false)
                                                <div id="hSemester">
                                                    <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">@lang('university::un.semester') : @foreach($staff_student->studentRecords as $record)
                                                        {{ $record->unSemester->name}} ,
                                                        @endforeach
                                                    </h3>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                            <!-- TODO: New section for qr code -->
                            @if(moduleStatusCheck('QRCodeAttendance') == true)
                            <div class="card_qr_code" style="border: 2px solid #6D36E9; width: 120px; height: 120px; aspect-ratio: 1/1; margin: auto; padding: 2px; margin-top: -7px">
                                <img src="{{asset('public/qr_codes/'.$id_role.'-'.$staff_student->id.'-qrcode.png')}}" style="width: 100%; height: 100%; aspect-ratio: 1/1; object-fit: cover; object-position: center center;" alt="qr code">
                            </div>

                            @else    
                            <!-- TODO: Table Section -->
                            <table style="border: 1px solid #B4B4B4; margin: 0 18px; width: calc(100% - 36px);">
                                {{-- <thead>
                                    <tr>
                                        <th style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-weight: 500; font-size: 8px; line-height: 1">Info 1</th>
                                        <th style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-weight: 500; font-size: 8px; line-height: 1">Info 2</th>
                                    </tr>
                                </thead> --}}

                                <tbody>
                                    @if($id_card->father_name ==1)
                                        <tr id="hFatherName">
                                            <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                                @lang('student.father_name')
                                            </td>
                                            <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                                @if($role_id==2) {{@$staff_student->parents !=""?@$staff_student->parents->fathers_name:""}}@else {{!empty($staff_student->fathers_name) ? $staff_student->fathers_name:''}} @endif
                                            </td>
                                        </tr>
                                    @endif

                                    @if($id_card->mother_name==1)
                                        <tr id="hMotherName">
                                            <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                                @lang('student.mother_name')
                                            </td>
                                            <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                                @if($role_id==2) {{@$staff_student->parents !=""?@$staff_student->parents->mothers_name:""}} @else {{$staff_student->mothers_name}} @endif
                                            </td>
                                        </tr>
                                    @endif

                                    @if($id_card->dob==1)
                                        <tr id="hDob">
                                            <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                                @lang('common.date_of_birth')
                                            </td>
                                            <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                                {{@dateConvert($staff_student->date_of_birth)}}
                                            </td>
                                        </tr>
                                    @endif
                                    @if($id_card->blood==1 && $role_id==2)
                                        <tr id="hBloodGroup">
                                            <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                                @lang('student.blood_group')
                                            </td>
                                            <td style="padding: 4px; border: 1px solid #B4B4B4; color: #000000; font-size: 8px; line-height: 1">
                                                {{@$staff_student->bloodGroup!=""?@$staff_student->bloodGroup->base_setup_name:""}}
                                            </td>
                                        </tr>
                                    @endif
                                    
                                </tbody>
                            </table>
                            @endif
                        
                        </div>
                        
                        <div class="horizontal_card_footer" style="line-height:1.02; text-align: right; flex-grow: 1; display: flex;">
                            @if(!empty($staff_student->current_address))
                            <div style="background: #000F28; border-radius: 46px; padding: 2px 5px; align-self: flex-end; width: 100%; margin-bottom: 37px; margin-left: 5px; margin-right: 5px; margin-top:-3px;">
                                @if($id_card->student_address==1)
                                    <div class="card_text_left" id="hAddress">
                                        <h3 style="line-height:1.5; font-size:8px; font-weight:500; color: #ffffff; text-align: center; margin-bottom: 0">{{generalSetting()->address}} </h3>
                                    </div>
                                @endif
                            </div>
                            @endif
                            <div class="singnature_img signPhoto hSign {{ $id_card->signature_status != 1 ? 'd-none':''}}" style="background-image:url({{ $id_card->signature != "" ? asset($id_card->signature) : asset('public/backEnd/id_card/img/Signature.png') }});line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 5px;height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
                        </div>
                        
                    </div>
                </div>
                
                @endif
            @else
                    
            {{-- id card For student --}}
                @if($id_card->page_layout_style=='horizontal')      
                <div id="gHorizontal"   style="overflow: auto; margin: 0; padding: 0; font-family: 'Poppins', sans-serif;  font-size: 12px; line-height:1.02 ;" >
                    <div class="vertical__card hr_bg" style="line-height:1.02; background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->background_img) : asset('public/backEnd/id_card/img/horizontal_bg.png') }}); width: 86mm; height: 54mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative;">
                        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding: 12px">
                            
                            <!-- TODO: Institute name new section -->
                            <div class="card-institute-name" style="position: absolute;right: 11px;">
                                <p style="line-height:1.02; font-size: 10px; font-weight: 700; margin: 0; padding: 0; text-align: center; font-size: 10px; font-weight: 700; text-align: right; line-heigtht: 9px; color: #000000;">{{ @$school_name }}</p>
                            </div>
                        </div>
                        <div class="vertical_card_body" style="line-height:1.02; padding-top: 2.5mm; padding-bottom: 2.5mm; align-items: center; gap: 14px; margin-left: 11px; margin-right: 0px; display: flex;">
                            <div style="width: 50%; background: #ffffff; padding: 12px; border-radius: 5px; border-top-left-radius: 10px;">
                                <div class="thumb hSize hSizeX photo hImg hRoundImg  " style=" background-image: url({{ asset('public/backEnd/id_card/img/thumb.png') }}); line-height:1.02; width: 18.5mm; height: 18.5mm; flex-basis: 18.5mm; flex-grow: 0; flex-shrink: 0; margin-right: 0px; background-size: cover; background-position: center center; border: 3px solid #ffffff; margin-top: -35px; margin-left: -12px;"></div>
                                @if($id_card->student_name==1)
                                    <div id="gHName" style="">
                                      <h3 style="line-height:1.5; margin-top: 0; margin-bottom: 0px; font-size:12px; font-weight:700 ; color: #000F28; font-family: Poppins;">{{$staff_student->guardians_name ? $staff_student->guardians_name :  $staff_student->father_name}}</h3>
                                    </div>
                                @endif
                                @if($id_card->phone_number == 1)
                                    <div id="hPhoneNumber" style="">
                                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">Phone No : {{$staff_student->guardians_mobile ? $staff_student->guardians_mobile :  $staff_student->fathers_mobile}}</h4>
                                    </div>
                                @endif
                               
                                                                    
                                <div class="singnature_img signPhoto hSign {{ $id_card->signature_status != 1 ? 'd-none':''}}" id="hSign" style="background-image: url({{ $id_card->signature != "" ? asset($id_card->signature) : asset('public/backEnd/id_card/img/Signature.png') }}); line-height:1.02; width: 50px; flex: 50px 0 0; height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center; margin-top: 6px">
                                </div>
                            </div>
                        
                                
                            <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                                <div class="child__thumbs" style="display:flex; align-items: center; margin: 15px 0 20px 0; display: flex;
                                    align-items: flex-start;
                                    margin: 15px 0 2px 0;
                                    justify-content: space-between;">
                                    @php
                                        $studentInfos= App\SmStudentIdCard::studentName($staff_student->id);
                                    @endphp
                                    @foreach ($studentInfos as $studentInfo)
                                        <div class="single__child" style="text-align: center; flex: 45px 0 0; margin-right:5px;">
                                            <div class="single__child__thumb" style=" background-image: url({{ @$studentInfo->student_photo != "" ? asset(@$studentInfo->student_photo) : asset('public/backEnd/id_card/img/thumb.png') }});background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 50px;
                                            flex: 45px 0 0;
                                            height: 46px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                                            </div>
                                            <p style="font-size:10px; font-weight:400">{{$studentInfo->first_name}} {{$studentInfo->last_name}}</p>
                                        </div>
                                    @endforeach
                                </div>                                             
                            </div>
                            <!-- TODO: New section for qr code -->
                            
                        </div>
                        <div class="horizontal_card_footer" style="line-height:1.02; ">
                            @if($id_card->student_address==1)
                             <div class="card_text_head " id="gHAddress" style="line-height: 1.02; width: 100%;"> 
                                <div class="card_text_left " style="background: #000F28; border: 0.5px solid #ffffff; border-radius: 46px; margin-left: 8px; padding: 2px 10px; width: calc(100% - 35%); position: absolute; bottom: 8px; ">
                                    <h3 style="line-height:1.5; margin-top: 0; font-size:8px; margin-bottom: 0; font-weight:500; color: #ffffff;">{{generalSetting()->address}} </h3>
                                </div>
                              </div>
                            @endif
                        <!-- TODO: new logo position -->                                
                        <div class="logo__img logoImage hLogo" style="line-height:1.02; width: 57px; background-image: url({{$id_card->logo !=''? asset($id_card->logo) : asset(generalSetting()->logo)}});background-size: cover; height: 23px;background-position: center center; background-repeat: no-repeat; position: absolute; margin-left: auto; right: 8px; bottom: 8px;"></div>
                        </div>
                    </div>
                </div>
                @endif
                @if($id_card->page_layout_style=='vertical')
               
                <div id="gVertical"  style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif; font-weight: 500;  font-size: 12px; line-height:1.02 ; color: #000">
                    <div class="horizontal__card vr_bg" style="line-height:1.02; background-image: url({{ @$id_card->background_img != "" ? asset(@$id_card->background_img) : asset('public/backEnd/id_card/img/vertical_bg.png') }}); width: 57.15mm; height: 88.9mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative; background-color: #fff; display: flex; flex-direction: column;">
                        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding:6px 5px; padding-left: 10px; gap: 16px">
                            <div class="logo__img logoImage vLogo" style="line-height:1.02; width: 80px; background-image: url({{$id_card->logo !=''? asset($id_card->logo) : asset(generalSetting()->logo)}});height: 23px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
                            <!-- TODO: Institute name new section -->
                                                                <div class="card-institute-name" style="border-radius: 46px; background: #ffffff; padding: 4px 10px;">
                                    <p style="line-height:1.02; font-size: 10px; font-weight: 600; margin: 0; padding: 0; text-align: center; font-size: 8px; font-weight: 500; line-heigtht: 9px; color: #000000;"> {{ @$school_name }} </p>
                                </div>
                                                        </div>
                
                        <div class="horizontal_card_body" style="line-height:1.02; display:flex; padding-top:2.5mm ; padding-bottom: 2.5mm ; padding-right: 3mm ; padding-left: 3mm ; flex-direction: column; padding-top: 0;">
                
                               @if($id_card->photo == 1)
                                <div class="thumb vSize photo vImg vRoundImg " style="@if (@$id_card->user_photo_style=='round') {{"border-radius : 50%;"}} @endif background-image: url({{ @$staff_student->guardians_photo != "" ? asset(@$staff_student->guardians_photo) : asset('public/backEnd/id_card/img/thumb.png') }});background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 16.41mm; flex: 62px 0 0; height: 16.41mm; border: 1px solid #fff; margin: auto;"></div>
                               @endif
                                <!-- TODO: Default color changed to 16.41.06mm may need to update backend setting -->
                
                                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: center; width: 100%; margin-top:-3px; margin-bottom:10px">
                                    <div class="card_text_left hId" style="text-align: center">
                                    @if($id_card->student_name==1)
                                        <div id="gVName" style="">
                                            <h4 style="line-height:1.5; margin-top: 0; margin-bottom: 0px; font-size:12px; font-weight:700 ; color: #000F28; font-family: Poppins; text-align: center;">{{$staff_student->guardians_name ? $staff_student->guardians_name :  $staff_student->father_name}}</h4>
                                        </div>
                                    @endif

                                    @if($id_card->phone_number == 1)
                                    <div id="gVphone" style="">
                                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">Phone No : {{$staff_student->guardians_mobile ? $staff_student->guardians_mobile :  $staff_student->fathers_mobile}}</h3>
                                    </div>
                                    @endif
                                                                                                                                    
                                   
                                            
                                                                                                                                            
                                  </div>
                        </div>
                
                            <!-- TODO: New section for qr code -->
                            <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                                <div class="child__thumbs" style="display:flex; align-items: center; margin: 15px 0 20px 0; display: flex;
                                    align-items: flex-start;
                                    margin: 15px 0 2px 0;
                                    justify-content: space-between;">
                                    @php
                                        $studentInfos= App\SmStudentIdCard::studentName($staff_student->id);
                                    @endphp
                                    @foreach ($studentInfos as $studentInfo)
                                        <div class="single__child" style="text-align: center; flex: 45px 0 0; margin-right:5px;">
                                            <div class="single__child__thumb" style=" background-image: url({{ @$studentInfo->student_photo != "" ? asset(@$studentInfo->student_photo) : asset('public/backEnd/id_card/img/thumb.png') }});background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 50px;
                                            flex: 45px 0 0;
                                            height: 46px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                                            </div>
                                            <p style="font-size:10px; font-weight:400">{{$studentInfo->first_name}} {{$studentInfo->last_name}}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="horizontal_card_footer" style="line-height:1.02; text-align: right; flex-grow: 1; @if(moduleStatusCheck('QRCodeAttendance') == false) display:flex; @endif ">
                            <div class="gVAddress" style="background: rgb(0, 15, 40); border-radius: 46px; padding: 2px 5px; align-self: flex-end; width: 100%; margin-bottom: 37px; margin-left: 5px; margin-right: 5px;">
                                    <div class="card_text_left">
                                        <h3 style="line-height:1.5; font-size:8px; font-weight:500; color: #ffffff; text-align: center; margin-bottom: 0">{{generalSetting()->address}}</h3>
                                    </div>
                            </div>
                            <div class="singnature_img signPhoto vSign " style="background-image:url({{ $id_card->signature != "" ? asset($id_card->signature) : asset('public/backEnd/id_card/img/Signature.png') }});line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 5px;height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
                        </div>
                        
                    </div>
                </div>
                @endif
            @endif
        @endforeach
</div>

    <script src="{{ asset('public/backEnd/') }}/vendors/js/jquery-3.2.1.min.js"></script>
    <script>
        function printDiv(divName) {
            document.getElementById("button").remove();
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</body>

</html>