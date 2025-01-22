<div class="container section_padding px-3 px-sm-0">
    <div class="common_data_table">
        <h4 class="text-center mb-5">{{ pagesetting('heading') }}</h4>
        <table class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>{{ pagesetting('form_download_col_1') }}</th>
                    <th>{{ pagesetting('form_download_col_2') }}</th>
                    <th>{{ pagesetting('form_download_col_3') }}</th>
                    <th>{{ pagesetting('form_download_col_4') }}</th>
                    <th>{{ pagesetting('form_download_col_5') }}</th>
                </tr>
            </thead>
            <tbody>
                <x-form-download></x-form-download>
            </tbody>
        </table>
    </div>
</div>
