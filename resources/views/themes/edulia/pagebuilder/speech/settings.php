<?php

return [
    'id' => 'speech',
    'name' => __('edulia.speech'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "General",
    'fields' => [
        [
            'id'            => 'speech_user_image',
            'type'          => 'file',
            'field_desc' => __('edulia.only_allowed_and_max_size_is_3MB'),
            'max_size' => 3, // size in MB
            'ext' => [
                'jpg',
                'png',
            ],
            'label_title'   => __('edulia.Image')
        ],
        [
            'id'            => 'speech_heading',
            'type'          => 'text',
            'value'         => 'Head Of Institution Speech',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_heading'),
        ],
        [
            'id'            => 'speech_user_name',
            'type'          => 'text',
            'value'         => 'Irina Mikailova',
            'class'         => '',
            'label_title'   => __('edulia.name'),
            'placeholder'   => __('edulia.speech_user_name'),
        ],
        [
            'id'            => 'speech_user_designation',
            'type'          => 'text',
            'value'         => 'Principle',
            'class'         => '',
            'label_title'   => __('edulia.designation'),
            'placeholder'   => __('edulia.speech_user_designation'),
        ],
        [
            'id'            => 'speech_description',
            'type'          => 'editor',
            'value'         => 'It is regarded as one of the best institutions due to its discipline, teaching technique and favorable teaching environment. In the meantime we achieved name and fame due to cent percent successful result in all public exams and has acquired position in top The students of the institution take part actively and successfully in the programs organized by Government and local authorities.',
            'class'         => '',
            'label_title'   => __('edulia.description'),
            'placeholder'   => __('edulia.description_here'),
        ],
    ]
];
