<?php

return [
    'id' => 'photo-gallery',
    'name' => __('edulia.photo_gallery'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'photo_gallery_title',
            'type'          => 'text',
            'value'         => 'Photo Of Memorable Events',
            'class'         => '',
            'label_title'   => __('edulia.title'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'photo_gallery_sub_title',
            'type'          => 'text',
            'value'         => 'PHOTO GALLERY',
            'class'         => '',
            'label_title'   => __('edulia.sub_title'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id' => 'photo_gallery_count',
            'type' => 'number',
            'value' => 3,
            'class' => '',
            'label_title' => __('edulia.how_many_photo_gallery')
        ],
        [
            'id' => 'photo_gallery_column',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.photo_gallery_grid'),
            'label_desc' => __('edulia.how_many_photo_gallery_in_1_row'),
            'options' => [
                12 => '1 photo gallery in a row',
                6 => '2 photo gallery in a row',
                4 => '3 photo gallery in a row',
                3 => '4 photo gallery in a row',
                2 => '6 photo gallery in a row',
                1 => '12 photo gallery in a row',
            ],
            'default' => 4,
        ]
    ]
];
