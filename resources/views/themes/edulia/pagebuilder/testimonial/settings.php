<?php

return [
    'id' => 'testimonial',
    'name' => __('edulia.testimonial'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'testimonial_sub_heading',
            'type'          => 'text',
            'value'         => '#CLIENT TESTIMONIAL',
            'class'         => '',
            'label_title'   => __('edulia.sub_heading'),
            'placeholder'   => __('edulia.enter_text'),

        ],
        [
            'id'            => 'testimonial_heading',
            'type'          => 'text',
            'value'         => 'What Clients Say About Us',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),

        ],
        [
            'id' => 'testionmonial_count',
            'type' => 'number',
            'value' => 3,
            'class' => '',
            'label_title' => __('edulia.how_many_testimonial')
        ],
        [
            'id' => 'testionmonial_sorting',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.testimonial_sorting'),
            'label_desc' => __('edulia.which_order_testimonial'),
            'options' => [
                'asc' => 'Ascending',
                'desc' => 'Descending',
                'randomly' => 'Randomly',
            ],
            'default' => 'asc',
            'value' => 'asc',
        ]
    ]
];
