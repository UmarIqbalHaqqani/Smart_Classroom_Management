<?php

return [
    'id' => 'about-us',
    'name' => __('About Us'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "About us",
    'fields' => [
        [
            'id'            => 'small-heading',
            'type'          => 'text',
            'value'         => 'Before we dive in to the your career, tell me a little.',
            'class'         => '',
            'label_title'   => __('Small heading'),
            'placeholder'   => __('Small heading'),

        ],
        [
            'id'            => 'heading',
            'type'          => 'text',
            'value'         => 'Apple opens another megastore in China amid William Barr',
            'class'         => '',
            'label_title'   => __('Heading'),
            'placeholder'   => __('Heading'),

        ],
        [
            'id'            => 'paragraph',
            'type'          => 'editor',
            'value'         => 'Lorem Ipsum',
            'class'         => '',
            'label_title'   => __('Paragraph'),
            'placeholder'   => __('Paragraph'),

        ],
        [
            'id'                => 'bullets',
            'type'              => 'repeater',
            'label_title'       => __('Bullets'),
            'label_desc'        => __('This is the repeator description you can use here'),
            'field'             => [
                'id'            => 'bullets-point',
                'type'          => 'text',
                'value'         => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
                'class'         => '',
                'label_title'   => __('Bullet point'),
                'placeholder'   => __('Bullet point'),
            ]
        ],
        [
            'id'            => 'blue-cta',
            'type'          => 'text',
            'value'         => 'Join us today',
            'class'         => '',
            'label_title'   => __('Blue Button text'),
            'placeholder'   => __('Blue Button text'),

        ],
        [
            'id'            => 'blue-cta-url',
            'type'          => 'text',
            'value'         => '#',
            'class'         => '',
            'label_title'   => __('Blue Button URL'),
            'placeholder'   => __('Blue Button URL'),

        ],
        [
            'id'            => 'white-cta',
            'type'          => 'text',
            'value'         => 'Cancel',
            'class'         => '',
            'label_title'   => __('White Button text'),
            'placeholder'   => __('White Button text'),

        ],
        [
            'id'            => 'white-cta-url',
            'type'          => 'text',
            'value'         => 'Cancel',
            'class'         => '',
            'label_title'   => __('White Button URL'),
            'placeholder'   => __('White Button URL'),

        ],

        [
            'id'            => 'last_paragraph',
            'type'          => 'editor',
            'value'         => 'Lorem Ipsum',
            'class'         => '',
            'label_title'   => __('Paragraph'),
            'placeholder'   => __('Paragraph'),

        ],
        [
            'id'            => 'aboutus_image',
            'type'          => 'file',
            'field_desc' => __('only .jpg,.png allowed and max size is 3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('About us image')
        ],

    ]
];
