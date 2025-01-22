@php
    $school_name = generalSetting()->school_name;
@endphp

<div id="gVertical" class="d-none" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif; font-weight: 500;  font-size: 12px; line-height:1.02 ; color: #000">
    <div class="horizontal__card vr_bg" style="line-height:1.02; background-image: url({{ asset('public/backEnd/id_card/img/vertical_bg.png') }}); width: 57.15mm; height: 88.9mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative; background-color: #fff; display: flex; flex-direction: column;">
        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding:6px 5px; padding-left: 10px; gap: 16px">
            <div class="logo__img logoImage vLogo" style="line-height:1.02; width: 80px; background-image: url({{asset(generalSetting()->logo)}});height: 23px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
            <!-- TODO: Institute name new section -->
                                                <div class="card-institute-name" style="border-radius: 46px; background: #ffffff; padding: 4px 10px;">
                    <p style="line-height:1.02; font-size: 10px; font-weight: 600; margin: 0; padding: 0; text-align: center; font-size: 8px; font-weight: 500; line-heigtht: 9px; color: #000000;"> {{ @$school_name }} </p>
                </div>
                                        </div>

        <div class="horizontal_card_body" style="line-height:1.02; display:flex; padding-top:2.5mm ; padding-bottom: 2.5mm ; padding-right: 3mm ; padding-left: 3mm ; flex-direction: column; padding-top: 0;">

                <div class="thumb vSize photo vImg vRoundImg " style="background-image: url({{ asset('public/backEnd/id_card/img/thumb.png') }});background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 16.41mm; flex: 62px 0 0; height: 16.41mm; border: 1px solid #fff; margin: auto;"></div>
                <!-- TODO: Default color changed to 16.41.06mm may need to update backend setting -->

                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: center; width: 100%; margin-top:-3px; margin-bottom:10px">
                    <div class="card_text_left hId" style="text-align: center">
                    <div id="gVName" style="">
                        <h4 style="line-height:1.5; margin-top: 0; margin-bottom: 0px; font-size:12px; font-weight:700 ; color: #000F28; font-family: Poppins; text-align: center;">Irma Boyle</h4>
                    </div>
                    <div id="gVphone" style="">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">Phone No : 83638</h3>
                    </div>
                                                                                                                    
                   
                            
                                                                                                                            
                  </div>
        </div>

            <!-- TODO: New section for qr code -->
            <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                <div class="child__thumbs" style="display:flex; align-items: center; margin: 15px 0 20px 0; display: flex;
                    align-items: flex-start;
                    margin: 15px 0 2px 0;
                    justify-content: space-between;">
                    <div class="single__child" style="text-align: center; flex: 45px 0 0; margin-right:5px;">
                        <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 50px;
                        flex: 46px  0 0;
                        height: 48px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                        </div>
                        <p style="font-size:12px; font-weight:400">Child 01</p>
                    </div>
                    <div class="single__child" style="text-align: center;flex: 45px 0 0; margin-right:5px;">
                        <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 50px;
                        flex: 46px  0 0;
                        height: 48px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                        </div>
                        <p style="font-size:12px; font-weight:400">Child 02</p>
                    </div>
                    <div class="single__child" style="text-align: center;flex: 45px 0 0; margin-right:5px;">
                        <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 50px;
                        flex: 46px  0 0;
                        height: 48px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                        </div>
                        <p style="font-size:12px; font-weight:400">Child 03</p>
                    </div>
                </div>
             
            </div>
            
            
        </div>
        
        <div class="horizontal_card_footer" style="line-height:1.02; text-align: right; flex-grow: 1; @if(moduleStatusCheck('QRCodeAttendance') == false) display:flex; @endif ">
            <div class="gVAddress" style="background: rgb(0, 15, 40); border-radius: 46px; padding: 2px 5px; align-self: flex-end; width: 100%; margin-bottom: 37px; margin-left: 5px; margin-right: 5px;">
                    <div class="card_text_left">
                        <h3 style="line-height:1.5; font-size:8px; font-weight:500; color: #ffffff; text-align: center; margin-bottom: 0">Address</h3>
                    </div>
            </div>
            <div class="singnature_img signPhoto vSign " style="background-image:url({{ asset("public/backEnd/id_card/img/Signature.png") }});line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 5px;height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
        </div>
        
    </div>
</div>


<div id="gHorizontal"  class="d-none"  style="overflow: auto; margin: 0; padding: 0; font-family: 'Poppins', sans-serif;  font-size: 12px; line-height:1.02 ;" >
    <div class="vertical__card hr_bg" style="line-height:1.02; background-image: url({{ asset('public/backEnd/id_card/img/horizontal_bg.png') }}); width: 86mm; height: 54mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative;">
        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding: 12px">
            
            <!-- TODO: Institute name new section -->
            <div class="card-institute-name" style="position: absolute;right: 11px;">
                <p style="line-height:1.02; font-size: 10px; font-weight: 700; margin: 0; padding: 0; text-align: center; font-size: 10px; font-weight: 700; text-align: right; line-heigtht: 9px; color: #000000;">{{ @$school_name }}</p>
            </div>
        </div>
        <div class="vertical_card_body" style="line-height:1.02; padding-top: 2.5mm; padding-bottom: 2.5mm; align-items: center; gap: 14px; margin-left: 11px; margin-right: 0px; display: flex;">
            <div style="width: 50%; background: #ffffff; padding: 12px; border-radius: 5px; border-top-left-radius: 10px;">
                <div class="thumb hSize hSizeX photo hImg hRoundImg  " style=" background-image: url({{ asset('public/backEnd/id_card/img/thumb.png') }}); line-height:1.02; width: 18.5mm; height: 18.5mm; flex-basis: 18.5mm; flex-grow: 0; flex-shrink: 0; margin-right: 0px; background-size: cover; background-position: center center; border: 3px solid #ffffff; margin-top: -35px; margin-left: -12px;"></div>

                <div id="gHName" style="">
                 <h3 style="line-height:1.5; margin-top: 0; margin-bottom: 0px; font-size:12px; font-weight:700 ; color: #000F28; font-family: Poppins;">Irma Boyle</h3>
                </div>
                <div id="hPhoneNumber" style="">
                    <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:8px; font-weight:500; color: #4B4E52">Phone No : 83638</h4>
                </div>
               
                                                    
                <div class="singnature_img signPhoto hSign" id="hSign" style="background-image: url({{ asset("public/backEnd/id_card/img/Signature.png") }}); line-height:1.02; width: 50px; flex: 50px 0 0; height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center; margin-top: 6px">
                </div>
            </div>
        
                
            <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                <div class="child__thumbs" style="display:flex; align-items: center; margin: 15px 0 20px 0; display: flex;
                    align-items: flex-start;
                    margin: 15px 0 2px 0;
                    justify-content: space-between;">
                    <div class="single__child" style="text-align: center; flex: 45px 0 0; margin-right:5px;">
                        <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 50px;
                        flex: 46px  0 0;
                        height: 48px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                        </div>
                        <p style="font-size:12px; font-weight:400">Child 01</p>
                    </div>
                    <div class="single__child" style="text-align: center;flex: 45px 0 0; margin-right:5px;">
                        <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 50px;
                        flex: 46px  0 0;
                        height: 48px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                        </div>
                        <p style="font-size:12px; font-weight:400">Child 02</p>
                    </div>
                    <div class="single__child" style="text-align: center;flex: 45px 0 0; margin-right:5px;">
                        <div class="single__child__thumb" style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 50px;
                        flex: 46px  0 0;
                        height: 48px; margin: auto;border-radius: 50%; padding: 3px; align-content: center; justify-content: center; display: flex; border: 3px solid #fff;">
                        </div>
                        <p style="font-size:12px; font-weight:400">Child 03</p>
                    </div>
                </div>
             
            </div>
            <!-- TODO: New section for qr code -->

            
            
            
        </div>
        <div class="horizontal_card_footer" style="line-height:1.02; ">

             <div class="card_text_head " id="gHAddress" style="line-height: 1.02; width: 100%;"> 
                <div class="card_text_left " style="background: #000F28; border: 0.5px solid #ffffff; border-radius: 46px; margin-left: 8px; padding: 2px 10px; width: calc(100% - 35%); position: absolute; bottom: 8px; ">
                    <h3 style="line-height:1.5; margin-top: 0; font-size:8px; margin-bottom: 0; font-weight:500; color: #ffffff;">Address </h3>
                </div>
            </div>
        <!-- TODO: new logo position -->

        <div class="logo__img logoImage hLogo" style="line-height:1.02; width: 57px; background-image: url({{asset(generalSetting()->logo)}});background-size: cover; height: 23px;background-position: center center; background-repeat: no-repeat; position: absolute; margin-left: auto; right: 8px; bottom: 8px;"></div>
        </div>
    </div>
</div>