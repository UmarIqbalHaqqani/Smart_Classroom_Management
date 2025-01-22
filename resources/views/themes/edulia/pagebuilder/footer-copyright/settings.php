<?php

return [
    'id' => 'footer-copyright',
    'name' => __('edulia.copyright_area'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Footer",
    'fields' => [
        [
            'id'            => 'footer-copy-right-text',
            'type'          => 'text',
            'value'         => '© 2024 InfixEdu. All rights reserved. Made By CodeThemes.',
            'class'         => '',
            'label_title'   => __('edulia.footer_copy_right_text'),
            'label_desc'    => '',
            'field_desc'    => '',
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'                => 'footer-social-icons',
            'type'              => 'repeater',
            'label_title'       => __('edulia.social_icons'),
            'repeater_title'    => __('edulia.icon'),
            'multi'             => true,
            'fields'            =>
                [
                    [
                        'id'            => 'footer-social-icon-class',
                        'type'          => 'text',
                        'value'         => 'fab fa-twitter',
                        'class'         => '',
                        'label_title'   => __('edulia.icon_class'),
                        'placeholder'   => __('edulia.enter_icon_class'),
                    ],
                    [
                        'id'            => 'footer-social-label',
                        'type'          => 'text',
                        'class'         => '',
                        'value'         => '',
                        'label_title'   => __('edulia.left_menu_label'),
                        'placeholder'   => __('edulia.enter_label'),
                    ],
                    [
                        'id'            => 'footer-social-icon-url',
                        'type'          => 'text',
                        'class'         => '',
                        'value'         => 'https://twitter.com/',
                        'label_title'   => __('edulia.url'),
                        'placeholder'   => __('edulia.enter_url'),
                    ],
                ],
        ],
    ]
];
