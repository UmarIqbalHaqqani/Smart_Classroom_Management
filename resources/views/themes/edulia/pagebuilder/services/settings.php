<?php

return [
    'id' => 'services',
    'name' => __('edulia.services'),
    'icon' => '<i class="icon-pie-chart"></i>',
    'tab' => "Common",
    'fields' => [
        [
            'id'            => 'heading',
            'type'          => 'text',
            'value'         => 'Caring is the new marketing',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.heading'),

        ],
        [
            'id'            => 'paragraph',
            'type'          => 'editor',
            'value'         => 'All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.',
            'class'         => '',
            'label_title'   => __('edulia.description'),
            'placeholder'   => __('edulia.description'),

        ],
        [
            'id'                => 'service_details',
            'type'              => 'repeater',
            'label_title'       => __('edulia.items'),
            'repeater_title'    => __('edulia.item'),
            'multi'       => true,
            'fields'       =>
            [
                [
                    'id'            => 'heading',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => 'Nunc eleifend sapien',
                    'label_title'   => __('edulia.heading'),
                ],
                [
                    'id'            => 'paragraph',
                    'type'          => 'editor',
                    'class'         => '',
                    'value'         => 'Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text',
                    'label_title'   => __('edulia.description'),
                ],
                [
                    'id'            => 'cta-text',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => 'Read More',
                    'label_title'   => __('edulia.button_text'),
                ],
                [
                    'id'            => 'cta-url',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => '#',
                    'label_title'   => __('edulia.button_link'),
                ],
            ],
        ]
    ]
];
