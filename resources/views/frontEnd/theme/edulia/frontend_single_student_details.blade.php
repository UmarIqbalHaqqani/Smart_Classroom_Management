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
                        <h1>
                            {{ __('edulia.student_details') }}
                            <span>
                                <a href="{{ url('/') }}">{{ __('edulia.home') }}</a> /{{ __('edulia.student_details') }}
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section_padding blog">
        <div class="container section_padding px-3 px-sm-0 single_user_profile_section">

            <div class="profile_details_header">
                <div class="d-flex justify-content-between align-items-center gap-10 flex-wrap flex-sm-nowrap">
                    <div class="d-flex align-items-center">
                        <img src="{{ file_exists($singleStudent->student_photo) ? asset($singleStudent->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                            class="user_photo" alt="user photo">
                        <div class="user_information">
                            <p class="single_header_info">
                                <span class="info_value user-name text-uppercase">
                                    {{ $singleStudent->full_name }}
                                </span>
                            </p>
                            <p class="single_header_info">
                                <span class="info_type">@lang('edulia.admission_id'): </span>
                                <span class="info_value">
                                    {{ $singleStudent->admission_no }}
                                </span>
                            </p>
                            <p class="single_header_info">
                                <span class="info_type">@lang('edulia.gender'): </span>
                                <span class="info_value">
                                    {{ $singleStudent->gender->base_setup_name }}
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
                            <span class="info_value text-uppercase">{{ $singleStudent->full_name }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.class'): </span>
                            <span
                                class="info_value">{{ $singleStudent->studentRecord->class->class_name }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.section'): </span>
                            <span
                                class="info_value">{{ $singleStudent->studentRecord->section->section_name }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.gender'): </span>
                            <span
                                class="info_value">{{ $singleStudent->gender->base_setup_name ? $singleStudent->gender->base_setup_name : 'N/A' }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.dob'): </span>
                            <span
                                class="info_value">{{ !empty($singleStudent->date_of_birth) ? dateConvert($singleStudent->date_of_birth) : 'N/A' }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.admission_date'): </span>
                            <span
                                class="info_value">{{ !empty($singleStudent->admission_date) ? dateConvert($singleStudent->admission_date) : 'N/A' }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.religion'): </span>
                            <span
                                class="info_value">{{ $singleStudent->religion->base_setup_name ? $singleStudent->religion->base_setup_name : 'N/A' }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.age'): </span>
                            <span
                                class="info_value">{{ \Carbon\Carbon::parse($singleStudent->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y years') }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.blood_group'): </span>
                            <span
                                class="info_value">{{ $singleStudent->bloodGroup->base_setup_name ? $singleStudent->bloodGroup->base_setup_name : 'N/A' }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.group'): </span>
                            <span
                                class="info_value">{{ $singleStudent->group ? $singleStudent->group->group : 'N/A' }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.category'): </span>
                            <span
                                class="info_value">{{ $singleStudent->category->category_name ? $singleStudent->category->category_name : 'N/A' }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.student_group'): </span>
                            <span
                                class="info_value">{{ $singleStudent->student_group_id ? $singleStudent->group->group : 'N/A' }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.height'): </span>
                            <span
                                class="info_value">{{ $singleStudent->height ? $singleStudent->height : 'N/A' }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.weight'): </span>
                            <span
                                class="info_value">{{ $singleStudent->weight ? $singleStudent->weight : 'N/A' }}</span>
                        </p>

                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.previous_school'): </span>
                            <span
                                class="info_value">{{ $singleStudent->previous_school_details ? $singleStudent->previous_school_details : 'N/A' }}</span>
                        </p>
                    </div>
                </div>
                <div class="details_info_section">
                    <h4 class="sectoin_header">@lang('edulia.parent_info')</h4>
                    <div class="row m-0">
                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.fathers_name'): </span>
                            <span
                                class="info_value text-uppercase">{{ $singleStudent->parents->fathers_name ? $singleStudent->parents->fathers_name : 'N/A' }}</span>
                        </p>
                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.fathers_occupation'): </span>
                            <span
                                class="info_value text-uppercase">{{ $singleStudent->parents->fathers_occupation ? $singleStudent->parents->fathers_occupation : 'N/A' }}</span>
                        </p>
                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.mothers_name'): </span>
                            <span
                                class="info_value text-uppercase">{{ $singleStudent->parents->mothers_name ? $singleStudent->parents->mothers_name : 'N/A' }}</span>
                        </p>
                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.mothers_occupation'): </span>
                            <span
                                class="info_value text-uppercase">{{ $singleStudent->parents->mothers_occupation ? $singleStudent->parents->mothers_occupation : 'N/A' }}</span>
                        </p>
                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.guardian_name'): </span>
                            <span
                                class="info_value text-uppercase">{{ $singleStudent->parents->guardians_name ? $singleStudent->parents->guardians_name : 'N/A' }}</span>
                        </p>
                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.guardian_occupation'): </span>
                            <span
                                class="info_value text-uppercase">{{ $singleStudent->parents->guardians_occupation ? $singleStudent->parents->guardians_occupation : 'N/A' }}</span>
                        </p>
                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.guardians_relation'): </span>
                            <span
                                class="info_value text-uppercase">{{ $singleStudent->parents->guardians_relation ? $singleStudent->parents->guardians_relation : 'N/A' }}</span>
                        </p>
                    </div>
                </div>
                <div class="details_info_section">
                    <h4 class="sectoin_header">@lang('edulia.address')</h4>
                    <div class="row m-0">
                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.present') @lang('edulia.address'): </span>
                            <span
                                class="info_value">{{ $singleStudent->current_address ? $singleStudent->current_address : 'N/A' }}</span>
                        </p>
                        <p class="single_info col-md-4">
                            <span class="info_type">@lang('edulia.permanent') @lang('edulia.address'): </span>
                            <span
                                class="info_value">{{ $singleStudent->permanent_address ? $singleStudent->permanent_address : 'N/A' }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
