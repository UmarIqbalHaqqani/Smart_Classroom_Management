<?php

return [
    'id' => 'tuition-fees',
    'name' => __('edulia.tuition_fees'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'tuition_fees_heading',
            'type'          => 'text',
            'value'         => 'Academic Year 2021 - 2022',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'tuition_fees_sub_heading',
            'type'          => 'text',
            'value'         => 'Annual tuition fees in advance and SAVE 4.25% on GEMS school fees.',
            'class'         => '',
            'label_title'   => __('edulia.sub_heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'tuition_fees_col1',
            'type'          => 'text',
            'value'         => 'SL',
            'class'         => '',
            'label_title'   => __('edulia.column_1'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'tuition_fees_col2',
            'type'          => 'text',
            'value'         => 'Class',
            'class'         => '',
            'label_title'   => __('edulia.column_2'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'tuition_fees_col3',
            'type'          => 'text',
            'value'         => 'Fees',
            'class'         => '',
            'label_title'   => __('edulia.column_3'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'                => 'tuition_fees_items',
            'type'              => 'repeater',
            'label_title'       => __('edulia.items'),
            'repeater_title'    => __('edulia.item'),
            'multi'             => true,
            'fields'            =>
            [

                [
                    'id'            => 'tuition_fees_item_col_1',
                    'type'          => 'text',
                    'value'         => '',
                    'class'         => '',
                    'label_title'   => __('edulia.column_1'),
                    'placeholder'   => __('edulia.enter_text'),
                ],
                [
                    'id'            => 'tuition_fees_item_col_2',
                    'type'          => 'text',
                    'value'         => '',
                    'class'         => '',
                    'label_title'   => __('edulia.column_2'),
                    'placeholder'   => __('edulia.enter_text'),
                ],
                [
                    'id'            => 'tuition_fees_item_col_3',
                    'type'          => 'text',
                    'value'         => '',
                    'class'         => '',
                    'label_title'   => __('edulia.column_3'),
                    'placeholder'   => __('edulia.enter_text'),
                ],
            ],
        ],
    ]
];
