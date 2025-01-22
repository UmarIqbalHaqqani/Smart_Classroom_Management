@pushonce(config('pagebuilder.site_style_var'))
    <style>
        table thead tr th:first-child {
            text-align: left;
        }

        table thead tr th {
            text-align: center;
        }
    </style>
@endpushonce
<section class="section_padding tution_fee">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12">
                <div class="tution_fee_wrapper">
                    <div class="tution_fee_wrapper_item tution_fee_wrapper_table">
                        <h4>{{ pagesetting('tuition_fees_heading') }}</h4>
                        <div class="tution_fee_wrapper_item_table">
                            <table>
                                <caption>{{ pagesetting('tuition_fees_sub_heading') }}{{-- <a href="#">Read more here</a> --}}</caption>
                                <thead>
                                    <tr>
                                        <th>{{ pagesetting('tuition_fees_col1') }}</th>
                                        <th>{{ pagesetting('tuition_fees_col2') }}</th>
                                        <th>{{ pagesetting('tuition_fees_col3') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty(pagesetting('tuition_fees_items')))
                                        @foreach (pagesetting('tuition_fees_items') as $item)
                                            <tr>
                                                <td>{{ $item['tuition_fees_item_col_1'] }}</td>
                                                <td>{{ $item['tuition_fees_item_col_2'] }}</td>
                                                <td>{{ $item['tuition_fees_item_col_3'] }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
