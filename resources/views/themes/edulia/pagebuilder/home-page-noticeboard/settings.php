<?php

return [
    'id' => 'home-page-noticeboard',
    'name' => __('edulia.homepage_noticeboard'),
    'icon' => '<i class="icon-book"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'noticeboard_title',
            'type'          => 'text',
            'value'         => 'Notice Board',
            'class'         => '',
            'label_title'   => __('edulia.noticeboard_title'),
            'placeholder'   => __('edulia.noticeboard_title'),
        ],
        [
            'id'            => 'noticeboard_show_more',
            'type'          => 'text',
            'value'         => 'View All',
            'class'         => '',
            'label_title'   => __('edulia.show_more_title'),
            'placeholder'   => __('edulia.show_more_title'),
        ],
        [
            'id'            => 'noticeboard_show_more_link',
            'type'          => 'text',
            'value'         => '/noticeboard',
            'class'         => '',
            'label_title'   => __('edulia.show_more_link'),
            'placeholder'   => __('edulia.show_more_link'),
        ],
        [
            'id' => 'notice_gallery_count',
            'type' => 'number',
            'value' => 7,
            'class' => '',
            'label_title' => __('edulia.how_many_notice_you_want_to_show')
        ],
        [
            'id' => 'notice_gallery_sorting',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.notice_sorting'),
            'label_desc' => '',
            'options' => [
                'asc' => 'Ascending',
                'desc' => 'Descending',
                'randomly' => 'Randomly',
            ],
            'default' => 'asc',
            'value' => 'asc',
        ],
    ]
];
