<?php

return [
    'id' => 'page-heading',
    'name' => __('edulia.page_heading'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Common",
    'fields' => [
        [
            'id'            => 'page_heading_title',
            'type'          => 'text',
            'value'         => 'Outreach, Exposure and International Exchange Program',
            'class'         => '',
            'label_title'   => __('edulia.page_heading_title'),
            'placeholder'   => __('edulia.title'),
        ],
        [
            'id'            => 'page_heading_sub_title',
            'type'          => 'editor',
            'value'         => 'Explore new skills, deepen existing passions, and get lost creativity What you find just might surprise and inspire you with so much to explore isn’t existing passions, and get lost in creativity.',
            'class'         => '',
            'label_title'   => __('edulia.page_heading_sub_title'),
            'placeholder'   => __('edulia.sub_title'),
        ],
    ]
];
