<?php

return [
    'id' => 'event',
    'name' => __('edulia.event'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'event_img',
            'type'          => 'file',
            'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('edulia.event_img')
        ],
        [
            'id'            => 'event_sub_heading',
            'type'          => 'text',
            'value'         => '#JOIN EVENTS',
            'class'         => '',
            'label_title'   => __('edulia.sub_heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'event_heading',
            'type'          => 'text',
            'value'         => 'Join Upcoming Events',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'event_description',
            'type'          => 'editor',
            'value'         => 'Choose the most powerful courses and always be on demand',
            'class'         => '',
            'label_title'   => __('edulia.description'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id' => 'event_count',
            'type' => 'number',
            'value' => 2,
            'class' => '',
            'label_title' => __('edulia.how_many_event')
        ],
    ]
];
