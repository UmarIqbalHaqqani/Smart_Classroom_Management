<html>
	<head>
		<title>@lang('admin.')</title>
		<title>@lang('admin.student_certificate')</title>

		<link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css" />
		
		<style>
			body{
				font-family: 'dejavu sans','Poppins', sans-serif;
				font-size: 14px;
				margin: 0;
				padding: 0;
			}

			.tdWidth{
				width: 33.33%;
			}
			.bgImage{
				height:auto; 
				background-repeat:no-repeat;
				background-image: url({{asset($certificate->file)}});
				  
			}
			table{
				text-align: center; 
			}
			 
			td{
				padding: 25px !important;
			}
			.DivBody{    
				height: 100vh;
				border: 1px solid var(--border_color) !important;
				margin-top: 0px;
			}
			.tdBody{
				text-align: justify !important;				
			    height: 140px;
			    padding-top: 0px;
			    padding-bottom: 0px;
			    padding-left: 65px;
			    padding-right: 65px;
			}
			img{
				position: absolute;
			}
			table{
				position: relative;
			}
			body{
				margin:0px !important;
			}
			.DivBody{
				position: relative;
			}
			.position_bg{
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
			}
			.certificate_body_inner {
				position: relative;
				height: 100%;
			}
			.tdBody {
				position: relative;
				top: 280px;
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
				top: 18%;
				width: calc(100% - 20%);
				left: 0;
				margin: auto;
				right: 0;
			}
			.postion_footer_certificate {
				position: absolute;
				bottom: 25%;
				width: calc(100% - 20%);
				left: 0;
				margin: auto;
				right: 0;
			}
			@page { 
				margin: 2px; 
				size: 21cm 17cm; 
				}
			
			body { margin: 1px; }

			.bb-15 {
				border-bottom: 1px solid rgba(65, 80, 148, 0.15);
			}
			.signature {
				padding-bottom: 10px;
				text-align: center;
			}
			@media print{.DivBody{page-break-after:always}}

			.certificate_box_wrapper {
				background-image: url(./img/bg.jpg);
				width: <?php echo $certificate->background_width?? 164?>mm;
				min-height: <?php echo $certificate->background_height?? 145?>mm;
				/* display: flex; */
				justify-content: center;
				margin: auto;
				background-repeat: no-repeat;
				background-size: cover;
				background-position: center center;
				padding-top: <?php echo $certificate->padding_top?? 5?>mm;
				padding-left: <?php echo $certificate->pading_left?? 5?>mm;
				padding-right: <?php echo $certificate->padding_right?? 5?>mm;
				padding-bottom: <?php echo $certificate->padding_bottom?? 5?>mm;
			}
    	</style>
	</head>
	<body>
		@foreach($users as $user)
				@if (moduleStatusCheck('Lms'))
					<div class="DivBody"  style="margin-bottom: {{@$gridGap}}px">
						@php
							$body = App\SmStudentCertificate::certificateBody($certificate->body, 'Lms', $user->id);  #passing here student_id
						@endphp

						<img class="position_bg" src="{{asset($certificate->file)}}" style="height: 100vh; width: 100% !important">
						
						<div class="certificate_body_inner">
							<table width="80%" align="center" class="postion_header_certificate">
								<tr>
									<td style="text-align: left;" class="tdWidth">Start: {{ dateConvert(@$start_date)}}</td>
									<td style="text-align: center;" class="tdWidth"></td>
									<td style="text-align: right;" class="tdWidth">@lang('common.date'): {{ @$complete_date}}</td>
								</tr>
							</table>
							<table width="80%" align="center" style="margin-top: 200px" >
								<tr>
									<td colspan="3" class="tdBody"> {!! $body !!}</td>
								</tr>
							</table>
							<table width="80%" align="center" class="postion_footer_certificate">
								<tr>
									<td style="text-align: left;" class="tdWidth">
										<div class="signature bb-15">{{ @$certificate->footer_left_text}}</div>
									</td>
									<td style="text-align: center;" class="tdWidth">
										<div class="signature bb-15">{{ @$certificate->footer_center_text}}</div>
									</td>
									<td style="text-align: right;" class="tdWidth ">
										<div class="signature bb-15">{{ @$certificate->footer_right_text}}</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
				@else
					<div class="certificate_box_wrapper" style="background-image: url('{{ isset($certificate)? asset($certificate->background_image): ''}}'); margin-bottom: {{$gridGap}}px;">
						@php
							$body = App\SmStudentCertificate::certificateBody($certificate->body, @$certificate->role, $user->user_id);
						@endphp
						{!!$body!!}
					</div>
				@endif
			</div>
		@endforeach
	</body>
</html>
