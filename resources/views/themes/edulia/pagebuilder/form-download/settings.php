<?php

return [
    'id' => 'form-download',
    'name' => __('edulia.form_download'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'heading',
            'type'          => 'text',
            'value'         => 'Form Download',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'form_download_col_1',
            'type'          => 'text',
            'value'         => 'SL',
            'class'         => '',
            'label_title'   => __('edulia.column_1'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'form_download_col_2',
            'type'          => 'text',
            'value'         => 'Image',
            'class'         => '',
            'label_title'   => __('edulia.column_2'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'form_download_col_3',
            'type'          => 'text',
            'value'         => 'Name',
            'class'         => '',
            'label_title'   => __('edulia.column_3'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'form_download_col_4',
            'type'          => 'text',
            'value'         => 'Profession',
            'class'         => '',
            'label_title'   => __('edulia.column_4'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'form_download_col_5',
            'type'          => 'text',
            'value'         => 'Email',
            'class'         => '',
            'label_title'   => __('edulia.column_5'),
            'placeholder'   => __('edulia.enter_text'),
        ],
    ]
];
