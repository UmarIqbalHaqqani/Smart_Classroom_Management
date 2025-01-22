<?php

return [
    'id' => 'teacher',
    'name' => __('edulia.teacher_list'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'teacher_sub_heading',
            'type'          => 'text',
            'value'         => 'Teachers',
            'class'         => '',
            'label_title'   => __('edulia.sub_heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'teacher_heading',
            'type'          => 'text',
            'value'         => 'Our Expart Teachers',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id' => 'teacher_count',
            'type' => 'number',
            'value' => 3,
            'class' => '',
            'label_title' => __('edulia.how_many_teacher')
        ],
        [
            'id' => 'teacher_area_column',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.teacher_grid'),
            'label_desc' => __('edulia.how_many_teacher_in_1_row'),
            'options' => [
                12 => '1 teacher in a row',
                6 => '2 teacher in a row',
                4 => '3 teacher in a row',
                3 => '4 teacher in a row',
                2 => '6 teacher in a row',
                1 => '12 teacher in a row',
            ],
            'default' => 4,
            'value' => 4
        ]
    ]
];
