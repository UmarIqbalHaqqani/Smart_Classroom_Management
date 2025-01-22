@pushonce(config('pagebuilder.site_style_var'))
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/packages/nice-select/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('public/theme/edulia/css/themify-icons.min.css') }}">
    <style>
        .nice-select:after {
            display: none !important;
        }
    </style>
@endpushonce
<div class="section_padding">
    <div class="container">
        <x-frontend-student-list></x-frontend-student-list>
    </div>
</div>
@pushonce(config('pagebuilder.site_script_var'))
    <script src="{{ asset('public/theme/edulia/packages/nice-select/jquery.nice-select.min.js') }}"></script>
    <script>
        $('select').niceSelect();
        $(".individual_result_datatable table").DataTable({
            responsive: true,
            language: {
                searchPlaceholder: "Search ...",
                search: "<i class='far fa-search datatable-search'></i>",
            },
        });

        if($('.individual_result_datatable .dataTables_length label select').length > 0){
            $('.individual_result_datatable .dataTables_length label select').niceSelect('destroy');
            $(".individual_result_datatable .dataTables_length label select").select2({
                minimumResultsForSearch: Infinity
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#academic_year_selector").on("change", function() {
                var url = $("#url").val();
                var formData = {
                    year: $(this).val(),
                };
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "frontend-get-class",
                    success: function(data) {
                        $.each(data, function(i, item) {
                            if (item.length) {
                                $("#class_selector").find("option").not(":first")
                                    .remove();
                                $("#class_selector_div ul").find("li").not(":first")
                                    .remove();

                                $.each(item, function(i, academic_class) {
                                    $("#class_selector").append(
                                        $("<option>", {
                                            value: academic_class.id,
                                            text: academic_class
                                                .class_name,
                                        })
                                    );

                                    $("#class_selector_div ul").append(
                                        "<li data-value='" +
                                        academic_class.id +
                                        "' class='option'>" +
                                        academic_class.class_name +
                                        "</li>"
                                    );
                                });
                            } else {
                                $("#class_selector_div .current").html(
                                    "SELECT CLASS *");
                                $("#class_selector").find("option").not(":first")
                                    .remove();
                                $("#class_selector_div ul").find("li").not(":first")
                                    .remove();
                            }
                        });
                    },
                    error: function(data) {
                        console.log("Error:", data);
                    },
                });
            });
        });
        $(document).ready(function() {
            $("#class_selector").on("change", function() {
                var url = $("#url").val();
                var formData = {
                    class: $(this).val(),
                };
                $.ajax({
                    type: "GET",
                    data: formData,
                    dataType: "json",
                    url: url + "/" + "frontend-get-section",
                    success: function(data) {
                        $.each(data, function(i, item) {
                            if (item.length) {
                                $("#section_selector").find("option").not(":first")
                                    .remove();
                                $("#section_selector_div ul").find("li").not(":first")
                                    .remove();

                                $.each(item, function(i, academic_section) {
                                    $("#section_selector").append(
                                        $("<option>", {
                                            value: academic_section.id,
                                            text: academic_section
                                                .section_name,
                                        })
                                    );

                                    $("#section_selector_div ul").append(
                                        "<li data-value='" +
                                        academic_section.id +
                                        "' class='option'>" +
                                        academic_section.section_name +
                                        "</li>"
                                    );
                                });
                            } else {
                                $("#section_selector_div .current").html(
                                    "SELECT SECTION *");
                                $("#section_selector").find("option").not(":first")
                                    .remove();
                                $("#section_selector_div ul").find("li").not(":first")
                                    .remove();
                            }
                        });
                    },
                    error: function(data) {
                        console.log("Error:", data);
                    },
                });
            });
        });
    </script>
@endpushonce
