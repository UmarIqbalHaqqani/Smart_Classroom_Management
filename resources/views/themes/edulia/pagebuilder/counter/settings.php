<?php

return [
    'id' => 'counter',
    'name' => __('edulia.counter'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'counter_image',
            'type'          => 'file',
            'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('edulia.counter_image')
        ],
        [
            'id'            => 'counter_heading',
            'type'          => 'text',
            'value'         => 'What will you Discover?',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_heading'),
        ],
        [
            'id'            => 'counter_description',
            'type'          => 'editor',
            'value'         => 'Explore new skills, deepen existing passions, and get lost in creativity.What you find just might surprise and inspire you. With so much to explore, real projects to create,and the support f fellow-creatives, Turitor empower',
            'class'         => '',
            'label_title'   => __('edulia.description'),
            'placeholder'   => __('edulia.description_here'),
        ],
        [
            'id'            => 'view_course_button',
            'type'          => 'text',
            'value'         => 'View Course',
            'class'         => '',
            'label_title'   => __('edulia.view_course_button'),
            'placeholder'   => __('edulia.enter_button_title'),
        ],
        [
            'id'            => 'view_course_button_link',
            'type'          => 'text',
            'value'         => 'course',
            'class'         => '',
            'label_title'   => __('edulia.view_course_button_link'),
            'placeholder'   => __('edulia.enter_button_link'),
        ],
        [
            'id'            => 'contact_us_button',
            'type'          => 'text',
            'value'         => 'Contact Us',
            'class'         => '',
            'label_title'   => __('edulia.contact_us_button'),
            'placeholder'   => __('edulia.enter_button_title'),
        ],
        [
            'id'            => 'contact_us_button_link',
            'type'          => 'text',
            'value'         => 'contact-us',
            'class'         => '',
            'label_title'   => __('edulia.contact_us_button_link'),
            'placeholder'   => __('edulia.enter_button_link'),
        ],
        [
            'id'                => 'counter_list_items',
            'type'              => 'repeater',
            'label_title'       => __('edulia.items'),
            'repeater_title'    => __('edulia.item'),
            'multi'             => true,
            'fields'            =>
            [
                [
                    'id'            => 'item_number',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => '649',
                    'label_title'   => __('edulia.item_number'),
                ],
                [
                    'id'            => 'item_heading',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => 'Courses & Videos',
                    'label_title'   => __('edulia.item_heading'),
                ],
            ],
        ]
    ]
];
