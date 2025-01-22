<?php

return [
    'id' => 'front-result',
    'name' => __('edulia.front_result'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'front_result_heading',
            'type'          => 'text',
            'value'         => 'Result',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('Enter Heading'),
        ],
        [
            'id'            => 'front_result_sl',
            'type'          => 'text',
            'value'         => 'SL',
            'class'         => '',
            'label_title'   => __('edulia.column_1'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'front_result_title',
            'type'          => 'text',
            'value'         => 'Title',
            'class'         => '',
            'label_title'   => __('edulia.column_2'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'front_result_date',
            'type'          => 'text',
            'value'         => 'Date',
            'class'         => '',
            'label_title'   => __('edulia.column_3'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'front_result_action',
            'type'          => 'text',
            'value'         => 'Action',
            'class'         => '',
            'label_title'   => __('edulia.column_4'),
            'placeholder'   => __('edulia.enter_text'),
        ],
    ]
];
