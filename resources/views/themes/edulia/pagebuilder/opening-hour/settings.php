<?php

return [
    'id' => 'opening-hour',
    'name' => __('edulia.opening_hour'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'opening_hour_heading',
            'type'          => 'text',
            'value'         => 'Opening Hour’s',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),

        ],
        [
            'id'            => 'opening_hour_description',
            'type'          => 'editor',
            'value'         => 'Choose the most powerful courses and always',
            'class'         => '',
            'label_title'   => __('edulia.description'),
            'placeholder'   => __('edulia.enter_text'),

        ],
            
        [
            'id'                => 'opening_hour_items',
            'type'              => 'repeater',
            'label_title'       => __('edulia.items'),
            'repeater_title'    => __('edulia.item'),
            'multi'             => true,
            'fields'            =>
            [
                [
                    'id'            => 'opening_hour_item_day',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => 'Monday',
                    'label_title'   => __('edulia.day_name'),
                ],
                [
                    'id'            => 'opening_hour_start',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => '9:30 AM',
                    'label_title'   => __('edulia.start_time'),
                ],
                [
                    'id'            => 'opening_hour_end',
                    'type'          => 'text',
                    'class'         => '',
                    'value'         => '6:30PM',
                    'label_title'   => __('edulia.end_time'),
                ]
            ],
        ]
    ]
];
