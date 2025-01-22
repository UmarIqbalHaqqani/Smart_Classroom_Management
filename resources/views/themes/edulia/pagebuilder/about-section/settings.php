<?php

return [
    'id' => 'about-section',
    'name' => __('edulia.about_section'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'about_area_img_small',
            'type'          => 'file',
            'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('edulia.about_area_img_small')
        ],
        [
            'id'            => 'about_area_img_medium',
            'type'          => 'file',
            'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('edulia.about_area_img_medium')
        ],
        [
            'id'            => 'about_area_img_large',
            'type'          => 'file',
            'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('edulia.about_area_img_large')
        ],
        [
            'id'            => 'alignment_left_right',
            'type'          => 'select',
            'class'         => '',
            'label_title'   => __('edulia.Image') . " " . __('edulia.alignment'),
            'options'       => [
                ' '                     => 'Left',
                'flex-row-reverse'      => 'Right',
            ],
            'default'       => ' ',
            'value'         => ' ',
        ],
        [
            'id'            => 'about_area_heading',
            'type'          => 'text',
            'value'         => 'When Ambition Meets Opportunity.',
            'class'         => '',
            'label_title'   => __('edulia.main_heading'),
            'placeholder'   => __('edulia.main_heading'),

        ],
        [
            'id'            => 'about_area_description',
            'type'          => 'editor',
            'value'         => 'Explore new skills, deepen existing passions, and get lost creativity What you find just might surprise and inspire you. With so much to explore isn’t existing passions, and get lost in creativity.',
            'class'         => '',
            'label_title'   => __('edulia.description'),
            'placeholder'   => __('edulia.description_here'),

        ],

        [
            'id'                => 'about_area_list_items',
            'type'              => 'repeater',
            'label_title'       => __('edulia.items'),
            'repeater_title'    => __('edulia.item'),
            'multi'       => true,
            'fields'       =>
            [
                [
                    'id'            => 'item_image',
                    'type'          => 'file',
                    'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
                    'max_size' => 3, // size in MB
                    'ext' => [
                        'jpg',
                        'png',
                    ],
                    'label_title'   => __('edulia.item_image')
                ],
                [
                    'id'            => 'item_heading',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => 'International Certification',
                    'label_title'   => __('edulia.item_heading'),
                ],
                [
                    'id'            => 'item_description',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => 'You can start and finish one of these popular courses in under our site',
                    'label_title'   => __('edulia.item_description'),
                ],
            ],
        ]
    ]
];
