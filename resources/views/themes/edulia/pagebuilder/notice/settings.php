<?php

return [
    'id' => 'notice',
    'name' => __('edulia.notice_list'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'notice_heading',
            'type'          => 'text',
            'value'         => 'Notice Board',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id' => 'notice_count',
            'type' => 'number',
            'value' => 7,
            'class' => '',
            'label_title' => __('edulia.how_many_notice')
        ],
    ]
];
