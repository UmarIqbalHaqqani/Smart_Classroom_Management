<?php

return [
    'id' => 'faqs',
    'name' => __('FAQ'),
    'icon' => '<i class="icon-book"></i>',
    'tab' => "Faqs",
    'fields' => [
        [
            'id'            => 'heading',
            'type'          => 'text',
            'value'         => 'Frequently Asked Questions',
            'class'         => '',
            'label_title'   => __('Heading'),
            'placeholder'   => __('Heading'),
        ],

        [
            'id'                => 'faq_data',
            'type'              => 'repeater',
            'label_title'       => __('FAQs'),
            'repeater_title'    => __('FAQ'),
            'multi'       => true,
            'fields'       =>
            [
                [
                    'id'            => 'question',
                    'type'          => 'text',
                    'value'         => 'How can I test new items I add to the design system before making them live?',
                    'class'         => '',
                    'label_title'   => __('Question'),
                    'placeholder'   => __('Question'),
                ],
                [
                    'id'            => 'answer',
                    'type'          => 'editor',
                    'class'         => '',
                    'value'         => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries Lorem Ipsum is simply dummy.',
                    'label_title'   => __('Answer'),
                ],
            ],
        ]

    ]
];
