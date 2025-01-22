<?php

return [
    'id' => 'services',
    'name' => __('Services'),
    'icon' => '<i class="icon-pie-chart"></i>',
    'tab' => "Home",
    'fields' => [
        [
            'id'            => 'heading',
            'type'          => 'text',
            'value'         => 'Caring is the new marketing',
            'class'         => '',
            'label_title'   => __('Heading'),
            'placeholder'   => __('Heading'),

        ],
        [
            'id'            => 'paragraph',
            'type'          => 'editor',
            'value'         => 'All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.',
            'class'         => '',
            'label_title'   => __('Paragraph'),
            'placeholder'   => __('Paragraph'),

        ],
        [
            'id'                => 'service_details',
            'type'              => 'repeater',
            'label_title'       => __('Services'),
            'repeater_title'    => __('Service'),
            'multi'       => true,
            'fields'       =>
            [
                [
                    'id'            => 'image',
                    'type'          => 'file',
                    'field_desc' => __('only .jpg,.png allowed and max size is 3MB'),
                    'max_size' => 3, // size in MB
                    'ext' => [
                        'jpg',
                        'png',
                    ],
                    'label_title'   => __('Image')
                ],
                [
                    'id'            => 'heading',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => 'Nunc eleifend sapien',
                    'label_title'   => __('Heading'),
                ],
                [
                    'id'            => 'paragraph',
                    'type'          => 'editor',
                    'class'         => '',
                    'value'         => 'Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text',
                    'label_title'   => __('Paragraph'),
                ],
                [
                    'id'            => 'cta-text',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => 'Read More',
                    'label_title'   => __('Button Text'),
                ],
                [
                    'id'            => 'cta-url',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => '#',
                    'label_title'   => __('Button URL'),
                ],
            ],
        ]
    ]
];
