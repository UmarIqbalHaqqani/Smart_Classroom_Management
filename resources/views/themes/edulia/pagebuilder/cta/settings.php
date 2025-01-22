<?php

return [
    'id' => 'cta',
    'name' => __('edulia.cta'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'online_admission_header',
            'type'          => 'text',
            'value'         => 'Online Admission going on session 2024-25',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'online_admission_header_description',
            'type'          => 'editor',
            'value'         => 'Explore new skills, deepen existing passions, and get lost creativity what you find just might surprise and inspire you.',
            'class'         => '',
            'label_title'   => __('edulia.description'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'online_admission_button',
            'type'          => 'text',
            'value'         => 'Online Admission',
            'class'         => '',
            'label_title'   => __('edulia.button'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'online_admission_link',
            'type'          => 'text',
            'value'         => '#',
            'class'         => '',
            'label_title'   => __('edulia.link'),
            'placeholder'   => __('edulia.enter_url'),
        ],
    ]
];
