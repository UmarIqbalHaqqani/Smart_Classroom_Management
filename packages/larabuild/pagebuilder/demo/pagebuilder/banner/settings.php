<?php

return [
    'id' => 'banner',
    'name' => __('Banner'),
    'icon' => '<i class="icon-book-open"></i>',
    'tab' => "About us",
    'fields' => [
        [
            'id'            => 'banner_image',
            'type'          => 'file',
            'field_desc' => __('only .jpg,.png allowed and max size is 3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('Banner image')
        ],

        [
            'id'            => 'caption',
            'type'          => 'text',
            'value'         => 'Tech photo with dimension',
            'class'         => '',
            'label_title'   => __('Image caption'),
            'placeholder'   => __('Image caption'),
            'hint'     => [
                'content' => __('The caption below the image'),
            ],
        ],

    ]
];
