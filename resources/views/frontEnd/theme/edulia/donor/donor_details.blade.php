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
                        <h1>{{ __('edulia.donor_details') }} <span><a
                                    href="{{ url('/') }}">{{ __('edulia.home') }}</a> /
                                {{ __('edulia.donor_details') }}</span></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container section_padding px-3 px-sm-0 single_user_profile_section">
        <div class="profile_details_header">
            <div class="d-flex justify-content-between align-items-center gap-10 flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center">
                    <img src="{{ asset($donorDetails->photo) }}" class="user_photo" alt="user photo">
                    <div class="user_information">
                        <p class="single_header_info">
                            <span class="info_value user-name text-uppercase">
                                {{ $donorDetails->full_name }}
                            </span>
                        </p>
                        <p class="single_header_info">
                            <span class="info_type">@lang('edulia.email'): </span>
                            <span class="info_value">
                                {{ $donorDetails->email }}
                            </span>
                        </p>
                        <p class="single_header_info">
                            <span class="info_type">@lang('edulia.gender'): </span>
                            <span class="info_value">
                                {{ $donorDetails->gender->base_setup_name }}
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
                        <span class="info_value text-uppercase">{{ $donorDetails->full_name }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.profession'): </span>
                        <span class="info_value text-uppercase">{{ $donorDetails->profession }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.date_of_birth'): </span>
                        <span
                            class="info_value text-uppercase">{{ date('d/m/Y', strtotime($donorDetails->date_of_birth)) }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.email'): </span>
                        <span class="info_value">{{ $donorDetails->email }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.mobile'): </span>
                        <span class="info_value">{{ $donorDetails->mobile }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.age'): </span>
                        <span class="info_value">{{ $donorDetails->age }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.gender'): </span>
                        <span class="info_value">{{ $donorDetails->gender->base_setup_name }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.blood'): </span>
                        <span class="info_value">{{ $donorDetails->bloodGroup->base_setup_name }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.religion'): </span>
                        <span class="info_value">{{ $donorDetails->religion->base_setup_name }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.present_address'): </span>
                        <span class="info_value">{{ $donorDetails->current_address }}</span>
                    </p>

                    <p class="single_info col-md-4">
                        <span class="info_type">@lang('edulia.permanent_address'): </span>
                        <span class="info_value">{{ $donorDetails->permanent_address }}</span>
                    </p>
                </div>
            </div>
            <div class="details_info_section">
                <h4 class="sectoin_header">@lang('edulia.other_information')</h4>
                <div class="row m-0">
                    @if (@$custom_filed_values)
                        @foreach ($custom_filed_values as $key => $data)
                            <p class="single_info col-md-4">
                                <span class="info_type">{{ $key }}: </span>
                                <span class="info_value">{{ $data }}</span>
                            </p>
                        @endforeach
                    @endif
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
