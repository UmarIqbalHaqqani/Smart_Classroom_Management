<?php

return [
    'id' => 'footer-widget',
    'name' => __('edulia.widget_area'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Footer",
    'fields' => [
        [
            'id'            => 'footer-widget-heading',
            'type'          => 'text',
            'value'         => 'Company',
            'class'         => '',
            'label_title'   => __('edulia.widget_heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'                => 'footer-widget-links',
            'type'              => 'repeater',
            'label_title'       => __('edulia.add_widget_links'),
            'repeater_title'    => __('edulia.link'),
            'multi'             => true,
            'fields'            =>
                [
                    [
                        'id'            => 'footer-widget-label',
                        'type'          => 'text',
                        'class'         => '',
                        'value'         => 'About Us',
                        'label_title'   => __('edulia.footer_widget_label'),
                        'placeholder'   => __('edulia.enter_label'),
                    ],
                    [
                        'id'            => 'footer-widget-url',
                        'type'          => 'text',
                        'class'         => '',
                        'value'         => config('app.url'),
                        'label_title'   => __('edulia.url'),
                        'placeholder'   => __('edulia.enter_url'),
                    ],
                    [
                        'id'            => 'footer-widget-open-url',
                        'type'          => 'select',
                        'class'         => '',
                        'label_title'   => __('edulia.link_open'),
                        'label_desc'    => '',
                        'field_desc'    => '',
                        'options'       => [
                            'new_tab'   => __('edulia.new_tab'),
                            'same_tab'   => __('edulia.same_tab'),
                        ],
                        'default'       => 'new_tab',
                    ],
                ],
        ],
        [
            'id'            => 'footer-widget-bg-color',
            'type'          => 'colorpicker',
            'value'         => 'rgb(0,0,0)',
            'class'         => '',
            'label_title'   => __('Color picker field'),
            'label_desc'    => __('This is the label description you can use here'),
            'field_desc'    => __('This is the field description text you can use here'),
            'placeholder'   => __('Add color code or select'),
        ],
    ]
];
