@extends('backEnd.master')
@section('title')
{{@$pt}}
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>{{@$pt}}</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">{{@$pt}} </a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_admin_visitor empty_table_tab">
        <div class="container-fluid p-0">
          <div class="row mt-40">
               <div class="col-lg-12">
                   <div class="white-box">
                       <form method="post" action="{{route('tawkSettingUpdate')}}" enctype = 'multipart/form-data'>
                           @csrf
                           <div class="row p-0">
                               <div class="col-lg-12">
                                   <h3 class="text-center">{{@$pt}}</h3>
                                   <p class="text-center">
                                       <code><a href="https://dashboard.tawk.to/login">https://dashboard.tawk.to/login</a></code>
                                   </p>
                                   <hr>
                                   <div class="row mb-40 mt-40">
                                        <div class="col-lg-5 d-flex">
                                             <p class="text-uppercase fw-500 mb-10">@lang('system_settings.Tawk To Chat')</p>
                                         </div>
                                         <div class="col-lg-7">
                                             <div class="d-flex radio-btn-flex flex-wrap gap-20">
                                                  <div class="mr-30">
                                                     <input type="radio" name="is_enable" id="via_sms" class="common-radio relationButton copy_per_th" {{@$setting->is_enable == 1? 'checked':''}}  value="1" >
                                                     <label for="via_sms" id="via_sms">@lang('common.enable')</label>
                                                 </div>

                                                 <div class="mr-30">
                                                     <input type="radio" name="is_enable" id="via_email"  class="common-radio relationButton copy_per_th" {{@$setting->is_enable == 0? 'checked':''}} value="0" >
                                                     <label for="via_email" id="via_email">@lang('common.disable')</label>
                                                 </div>                                        
                                             </div>
                                         </div>
                                   </div>
   
                                   <div class="row mb-40 mt-40">
                                       <div class="col-lg-12">
                                           <div class="row">
                                               <div class="col-lg-2 d-flex">
                                                   <p class="text-uppercase fw-500 mb-10">@lang('auth.applicable_for')</p>
                                               </div>
                                               <div class="col-lg-10">
                                                   <div class="d-flex radio-btn-flex flex-wrap gap-20 flex-column flex-sm-row"> 
                                                       @foreach ($roles as $role)
                                                           <div class="mr-30">
                                                               <input type="checkbox" name="applicable_for[]" id="applicable_for_{{$role->id}}" class="common-radio relationButton copy_per_th" value="{{$role->id}}" @if(!is_null($setting->applicable_for) &&  in_array($role->id,$setting->applicable_for)) checked @endif>
                                                               <label for="applicable_for_{{$role->id}}" id="applicable_for_{{$role->id}}">{{$role->name}}</label>
                                                           </div>
                                                       @endforeach  
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>

                                   <div class="row mb-40 mt-40" id="pusher">
                                        <div class="col-xl-6 mt-4">
                                             <p class="primary_input_label">
                                                 {{ __('system_settings.show_admin_panel') }}
                                             </p>
                                             <div class="d-flex radio-btn-flex flex-wrap gap-20">
                                                 <div >
                                                     <input type="radio" name="show_admin_panel"
                                                         {{ $setting->show_admin_panel == 1 ? 'checked' : '' }}
                                                         id="relationv" value="1" class="common-radio relationButton">
                                                     <label for="relationv">{{ __('common.yes') }}</label>
                                                 </div>
                                                 <div >
                                                     <input type="radio" name="show_admin_panel"
                                                         {{ $setting->show_admin_panel == 0 ? 'checked' : '' }}
                                                         id="relation3v" value="0" class="common-radio relationButton">
                                                     <label for="relation3v">{{ __('common.no') }}</label>
                                                 </div>
                                                 @error('show_admin_panel')
                                                     <small class="text-danger font-italic">*{{ @$message }}</small>
                                                 @enderror
                                             </div>
                                         </div>
                                         
                                        <div class="col-xl-6 mt-4">
                                             <p class="primary_input_label">
                                                  {{ __('system_settings.availability') }}</p>
                                             <div class="d-flex radio-btn-flex flex-wrap gap-20">
                                                  <div >
                                                       <input type="radio" name="availability"
                                                            {{ $setting->availability == 'mobile' ? 'checked' : '' }}
                                                            id="relationFather33333" value="mobile"
                                                            class="common-radio relationButton" checked>
                                                       <label
                                                            for="relationFather33333">{{ __('Mobile') }}</label>
                                                  </div>
                                                  <div >
                                                       <input type="radio" name="availability"
                                                            {{ $setting->availability == 'desktop' ? 'checked' : '' }}
                                                            id="relationMother4433" value="desktop"
                                                            class="common-radio relationButton">
                                                       <label
                                                            for="relationMother4433">{{ __('system_settings.only_desktop') }}</label>
                                                  </div>
                                                  <div >
                                                       <input type="radio" name="availability"
                                                            {{ $setting->availability == 'both' ? 'checked' : '' }}
                                                            id="relationMother4222" value="both"
                                                            class="common-radio relationButton">
                                                       <label
                                                            for="relationMother4222">{{ __('system_settings.both') }}</label>
                                                  </div>
                                                  @error('availability')
                                                       <small class="text-danger font-italic">*{{ @$message }}</small>
                                                  @enderror
                                             </div>
                                        </div>

                                        <div class="col-xl-6 mt-4">
                                             <p class="primary_input_label">
                                                 {{ __('system_settings.show_website') }}
                                             </p>
                                             <div class="d-flex radio-btn-flex flex-wrap gap-20">
                                                 <div >
                                                     <input type="radio" name="show_website"
                                                         {{ $setting->show_website == 1 ? 'checked' : '' }}
                                                         id="relationvC" value="1" class="common-radio relationButton" >
                                                     <label for="relationvC">{{ __('common.yes') }}</label>
                                                 </div>
                                                 <div >
                                                     <input type="radio" name="show_website"
                                                         {{ $setting->show_website == 0 ? 'checked' : '' }}
                                                         id="relation3vC" value="0" class="common-radio relationButton">
                                                     <label for="relation3vC">{{ __('common.no') }}</label>
                                                 </div>
                                                 @error('disable_for_admin_panel')
                                                     <small class="text-danger font-italic">*{{ @$message }}</small>
                                                 @enderror
                                             </div>
                                         </div>
                                         
                                        <div class="col-xl-6 mt-4">
                                             <p class="primary_input_label">
                                                  {{ __('system_settings.showing_page') }}</p>
                                             <div class="d-flex radio-btn-flex flex-wrap gap-20">
                                                  <div >
                                                       <input type="radio" name="showing_page"
                                                            {{ $setting->showing_page == 'homepage' ? 'checked' : '' }}
                                                            id="relationFather311" value="homepage"
                                                            class="common-radio relationButton" checked>
                                                       <label
                                                            for="relationFather311">{{ __('system_settings.only_homepage') }}</label>
                                                  </div>
                                                  <div >
                                                       <input type="radio" name="showing_page"
                                                            {{ $setting->showing_page == 'all' ? 'checked' : '' }}
                                                            id="relationMother411" value="all"
                                                            class="common-radio relationButton">
                                                       <label
                                                            for="relationMother411">{{ __('system_settings.all_page') }}</label>
                                                  </div>
                                                  @error('showing_page')
                                                       <small class="text-danger font-italic">*{{ @$message }}</small>
                                                  @enderror
                                             </div>
                                        </div>
                                        
                                        <div class="col-xl-6 mt-4">
                                             <p class="primary_input_label">
                                                  {{ __('system_settings.position') }}</p>
                                             <div class="d-flex radio-btn-flex flex-wrap gap-20">
                                                  <div >
                                                       <input type="radio" name="position"
                                                            {{ $setting->position == 'left' ? 'right' : '' }}
                                                            id="relationFather312" value="left"
                                                            class="common-radio relationButton" checked>
                                                       <label
                                                            for="relationFather312">{{ __('system_settings.left_side') }}</label>
                                                  </div>
                                                  <div >
                                                       <input type="radio" name="position"
                                                            {{ $setting->position == 'right' ? 'checked' : '' }}
                                                            id="relationMother412" value="right"
                                                            class="common-radio relationButton">
                                                       <label
                                                            for="relationMother412">{{ __('system_settings.right_side') }}</label>
                                                  </div>
                                                  @error('position')
                                                       <small class="text-danger font-italic">*{{ @$message }}</small>
                                                  @enderror
                                             </div>
                                        </div>
                                   </div>
                                   
                                   <div class="row mb-40 mt-40">
                                        <div class="col-lg-12"> 
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.Short Code') </label>
                                                <textarea class="primary_input_field form-control" name="short_code" autocomplete="off">{!! isset($setting) ? $setting->short_code : '' !!}</textarea>
                                                <p>
                                                    Please put the unique code of <strong>Direct Chat Link</strong> on here except the : <code>https://tawk.to/chat/</code>
                                                </p>
                                            </div> 
                                        </div>
                                    </div>
   
                                   @if(userPermission('two_factor_auth_setting'))
                                       <div class="row mt-40">
                                           <div class="col-lg-12 text-center">
                                           <button class="primary-btn fix-gr-bg">
                                                   <span class="ti-check"></span>
                                                   @lang('common.update')
                                               </button>
                                           </div>
                                       </div>
                                   @endif
                               </div>
                           </div>
                       </form>
                    </div>
               </div>
          </div>
    </section>
@endsection

@section('script')
    <script language="JavaScript">
        $('#selectAll').click(function () {
            $('input:checkbox').prop('checked', this.checked);

        });
    </script>
@endsection

@push('script')
    <script>
        $(document).on('change', '#imgInpBac', function(event){
            getFileName($(this).val(),'#placeholderFileFourName');
            imageChangeWithFile($(this)[0],'#blahImg');
        });
    </script>
@endpush


