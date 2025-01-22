<?php

return [
    'id' => 'faqs',
    'name' => __('edulia.faq'),
    'icon' => '<i class="icon-book"></i>',
    'tab' => "Common",
    'fields' => [
        [
            'id'            => 'faqs_heading',
            'type'          => 'text',
            'value'         => 'Frequently Asked Questions',
            'class'         => '',
            'label_title'   => __('edulia.faq_heading'),
            'placeholder'   => __('edulia.faq_heading'),
        ],

        [
            'id'                => 'faq_datas',
            'type'              => 'repeater',
            'label_title'       => __('edulia.faqs'),
            'repeater_title'    => __('edulia.faqs'),
            'multi'       => true,
            'fields'       =>
            [
                [
                    'id'            => 'faq_question',
                    'type'          => 'text',
                    'value'         => 'How can I test new items I add to the design system before making them live?',
                    'class'         => '',
                    'label_title'   => __('edulia.faq_question'),
                    'placeholder'   => __('edulia.faq_question'),
                ],
                [
                    'id'            => 'faq_answer',
                    'type'          => 'editor',
                    'class'         => '',
                    'value'         => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries Lorem Ipsum is simply dummy.',
                    'label_title'   => __('edulia.faq_answer'),
                ],
            ],
        ]

    ]
];
