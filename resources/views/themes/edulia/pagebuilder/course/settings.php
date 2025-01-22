<?php

return [
    'id' => 'course',
    'name' => __('edulia.course'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'course_title',
            'type'          => 'text',
            'value'         => 'Find What Fascinates you',
            'class'         => '',
            'label_title'   => __('edulia.title'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'course_sub_title',
            'type'          => 'text',
            'value'         => '#TOP COURSES',
            'class'         => '',
            'label_title'   => __('edulia.sub_title'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'course_description',
            'type'          => 'editor',
            'value'         => 'Choose the most powerful courses and always be on demand',
            'class'         => '',
            'label_title'   => __('edulia.description'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id' => 'course_count',
            'type' => 'number',
            'value' => 3,
            'class' => '',
            'label_title' => __('edulia.how_many_course_you_want_to_how')
        ],
        [
            'id' => 'course_area_column',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.grid'),
            'label_desc' => __(''),
            'options' => [
                12 => '1 in a row',
                6 => '2 in a row',
                4 => '3 in a row',
                3 => '4 in a row',
                2 => '6 in a row',
                1 => '12 in a row',
            ],
            'default' => 4,
            'value' => 4
        ],
        [
            'id' => 'course_sorting',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.sorting'),
            'label_desc' => __('edulia.Which order you want to show your courses'),
            'options' => [
                'asc'       => __('edulia.ascending'),
                'desc'      => __('edulia.descending'),
                'randomly'  => __('edulia.randomly'),
            ],
            'default' => 'asc',
            'value' => 'asc',
        ],
    ]
];
