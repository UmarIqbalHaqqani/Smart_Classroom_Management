<?php

return [
    'id' => 'facilities',
    'name' => __('edulia.facilities'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id' => 'facilities_image_align',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.image_align'),
            'options' => [
                'right' => 'Right',
                'left' => 'Left',
            ],
            'default' => 'left',
            'value' => 'left',
        ],
        [
            'id'            => 'facilities_image_upload',
            'type'          => 'file',
            'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('edulia.facilities_image')
        ],
        [
            'id'            => 'facilities_heading',
            'type'          => 'text',
            'value'         => 'AC Smart Classrooms',
            'class'         => '',
            'label_title'   => __('edulia.facilities_heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'facilities_description',
            'type'          => 'editor',
            'value'         => 'Explore new skills, deepen existing passions, and get lost creativity what you find just might surprise and inspire you. Explore new skills deepen existing passions.
                                What you find just might surprise and inspire you with so much to explore isn’t existing passions, and get lost in creativity.',
            'class'         => '',
            'label_title'   => __('edulia.facilities_description'),
            'placeholder'   => __('edulia.enter_text'),
        ],
    ]
];
