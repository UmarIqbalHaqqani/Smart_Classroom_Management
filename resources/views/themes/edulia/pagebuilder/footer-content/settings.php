<?php

return [
    'id' => 'footer-content',
    'name' => __('edulia.content_area'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Footer",
    'fields' => [
        [
            'id'            => 'footer_menu_image',
            'type'          => 'file',
            'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
            'max_size' => 3, // size in MB
            'ext' => ['jpg', 'png',],
            'label_title'   => __('edulia.footer_menu_upload_image')
        ],
        [
            'id'            => 'footer-right-content-text',
            'type'          => 'editor',
            'value'         => "Make school tasks easy! With InfixEDU's School Management Software, teachers can quickly handle attendance and grades. It helps teachers do their jobs better so they can focus on helping students succeed.",
            'class'         => '',
            'label_title'   => __('edulia.footer_content'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'footer-content-bg-color',
            'type'          => 'colorpicker',
            'value'         => 'rgb(0,0,0)',
            'class'         => '',
            'label_title'   => __('Color picker field'),
            'label_desc'    => __('This is the label description you can use here'),
            'field_desc'    => __('This is the field description text you can use here'),
            'placeholder'   => __('Add color code or select'),
        ],
    ]
];
