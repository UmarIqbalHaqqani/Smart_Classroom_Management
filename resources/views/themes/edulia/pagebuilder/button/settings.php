<?php

return [
    'id' => 'button',
    'name' => __('edulia.button'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Common",
    'fields' => [
        [
            'id'            => 'button_alignment',
            'type'          => 'select',
            'class'         => '',
            'label_title'   => __('edulia.button_alignment'),
            'options'       => [
                'center' => 'Center',
                'end'    => 'Right',
                'start'  => 'Left',
            ],
            'default'       => 'center',
            'value'         => 'center',
        ],
        [
            'id'                => 'button_items',
            'type'              => 'repeater',
            'label_title'       => __('edulia.items'),
            'repeater_title'    => __('edulia.item'),
            'multi'       => true,
            'fields'       =>
            [
                [
                    'id'            => 'button_type',
                    'type'          => 'select',
                    'class'         => '',
                    'label_title'   => __('edulia.button_type'),
                    'options'       => [
                        ''              => 'Default',
                        'btn-primary'   => 'Primary',
                        'btn-success'   => 'Success',
                        'btn-danger'    => 'Danger',
                        'btn-warning'   => 'Warning',
                    ],
                    'default'       => 'btn-primary',
                    'value'         => 'btn-primary',
                ],
                [
                    'id'            => 'button_text',
                    'type'          => 'text',
                    'value'         => 'Button',
                    'class'         => '',
                    'label_title'   => __('edulia.button_text'),
                    'placeholder'   => __('edulia.enter_text'),
                ],
                [
                    'id'            => 'button_text_size',
                    'type'          => 'select',
                    'class'         => '',
                    'label_title'   => __('edulia.button_text_size'),
                    'options'       => [
                        '10px' => 'Small',
                        '16px' => 'Default',
                        '24px' => 'Large',
                    ],
                    'default'       => '16px',
                    'value'         => '16px',
                ],
                [
                    'id'            => 'button_link',
                    'type'          => 'text',
                    'value'         => '',
                    'class'         => '',
                    'label_title'   => __('edulia.button_link'),
                    'placeholder'   => __('edulia.give_link_here'),
                ],
                [
                    'id'            => 'button_link_option',
                    'type'          => 'select',
                    'class'         => '',
                    'label_title'   => __('edulia.button_link_option'),
                    'options'       => [
                        '_blank' => 'New Window',
                        '_self'  => 'Current Window',
                    ],
                    'default'       => '_blank',
                    'value'         => '_blank',
                ],
                [
                    'id'            => 'button_size',
                    'type'          => 'select',
                    'class'         => '',
                    'label_title'   => __('edulia.button_size'),
                    'options'       => [
                        '10px 20px'     => 'Small',
                        '15px 30px'     => 'Default',
                        '20px 50px'     => 'Large',
                    ],
                    'default'       => '15px 30px',
                    'value'         => '15px 30px',
                ],
                [
                    'id'            => 'button_id',
                    'type'          => 'text',
                    'value'         => '',
                    'class'         => '',
                    'label_title'   => __('edulia.button_id'),
                    'placeholder'   => __('edulia.example_buttonId_button_id'),
                    'field_desc'    => __('edulia.please_use_unique_id'),
                ],
            ],
        ],

    ]
];
