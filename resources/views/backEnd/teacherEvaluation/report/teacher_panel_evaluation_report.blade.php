@extends('backEnd.master')
@push('css')
    <style>
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            font-size: 1.5em;
            justify-content: space-around;
            text-align: center;
            width: 5em;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #ccc;
            cursor: pointer;
        }

        .star-rating :checked~label {
            color: #f90;
        }

        article {
            background-color: #ffe;
            box-shadow: 0 0 1em 1px rgba(0, 0, 0, .25);
            color: #006;
            font-family: cursive;
            font-style: italic;
            margin: 4em;
            max-width: 30em;
            padding: 2em;
        }
    </style>
@endpush
@section('title')
    @lang('teacherEvaluation.my_report')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-20">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('teacherEvaluation.my_report')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('teacherEvaluation.dashboard')</a>
                    <a href="#">@lang('teacherEvaluation.teacher_evaluation')</a>
                    <a href="#">@lang('teacherEvaluation.my_report')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row mt-20">
                <div class="col-lg-12 student-details up_admin_visitor">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box">
                            <div class="row">
                                <div class="col-lg-4 no-gutters">
                                    <div class="main-title">
                                        <h3 class="mb-15">@lang('teacherEvaluation.my_report') </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <x-table>
                                        <table id="table_id" class="table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>@lang('teacherEvaluation.class')</th>
                                                    <th>@lang('teacherEvaluation.section')</th>
                                                    <th>@lang('teacherEvaluation.submitted_by')</th>
                                                    <th>@lang('teacherEvaluation.rating')</th>
                                                    <th>@lang('teacherEvaluation.comment')</th>
                                                    <th>@lang('teacherEvaluation.submitted_on')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($teacherEvaluations as $teacherEvaluation)
                                                    <tr>
                                                        <td>{{ $teacherEvaluation->studentRecord->class->class_name }}
                                                        </td>
                                                        <td>{{ $teacherEvaluation->studentRecord->section->section_name }}
                                                        </td>
                                                        <td>
                                                            @if ($teacherEvaluation->role_id == 2)
                                                                @lang('teacherEvaluation.student')
                                                            @else
                                                                @lang('teacherEvaluation.parent')
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="star-rating">
                                                                <input type="radio"
                                                                    id="5-stars{{ $teacherEvaluation->id }}"
                                                                    name="rating{{ $teacherEvaluation->id }}" value="5"
                                                                    {{ $teacherEvaluation->rating == 5 ? 'checked' : '' }}
                                                                    disabled />
                                                                <label for="5-stars{{ $teacherEvaluation->id }}"
                                                                    class="star">&#9733;</label>
                                                                <input type="radio"
                                                                    id="4-stars{{ $teacherEvaluation->id }}"
                                                                    name="rating{{ $teacherEvaluation->id }}" value="4"
                                                                    {{ $teacherEvaluation->rating == 4 ? 'checked' : '' }}
                                                                    disabled />
                                                                <label for="4-stars{{ $teacherEvaluation->id }}"
                                                                    class="star">&#9733;</label>
                                                                <input type="radio"
                                                                    id="3-stars{{ $teacherEvaluation->id }}"
                                                                    name="rating{{ $teacherEvaluation->id }}"
                                                                    value="3"
                                                                    {{ $teacherEvaluation->rating == 3 ? 'checked' : '' }}
                                                                    disabled />
                                                                <label for="3-stars{{ $teacherEvaluation->id }}"
                                                                    class="star">&#9733;</label>
                                                                <input type="radio"
                                                                    id="2-stars{{ $teacherEvaluation->id }}"
                                                                    name="rating{{ $teacherEvaluation->id }}"
                                                                    value="2"
                                                                    {{ $teacherEvaluation->rating == 2 ? 'checked' : '' }}
                                                                    disabled />
                                                                <label for="2-stars{{ $teacherEvaluation->id }}"
                                                                    class="star">&#9733;</label>
                                                                <input type="radio"
                                                                    id="1-star{{ $teacherEvaluation->id }}"
                                                                    name="rating{{ $teacherEvaluation->id }}"
                                                                    value="1"
                                                                    {{ $teacherEvaluation->rating == 1 ? 'checked' : '' }}
                                                                    disabled />
                                                                <label for="1-star{{ $teacherEvaluation->id }}"
                                                                    class="star">&#9733;</label>
                                                            </div>
                                                        </td>
                                                        <td data-bs-toggle="tooltip"
                                                            title="{{ $teacherEvaluation->comment }}">
                                                            {{ $teacherEvaluation->comment }}</td>
                                                        <td>{{ dateConvert($teacherEvaluation->created_at) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </x-table>

                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@push('script')
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
