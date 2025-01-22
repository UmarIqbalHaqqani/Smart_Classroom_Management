<?php

return [
    'id' => 'divider',
    'name' => __('edulia.divider'),
    'icon' => '<i class="icon-divide"></i>',
    'tab' => "Common",
    'fields' => [
        [
            'id' => 'divider_style',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.divider_style'),
            'label_desc' => '',
            'options' => [
                'solid' => __('edulia.Solid'),
                'double' => __('edulia.Double'),
                'dotted' => __('edulia.Dotted'),
                'dashed' => __('edulia.Dashed'),
                'groove' => __('edulia.Groove'),
                'ridge' => __('edulia.Ridge'),
                'inset' => __('edulia.Inset'),
                'outset' => __('edulia.Outset'),
                'mix' => __('edulia.Mix'),
            ],
            'default' => 'solid',
            'value' => 'solid'
        ],
        [
            'id'            => 'divider_width',
            'type'          => 'number',
            'value'         => 100,
            'class'         => '',
            'label_title'   => __('edulia.divider_width'),
            'placeholder'   => __('edulia.divider_width'),
        ],
        [
            'id' => 'divider_alignment',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.divider_alignment'),
            'label_desc' => '',
            'options' => [
                'right' => __('edulia.right'),
                'center' => __('edulia.center'),
                'left' => __('edulia.left'),
            ],
            'default' => 'center',
            'value' => 'center'
        ],
    ]
];
