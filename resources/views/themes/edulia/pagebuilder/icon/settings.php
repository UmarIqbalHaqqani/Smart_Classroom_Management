<?php

return [
    'id' => 'icon',
    'name' => __('edulia.icon'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Common",
    'fields' => [
        [
            'id' => 'icon_alignment',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.icon_alignment'),
            'options' => [
                'center' => 'Center',
                'end' => 'Right',
                'start' => 'Left',
            ],
            'default' => 'center',
            'value' => 'center',
        ],
        [
            'id'                => 'icon_items',
            'type'              => 'repeater',
            'label_title'       => __('edulia.items'),
            'repeater_title'    => __('edulia.item'),
            'multi'       => true,
            'fields'       =>
            [
                [
                    'id'            => 'icon_note',
                    'type'          => 'info',
                    'label_title'   => __('edulia.class_svg_only'),
                ],
                [
                    'id'            => 'icon_class',
                    'type'          => 'text',
                    'value'         => '',
                    'class'         => '',
                    'label_title'   => __('edulia.font_awsome_class'),
                    'placeholder'   => __('edulia.example'),
                    'field_desc'    => __('edulia.only_use_fontawsome_version_5'),
                ],
                [
                    'id'            => 'icon_svg',
                    'type'          => 'file',
                    'field_desc'    => __('edulia.only_svg_allowed'),
                    'max_size'      => 3, // size in MB
                    'ext' => [
                        'svg',
                    ],
                    'label_title'   => __('edulia.icon_svg')
                ],
                [
                    'id' => 'icon_size',
                    'type' => 'select',
                    'class' => '',
                    'label_title' => __('edulia.icon_size'),
                    'options' => [
                        '5' => 'Extra Small',
                        '15' => 'Small',
                        '30' => 'Medium',
                        '45' => 'Large',
                        '60' => 'Extra Large',
                    ],
                    'default' => '5',
                    'value' => '5',
                ],
                [
                    'id'            => 'icon_link',
                    'type'          => 'text',
                    'value'         => '',
                    'class'         => '',
                    'label_title'   => __('edulia.icon_link'),
                    'placeholder'   => __('edulia.give_link_here'),
                ],
                [
                    'id' => 'icon_link_option',
                    'type' => 'select',
                    'class' => '',
                    'label_title' => __('edulia.icon_link_option'),
                    'options' => [
                        '_blank' => 'New Window',
                        '_self' => 'Current Window',
                    ],
                    'default' => '_blank',
                    'value' => '_blank',
                ],
            ],
        ],
    ]
];
