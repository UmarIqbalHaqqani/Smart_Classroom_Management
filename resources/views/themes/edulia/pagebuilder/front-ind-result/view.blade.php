@pushonce(config('pagebuilder.site_style_var'))
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/packages/nice-select/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/themify-icons.min.css') }}">
    <style>
        .result_filters .input-control,
        .result_filters .nice-select {
            margin-top: 0px !important;
        }

        .nice-select:after {
            display: none;
        }
        .nice-select.w-100 {
            min-height: 55px;
        }
    </style>
@endpushonce
<div class="container mt-5">
    <div class="result_filters">
        <x-frontend-individual-result></x-frontend-individual-result>
    </div>
</div>
@pushonce(config('pagebuilder.site_script_var'))
    <script src="{{ asset('public/theme/edulia/packages/nice-select/jquery.nice-select.min.js') }}"></script>
    <script>
        $('#academic_year_selector').niceSelect();
        $(".individual_result_datatable table").DataTable({
            responsive: true,
            language: {
                searchPlaceholder: "Search ...",
                search: "<i class='far fa-search datatable-search'></i>",
            },
        });
    </script>
@endpushonce
