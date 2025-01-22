<?php

return [
    'id' => 'front-class-routine',
    'name' => __('edulia.front_class_routine'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'front_class_routine_heading',
            'type'          => 'text',
            'value'         => 'Class Routine',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'front_class_routine_sl',
            'type'          => 'text',
            'value'         => 'SL',
            'class'         => '',
            'label_title'   => __('edulia.column_1'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'front_class_routine_title',
            'type'          => 'text',
            'value'         => 'Title',
            'class'         => '',
            'label_title'   => __('edulia.column_2'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'front_class_routine_date',
            'type'          => 'text',
            'value'         => 'Date',
            'class'         => '',
            'label_title'   => __('edulia.column_3'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'front_class_routine_action',
            'type'          => 'text',
            'value'         => 'Action',
            'class'         => '',
            'label_title'   => __('edulia.column_4'),
            'placeholder'   => __('edulia.enter_text'),
        ],
    ]
];
