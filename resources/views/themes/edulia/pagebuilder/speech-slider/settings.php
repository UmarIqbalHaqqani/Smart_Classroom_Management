<?php

return [
    'id' => 'speech-slider',
    'name' => __('edulia.speech_slider'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'speech_slider_heading',
            'type'          => 'text',
            'value'         => 'SPEECH',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'speech_slider_sub_heading',
            'type'          => 'text',
            'value'         => 'Speech of honorable members',
            'class'         => '',
            'label_title'   => __('edulia.description'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id' => 'speech_slider_count',
            'type' => 'number',
            'value' => 4,
            'class' => '',
            'label_title' => __('edulia.how_many_speech_slider')
        ],
    ]
];
