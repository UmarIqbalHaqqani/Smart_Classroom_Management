<?php

return [
    'id' => 'google-map',
    'name' => __('edulia.google_map'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Common",
    'fields' => [
        [
            'id'            => 'google_map_editor',
            'type'          => 'editor',
            'value'         => 'Information',
            'class'         => '',
            'label_title'   => __('edulia.text_editor'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'google_map_key',
            'type'          => 'text',
            'value'         => '',
            'class'         => '',
            'label_title'   => __('edulia.google_map_embed_code'),
            'placeholder'   => __('edulia.enter_google_map_embed_code'),
            'field_desc' => __('edulia.please_input_embed_code'),
        ],
    ]
];
