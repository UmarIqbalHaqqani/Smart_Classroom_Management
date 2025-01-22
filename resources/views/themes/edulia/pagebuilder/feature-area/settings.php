<?php

return [
    'id' => 'feature-area',
    'name' => __('edulia.feature_area'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'                => 'feature_area_items',
            'type'              => 'repeater',
            'label_title'       => __('edulia.items'),
            'repeater_title'    => __('edulia.item'),
            'multi'       => true,
            'fields'       =>
            [
                [
                    'id'            => 'item_image',
                    'type'          => 'file',
                    'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
                    'max_size' => 3, // size in MB
                    'ext' => [
                        'jpg',
                        'png',
                    ],
                    'label_title'   => __('edulia.item_image')
                ],
                [
                    'id'            => 'item_heading',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => 'Learn New Skills',
                    'label_title'   => __('edulia.item_heading'),
                ],
                [
                    'id'            => 'item_description',
                    'type'          => 'editor',
                    'class'         => '',
                    'value'         => 'Skills deepen existing passions',
                    'label_title'   => __('edulia.item_description'),
                ],
            ],
        ]
    ]
];
