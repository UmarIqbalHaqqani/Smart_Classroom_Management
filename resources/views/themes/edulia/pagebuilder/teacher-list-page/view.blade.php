@pushonce(config('pagebuilder.site_style_var'))
    <style>
        .nice-select:after {
            display: none !important;
        }
    </style>
@endpushonce
<div class="container section_padding px-3 px-sm-0">
    <div class="user_list_container">
        <div class="common_data_table profile_list">
            <x-frontend-teacher-list></x-frontend-teacher-list>
        </div>
    </div>
</div>
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        if($('.common_data_table .dataTables_length label select').length > 0){
            $('.common_data_table .dataTables_length label select').niceSelect('destroy');
            $(".common_data_table .dataTables_length label select").select2({
                minimumResultsForSearch: Infinity
            });
        }
    </script>
@endpushonce

