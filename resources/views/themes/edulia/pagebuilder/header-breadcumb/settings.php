<?php

return [
    'id' => 'header-breadcumb',
    'name' => __('edulia.breadcumb'),
    'icon' => '<i class="icon-book"></i>',
    'tab' => "Common",
    'fields' => [
        [
            'id'            => 'header_bg_image',
            'type'          => 'file',
            'field_desc' => __('only .jpg,.png allowed and max size is 3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('edulia.header_background_image')
        ],
        [
            'id'            => 'left_title',
            'type'          => 'text',
            'value'         => 'Home',
            'class'         => '',
            'label_title'   => __('edulia.left_title'),
            'placeholder'   => __('edulia.left_title'),
        ],

        [
            'id'            => 'right_home',
            'type'          => 'text',
            'value'         => 'Home',
            'class'         => '',
            'label_title'   => __('edulia.home'),
            'placeholder'   => __('edulia.home'),
        ],
        [
            'id'            => 'right_content_title',
            'type'          => 'text',
            'value'         => 'Content Page',
            'class'         => '',
            'label_title'   => __('edulia.content_page_title'),
            'placeholder'   => __('edulia.enter_text'),
        ],
    ]
];
