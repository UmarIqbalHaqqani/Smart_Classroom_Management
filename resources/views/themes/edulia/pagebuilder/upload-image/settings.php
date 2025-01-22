<?php

return [
    'id' => 'upload-image',
    'name' => __('edulia.upload_image'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Common",
    'fields' => [
        [
            'id'            => 'upload_image_file',
            'type'          => 'file',
            'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
            'max_size' => 3, // size in MB
            'ext' => [ 'jpg', 'png', ],
            'label_title'   => __('edulia.upload_image')
        ],
        [
            'id' => 'upload_image_alignment',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.alignment'),
            'label_desc' => '',
            'options' => [
                'center' => __('edulia.center'),
                'right'  => __('edulia.right'),
                'left'   => __('edulia.left'),
            ],
            'default' => 'center',
            'value' => 'center'
        ],
        [
            'id' => 'upload_image_width_percent',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.width'),
            'label_desc' => '',
            'options' => [
                25 => '25%',
                50 => '50%',
                75 => '75%',
                100 =>'100%'
            ],
            'default' => 100,
            'value' => 100
        ],
        [
            'id'            => 'upload_image_link',
            'type'          => 'text',
            'value'         => '',
            'class'         => '',
            'label_title'   => __('edulia.back_link'),
            'placeholder'   => __('edulia.back_link'),
        ],
    ]
];
