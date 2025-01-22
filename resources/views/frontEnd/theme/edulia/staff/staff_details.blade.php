@extends(config('pagebuilder.site_layout'), ['edit' => false])
@pushonce(config('pagebuilder.site_style_var'))
    <style>
        @media print {

            .pb-themesection,
            .bradcrumb_area,
            #printProfile {
                display: none
            }

            .section_padding {
                padding: 30px 0;
            }

            @page {
                margin: 0 !important;
            }
        }
    </style>
@endpushonce
@section(config('pagebuilder.site_section'))
    {{ headerContent() }}
    <section class="bradcrumb_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="bradcrumb_area_inner">
                        <h1>{{ __('edulia.staff_details') }} <span><a
                                    href="{{ url('/') }}">{{ __('edulia.home') }}</a> /
                                {{ __('edulia.staff_details') }}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container section_padding px-3 px-sm-0 single_user_profile_section">
        <div class="profile_details_header">
            <div class="d-flex justify-content-between align-items-center gap-10 flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center">
                    <img src="{{ @$staffDetails->staff_photo ? asset(@$staffDetails->staff_photo) : asset('public/uploads/staff/staff.jpg') }}"
                        class="user_photo" alt="user photo">
                    <div class="user_information">
                        <p class="single_header_info">
                            <span class="info_value user-name text-uppercase">
                                {{ @$staffDetails->full_name }}
                            </span>
                        </p>
                        <p class="single_header_info">
                            <span class="info_type">@lang('edulia.designation'): </span>
                            <span class="info_value">
                                {{ @$staffDetails->designations->title }}
                            </span>
                        </p>
                        <p class="single_header_info">
                            <span class="info_type">@lang('edulia.qualification'): </span>
                            <span class="info_value">
                                {{ @$staffDetails->qualification }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="print_btn">
                    <a href="#" id="printProfile"><i class="fas fa-print"></i> @lang('edulia.print')</a>
                </div>
            </div>
        </div>
        <div class="profile_details_container">
            <div class="details_info_section">
                <h4 class="sectoin_header">@lang('edulia.basic_information')</h4>
                <div class="row m-0">
                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.name'): </span>
                        <span class="info_value text-uppercase">{{ @$staffDetails->full_name }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.designation'): </span>
                        <span class="info_value text-uppercase">{{ @$staffDetails->designations->title }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.department'): </span>
                        <span class="info_value text-uppercase">{{ @$staffDetails->departments->name }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.qualification'): </span>
                        <span class="info_value text-uppercase">{{ @$staffDetails->qualification }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.gender'): </span>
                        <span class="info_value">{{ @$staffDetails->genders->base_setup_name }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.experience'): </span>
                        <span class="info_value">{{ @$staffDetails->experience }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.age'): </span>
                        <span class="info_value">
                            {{ \Carbon\Carbon::parse($staffDetails->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y years') }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="profile_details_container mt-20">
            <div class="details_info_section">
                <h4 class="sectoin_header">@lang('edulia.contact_information')</h4>
                <div class="row m-0">
                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.email'): </span>
                        <span class="info_value">{{ @$staffDetails->email }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.mobile'): </span>
                        <span class="info_value">{{ @$staffDetails->mobile }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="profile_details_container mt-20">
            <div class="details_info_section">
                <h4 class="sectoin_header">@lang('edulia.address_information')</h4>
                <div class="row m-0">
                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.present_address'): </span>
                        <span class="info_value">{{ @$staffDetails->current_address }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.permanent_address'): </span>
                        <span class="info_value">{{ @$staffDetails->permanent_address }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    {{ footerContent() }}
@endsection
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        $("#printProfile").on("click", function(e) {
            e.preventDefault();
            window.print();
        })
    </script>
@endpushonce
