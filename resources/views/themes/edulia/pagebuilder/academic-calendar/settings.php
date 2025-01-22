<?php

return [
    'id' => 'academic-calendar',
    'name' => __('edulia.academic_calendar'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'academic_calendar_heading',
            'type'          => 'text',
            'value'         => 'Academic Calendar',
            'class'         => '',
            'label_title'   => __('edulia.academic_calendar'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'academic_calendar_sl',
            'type'          => 'text',
            'value'         => 'SL',
            'class'         => '',
            'label_title'   => __('edulia.column_1'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'academic_calendar_title',
            'type'          => 'text',
            'value'         => 'Title',
            'class'         => '',
            'label_title'   => __('edulia.column_2'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'academic_calendar_date',
            'type'          => 'text',
            'value'         => 'Publish Date',
            'class'         => '',
            'label_title'   => __('edulia.column_3'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'academic_calendar_action',
            'type'          => 'text',
            'value'         => 'Action',
            'class'         => '',
            'label_title'   => __('edulia.column_4'),
            'placeholder'   => __('edulia.enter_text'),
        ],
    ]
];
