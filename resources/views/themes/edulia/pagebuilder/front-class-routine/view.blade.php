<div class="container section_padding px-3 px-sm-0">
    <div class="common_data_table">
        <h4 class="text-center mb-5">{{ pagesetting('front_class_routine_heading') }}</h4>
        <table class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>{{ pagesetting('front_class_routine_sl') }}</th>
                    <th>{{ pagesetting('front_class_routine_title') }}</th>
                    <th>{{ pagesetting('front_class_routine_date') }}</th>
                    <th>{{ pagesetting('front_class_routine_action') }}</th>
                </tr>
            </thead>
            <tbody>
                <x-frontend-class-routine></x-frontend-class-routine>
            </tbody>
        </table>
    </div>
</div>
