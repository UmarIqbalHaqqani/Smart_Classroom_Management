<?php

return [
    'id' => 'event-gallery',
    'name' => __('edulia.event_gallery'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id' => 'event_gallery_count',
            'type' => 'number',
            'value' => 6,
            'class' => '',
            'label_title' => __('edulia.how_many_event')
        ],
        [
            'id' => 'event_gallery_area_column',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.event_grid'),
            'label_desc' => __('edulia.how_many_event_in_1_row'),
            'options' => [
                12 => '1 event in a row',
                6 => '2 event in a row',
                4 => '3 event in a row',
                3 => '4 event in a row',
                2 => '6 event in a row',
                1 => '12 event in a row',
            ],
            'default' => 4,
            'value' => 4
        ],
        [
            'id' => 'event_gallery_sorting',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.event_sorting'),
            'label_desc' => __('edulia.which_order_event'),
            'options' => [
                'asc' => 'Ascending',
                'desc' => 'Descending',
                'randomly' => 'Randomly',
            ],
            'default' => 'asc',
            'value' => 'asc',
        ],
        [
            'id'            => 'event_gallery_read_more_btn',
            'type'          => 'text',
            'value'         => 'View Details',
            'class'         => '',
            'label_title'   => __('edulia.view_details_button_title'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id' => 'event_gallery_show_date',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.event_date_show'),
            'label_desc' => __('edulia.want_to_display_event_date'),
            'options' => [
                1 => 'Show',
                0 => 'Hide',
            ],
            'default' => 1,
            'value' => 1,
        ],

        [
            'id' => 'event_gallery_show_location',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.event_location_show'),
            'label_desc' => __('edulia.want_to_display_event_location'),
            'options' => [
                1 => 'Show',
                0 => 'Hide',
            ],
            'default' => 1,
            'value' => 1,
        ],
    ]
];
