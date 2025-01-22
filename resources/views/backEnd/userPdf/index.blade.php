@extends('backEnd.master')

@section('title')
    @lang('common.pdf')
@endsection

@push('css')
    <style>
        .couse_wizged .thumb {
            position: relative;
            overflow: hidden;
        }

        .couse_wizged .thumb img {
            transform: scale(1);
            transition: 0.3s;
            object-fit: cover;
            height: 100%;
            width: 100%;
            }

        .couse_wizged .thumb .prise_tag {
            position: absolute;
            width: 60px;
            height: 30px;
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            top: 20px;
            right: 20px;
            border-radius: 5%;
            background: #fff;
            color: #fb1159;
            display: flex;
            flex-direction: column;
            line-height: auto;
            justify-content: center;
            align-items: center;
        }

        .couse_wizged .course_content {
            background-color: #f7f6f6;
            padding: 25px 20px;
        }

        .couse_wizged .thumb {
            aspect-ratio: 1/1;
            background: #f3eeee;
            object-fit: cover;
            object-position: center;
        }

        .couse_wizged .thumb .prise_tag {
            padding: 4px 10px!important;
            display: inline-block;
            width: fit-content;
            font-weight: 500;
            line-height: 20px;
            color: var(--base_color);
        }

        @media (max-width: 576px) {
            .custom-mt-15 {
                margin-top: 15px;
            }
        }

        .pagination {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .pagination_item {
            margin: 0 5px;
        }

        .pagination_link {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border: 2px solid #ddd !important;
            border-radius: 50% !important;
            font-size: 18px !important;
            color: #333 !important;
            text-decoration: none !important;
            transition: background-color 0.3s, color 0.3s !important;
        }

        .pagination_link:hover {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .pagination_item.active .pagination_link {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .pagination_link i {
            font-size: 20px;
        }

        @media (max-width: 576px) {
            .pagination {
                gap: 10px;
            }

            .pagination_link {
                padding: 10px 16px;
                font-size: 16px;
            }
            .custom-mt-15 {
                margin-top: 15px;
            }
        }
    </style>
@endpush

@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1> @lang('common.pdf_list')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('common.pdf')</a>
                    <a href="#">@lang('common.pdf_list')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">              
                    <div class="white-box">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="main-title">
                                    <h3 class="mb-15 ">@lang('common.select_criteria')</h3>
                                </div>
                            </div>
                        </div>

                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'user-pdf-search', 'method' => 'GET']) }}
                            
                            <div class="row">
                                {{-- <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> --}}

                                <div class="col-lg-6 mt-30-md">
                                    <select class="primary_select form-control{{ @$errors->has('pdf_category_id') ? ' is-invalid' : '' }}" name="pdf_category_id">
                                        <option data-display="@lang('common.select_pdf_category')" value="">@lang('common.select_pdf_category')</option>
                                        @foreach($pdf_categories as $category)
                                            <option value="{{@$category->id}}" {{isset($category_id)? ($category_id == $category->id? 'selected':''):''}}>{{@$category->title}}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('pdf_category_id'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ @$errors->first('pdf_category_id') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- <div class="col-lg-4 custom-mt-15" id="date">
                                    <div class="primary_datepicker_input">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="primary_input">
                                                    <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" id="date" type="text" name="date" value="{{isset($pdf)? date('m/d/Y', strtotime($pdf->publish_date)) : date('m/d/Y')}}">
                                                        
                                                    <button class="btn-date" style="top: 55% !important;" data-id="#date" type="button">
                                                        <label class="m-0 p-0" for="date_of_birth">
                                                            <i class="ti-calendar" id="start-date-icon"></i>
                                                        </label>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                          
                                </div> --}}
                            
                                <div class="col-lg-2 mt-10 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_admin_visitor">
        <div class="col-lg-12 mt-40 white-box">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-15">@lang('common.pdf_list')</h3>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="container-fluid">
                    <div class="row">
                        @forelse ($pdf_items as $pdf)
                            <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                                <div class="couse_wizged">
                                    <div class="thumb" style="min-height:285px !important">
                                        <a href="{{route('user-pdf.view',$pdf->id)}}">
                                            <img class="w-100"  src="{{empty(!$pdf->image) ? asset($pdf->image) : asset('demo/images/services/img-1.png')}}">
                                            <span class="prise_tag">{{ $pdf->pdfCategory->title }}</span>
                                        </a>
                                    </div>
                                    <div class="course_content">
                                        <a href="{{route('user-pdf.view',$pdf->id)}}">
                                            <h4>{{ @$pdf->title }}</h4>
                                        </a>
                                        <div class="course_less_students">
                                            <p>Publish Date : {{ dateConvert($pdf->publish_date) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-lg-12 text-center">
                                <h3>
                                    @lang('common.no_data_found')
                                </h3>
                            </div>
                        @endforelse
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            {{ $pdf_items->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')