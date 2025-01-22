<?php

return [
    'id' => 'skills',
    'name' => __('Skills'),
    'icon' => '<i class="icon-briefcase"></i>',
    'tab' => "About us",
    'fields' => [
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
            'id'            => 'counter',
            'type'          => 'text',
            'value'         => '548,324',
            'class'         => '',
            'label_title'   => __('Counter'),
            'placeholder'   => __('Counter'),

        ],
        [
            'id'            => 'counter-text',
            'type'          => 'text',
            'value'         => 'All lorem ipsum text counter',
            'class'         => '',
            'label_title'   => __('Counter text'),
            'placeholder'   => __('Counter text'),

        ],
        [
            'id'            => 'button-cta',
            'type'          => 'text',
            'value'         => 'Buy edition now',
            'class'         => '',
            'label_title'   => __('Button text'),
            'placeholder'   => __('Button text'),

        ],
    ]
];
