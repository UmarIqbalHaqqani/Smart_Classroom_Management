<div class="container section_padding px-3 px-sm-0">
    <div class="common_data_table">
        <h4 class="text-center mb-5">{{ pagesetting('front_result_heading') }}</h4>
        <table class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>{{ pagesetting('front_result_sl') }}</th>
                    <th>{{ pagesetting('front_result_title') }}</th>
                    <th>{{ pagesetting('front_result_date') }}</th>
                    <th>{{ pagesetting('front_result_action') }}</th>
                </tr>
            </thead>
            <tbody>
                <x-frontend-result></x-frontend-result>
            </tbody>
        </table>
    </div>
</div>
