@extends('backEnd.master')
@section('title')
   @lang('front_settings.Theme Manager')
@endsection

@push('css')
     <style>
          #add_new {
               width: 100%;
               height: 93% !important;
               border: 5px dashed rgba(0, 0, 0, .1);
          }
          #plus {
               background: #e5e5e5;
               background: rgba(153, 153, 153, .1);
               border-radius: 50%;
               display: inline-block;
               width: 100px;
               height: 100px;
               line-height: 100px;
               text-align: center;
               color: #999;
               position: absolute;
               text-shadow: none;
               font-size: 50px;
               left: 50%;
               top: 50%;
               transform: translate(-50%, -50%);
               margin-top: -15px;
          }
          .item_section {
          overflow: hidden;
          margin-bottom: 25px;
          }

          .gap-10{
            gap: 10px;
          }
          .theme_name{
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 0;
          }
          .single_item_img_div{
            width: 100%;
            height: auto;
            aspect-ratio: 1.9/1;
            object-fit: cover;
            object-position: center;
          }
          .active_btn a{
            background: #415094;
            padding: 5px 20px;
            border-radius: 6px;
            color: #fff!important;
          }
          .active_btn a:hover{
            background: #7c32ff;
          }
          .card-footer{
            height: 60px;
          }
          .active_theme .card-footer {
            background: #415094;
        }
        .active_theme .card-footer h4{
            color: #fff;
        }
        .card{
            border-radius: 4px;
            overflow: hidden;
            border-color: rgba(130, 139, 178, 0.5)!important;
        }
        .card-footer.d-flex {
            border-radius: 0px!important;
        }

     </style>
@endpush

@section('mainContent')
    <section class="sms-breadcrumb mb-20 up_breadcrumb">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('front_settings.Theme Manager')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('common.frontend_cms')</a>
                    <a href="#">@lang('front_settings.Theme Manager') - {{activeTheme()}}</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
          <div class="row">
               <div class="col-lg-12">
                    <div class="row">
                        <div style="position: relative;" class="col-xl-4 col-md-6 item_section @if(activeTheme() == 'edulia') active_theme @endif">
                            <div style="border:1px solid;" class="card">
                                <div style="padding: 0;" class="card-body screenshot">
                                    <div class="single_item_img_div">
                                        <img style="width: 100%" src="{{asset('public/theme/edulia/img/edulia.png')}}" alt="">
                                    </div>
                                </div>
                                <div class="card-footer d-flex">
                                    <div class="d-flex flex-grow-1 align-items-center gap-10">
                                        <div class="flex-grow-1">
                                            <h4 class="theme_name">@if(activeTheme() == 'edulia')Active: @endif Edulia</h4>
                                        </div>
                                        @if(activeTheme() != 'edulia')
                                            <div class="text-center active_btn">
                                                <a href="javascript:void(0)"  onclick="themeActive('edulia')">Active</a>
                                            </div>
                                        @endif 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="position: relative;" class="col-xl-4 col-md-6 item_section @if(activeTheme() != 'edulia') active_theme @endif">
                            <div style="border:1px solid;" class="card">
                                <div style="padding: 0;" class="card-body screenshot">
                                    <div class="single_item_img_div">
                                        <img style="width: 100%" src="{{asset('public/theme/default/img/default.png')}}" alt="">
                                    </div>
                                </div>
                                <div class="card-footer d-flex">
                                    <div class="d-flex flex-grow-1 align-items-center gap-10">
                                        <div class="flex-grow-1">
                                            <h4 class="theme_name">@if(activeTheme() != 'edulia') Active: @endif Default</h4>
                                        </div>
                                        @if(activeTheme() == 'edulia')
                                            <div class="text-center active_btn">
                                                <a href="javascript:void(0)"  onclick="themeActive('default')">Active</a>
                                            </div>
                                        @endif 
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                      

                    </div>
                </div>
          </div>
           
        </div>
    </section>


    {{-- modal start here  --}}


    <div class="modal fade admin-query" id="themeActiveModal">
        <div class="modal-dialog modal-dialog-centered small-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('common.Active Theme')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('theme.install')}}">
                        @csrf 
                        <input type="hidden" name="theme">
                        <div class="text-center">
                            <h4>@lang('common.are_you_sure_to_active_these_theme') ?</h4>
                        </div>
                        <div class="mt-40 d-flex justify-content-between">
                            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>

                            <button class="primary-btn fix-gr-bg" type="submit">@lang('common.active')</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

  
@endsection

@push('script')
    <script>
        function themeActive(theme){
            var modal = $('#themeActiveModal');
            modal.find('input[name=theme]').val(theme);
            modal.modal('show');
        }
    </script>
@endpush