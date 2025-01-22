<?php

return [
    'id' => 'contact-from',
    'name' => __('edulia.contact_form'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Common",
    'fields' => [
        [
            'id'            => 'heading',
            'type'          => 'text',
            'value'         => 'Get in touch with us',
            'class'         => '',
            'label_title'   => __('edulia.heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'sub_heading',
            'type'          => 'text',
            'value'         => '#CONTACT',
            'class'         => '',
            'label_title'   => __('edulia.sub_heading'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'name_placeholder',
            'type'          => 'text',
            'value'         => 'Name',
            'class'         => '',
            'label_title'   => __('edulia.name'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'phone_placeholder',
            'type'          => 'text',
            'value'         => 'Phone No',
            'class'         => '',
            'label_title'   => __('edulia.phone_no'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'email_placeholder',
            'type'          => 'text',
            'value'         => 'Type e-mail address',
            'class'         => '',
            'label_title'   => __('edulia.email'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'subject_placeholder',
            'type'          => 'text',
            'value'         => 'Subject',
            'class'         => '',
            'label_title'   => __('edulia.subject'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'message_placeholder',
            'type'          => 'text',
            'value'         => 'Write message here',
            'class'         => '',
            'label_title'   => __('edulia.message'),
            'placeholder'   => __('edulia.enter_text'),
        ],
        [
            'id'            => 'button_text',
            'type'          => 'text',
            'value'         => 'Send Message',
            'class'         => '',
            'label_title'   => __('edulia.button_text'),
            'placeholder'   => __('edulia.enter_text'),
        ],
    ]
];
