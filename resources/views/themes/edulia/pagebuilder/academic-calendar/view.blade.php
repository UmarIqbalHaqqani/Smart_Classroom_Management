<div class="container section_padding px-3 px-sm-0">
    <div class="common_data_table">
        <h4 class="text-center mb-5">{{ pagesetting('academic_calendar_heading') }}</h4>
        <table class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>{{ pagesetting('academic_calendar_sl') }}</th>
                    <th>{{ pagesetting('academic_calendar_title') }}</th>
                    <th>{{ pagesetting('academic_calendar_date') }}</th>
                    <th>{{ pagesetting('academic_calendar_action') }}</th>
                </tr>
            </thead>
            <x-frontend-academic-calendar></x-frontend-academic-calendar>
        </table>
    </div>
</div>
