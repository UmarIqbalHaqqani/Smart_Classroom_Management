<?php

return [
    'id' => 'news-section',
    'name' => __('edulia.news_section'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'news_area_sub_heading',
            'type'          => 'text',
            'value'         => '# POPULAR ARTICLES',
            'class'         => '',
            'label_title'   => __('edulia.news_area_sub_heading'),
            'placeholder'   => __('edulia.enter_text'),

        ],

        [
            'id'            => 'news_area_heading',
            'type'          => 'text',
            'value'         => 'Latest News From Blog',
            'class'         => '',
            'label_title'   => __('edulia.news_area_heading'),
            'placeholder'   => __('edulia.enter_text'),

        ],
        [
            'id'            => 'news_area__description',
            'type'          => 'editor',
            'value'         => 'Choose the most powerful courses and always',
            'class'         => '',
            'label_title'   => __('edulia.news_area_description'),
            'placeholder'   => __('edulia.enter_text'),

        ],

        [
            'id'            => 'read_more_btn',
            'type'          => 'text',
            'value'         => 'Read More',
            'class'         => '',
            'label_title'   => __('edulia.read_more_button_title'),
            'placeholder'   => __('edulia.enter_text'),

        ],

        [
            'id' => 'news_count',
            'type' => 'number',
            'value' => 4,
            'class' => '',
            'label_title' => __('edulia.how_many_news')
        ],
        [
            'id' => 'news_area_column',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.news_grid'),
            'label_desc' => __('edulia.how_many_news_in_a_row'),
            'options' => [
                12 => '1 News in a row',
                6 => '2 News in a row',
                4 => '3 News in a row',
                3 => '4 News in a row',
                2 => '6 News in a row',
                1 => '12 News in a row',

            ],
            'default' => 4,
            'value' => 4,
        ],
        [
            'id' => 'news_sorting',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.news_sorting'),
            'label_desc' => __('edulia.which_order_news'),
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
