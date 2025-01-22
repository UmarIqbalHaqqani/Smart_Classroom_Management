<?php

return [
    'id' => 'home-about-area',
    'name' => __('edulia.home_about_area'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'home_about_area_image',
            'type'          => 'file',
            'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('edulia.home_about_area_image')
        ],
        [
            'id'            => 'home_about_area_header',
            'type'          => 'text',
            'value'         => 'When Ambition Meets Opportunity.',
            'class'         => '',
            'label_title'   => __('edulia.home_about_area_header'),
            'placeholder'   => __('edulia.enter_text'),

        ],
        [
            'id'            => 'home_about_area_description',
            'type'          => 'editor',
            'value'         => 'Explore new skills, deepen existing passions, and get lost creativity What you find just might surprise and inspire you. With so much to explore isn’t existing passions, and get lost in creativity.',
            'class'         => '',
            'label_title'   => __('edulia.home_about_area_description'),
            'placeholder'   => __('edulia.enter_text'),

        ],
        [
            'id'                => 'home_about_area_items',
            'type'              => 'repeater',
            'label_title'       => __('edulia.items'),
            'repeater_title'    => __('edulia.item'),
            'multi'       => true,
            'fields'      =>
            [
                [
                    'id'            => 'item_heading',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => 'Upskill your organization.',
                    'label_title'   => __('edulia.item_heading'),
                    'placeholder'   => __('edulia.enter_text'),
                ],
            ],
        ],
        [
            'id'            => 'home_about_area_phone',
            'type'          => 'text',
            'value'         => '+426 322 764 22',
            'class'         => '',
            'label_title'   => __('edulia.home_about_area_phone'),
            'placeholder'   => __('edulia.enter_number'),

        ],
        [
            'id'            => 'home_about_area_button',
            'type'          => 'text',
            'value'         => 'More about us',
            'class'         => '',
            'label_title'   => __('edulia.home_about_area_button'),
            'placeholder'   => __('edulia.enter_text'),

        ],
        [
            'id'            => 'home_about_area_button_link',
            'type'          => 'text',
            'value'         => '/aboutus-page',
            'class'         => '',
            'label_title'   => __('edulia.home_about_area_button_link'),
            'placeholder'   => __('edulia.enter_link'),
        ],
    ]
];
