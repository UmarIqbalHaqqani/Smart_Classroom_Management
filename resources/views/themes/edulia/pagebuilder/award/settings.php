<?php

return [
    'id' => 'award',
    'name' => __('edulia.award'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'award_heading',
            'type'          => 'text',
            'value'         => __('edulia.student_scholarship_information'),
            'class'         => '',
            'label_title'   => __('edulia.student_scholarship_heading'),
            'placeholder'   => __('edulia.student_scholarship_heading'),
        ],
        [
            'id'            => 'photo_title',
            'type'          => 'text',
            'value'         => 'Student Photo',
            'class'         => '',
            'label_title'   => __('edulia.photo_title'),
            'placeholder'   => __('edulia.photo_title'),
        ],
        [
            'id'            => 'name_title',
            'type'          => 'text',
            'value'         => 'Name',
            'class'         => '',
            'label_title'   => __('edulia.name_title'),
            'placeholder'   => __('edulia.name_title'),
        ],
        [
            'id'            => 'session_title',
            'type'          => 'text',
            'value'         => 'Session',
            'class'         => '',
            'label_title'   => __('edulia.session_title'),
            'placeholder'   => __('edulia.session_title'),
        ],
        [
            'id'            => 'scholarship_title',
            'type'          => 'text',
            'value'         => 'Scholarship',
            'class'         => '',
            'label_title'   => __('edulia.scholarship_title'),
            'placeholder'   => __('edulia.scholarship_title'),
        ],
        [
            'id'                => 'scholarship_items',
            'type'              => 'repeater',
            'label_title'       => __('edulia.items'),
            'repeater_title'    => __('edulia.item'),
            'multi'             => true,
            'fields'            =>
            [
                [
                    'id'            => 'scholarship_item_img',
                    'type'          => 'file',
                    'field_desc'    => __('edulia.only_allowed_and_max_size_is_3MB'),
                    'max_size'      => 3, // size in MB
                    'ext' => [
                        'jpg',
                        'png',
                    ],
                    'label_title'   => __('edulia.item_image')
                ],
                [
                    'id'            => 'scholarship_item_name',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => '',
                    'label_title'   => __('edulia.name'),
                ],
                [
                    'id'            => 'scholarship_item_session',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => '',
                    'label_title'   => __('edulia.session'),
                ],
                [
                    'id'            => 'scholarship_item_scholarship',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => '',
                    'label_title'   => __('edulia.scholarship'),
                ]
            ],
        ]
    ]
];
