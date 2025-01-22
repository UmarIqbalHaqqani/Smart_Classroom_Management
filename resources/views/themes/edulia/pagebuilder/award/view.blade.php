<div class="container section_padding px-3 px-sm-0">
    <div class="common_data_table">
        <h4 class="text-center mb-5">{{ pagesetting('award_heading') }}</h4>
        <div class="scholar_student_table">
            <table class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ pagesetting('photo_title') }}</th>
                        <th>{{ pagesetting('name_title') }}</th>
                        <th>{{ pagesetting('session_title') }}</th>
                        <th>{{ pagesetting('scholarship_title') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty(pagesetting('scholarship_items')))
                        @foreach (pagesetting('scholarship_items') as $item)
                            <tr>
                                <td>
                                    @if (!empty($item['scholarship_item_img']))
                                        <img style="height: 50px; width: 50px;"
                                            src="{{ $item['scholarship_item_img'][0]['thumbnail'] }}"
                                            alt="{{ __('edulia.Image') }}">
                                    @endif
                                </td>
                                <td>{{ $item['scholarship_item_name'] }}</td>
                                <td>{{ $item['scholarship_item_session'] }}</td>
                                <td>{{ $item['scholarship_item_scholarship'] }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
