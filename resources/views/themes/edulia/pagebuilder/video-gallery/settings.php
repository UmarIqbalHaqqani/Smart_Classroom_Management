<?php

return [
    'id' => 'video-gallery',
    'name' => __('edulia.video_gallery'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'video_sub_heading',
            'type'          => 'text',
            'value'         => 'VIDEO GALLERY',
            'class'         => '',
            'label_title'   => __('edulia.sub_heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'video_heading',
            'type'          => 'text',
            'value'         => 'Video of Memorable Events',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id' => 'video_gallery_count',
            'type' => 'number',
            'value' => 3,
            'class' => '',
            'label_title' => __('edulia.how_many_video_gallery')
        ],
        [
            'id' => 'video_gallery_column',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.video_gallery_grid'),
            'label_desc' => __('edulia.how_many_video_gallery_in_1_row'),
            'options' => [
                12 => '1 video gallery in a row',
                6 => '2 video gallery in a row',
                4 => '3 video gallery in a row',
                3 => '4 video gallery in a row',
                2 => '6 video gallery in a row',
                1 => '12 video gallery in a row',
            ],
            'default' => 4,
        ]
    ]
];
