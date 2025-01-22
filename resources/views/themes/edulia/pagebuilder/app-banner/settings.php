<?php

return [
    'id'        => 'app-banner',
    'name'      => __('edulia.app_banner'),
    'icon'      => '<i class="icon-clipboard"></i>',
    'tab'       => "General",
    'fields'    => [
        [
            'id'            => 'app_banner_heading',
            'type'          => 'text',
            'value'         => 'Ready Flutter Mobile Apps',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'app_banner_image',
            'type'          => 'file',
            'field_desc'    => __('edulia.only_allowed_and_max_size_is_3MB'),
            'max_size'      => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('edulia.image')
        ],
        [
            'id'                => 'app_banner_items',
            'type'              => 'repeater',
            'label_title'       => __('edulia.app_banner_items'),
            'repeater_title'    => __('edulia.item'),
            'multi'             => true,
            'fields'            =>
            [
                [
                    'id'            => 'item_icon',
                    'type'          => 'file',
                    'field_desc'    => __('edulia.only_allowed_and_max_size_is_3MB'),
                    'max_size'      => 3, // size in MB
                    'ext' => [
                        'jpg',
                        'png',
                        'svg',
                    ],
                    'label_title'   => __('edulia.item_image')
                ],
                [
                    'id'            => 'item_heading',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => 'Multilingual Support (8+ Languages)',
                    'label_title'   => __('edulia.item_heading'),
                    'placeholder'   => __('edulia.enter_text'),
                ],
            ],
        ],
        [
            'id'                => 'app_download_items',
            'type'              => 'repeater',
            'label_title'       => __('edulia.app_download_items'),
            'repeater_title'    => __('edulia.item'),
            'multi'             => true,
            'fields'            =>
            [
                [
                    'id'            => 'item_image',
                    'type'          => 'file',
                    'field_desc'    => __('edulia.only_allowed_and_max_size_is_3MB'),
                    'max_size'      => 3, // size in MB
                    'ext' => [
                        'jpg',
                        'png',
                        'svg',
                    ],
                    'label_title'   => __('edulia.item_image')
                ],
                [
                    'id'            => 'item_link',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => '#',
                    'label_title'   => __('edulia.link'),
                    'placeholder'   => __('edulia.enter_link'),
                ],
            ],
        ]
    ]
];
