<div class="container section_padding px-3 px-sm-0">
    <div class="user_list_container">
        <div class="common_data_table profile_list">
            <table class="user_list_table table display nowrap" style="width:100%">
                <thead class="text-nowrap">
                    <tr>
                        <th>{{ pagesetting('donor_col_1') }}</th>
                        <th>{{ pagesetting('donor_col_2') }}</th>
                        <th>{{ pagesetting('donor_col_3') }}</th>
                        <th>{{ pagesetting('donor_col_4') }}</th>
                        <th>{{ pagesetting('donor_col_5') }}</th>
                        <th>{{ pagesetting('donor_col_6') }}</th>
                        <th>{{ pagesetting('donor_col_7') }}</th>
                        <th>{{ pagesetting('donor_col_8') }}</th>
                        <th>{{ pagesetting('donor_col_9') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <x-donor></x-donor>
                </tbody>
            </table>
        </div>
    </div>
</div>
