<?php
    $actions = $this->settings_obj;
    $questions_obj = new Questions_List_Table($this->plugin_name);
    $loader_iamge = "<span class='display_none ays_quiz_loader_box'><img src='". AYS_QUIZ_ADMIN_URL ."/images/loaders/loading.gif'></span>";

    if( isset( $_REQUEST['ays_submit'] ) ){
        $actions->store_data();
    }
    if(isset($_GET['ays_quiz_tab'])){
        $ays_quiz_tab = sanitize_key( $_GET['ays_quiz_tab'] );
    }else{
        $ays_quiz_tab = 'tab1';
    }
    $data = $actions->get_data();
    global $wp_roles;
    $ays_users_roles = $wp_roles->role_names;

    $question_types = array(
        "radio"             => __("Radio", $this->plugin_name),
        "checkbox"          => __("Checkbox( Multiple )", $this->plugin_name),
        "select"            => __("Dropdown", $this->plugin_name),
        "text"              => __("Text", $this->plugin_name),
        "short_text"        => __("Short Text", $this->plugin_name),
        "number"            => __("Number", $this->plugin_name),
        "date"              => __("Date", $this->plugin_name),
        "true_or_false"     => __("True/False", $this->plugin_name),
        "custom"            => __("Custom Banner (PRO)", $this->plugin_name),
        "fill_in_blank"     => __("Fill in the blanks (PRO)", $this->plugin_name),
        "matching"          => __("Matching (PRO)", $this->plugin_name),
    );

    $question_types_icon_url = array(
        "radio"             => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-radio-type.svg",
        "checkbox"          => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-checkbox-type.svg",
        "select"            => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-dropdown-type.svg",
        "text"              => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-text-type.svg",
        "short_text"        => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-short-text-type.svg",
        "number"            => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-number-type.svg",
        "date"              => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-date-type.svg",
        "true_or_false"     => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-true-or-false-type.svg",
        "custom"            => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-custom-type.svg",
        "fill_in_blank"     => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-fill-in-blank-type.svg",
        "matching"          => AYS_QUIZ_ADMIN_URL ."/images/QuestionTypes/quiz-maker-matching-type.svg",
    );

    $options = ($actions->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes( $actions->ays_get_setting('options') ), true);
    $options['question_default_type'] = !isset($options['question_default_type']) ? 'radio' : $options['question_default_type'];
    $question_default_type = isset($options['question_default_type']) ? $options['question_default_type'] : '';
    $ays_answer_default_count = isset($options['ays_answer_default_count']) ? $options['ays_answer_default_count'] : '3';

    if ( $question_default_type == 'true_or_false' ) {
        $ays_answer_default_count = 2;
    }

    $right_answer_sound = isset($options['right_answer_sound']) ? $options['right_answer_sound'] : '';
    $wrong_answer_sound = isset($options['wrong_answer_sound']) ? $options['wrong_answer_sound'] : '';

    //Questions title length
    $question_title_length = (isset($options['question_title_length']) && intval($options['question_title_length']) != 0) ? absint(intval($options['question_title_length'])) : 5;

    //Quizzes title length
    $quizzes_title_length = (isset($options['quizzes_title_length']) && intval($options['quizzes_title_length']) != 0) ? absint(intval($options['quizzes_title_length'])) : 5;

    //Results title length
    $results_title_length = (isset($options['results_title_length']) && intval($options['results_title_length']) != 0) ? absint(intval($options['results_title_length'])) : 5;

    // Question title length
    $question_categories_title_length = (isset($options['question_categories_title_length']) && intval($options['question_categories_title_length']) != 0) ? absint(intval($options['question_categories_title_length'])) : 5;

    // Quiz title length
    $quiz_categories_title_length = (isset($options['quiz_categories_title_length']) && intval($options['quiz_categories_title_length']) != 0) ? absint(intval($options['quiz_categories_title_length'])) : 5;

    // Reviews title length
    $quiz_reviews_title_length = (isset($options['quiz_reviews_title_length']) && intval($options['quiz_reviews_title_length']) != 0) ? absint(intval($options['quiz_reviews_title_length'])) : 5;
            
    $default_leadboard_column_names = array(
        "pos" => __( 'Pos.', $this->plugin_name ),
        "name" => __( 'Name', $this->plugin_name ),
        "score" => __( 'Score', $this->plugin_name ),
        "duration" => __( 'Duration', $this->plugin_name ),
        "points" => __( 'Points', $this->plugin_name ),
    );

    $default_user_page_column_names = array(
        "quiz_name" => __( 'Quiz name', $this->plugin_name ),
        "start_date" => __( 'Start date', $this->plugin_name ),
        "end_date" => __( 'End date', $this->plugin_name ),
        "duration" => __( 'Duration', $this->plugin_name ),
        "score" => __( 'Score', $this->plugin_name ),
        "details" => __( 'Details', $this->plugin_name ),
        "points" => __( 'Points', $this->plugin_name ),
    );

     // Aro Buttons Text

    $buttons_texts_res      = ($actions->ays_get_setting('buttons_texts') === false) ? json_encode(array()) : $actions->ays_get_setting('buttons_texts');
    $buttons_texts          = json_decode( stripcslashes( $buttons_texts_res ) , true);

    $start_button           = (isset($buttons_texts['start_button']) && $buttons_texts['start_button'] != '') ? stripslashes( esc_attr( $buttons_texts['start_button'] ) ) : 'Start' ;
    $next_button            = (isset($buttons_texts['next_button']) && $buttons_texts['next_button'] != '') ? stripslashes( esc_attr( $buttons_texts['next_button'] ) ) : 'Next' ;
    $previous_button        = (isset($buttons_texts['previous_button']) && $buttons_texts['previous_button'] != '') ? stripslashes( esc_attr( $buttons_texts['previous_button'] ) ) : 'Prev' ;
    $clear_button           = (isset($buttons_texts['clear_button']) && $buttons_texts['clear_button'] != '') ? stripslashes( esc_attr( $buttons_texts['clear_button'] ) ) : 'Clear' ;
    $finish_button          = (isset($buttons_texts['finish_button']) && $buttons_texts['finish_button'] != '') ? stripslashes( esc_attr( $buttons_texts['finish_button'] ) ) : 'Finish' ;
    $see_result_button      = (isset($buttons_texts['see_result_button']) && $buttons_texts['see_result_button'] != '') ? stripslashes( esc_attr( $buttons_texts['see_result_button'] ) ) : 'See Result' ;
    $restart_quiz_button    = (isset($buttons_texts['restart_quiz_button']) && $buttons_texts['restart_quiz_button'] != '') ? stripslashes( esc_attr( $buttons_texts['restart_quiz_button'] ) ) : 'Restart quiz' ;
    $send_feedback_button   = (isset($buttons_texts['send_feedback_button']) && $buttons_texts['send_feedback_button'] != '') ? stripslashes( esc_attr( $buttons_texts['send_feedback_button'] ) ) : 'Send feedback' ;
    $load_more_button       = (isset($buttons_texts['load_more_button']) && $buttons_texts['load_more_button'] != '') ? stripslashes( esc_attr( $buttons_texts['load_more_button'] ) ) : 'Load more' ;
    $exit_button            = (isset($buttons_texts['exit_button']) && $buttons_texts['exit_button'] != '') ? stripslashes( esc_attr( $buttons_texts['exit_button'] ) ) : 'Exit' ;
    $check_button           = (isset($buttons_texts['check_button']) && $buttons_texts['check_button'] != '') ? stripslashes( esc_attr( $buttons_texts['check_button'] ) ) : 'Check' ;
    $login_button           = (isset($buttons_texts['login_button']) && $buttons_texts['login_button'] != '') ? stripslashes( esc_attr( $buttons_texts['login_button'] ) ) : 'Log In' ;
    
    //Aro end

    // Do not store IP addresses
    $options['disable_user_ip'] = isset($options['disable_user_ip']) ? $options['disable_user_ip'] : 'off';
    $disable_user_ip = (isset($options['disable_user_ip']) && $options['disable_user_ip'] == "on") ? true : false;

    //default all results column
    $default_all_results_columns = array(
        'user_name'    => 'user_name',
        'quiz_name'    => 'quiz_name',
        'start_date'   => 'start_date',
        'end_date'     => 'end_date',
        'duration'     => 'duration',
        'score'        => 'score',
        'status'       => '',
    );

    $default_all_results_column_names = array(
        "user_name"  => __( 'User name', $this->plugin_name),
        "quiz_name"  => __( 'Quiz name', $this->plugin_name ),
        "start_date" => __( 'Start date',$this->plugin_name ),
        "end_date"   => __( 'End date',  $this->plugin_name ),
        "duration"   => __( 'Duration',  $this->plugin_name ),
        "score"      => __( 'Score',     $this->plugin_name ),
        "status"     => __( 'Status',    $this->plugin_name ),
    );

    $options['all_results_columns'] = ! isset( $options['all_results_columns'] ) ? $default_all_results_columns : $options['all_results_columns'];
    $all_results_columns = (isset( $options['all_results_columns'] ) && !empty($options['all_results_columns']) ) ? $options['all_results_columns'] : array();
    $all_results_columns_order = (isset( $options['all_results_columns_order'] ) && !empty($options['all_results_columns_order']) ) ? $options['all_results_columns_order'] : $default_all_results_columns;

    $all_results_columns_order_arr = $all_results_columns_order;

    foreach( $default_all_results_columns as $key => $value ){
        if( !isset( $all_results_columns[$key] ) ){
            $all_results_columns[$key] = '';
        }

        if( !isset( $all_results_columns_order[$key] ) ){
            $all_results_columns_order[$key] = $key;
        }

        if ( ! in_array( $key , $all_results_columns_order_arr) ) {
            $all_results_columns_order_arr[] = $key;
        }
    }

    foreach( $all_results_columns_order as $key => $value ){
        if( !isset( $all_results_columns[$key] ) ){
            if( isset( $all_results_columns[$value] ) ){
                $all_results_columns_order[$value] = $value;
            }
            unset( $all_results_columns_order[$key] );
        }
    }

    foreach ($all_results_columns_order_arr  as $key => $value) {
        if( isset( $all_results_columns_order[$value] ) ){
            $all_results_columns_order_arr[$value] = $value;
        }
        
        if ( is_int( $key ) ) {
            unset( $all_results_columns_order_arr[$key] );
        }
    }

    $all_results_columns_order = $all_results_columns_order_arr;

    // Animation Top 
    $quiz_animation_top = (isset($options['quiz_animation_top']) && $options['quiz_animation_top'] != '') ? absint(intval($options['quiz_animation_top'])) : 100 ;
    $options['quiz_enable_animation_top'] = isset($options['quiz_enable_animation_top']) ? $options['quiz_enable_animation_top'] : 'on';
    $quiz_enable_animation_top = (isset($options['quiz_enable_animation_top']) && $options['quiz_enable_animation_top'] == "on") ? true : false;

    // Question Categories Array
    $question_categories = $questions_obj->get_question_categories();

    // Question Category
    $question_default_category = isset($options['question_default_category']) ? absint(intval($options['question_default_category'])) : 1; 

    // Show publicly ( All Results )
    $options['all_results_show_publicly'] = isset($options['all_results_show_publicly']) ? $options['all_results_show_publicly'] : 'off';
    $all_results_show_publicly = (isset($options['all_results_show_publicly']) && $options['all_results_show_publicly'] == "on") ? true : false;

    // Show publicly ( Single Quiz Results )
    $options['quiz_all_results_show_publicly'] = isset($options['quiz_all_results_show_publicly']) ? $options['quiz_all_results_show_publicly'] : 'off';
    $quiz_all_results_show_publicly = (isset($options['quiz_all_results_show_publicly']) && $options['quiz_all_results_show_publicly'] == "on") ? true : false;

    //default quiz all results column
    $default_quiz_all_results_columns = array(
        'user_name'    => 'user_name',
        'start_date'   => 'start_date',
        'end_date'     => 'end_date',
        'duration'     => 'duration',
        'score'        => 'score',
    );

    $default_quiz_all_results_column_names = array(
        "user_name"  => __( 'User name', $this->plugin_name ),
        "start_date" => __( 'Start date',$this->plugin_name ),
        "end_date"   => __( 'End date',  $this->plugin_name ),
        "duration"   => __( 'Duration',  $this->plugin_name ),
        "score"      => __( 'Score',     $this->plugin_name ),
    );

    $options['quiz_all_results_columns'] = ! isset( $options['quiz_all_results_columns'] ) ? $default_quiz_all_results_columns : $options['quiz_all_results_columns'];
    $quiz_all_results_columns = (isset( $options['quiz_all_results_columns'] ) && !empty($options['quiz_all_results_columns']) ) ? $options['quiz_all_results_columns'] : array();
    $quiz_all_results_columns_order = (isset( $options['quiz_all_results_columns_order'] ) && !empty($options['quiz_all_results_columns_order']) ) ? $options['quiz_all_results_columns_order'] : $default_quiz_all_results_columns;

    // Enable question allow HTML
    $options['quiz_enable_question_allow_html'] = isset($options['quiz_enable_question_allow_html']) ? sanitize_text_field( $options['quiz_enable_question_allow_html'] ) : 'off';
    $quiz_enable_question_allow_html = (isset($options['quiz_enable_question_allow_html']) && sanitize_text_field( $options['quiz_enable_question_allow_html'] ) == "on") ? true : false;

    // Enable No influence to score for new question
    $options['quiz_enable_question_not_influence_to_score'] = isset($options['quiz_enable_question_not_influence_to_score']) ? sanitize_text_field( $options['quiz_enable_question_not_influence_to_score'] ) : 'off';
    $quiz_enable_question_not_influence_to_score = (isset($options['quiz_enable_question_not_influence_to_score']) && sanitize_text_field( $options['quiz_enable_question_not_influence_to_score'] ) == "on") ? true : false;

    // Start button activation
    $options['enable_start_button_loader'] = isset($options['enable_start_button_loader']) ? sanitize_text_field( $options['enable_start_button_loader'] ) : 'off';
    $enable_start_button_loader = (isset($options['enable_start_button_loader']) && sanitize_text_field( $options['enable_start_button_loader'] ) == "on") ? true : false;

    // Leaderboard By Quiz Category Settings
    $default_leadboard_column_names = array(
        "pos" => __( 'Pos.', $this->plugin_name ),
        "name" => __( 'Name', $this->plugin_name ),
        "score" => __( 'Score', $this->plugin_name ),
        "duration" => __( 'Duration', $this->plugin_name ),
        "points" => __( 'Points', $this->plugin_name ),
    );

    // WP Editor height
    $quiz_wp_editor_height = (isset($options['quiz_wp_editor_height']) && $options['quiz_wp_editor_height'] != '' && $options['quiz_wp_editor_height'] != 0) ? absint( sanitize_text_field($options['quiz_wp_editor_height']) ) : 100 ;

    // Textarea height (public)
    $quiz_textarea_height = (isset($options['quiz_textarea_height']) && $options['quiz_textarea_height'] != '' && $options['quiz_textarea_height'] != 0) ? absint( sanitize_text_field($options['quiz_textarea_height']) ) : 100 ;

    // Show quiz button to Admins only
    $options['quiz_show_quiz_button_to_admin_only'] = isset($options['quiz_show_quiz_button_to_admin_only']) ? sanitize_text_field( $options['quiz_show_quiz_button_to_admin_only'] ) : 'off';
    $quiz_show_quiz_button_to_admin_only = (isset($options['quiz_show_quiz_button_to_admin_only']) && sanitize_text_field( $options['quiz_show_quiz_button_to_admin_only'] ) == "on") ? true : false;


    // Fields placeholders | Start

    $fields_placeholders_res      = ($actions->ays_get_setting('fields_placeholders') === false) ? json_encode(array()) : $actions->ays_get_setting('fields_placeholders');
    $fields_placeholders          = json_decode( stripcslashes( $fields_placeholders_res ) , true);

    $quiz_fields_placeholder_name  = (isset($fields_placeholders['quiz_fields_placeholder_name']) && $fields_placeholders['quiz_fields_placeholder_name'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_placeholder_name'] ) ) : 'Name';

    $quiz_fields_placeholder_eamil = (isset($fields_placeholders['quiz_fields_placeholder_eamil']) && $fields_placeholders['quiz_fields_placeholder_eamil'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_placeholder_eamil'] ) ) : 'Email';

    $quiz_fields_placeholder_phone = (isset($fields_placeholders['quiz_fields_placeholder_phone']) && $fields_placeholders['quiz_fields_placeholder_phone'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_placeholder_phone'] ) ) : 'Phone Number';

    $quiz_fields_label_name  = (isset($fields_placeholders['quiz_fields_label_name']) && $fields_placeholders['quiz_fields_label_name'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_label_name'] ) ) : 'Name';

    $quiz_fields_label_eamil = (isset($fields_placeholders['quiz_fields_label_eamil']) && $fields_placeholders['quiz_fields_label_eamil'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_label_eamil'] ) ) : 'Email';

    $quiz_fields_label_phone = (isset($fields_placeholders['quiz_fields_label_phone']) && $fields_placeholders['quiz_fields_label_phone'] != '') ? stripslashes( esc_attr( $fields_placeholders['quiz_fields_label_phone'] ) ) : 'Phone Number';

    // Fields placeholders | End

    // General CSS File
    $options['quiz_exclude_general_css'] = isset($options['quiz_exclude_general_css']) ? esc_attr( $options['quiz_exclude_general_css'] ) : 'off';
    $quiz_exclude_general_css = (isset($options['quiz_exclude_general_css']) && esc_attr( $options['quiz_exclude_general_css'] ) == "on") ? true : false;

    // Enable question answers
    $options['quiz_enable_question_answers'] = isset($options['quiz_enable_question_answers']) ? esc_attr( $options['quiz_enable_question_answers'] ) : 'off';
    $quiz_enable_question_answers = (isset($options['quiz_enable_question_answers']) && esc_attr( $options['quiz_enable_question_answers'] ) == "on") ? true : false;

    // Show correct answers
    $options['quiz_show_correct_answers'] = isset($options['quiz_show_correct_answers']) ? esc_attr( $options['quiz_show_correct_answers'] ) : 'off';
    $quiz_show_correct_answers = (isset($options['quiz_show_correct_answers']) && esc_attr( $options['quiz_show_correct_answers'] ) == "on") ? true : false;

    // Default all orders column
    $default_all_orders_columns = array(
        'quiz_name'      => 'quiz_name',
        'payment_date'   => 'payment_date',
        'amount'         => 'amount',
        'type'           => 'type'
    );

    $default_all_orders_columns_names = array(
        "quiz_name"      => __( 'Quiz name', $this->plugin_name ),
        "payment_date"   => __( 'Payment date',$this->plugin_name ),
        "amount"         => __( 'Amount',  $this->plugin_name ),
        "type"           => __( 'Type',  $this->plugin_name )
    );

    $options['quiz_all_orders_columns'] = !isset( $options['quiz_all_orders_columns'] ) || empty($options['quiz_all_orders_columns']) ? $default_all_orders_columns : $options['quiz_all_orders_columns'];
    $quiz_all_orders_columns = (isset( $options['quiz_all_orders_columns'] ) && !empty($options['quiz_all_orders_columns']) ) ? $options['quiz_all_orders_columns'] : array();
    $quiz_all_orders_columns_order = (isset( $options['quiz_all_orders_columns_order'] ) && !empty($options['quiz_all_orders_columns_order']) ) ? $options['quiz_all_orders_columns_order'] : $default_all_orders_columns;

    // Enable lazy loading attribute for images
    $options['quiz_enable_lazy_loading'] = isset($options['quiz_enable_lazy_loading']) ? esc_attr( $options['quiz_enable_lazy_loading'] ) : 'off';
    $quiz_enable_lazy_loading = (isset($options['quiz_enable_lazy_loading']) && esc_attr( $options['quiz_enable_lazy_loading'] ) == "on") ? true : false;

    // Disable Quiz maker menu item notification
    $options['quiz_disable_quiz_menu_notification'] = isset($options['quiz_disable_quiz_menu_notification']) ? esc_attr( $options['quiz_disable_quiz_menu_notification'] ) : 'off';
    $quiz_disable_quiz_menu_notification = (isset($options['quiz_disable_quiz_menu_notification']) && esc_attr( $options['quiz_disable_quiz_menu_notification'] ) == "on") ? true : false;

    // Disable results menu item notification
    $options['quiz_disable_results_menu_notification'] = isset($options['quiz_disable_results_menu_notification']) ? esc_attr( $options['quiz_disable_results_menu_notification'] ) : 'off';
    $quiz_disable_results_menu_notification = (isset($options['quiz_disable_results_menu_notification']) && esc_attr( $options['quiz_disable_results_menu_notification'] ) == "on") ? true : false;

    // Show Result Information | Start

    $options['ays_quiz_show_result_info_user_ip'] = isset($options['ays_quiz_show_result_info_user_ip']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user_ip'] ) : 'on';
    $ays_quiz_show_result_info_user_ip = (isset($options['ays_quiz_show_result_info_user_ip']) && sanitize_text_field( $options['ays_quiz_show_result_info_user_ip'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_user_id'] = isset($options['ays_quiz_show_result_info_user_id']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user_id'] ) : 'on';
    $ays_quiz_show_result_info_user_id = (isset($options['ays_quiz_show_result_info_user_id']) && sanitize_text_field( $options['ays_quiz_show_result_info_user_id'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_user'] = isset($options['ays_quiz_show_result_info_user']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user'] ) : 'on';
    $ays_quiz_show_result_info_user = (isset($options['ays_quiz_show_result_info_user']) && sanitize_text_field( $options['ays_quiz_show_result_info_user'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_user_email'] = isset($options['ays_quiz_show_result_info_user_email']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user_email'] ) : 'on';
    $ays_quiz_show_result_info_user_email = (isset($options['ays_quiz_show_result_info_user_email']) && sanitize_text_field( $options['ays_quiz_show_result_info_user_email'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_user_name'] = isset($options['ays_quiz_show_result_info_user_name']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user_name'] ) : 'on';
    $ays_quiz_show_result_info_user_name = (isset($options['ays_quiz_show_result_info_user_name']) && sanitize_text_field( $options['ays_quiz_show_result_info_user_name'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_user_phone'] = isset($options['ays_quiz_show_result_info_user_phone']) ? sanitize_text_field( $options['ays_quiz_show_result_info_user_phone'] ) : 'on';
    $ays_quiz_show_result_info_user_phone = (isset($options['ays_quiz_show_result_info_user_phone']) && sanitize_text_field( $options['ays_quiz_show_result_info_user_phone'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_start_date'] = isset($options['ays_quiz_show_result_info_start_date']) ? sanitize_text_field( $options['ays_quiz_show_result_info_start_date'] ) : 'on';
    $ays_quiz_show_result_info_start_date = (isset($options['ays_quiz_show_result_info_start_date']) && sanitize_text_field( $options['ays_quiz_show_result_info_start_date'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_duration'] = isset($options['ays_quiz_show_result_info_duration']) ? sanitize_text_field( $options['ays_quiz_show_result_info_duration'] ) : 'on';
    $ays_quiz_show_result_info_duration = (isset($options['ays_quiz_show_result_info_duration']) && sanitize_text_field( $options['ays_quiz_show_result_info_duration'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_score'] = isset($options['ays_quiz_show_result_info_score']) ? sanitize_text_field( $options['ays_quiz_show_result_info_score'] ) : 'on';
    $ays_quiz_show_result_info_score = (isset($options['ays_quiz_show_result_info_score']) && sanitize_text_field( $options['ays_quiz_show_result_info_score'] ) == "on") ? true : false;

    $options['ays_quiz_show_result_info_rate'] = isset($options['ays_quiz_show_result_info_rate']) ? sanitize_text_field( $options['ays_quiz_show_result_info_rate'] ) : 'on';
    $ays_quiz_show_result_info_rate = (isset($options['ays_quiz_show_result_info_rate']) && sanitize_text_field( $options['ays_quiz_show_result_info_rate'] ) == "on") ? true : false;
    
    // Show Result Information | End

    // Enable Hide question text for new question
    $options['quiz_enable_question_hide_question_text'] = isset($options['quiz_enable_question_hide_question_text']) ? sanitize_text_field( $options['quiz_enable_question_hide_question_text'] ) : 'off';
    $quiz_enable_question_hide_question_text = (isset($options['quiz_enable_question_hide_question_text']) && sanitize_text_field( $options['quiz_enable_question_hide_question_text'] ) == "on") ? true : false;

    // Strip slashes for answers
    $options['quiz_stripslashes_for_answer'] = isset($options['quiz_stripslashes_for_answer']) ? sanitize_text_field( $options['quiz_stripslashes_for_answer'] ) : 'off';
    $quiz_stripslashes_for_answer = (isset($options['quiz_stripslashes_for_answer']) && sanitize_text_field( $options['quiz_stripslashes_for_answer'] ) == "on") ? true : false;

    // Enable case sensitive text for a new question
    $options['quiz_case_sensitive_text'] = isset($options['quiz_case_sensitive_text']) ? sanitize_text_field( $options['quiz_case_sensitive_text'] ) : 'off';
    $quiz_case_sensitive_text = (isset($options['quiz_case_sensitive_text']) && sanitize_text_field( $options['quiz_case_sensitive_text'] ) == "on") ? true : false;

?>
<div class="wrap" style="position:relative;">
    <div class="container-fluid">
        <form method="post" class="ays-quiz-general-settings-form" id="ays-quiz-general-settings-form">
            <input type="hidden" name="ays_quiz_tab" value="<?php echo esc_attr($ays_quiz_tab); ?>">
            <div class="ays-quiz-heading-box">
                <div class="ays-quiz-wordpress-user-manual-box">
                    <a href="https://ays-pro.com/wordpress-quiz-maker-user-manual" target="_blank"><?php echo __("View Documentation", $this->plugin_name); ?></a>
                </div>
            </div>
            <h1 class="wp-heading-inline">
            <?php
                echo __('General Settings',$this->plugin_name);
            ?>
            </h1>
            <hr/>
            <div class="form-group ays-settings-wrapper">
                <div>
                    <div class="nav-tab-wrapper" style="position:sticky; top:35px;">
                        <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_quiz_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("General", $this->plugin_name);?>
                        </a>
                        <!-- <a href="#tab2" data-tab="tab2" class="nav-tab <?php echo ($ays_quiz_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Integrations", $this->plugin_name);?>
                        </a> -->
                        <a href="#tab3" data-tab="tab3" class="nav-tab <?php echo ($ays_quiz_tab == 'tab3') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Shortcodes", $this->plugin_name);?>
                        </a>
                        <a href="#tab7" data-tab="tab7" class="nav-tab <?php echo ($ays_quiz_tab == 'tab7') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Extra shortcodes", $this->plugin_name);?>
                        </a>
                        <a href="#tab4" data-tab="tab4" class="nav-tab <?php echo ($ays_quiz_tab == 'tab4') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Message variables", $this->plugin_name);?>
                        </a>
                        <a href="#tab5" data-tab="tab5" class="nav-tab <?php echo ($ays_quiz_tab == 'tab5') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Buttons Texts", $this->plugin_name);?>
                        </a>
                        <a href="#tab6" data-tab="tab6" class="nav-tab <?php echo ($ays_quiz_tab == 'tab6') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Fields texts", $this->plugin_name);?>
                        </a>
                        <a href="#tab8" data-tab="tab8" class="nav-tab <?php echo ($ays_quiz_tab == 'tab8') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Detailed Report Options", $this->plugin_name);?>
                        </a>
                    </div>
                </div>
                <div class="ays-quiz-tabs-wrapper">
                    <div id="tab1" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab1') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('General Settings',$this->plugin_name)?></p>
                        <hr class="ays-quiz-bolder-hr"/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_question_circle"></i></strong>
                                <h5><?php echo __('Default parameters for Quiz',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_questions_default_type">
                                        <?php echo __( "Questions default type", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can choose default question type which will be selected in the Add new question page.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays-type" name="ays_question_default_type">
                                        <option></option>
                                        <?php
                                            foreach($question_types as $type => $label):
                                            $selected = $question_default_type == $type ? "selected" : "";
                                            $ays_question_disabled = "";
                                            if ( $type == "custom" || $type == "fill_in_blank" || $type == "matching" ) {
                                                $ays_question_disabled = "disabled title='". __( "This feature is available only in PRO version", $this->plugin_name ) ."' ";
                                            }
                                        ?>
                                        <option value="<?php echo $type; ?>" data-nkar="<?php echo $question_types_icon_url[ $type ]; ?>" <?php echo $selected; ?> <?php echo $ays_question_disabled; ?> ><?php echo $label; ?></option>
                                        <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_answer_default_count">
                                        <?php echo __( "Answer default count", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can write the default answer count which will be showing in the Add new question page (this will work only with radio, checkbox, and dropdown types).',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_answer_default_count" id="ays_answer_default_count" min="2" class="ays-text-input" value="<?php echo $ays_answer_default_count; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_question_default_category">
                                        <?php echo __( "Questions default category", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the category of the questions which will be selected by default each time you create a question by the Add New button.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays_question_default_category" name="ays_question_default_category">
                                        <option></option>
                                        <?php
                                            foreach($question_categories as $key => $question_category):
                                                $question_category_id = $question_category['id'];
                                                $question_category_title = $question_category['title'];
                                                $selected = ($question_default_category == $question_category_id) ? "selected" : "";
                                        ?>
                                        <option value="<?php echo $question_category_id; ?>" <?php echo $selected; ?> ><?php echo $question_category_title; ?></option>
                                        <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_wp_editor_height">
                                        <?php echo __( "WP Editor height", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Give the default value to the height of the WP Editor. It will apply to all WP Editors within the plugin on the dashboard.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quiz_wp_editor_height" id="ays_quiz_wp_editor_height" class="ays-text-input" value="<?php echo $quiz_wp_editor_height; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_textarea_height">
                                        <?php echo __( "Textarea height (public)", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set the height of the textarea by entering a numeric value. It applies to Text question type textarea, Feedback textarea and so on.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quiz_textarea_height" id="ays_quiz_textarea_height" class="ays-text-input" value="<?php echo $quiz_textarea_height; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_enable_question_allow_html">
                                        <?php echo __( "Enable answers allow HTML for new question", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow implementing HTML coding in answer boxes while adding new question. This works only for Radio and Checkbox (Multiple) questions.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_enable_question_allow_html" name="ays_quiz_enable_question_allow_html" value="on" <?php echo $quiz_enable_question_allow_html ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_enable_question_not_influence_to_score">
                                        <?php echo __( "Enable No influence to score for new question", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option and the No Influence to score option will be ticked for a new question by default.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_enable_question_not_influence_to_score" name="ays_quiz_enable_question_not_influence_to_score" value="on" <?php echo $quiz_enable_question_not_influence_to_score ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_enable_question_hide_question_text">
                                        <?php echo __( "Enable Hide question text for new question", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option and the Hide question text on the front-end option will be ticked for a new question by default.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_enable_question_hide_question_text" name="ays_quiz_enable_question_hide_question_text" value="on" <?php echo $quiz_enable_question_hide_question_text ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_stripslashes_for_answer">
                                        <?php echo __( "Strip slashes for answers for a new question", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option and the Strip slashes for answers option will be ticked for a new question by default.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_stripslashes_for_answer" name="ays_quiz_stripslashes_for_answer" value="on" <?php echo $quiz_stripslashes_for_answer ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_case_sensitive_text">
                                        <?php echo __( "Enable case sensitive text for a new question", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable this option and the case sensitive text option will be ticked for a new question by default.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_case_sensitive_text" name="ays_quiz_case_sensitive_text" value="on" <?php echo $quiz_case_sensitive_text ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_show_quiz_button_to_admin_only">
                                        <?php echo __( "Show quiz button to Admins only", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow only admins to see the Quiz Maker button within the WP Editor while adding/editing a new post/page.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_quiz_button_to_admin_only" name="ays_quiz_show_quiz_button_to_admin_only" value="on" <?php echo $quiz_show_quiz_button_to_admin_only ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_questions_default_keyword">
                                                <?php echo __( "Keyword default count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the default keyword count which will be selected while adding answers to your new question. It will apply to the previous questions and intervals as well.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number" id="ays_keyword_default_max_value" class="ays-text-input">
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Default parameters for Quiz -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_user_ip"></i></strong>
                                <h5><?php echo __('Users IP addresses',$this->plugin_name)?></h5>
                            </legend>
                            <blockquote class="ays_warning">
                                <p style="margin:0;"><?php echo __( "If this option is enabled then the 'Limitation by IP' option will not work!", $this->plugin_name ); ?></p>
                            </blockquote>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_disable_user_ip">
                                        <?php echo __( "Do not store IP addresses", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, IP address of the users will not be stored in database. Note: If this option is enabled, then the `Limits user by IP` option will not work.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_disable_user_ip" name="ays_disable_user_ip" value="on" <?php echo $disable_user_ip ? 'checked' : ''; ?> />
                                </div>
                            </div>
                        </fieldset> <!-- Users IP addresses -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_music"></i></strong>
                                <h5><?php echo __('Quiz Right/Wrong answers sounds',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_questions_default_type">
                                        <?php echo __( "Sounds for right/wrong answers", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('This option will work with Enable correct answers option.',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label for="ays_questions_default_type">
                                                <?php echo __( "Sounds for right answers", $this->plugin_name ); ?>
                                            </label>                                            
                                            <div class="ays-bg-music-container">
                                                <a class="add-quiz-bg-music" href="javascript:void(0);"><?php echo __("Select sound", $this->plugin_name); ?></a>
                                                <audio controls src="<?php echo $right_answer_sound; ?>"></audio>
                                                <input type="hidden" name="ays_right_answer_sound" class="ays_quiz_bg_music" value="<?php echo $right_answer_sound; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">                                            
                                            <label for="ays_questions_default_type">
                                                <?php echo __( "Sounds for wrong answers", $this->plugin_name ); ?>
                                            </label>
                                            <div class="ays-bg-music-container">
                                                <a class="add-quiz-bg-music" href="javascript:void(0);"><?php echo __("Select sound", $this->plugin_name); ?></a>
                                                <audio controls src="<?php echo $wrong_answer_sound; ?>"></audio>
                                                <input type="hidden" name="ays_wrong_answer_sound" class="ays_quiz_bg_music" value="<?php echo $wrong_answer_sound; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Quiz Right/Wrong answers sounds -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_text"></i></strong>
                                <h5><?php echo __('Excerpt words count in list tables',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_question_title_length">
                                        <?php echo __( "Questions list table", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the questions to be shown in the Questions List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each question in the Questions page of your dashboard).', $this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_question_title_length" id="ays_question_title_length" class="ays-text-input" value="<?php echo $question_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quizzes_title_length">
                                        <?php echo __( "Quizzes list table", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the quizzes to be shown in the Quizzes List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each quiz in the Quizzes page of your dashboard).', $this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quizzes_title_length" id="ays_quizzes_title_length" class="ays-text-input" value="<?php echo $quizzes_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_results_title_length">
                                        <?php echo __( "Results list table", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the results to be shown in the Results List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each result in the Results page of your dashboard).', $this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_results_title_length" id="ays_results_title_length" class="ays-text-input" value="<?php echo $results_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_question_categories_title_length">
                                        <?php echo __( "Question categories list table", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the results to be shown in the Question categories List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each result in the Question categories page of your dashboard).', $this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_question_categories_title_length" id="ays_question_categories_title_length" class="ays-text-input" value="<?php echo $question_categories_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_categories_title_length">
                                        <?php echo __( "Quiz categories list table", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the results to be shown in the Quiz categories List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each result in the Question categories page of your dashboard).', $this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quiz_categories_title_length" id="ays_quiz_categories_title_length" class="ays-text-input" value="<?php echo $quiz_categories_title_length; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_reviews_title_length">
                                        <?php echo __( "Reviews list table", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the results to be shown in the Reviews List Table by putting your preferred count of words in the following field. (For example: if you put 10, you will see the first 10 words of each result in the Reviews page of your dashboard)  .', $this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quiz_reviews_title_length" id="ays_quiz_reviews_title_length" class="ays-text-input" value="<?php echo $quiz_reviews_title_length; ?>">
                                </div>
                            </div>
                        </fieldset> <!-- Excerpt words count in list tables -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_spinner"></i></strong>
                                <h5><?php echo __('Start button activation',$this->plugin_name); ?></h5>
                            </legend>
                            <blockquote>
                                <?php echo __( 'Tick on the checkbox if you would like to show loader and "Loading ..." text over the start button while the JavaScript of the given webpage loads. As soon as the webpage completes its loading, the start button will become active.', $this->plugin_name ); ?>
                            </blockquote>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_enable_start_button_loader">
                                        <?php echo __( "Enable Start button loader", $this->plugin_name ); ?>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_enable_start_button_loader" name="ays_enable_start_button_loader" value="on" <?php echo $enable_start_button_loader ? 'checked' : ''; ?> />
                                </div>
                            </div>                            
                        </fieldset> <!-- Start button activation -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_code"></i></strong>
                                <h5><?php echo __('Animation Top',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_enable_animation_top">
                                        <?php echo __( "Enable animation", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable animation of the scroll offset of the quiz container. It works when the quiz container is visible on the screen partly and the user starts the quiz and moves from one question to another.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_enable_animation_top" id="ays_quiz_enable_animation_top" value="on" <?php echo $quiz_enable_animation_top ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_animation_top">
                                        <?php echo __( "Scroll offset(px)", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the scroll offset of the quiz container after the animation starts. It works when the quiz container is visible on the screen partly and the user starts the quiz and moves from one question to another.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_quiz_animation_top" id="ays_quiz_animation_top" class="ays-text-input" value="<?php echo $quiz_animation_top; ?>">
                                </div>
                            </div>                            
                        </fieldset> <!-- Animation Top -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_file_code"></i></strong>
                                <h5><?php echo __('General CSS File',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_exclude_general_css">
                                        <?php echo __( "Exclude general CSS file from home page", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('If the option is enabled, then the quiz general CSS file will not be applied to the home page. Please note, that if you have inserted the quiz on the home page, then the option must be disabled so that the CSS File can normally work for that quiz..',$this->plugin_name); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_exclude_general_css" id="ays_quiz_exclude_general_css" value="on" <?php echo $quiz_exclude_general_css ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </fieldset> <!-- General CSS File -->
                        <hr>
                        <fieldset>
                            <legend>
                                <img class="ays_integration_logo" src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/integrations/ays-quiz-loading-icon.svg" alt="" style="width: 30px;">
                                <h5><?php echo __('Lazy loading',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_enable_lazy_loading">
                                        <?php echo __( "Enable lazy loading attribute for images", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('If you enable this option, the loading="lazy" attribute will be added to all the question and answer images, except of the first question and answer images. Note: The feature will not work for the Quiz image option. The default value for this option is set as "On".',$this->plugin_name) ); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_enable_lazy_loading" id="ays_quiz_enable_lazy_loading" value="on" <?php echo $quiz_enable_lazy_loading ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </fieldset> <!-- Lazy loading -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_bell"></i></strong>
                                <h5><?php echo __('Menu notifications',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_disable_quiz_menu_notification">
                                        <?php echo __( "Disable Quiz maker menu item notification", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option and the notifications will not be displayed in the Quiz Maker menu.',$this->plugin_name) ); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_disable_quiz_menu_notification" id="ays_quiz_disable_quiz_menu_notification" value="on" <?php echo $quiz_disable_quiz_menu_notification ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_quiz_disable_results_menu_notification">
                                        <?php echo __( "Disable Results menu item notification", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option and the notifications will not be displayed in the Results menu.',$this->plugin_name) ); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_quiz_disable_results_menu_notification" id="ays_quiz_disable_results_menu_notification" value="on" <?php echo $quiz_disable_results_menu_notification ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </fieldset> <!-- Menu notifications -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_trash"></i></strong>
                                <h5><?php echo __('Erase Quiz data',$this->plugin_name)?></h5>
                            </legend>
                            <?php if( isset( $_GET['del_stat'] ) ): ?>
                            <blockquote style="border-color:#46b450;background: rgba(70, 180, 80, 0.2);">
                                <?php echo "Results up to a ". sanitize_text_field( $_GET['mcount'] ) ." month ago deleted successfully."; ?>
                            </blockquote>
                            <hr>
                            <?php endif; ?>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_delete_results_by">
                                        <?php echo __( "Delete results older than 'X' the month", $this->plugin_name ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify count of months and save changes. Attention! it will remove submissions older than specified months permanently.',$this->plugin_name)?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_delete_results_by" id="ays_delete_results_by" class="ays-text-input">
                                </div>
                            </div>                            
                        </fieldset> <!-- Erase Quiz data -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_list_alt"></i></strong>
                                <h5><?php echo __('Results settings',$this->plugin_name); ?></h5>
                            </legend>
                            <blockquote>
                                <?php echo __( 'All started, but not finished data of quizzes will be stored on the Not finished tab of the Results page.', $this->plugin_name ); ?>
                            </blockquote>
                            <hr>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_store_all_not_finished_results">
                                                <?php echo __( "Store all not finished results", $this->plugin_name ); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" value="on" />
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Results settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_sign_in"></i></strong>
                                <h5><?php echo __('Quiz Login Form Settings',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="justify-content:flex-end;">

                                    </div>
                                    <div class="form-group row ays_toggle_parent">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Enable Login Form Custom Redirection", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __('Enable this option to redirect users to your desired Login Form in case of filling an incorrect email address or password.',$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-checkbox-input ays-enable-timer1" />
                                        </div>
                                        <div class="col-sm-7 ays_toggle_target ays_divider_left">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label class="form-check-label">
                                                        <?php echo __('Custom login form link', $this->plugin_name); ?>
                                                        <a class="ays_help" data-toggle="tooltip"
                                                        title="<?php echo __('The URL for redirecting after writing an incorrect email address or password.', $this->plugin_name); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="ays-enable-timerl ays-text-input">
                                                </div>
                                            </div>
                                            <blockquote>
                                                <?php echo __( 'Note: If you leave the option empty,  the user will stay on the same page in case of a fail.', $this->plugin_name ); ?>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Results settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_globe"></i></strong>
                                <h5><?php echo __('Who will have permission to Quiz menu',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="">

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_user_roles">
                                                <?php echo __( "Select user role for giving access to Quiz menu", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Give permissions to see only their own quizzes to these user roles.',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8 ays-quiz-user-roles">
                                            <select id="ays_user_roles" multiple>
                                                <option selected><?php echo __( "Administrator" , $this->plugin_name); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Select user role for giving access to change all Quiz data", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Give permissions to manage all quizzes and results to these user roles. Please add the given user roles to the above field as well.',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8 ays-quiz-user-roles">
                                            <select id="ays_user_roles_to_change_quiz" multiple>
                                                <option selected><?php echo __( "Administrator" , $this->plugin_name); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo __( "Control the access of the plugin from the dashboard and manage the capabilities of those user roles.", $this->plugin_name ); ?>
                                        <br>
                                        <?php echo __( "If you want to give a full control to the given user role, please add the role in both fields.", $this->plugin_name ); ?>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Who will have permission to Quiz menu -->
                    </div>
                    <div id="tab3" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab3') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Shortcodes',$this->plugin_name)?></p>
                        <hr class="ays-quiz-bolder-hr"/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('All Results Settings',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/1G94I4mw4hY" target="_blank">
                                                <?php echo __("How to set All Results Settings Shortcode - video", $this->plugin_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_all_results">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can copy the shortcode and insert it to any post to show all results.',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_all_results" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_all_results id="Your_Category_ID"]'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_all_results_show_publicly">
                                                <?php echo __( "Show to guests too", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the All results table to guests as well. By default, it is displayed only for logged-in users. If this option is disabled, then only the logged-in users will be able to see the table. Note: Despite the fact of showing the table to the guests, the table will contain only info of the logged-in users.',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" id="ays_all_results_show_publicly" name="ays_all_results_show_publicly" value="on" <?php echo $all_results_show_publicly ? 'checked' : ''; ?> />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "Table columns", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-show-user-page-table">
                                                    <?php
                                                        foreach ($all_results_columns_order as $key => $val) {
                                                            $checked = '';
                                                            if(isset($all_results_columns[$val]) && $all_results_columns[$val] != ''){
                                                                $checked = 'checked';
                                                            }

                                                            if ($val == '') {
                                                               $checked = '';
                                                               $default_leadboard_column_names[$val] = $key;
                                                               $val = $key;
                                                            }

                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="hidden" value="<?php echo $val; ?>" name="ays_all_results_columns_order[]"/>
                                                                <input type="checkbox" id="ays_show_result<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" name="ays_all_results_columns[<?php echo $val; ?>]" <?php echo $checked; ?>/>
                                                                <label for="ays_show_result<?php echo $val; ?>">
                                                                    <?php echo $default_all_results_column_names[$val]; ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                     ?>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <blockquote>
                                        <ul class="ays-quiz-general-settings-blockquote-ul" style="margin: 0;">
                                            <li style="padding-bottom: 5px;">
                                                <?php
                                                    echo sprintf(
                                                        __( '%s ID %s', $this->plugin_name ) . ' - ' . esc_attr( __( "Enter the ID of the quiz category. Example: id='23'. Note: In case you don't insert the ID of the Quiz Category, all results of all the quizzes will be displayed on the Front-end.", $this->plugin_name ) ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset> <!-- All Results Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Single Quiz Results Settings',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/2qksH-vVc4w" target="_blank">
                                                <?php echo __("How to set Single Quiz Results Shortcode - video", $this->plugin_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_all_results">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can copy the shortcode and insert it to any post to show quiz all results.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_all_results" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_all_results id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_all_results_show_publicly">
                                                <?php echo __( "Show to guests too", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the Single quiz results table to guests as well. By default, it is displayed only for logged-in users. If this option is disabled, then only the logged-in users will be able to see the table. Note: Despite the fact of showing the table to the guests, the table will contain only info of the logged-in users.',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_all_results_show_publicly" name="ays_quiz_all_results_show_publicly" value="on" <?php echo $quiz_all_results_show_publicly ? 'checked' : ''; ?> />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "Table columns", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-show-user-page-table">
                                                    <?php
                                                        foreach ($quiz_all_results_columns_order as $key => $val) {
                                                            $checked = '';
                                                            if(isset($quiz_all_results_columns[$val])){
                                                                $checked = 'checked';
                                                            }
                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="hidden" value="<?php echo $val; ?>" name="ays_quiz_all_results_columns_order[]"/>
                                                                <input type="checkbox" id="ays_show_quiz_result<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" name="ays_quiz_all_results_columns[<?php echo $val; ?>]" <?php echo $checked; ?>/>
                                                                <label for="ays_show_quiz_result<?php echo $val; ?>">
                                                                    <?php echo $default_quiz_all_results_column_names[$val]; ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                     ?>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Single Quiz Results Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Display Quiz Bank(questions)',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/iciHKd_nyms" target="_blank">
                                                <?php echo __("How to set Display Quiz Bank Questions Shortcode - video", $this->plugin_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_display_questions">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Paste the shortcode into any of your posts to show questions of a given quiz. Designed to show questions to students, earlier on, for preparing for the test.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_display_questions" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_display_questions by="quiz/category" id="N" orderby="ASC"]'>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row ays_toggle_parent">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_enable_question_answers">
                                                <?php echo __( "Enable question answers", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, the answers of the questions will be displayed in a list on the Front-end.',$this->plugin_name);?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-checkbox-input ays_toggle_checkbox" id="ays_quiz_enable_question_answers" name="ays_quiz_enable_question_answers" value="on" <?php echo $quiz_enable_question_answers ? 'checked' : ''; ?> />
                                        </div>
                                        <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo ($quiz_enable_question_answers) ? '' : 'display_none'; ?>">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label class="form-check-label" for="ays_quiz_show_correct_answers">
                                                        <?php echo __('Show correct answers', $this->plugin_name); ?>
                                                        <a class="ays_help" data-toggle="tooltip"
                                                        title="<?php echo __('If this option is activated, the correct answers will be in bold on the front-end.', $this->plugin_name); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="checkbox" class="" id="ays_quiz_show_correct_answers" name="ays_quiz_show_correct_answers" value="on" <?php echo $quiz_show_correct_answers ? 'checked' : ''; ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <blockquote>
                                <ul class="ays-quiz-general-settings-blockquote-ul">
                                    <li>
                                        <?php
                                            echo sprintf(
                                                __( '%s By %s', $this->plugin_name ) . ' - ' . __( 'Choose the method of filtering. Example: by="category".', $this->plugin_name ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-quiz-general-settings-ul'>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s quiz %s', $this->plugin_name ) . ' - ' . __( 'If you set the method as Quiz, it will show all questions added in the given quiz.', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s category %s', $this->plugin_name ) . ' - ' . __( 'If you set the method as Category, it will show all questions assigned to the given category.', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <?php
                                            echo sprintf(
                                                __( '%s ID %s', $this->plugin_name ) . ' - ' . __( 'Select the ID. Example: id="23".', $this->plugin_name ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-quiz-general-settings-ul'>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s quiz %s', $this->plugin_name ) . ' - ' . __( 'If you set the method as Quiz, please enter the ID of the given quiz.', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s category %s', $this->plugin_name ) . ' - ' . __( 'If you set the method as Category, please enter the ID of the given category.', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <?php
                                            echo sprintf(
                                                __( '%s Orderby %s', $this->plugin_name ) . ' - ' . __( 'Choose the way of ordering the questions. Example: orderby="ASC".', $this->plugin_name ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-quiz-general-settings-ul'>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s ASC %s', $this->plugin_name ) . ' - ' . __( 'The earliest created questions will appear at top of the list. The order will be classified based on question ID (oldest to newest).', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s DESC %s', $this->plugin_name ) . ' - ' . __( 'The latest created questions will appear at top of the list. The order will be classified based on question ID (newest to oldest).', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s default %s', $this->plugin_name ) . ' - ' . __( 'The order will be classified based on the reordering you have done while adding the questions to the quiz. It will work only with the by="quiz" method. The by="category" method will show the same order as orderby="ASC".', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s random %s', $this->plugin_name ) . ' - ' . __( 'The questions will be displayed in random order every time the users refresh the page.', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </blockquote>
                        </fieldset> <!-- Display Quiz Bank(questions) -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Quiz categories',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/bRTNyC-8Byw" target="_blank">
                                                <?php echo __("How to set Quiz Categories shortcode - video", $this->plugin_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_categories">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode, configure it based on your preferences and paste it into the post/page. Put the ID of your preferred category,  choose the method of displaying (all/random) and specify the count of quizzes.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_categories" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_cat id="Your_Quiz_Category_ID" display="random" count="5" layout="list"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <blockquote>
                                <ul class="ays-quiz-general-settings-blockquote-ul">
                                    <li style="padding-bottom: 5px;">
                                        <?php
                                            echo sprintf(
                                                __( '%s ID %s', $this->plugin_name ) . ' - ' . __( 'Enter the ID of the category. Example: id="23".', $this->plugin_name ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                    </li>
                                    <li>
                                        <?php
                                            echo sprintf(
                                                __( '%s Display %s', $this->plugin_name ) . ' - ' . __( 'Choose the method of displaying. Example: display="random" count="5".', $this->plugin_name ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-quiz-general-settings-ul'>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s All %s', $this->plugin_name ) . ' - ' . __( 'If you set the method as All, it will show all quizzes from the given category. In this case, it is not required to fill the %s Count %s attribute. You can either remove it or the system will ignore the value given to it.', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>',
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s Random %s', $this->plugin_name ) . ' - ' . __( 'If you set the method as Random, please give a value to %s Count %s option too, and it will randomly display that given amount of quizzes from the given category.', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>',
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <?php
                                            echo sprintf(
                                                __( '%s Layout %s', $this->plugin_name ) . ' - ' . __( 'Choose the design of the layout. Example:layout=grid.', $this->plugin_name ),
                                                '<b>',
                                                '</b>'
                                            );
                                        ?>
                                        <ul class='ays-quiz-general-settings-ul'>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s List %s', $this->plugin_name ) . ' - ' . __( 'Choose the design of the layout as list․', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s Grid %s', $this->plugin_name ) . ' - ' . __( 'Choose the design of the layout as grid․', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </blockquote>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_cat_title">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You need to insert Your Quiz Category ID in the shortcode. It will show the category title. If there is no quiz category available/unavailable with that particular Quiz Category ID, the shortcode will stay empty.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_cat_title" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_cat_title id="Your_Quiz_Category_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_cat_description">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You need to insert Your Quiz Category ID in the shortcode. It will show the category description. If there is no quiz category available/unavailable with that particular Quiz Category ID, the shortcode will stay empty.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_cat_description" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_cat_description id="Your_Quiz_Category_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Quiz categories -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Question categories',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/bRTNyC-8Byw" target="_blank">
                                                <?php echo __("How to set Question Categories shortcode - video", $this->plugin_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_question_categories_title">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You need to insert Your Quiz Question Category ID in the shortcode. It will show the category title. If there is no quiz question category available/unavailable with that particular Quiz Question Category ID, the shortcode will stay empty.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_question_categories_title" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_question_categories_title id="Your_Quiz_Question_Category_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_question_categories_description">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You need to insert Your Quiz Question Category ID in the shortcode. It will show the category description. If there is no quiz question category available/unavailable with that particular Quiz Question Category ID, the shortcode will stay empty.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_question_categories_description" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_question_categories_description id="Your_Quiz_Question_Category_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Question categories -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Most popular quiz',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://youtu.be/QSJnZSvOqa0" target="_blank">
                                                <?php echo __("How to set the Most Popular Quiz shortcode - video", $this->plugin_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_most_popular">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Designed to show the most popular quiz that is passed most commonly by users.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_most_popular" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_most_popular count="1"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Most popular quiz -->
                        <hr>
                        <fieldset>
                            <legend style="margin-bottom: 0;">
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Individual Leaderboard Settings',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://www.youtube.com/watch?v=trZEpGWm9GE" target="_blank">
                                                <?php echo __("How to add leaderboard - video", $this->plugin_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                    <div class="pro_features pro_features_popup pro_features_background_bolder">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("How to set Leaderboards with WordPress Quiz Plugin", $this->plugin_name); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/I4rfyzf5D3E">
                                                <p>
                                                    <?php echo sprintf( __("The Quiz Maker plugin gives you the opportunity to add %s advanced Leaderboards %s to your WordPress website. Having Leaderboards on the website helps you %s stimulate competition %s and %s motivate quiz takers %s to improve their skills.", $this->plugin_name),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("Follow the steps mentioned in this step-by-step video tutorial to create your own Leaderboard. ", $this->plugin_name); ?>
                                                </p>
                                                <div>
                                                    <a href="https://quiz-plugin.com/individual-leaderboard" target="_blank"><?php echo __("See Demo", $this->plugin_name); ?></a>
                                                </div>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-individual-leaderboard-shortcode">
                                                <?php echo __("Upgrade PRO NOW", $this->plugin_name); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_invidLead">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_invidLead" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_leaderboard id="Your_Quiz_ID" from="Y-m-d H:i:s" to="Y-m-d H:i:s"]'>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_leadboard_count">
                                                <?php echo __('Users count',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                class="ays-text-input"                 
                                                id="ays_leadboard_count"
                                                value="5"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_leadboard_width">
                                                <?php echo __('Width',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                class="ays-text-input"                 
                                                id="ays_leadboard_width"
                                                value="500"
                                            />
                                            <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo __("For 100% leave blank", $this->plugin_name);?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Group users by',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="id" checked/>
                                                <span><?php echo __( "ID", $this->plugin_name); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="email"/>
                                                <span><?php echo __( "Email", $this->plugin_name); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Show user’s result',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="avg" checked/>
                                                <span><?php echo __( "AVG", $this->plugin_name); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="max"/>
                                                <span><?php echo __( "MAX", $this->plugin_name); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Show points',$this->plugin_name)?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Decide how to display the score. For instance, if you choose the correct answer count, the score will be shown in this format: 8/10.',$this->plugin_name); ?>">
                                                    <!-- <i class="ays_fa ays_fa_info_circle"></i> -->
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_quiz_loader">
                                                <input type="radio" checked />
                                                <span><?php echo __( "Without maximum point", $this->plugin_name); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" />
                                                <span><?php echo __( "With maximum point", $this->plugin_name); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Enable pagination", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('When this option is enabled, the data on the leaderboard will be displayed with pages. You can sort the data by leaderboard columns.',$this->plugin_name); ?>">
                                                    <!-- <i class="ays_fa ays_fa_info_circle"></i> -->
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" value="on" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Enable User Avatar", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By enabling this option, you can display the user avatar on the Front-end. Note: The Name field (Information Form option) must be enabled so that this option can work for you. If the Name table column is disabled, but the User Avatar option is enabled, the avatar will not be displayed on the front end. The user avatar will be displayed next to the name of the user.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_leadboard_color">
                                                <?php echo __('Color',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_leadboard_color" data-alpha="true" value="#99BB5A" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_leadboard_custom_css">
                                                <?php echo __('Custom CSS',$this->plugin_name)?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Field for entering your own CSS code',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle_test"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <textarea class="ays-textarea" id="ays_leadboard_custom_css" cols="30" rows="10" style="height: 80px;"></textarea>
                                        </div>
                                    </div> <!-- Custom leadboard CSS -->
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "Leaderboard Columns", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-show-user-page-table">
                                                    <?php    
                                                        foreach ($default_leadboard_column_names as $key => $val) {
                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="hidden" value="<?php echo $val; ?>" />
                                                                <input type="checkbox" id="ays_show_ind<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" checked/>
                                                                <label for="ays_show_ind<?php echo $val; ?>">
                                                                    <?php echo $val; ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                     ?>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-individual-leaderboard-shortcode" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-quiz-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                    </div>
                                    <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                        <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                                            <div class="ays-quiz-center-new-watch-video-demo-button">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                                <?php echo __("Watch Video", "quiz-maker"); ?>
                                            </div>
                                        </div>
                                        <div class="ays-quiz-center-big-upgrade-button-box">
                                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-individual-leaderboard-shortcode" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                                <div class="ays-quiz-center-new-big-upgrade-button">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                    <?php echo __("Upgrade", "quiz-maker"); ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Individual Leaderboard Settings -->
                        <hr>
                        <fieldset>
                            <legend style="margin-bottom: 0;">
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5 class="ays-subtitle"><?php echo __('Global Leaderboard Settings',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://www.youtube.com/watch?v=trZEpGWm9GE" target="_blank">
                                                <?php echo __("How to add leaderboard - video", $this->plugin_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                    <div class="pro_features pro_features_popup pro_features_background_bolder">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("How to set Leaderboards with WordPress Quiz Plugin", $this->plugin_name); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/I4rfyzf5D3E">
                                                <p>
                                                    <?php echo sprintf( __("The Quiz Maker plugin gives you the opportunity to add %s advanced Leaderboards %s to your WordPress website. Having Leaderboards on the website helps you %s stimulate competition %s and %s motivate quiz takers %s to improve their skills.", $this->plugin_name),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("Follow the steps mentioned in this step-by-step video tutorial to create your own Leaderboard. ", $this->plugin_name); ?>
                                                </p>
                                                <div>
                                                    <a href="https://quiz-plugin.com/global-leaderboard-2/" target="_blank"><?php echo __("See Demo", $this->plugin_name); ?></a>
                                                </div>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-global-leaderboard-shortcode">
                                                <?php echo __("Upgrade PRO NOW", $this->plugin_name); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_globLead">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_globLead" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_gleaderboard from="Y-m-d H:i:s" to="Y-m-d H:i:s"]'>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_count">
                                                <?php echo __('Users count',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                class="ays-text-input"                 
                                                id="ays_gleadboard_count"
                                                value="10"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_width">
                                                <?php echo __('Width',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                class="ays-text-input"                 
                                                id="ays_gleadboard_width"
                                                value="600"
                                            />
                                            <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo __("For 100% leave blank", $this->plugin_name);?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Users order by',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="id"/>
                                                <span><?php echo __( "ID", $this->plugin_name); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="email" checked/>
                                                <span><?php echo __( "Email", $this->plugin_name); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __('Show user’s result',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="avg"/>
                                                <span><?php echo __( "AVG", $this->plugin_name); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="max" checked/>
                                                <span><?php echo __( "MAX", $this->plugin_name); ?></span>
                                            </label>
                                            <label class="ays_quiz_loader">
                                                <input type="radio" value="sum"/>
                                                <span><?php echo __( "SUM", $this->plugin_name); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Enable pagination", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('When this option is enabled, the data on the leaderboard will be displayed with pages. You can sort the data by leaderboard columns.',$this->plugin_name); ?>">
                                                    <!-- <i class="ays_fa ays_fa_info_circle"></i> -->
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" value="on" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Enable User Avatar", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By enabling this option, you can display the user avatar on the Front-end. Note: The Name field (Information Form option) must be enabled so that this option can work for you. If the Name table column is disabled, but the User Avatar option is enabled, the avatar will not be displayed on the front end. The user avatar will be displayed next to the name of the user.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_color">
                                                <?php echo __('Color',$this->plugin_name)?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_gleadboard_color" data-alpha="true" value="#99BB5A" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_gleadboard_custom_css">
                                                <?php echo __('Custom CSS',$this->plugin_name)?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Field for entering your own CSS code',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle_aa"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <textarea class="ays-textarea" id="ays_gleadboard_custom_css" cols="30"
                                                  rows="10" style="height: 80px;"></textarea>
                                        </div>
                                    </div> <!-- Custom global leadboard CSS -->
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "Leaderboard Columns", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-show-user-page-table">
                                                    <?php    
                                                        foreach ($default_leadboard_column_names as $key => $val) {
                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="hidden" value="<?php echo $val; ?>" />
                                                                <input type="checkbox" id="ays_show_gl<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" checked/>
                                                                <label for="ays_show_gl<?php echo $val; ?>">
                                                                    <?php echo $val; ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                     ?>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-global-leaderboard-shortcode" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-quiz-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                    </div>
                                    <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                        <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                                            <div class="ays-quiz-center-new-watch-video-demo-button">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                                <?php echo __("Watch Video", "quiz-maker"); ?>
                                            </div>
                                        </div>
                                        <div class="ays-quiz-center-big-upgrade-button-box">
                                            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-global-leaderboard-shortcode" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                                <div class="ays-quiz-center-new-big-upgrade-button">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                    <?php echo __("Upgrade", "quiz-maker"); ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Global Leaderboard Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5 class="ays-subtitle"><?php echo __('Leaderboard By Quiz Category Settings',$this->plugin_name)?></h5>
                            </legend>
                            <blockquote>
                                <?php echo __( "It is designed for a particular quiz category results.", $this->plugin_name ); ?>
                            </blockquote>
                            <hr>
                            <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                <div class="pro_features pro_features_popup pro_features_background_bolder">
                                    <div class="pro-features-popup-conteiner">
                                        <div class="pro-features-popup-title">
                                            <?php echo __("How to set Leaderboards with WordPress Quiz Plugin", $this->plugin_name); ?>
                                        </div>
                                        <div class="pro-features-popup-content" data-link="https://youtu.be/I4rfyzf5D3E">
                                            <p>
                                                <?php echo sprintf( __("The Quiz Maker plugin gives you the opportunity to add %s advanced Leaderboards %s to your WordPress website. Having Leaderboards on the website helps you %s stimulate competition %s and %s motivate quiz takers %s to improve their skills.", $this->plugin_name),
                                                    "<strong>",
                                                    "</strong>",
                                                    "<strong>",
                                                    "</strong>",
                                                    "<strong>",
                                                    "</strong>"
                                                ); ?>
                                            </p>
                                            <p>
                                                <?php echo __("Follow the steps mentioned in this step-by-step video tutorial to create your own Leaderboard. ", $this->plugin_name); ?>
                                            </p>
                                            <div>
                                                <a href="https://quiz-plugin.com/leaderboard-by-quiz-category" target="_blank"><?php echo __("See Demo", $this->plugin_name); ?></a>
                                            </div>
                                        </div>
                                        <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-category-leaderboard-shortcode">
                                            <?php echo __("Upgrade PRO NOW", $this->plugin_name); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_globLead_cat">
                                            <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can copy the shortcode and paste it to any post/page to see the list of the top user’s who passed any quiz.',$this->plugin_name)?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="ays_globLead_cat" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_cat_gleaderboard id="Your_Quiz_Category_ID" from="Y-m-d H:i:s" to="Y-m-d H:i:s"]'>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_gleadboard_quiz_cat_count">
                                            <?php echo __('Users count',$this->plugin_name)?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('How many users’ results will be shown in the leaderboard.',$this->plugin_name)?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="number" class="ays-text-input" id="ays_gleadboard_quiz_cat_count" value="5"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_gleadboard_quiz_cat_width">
                                            <?php echo __('Width',$this->plugin_name)?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The width of the Leaderboard box. It accepts only numeric values. For 100% leave it blank.',$this->plugin_name)?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="number" class="ays-text-input" id="ays_gleadboard_quiz_cat_width" value="500"
                                        />
                                        <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo __("For 100% leave blank", $this->plugin_name);?></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __('Group users by',$this->plugin_name);?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Select the way for grouping the results. If you want to make Leaderboard for logged in users, then choose ID. It will collect results by WP user ID. If you want to make Leaderboard for guests, then you need to choose Email and enable Information Form and Email, Name options from quiz settings. It will group results by emails and display guests Names.',$this->plugin_name); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="ays_quiz_loader">
                                            <input type="radio" checked />
                                            <span><?php echo __( "ID", $this->plugin_name); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" />
                                            <span><?php echo __( "Email", $this->plugin_name); ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __('Show user’s result',$this->plugin_name);?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the users’ Average, Maximum or Sum results in the leaderboard. SUM does not work with Score(table column)',$this->plugin_name);?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="ays_quiz_loader">
                                            <input type="radio" checked />
                                            <span><?php echo __( "AVG", $this->plugin_name); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" />
                                            <span><?php echo __( "MAX", $this->plugin_name); ?></span>
                                        </label>
                                        <label class="ays_quiz_loader">
                                            <input type="radio" />
                                            <span><?php echo __( "SUM", $this->plugin_name); ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __( "Enable pagination", $this->plugin_name ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('When this option is enabled, the data on the leaderboard will be displayed with pages. You can sort the data by leaderboard columns.',$this->plugin_name); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-checkbox-input" value="on" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>
                                            <?php echo __( "Enable User Avatar", $this->plugin_name ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By enabling this option, you can display the user avatar on the Front-end. Note: The Name field (Information Form option) must be enabled so that this option can work for you. If the Name table column is disabled, but the User Avatar option is enabled, the avatar will not be displayed on the front end. The user avatar will be displayed next to the name of the user.',$this->plugin_name); ?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-checkbox-input">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_gleadboard_quiz_cat_color">
                                            <?php echo __('Color',$this->plugin_name);?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Top color of the leaderboard',$this->plugin_name);?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="ays_gleadboard_quiz_cat_color" data-alpha="true" value="#99BB5A" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_gleadboard_quiz_cat_custom_css">
                                            <?php echo __('Custom CSS',$this->plugin_name);?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Field for entering your own CSS code',$this->plugin_name);?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea class="ays-textarea" id="ays_gleadboard_quiz_cat_custom_css" cols="30"
                                              rows="10" style="height: 80px;"></textarea>
                                    </div>
                                </div> <!-- Custom global leadboard CSS -->
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label>
                                            <?php echo __( "Leaderboard Columns", $this->plugin_name ); ?>
                                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.',$this->plugin_name);?>">
                                                <i class="ays_fa ays_fa_info_circle"></i>
                                            </a>
                                        </label>
                                        <div class="ays-show-user-page-table-wrap">
                                            <ul class="ays-show-user-page-table">
                                                <?php
                                                    foreach ($default_leadboard_column_names as $key => $val) {
                                                        ?>
                                                        <li class="ays-user-page-option-row ui-state-default">
                                                            <input type="checkbox" class="ays-checkbox-input" checked/>
                                                            <label>
                                                                <?php echo $val; ?>
                                                            </label>
                                                        </li>
                                                        <?php
                                                    }
                                                 ?>
                                            </ul>
                                       </div>
                                    </div>
                                </div>
                                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-category-leaderboard-shortcode" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-new-watch-video-button-box">
                                    <div>
                                        <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                        <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                    </div>
                                    <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                </div>
                                <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                    <div class="ays-quiz-center-big-watch-video-button-box ays-quiz-big-upgrade-margin-right-10">
                                        <div class="ays-quiz-center-new-watch-video-demo-button">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                            <?php echo __("Watch Video", "quiz-maker"); ?>
                                        </div>
                                    </div>
                                    <div class="ays-quiz-center-big-upgrade-button-box">
                                        <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-category-leaderboard-shortcode" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-center-new-big-upgrade-button">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                <?php echo __("Upgrade", "quiz-maker"); ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Leaderboard By Quiz Category Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('User Page Settings',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features pro_features_popup pro_features_background_bolder">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("How to set User Page Settings Shortcode with WordPress Quiz Plugin", $this->plugin_name); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/YVp2KpsOAOc">
                                                <p>
                                                    <?php echo __("With the help of the WordPress Quiz plugin, you can display the current quiz taker's results history on the Front-end. By this, the users can understand what quizzes they passed and what score they got for each of those quizzes.", $this->plugin_name); ?>
                                                </p>
                                                <p>
                                                    <?php echo sprintf( __("Moreover, the users will get the chance to %s download/export their results %s (in PDF file format) and %s Certificates %s right from the Front-end.", $this->plugin_name),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo sprintf( __("Besides all these advanced features, you can %s collect data %s from quiz takers with the %s Custom Fields %s feature the plugin offers and increase your website traffic.", $this->plugin_name),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <div>
                                                    <a href="https://quiz-plugin.com/user-page-2" target="_blank"><?php echo __("See Demo", $this->plugin_name); ?></a>
                                                </div>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-user-page-shortcode">
                                                <?php echo __("Upgrade PRO NOW", $this->plugin_name); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_user_page">
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_user_page" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_user_page id="Your_Category_ID"]'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_hide_correct_answer_user_page">
                                                <?php echo __( "Hide correct answer", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox if you want to hide the correct answers presented in the detailed report.',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" value="on" >
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "User Page results table columns", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.',$this->plugin_name)?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-show-user-page-table">
                                                    <?php
                                                        foreach ($default_user_page_column_names as $key => $val) {
                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="hidden" value="<?php echo $val; ?>"/>
                                                                <input type="checkbox" id="ays_show_user_page_<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" checked/>
                                                                <label for="ays_show_user_page_<?php echo $val; ?>">
                                                                    <?php echo $val; ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                     ?>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-user-page-shortcode" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-quiz-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- User Page Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Flash Cards Settings',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12">
                                    <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                            <a href="https://www.youtube.com/watch?v=uBpzFjXyKC8" target="_blank">
                                                <?php echo __("How to create flashcards - video", $this->plugin_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 only_pro ays-quiz-margin-top-20" style="padding:20px;">
                                    <div class="pro_features pro_features_popup_only_hover" style="">

                                    </div>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label>
                                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Paste the shortcode into any of your posts/pages to create flashcards in a question-and-answer format. Each flashcard shows a question on one side and a correct answer on the other.',$this->plugin_name); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_flash_card by="quiz/category" id="ID(s)"]'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_flash_card_width">
                                                        <?php echo __( "Width", $this->plugin_name ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The width of the Flash Card. It accepts only numeric values. For 100% leave it blank.',$this->plugin_name); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="ays-text-input ays-quiz-flash-card-width" value=''>
                                                    <span style="display:block;" class="ays_quiz_small_hint_text"><?php echo __("For 100% leave blank", $this->plugin_name);?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_flash_card_color">
                                                        <?php echo __( "Background color", $this->plugin_name ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('The background color of the Flash Card.',$this->plugin_name); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="ays_quiz_flash_card_color" data-alpha="true" value="#ffffff">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12">
                                            <div class="form-group row ays_toggle_parent">
                                                <div class="col-sm-4">
                                                    <label for="ays_enable_fc_introduction">
                                                        <?php echo __( "Introduction page", $this->plugin_name ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox to add a Start page to your Flashcards. You can customize the Start page and write your preferred texts in WP Editor.',$this->plugin_name); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-1">
                                                    <input type="checkbox" class="ays_toggle_checkbox">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_flash_card_randomize">
                                                        <?php echo __( "Randomize Flash Cards", $this->plugin_name ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Display the flashcard questions in random order.',$this->plugin_name); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="checkbox" id="ays_quiz_flash_card_randomize" class="ays-quiz-flash-card-randomize" value='on'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <blockquote>
                                        <ul class="ays-quiz-general-settings-blockquote-ul">
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s By %s', $this->plugin_name ) . ' - ' . __( 'Choose the method of filtering. Example: by="quiz"', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                                <ul class='ays-quiz-general-settings-ul'>
                                                    <li>
                                                        <?php
                                                            echo sprintf(
                                                                __( '%s quiz %s', $this->plugin_name ) . ' - ' . __( 'If you set the method as Quiz, it will show all questions added in the given quiz.', $this->plugin_name ),
                                                                '<b>',
                                                                '</b>'
                                                            );
                                                        ?>
                                                    </li>
                                                    <li>
                                                        <?php
                                                            echo sprintf(
                                                                __( '%s category %s', $this->plugin_name ) . ' - ' . __( 'If you set the method as Category, it will show all questions assigned to the given category.', $this->plugin_name ),
                                                                '<b>',
                                                                '</b>'
                                                            );
                                                        ?>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s ID %s', $this->plugin_name ) . ' - ' . __( 'Select a single ID or multiple IDs. List multiple IDs by separating them with commas. Example id="13,23,33"', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <a href="https://quiz-plugin.com/flash-cards" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-view-demo-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/view-demo.svg'?>">
                                            </div>
                                            <div class="ays-quiz-new-view-demo-button"><?php echo __("View Demo", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    
                                    <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                        <div class="ays-quiz-center-big-view-demo-button-box ays-quiz-big-upgrade-margin-right-10">
                                            <a href="https://quiz-plugin.com/flash-cards" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                                <div class="ays-quiz-center-new-big-view-demo-button">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/view-demo.svg'?>">
                                                    <?php echo __("View Demo", "quiz-maker"); ?>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="ays-quiz-center-big-upgrade-button-box">
                                            <a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                                <div class="ays-quiz-center-new-big-upgrade-button">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                    <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                    <?php echo __("Upgrade", "quiz-maker"); ?>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </fieldset> <!-- Flash Cards Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Recent Quizzes Settings',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features pro_features_popup pro_features_background_bolder">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("How to set Recent Quizzes Settings shortcode with WordPress Quiz Plugin", $this->plugin_name); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/Eg7wzKTSsEA">
                                                <p>
                                                    <?php echo sprintf( __("With the help of the Recent Quizzes Settings shortcode, you can display the %s recently created quizzes %s on the Front-end. The shortcode has two ordering options: %s By random or By recent. %s", $this->plugin_name),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo sprintf( __("With %s random ordering %s, you can display your chosen amount of quizzes of all the quizzes you have created. With %s recent ordering %s, you can display your chosen amount of quizzes of your recently-created quizzes.", $this->plugin_name),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo sprintf( __("The shortcode will help to %s boost user engagement %s and provide your website visitors with fresh and %s up-to-date content. %s", $this->plugin_name),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <div>
                                                    <a href="https://quiz-plugin.com/recent-quizzes" target="_blank"><?php echo __("See Demo", $this->plugin_name); ?></a>
                                                </div>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-recent-quizzes-shortcode">
                                                <?php echo __("Upgrade PRO NOW", $this->plugin_name); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" data-html="true"
                                                    title="<?php
                                                        echo __('Copy the following shortcode, configure it based on your preferences and paste it into the post.',$this->plugin_name) .
                                                        "<ul style='list-style-type: circle;padding-left: 20px;'>".
                                                            "<li>". __('Random - If you set the ordering method as random and gave a value to count option, then it will randomly display that given amount of quizzes from your created quizzes.',$this->plugin_name) ."</li>".
                                                            "<li>". __('Recent - If you set the ordering method as recent and gave a value to count option, then it will display that given amount of quizzes from your recently created quizzes.',$this->plugin_name) ."</li>".
                                                        "</ul>";
                                                    ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_display_quizzes orderby="random/recent" count="5"]'>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-recent-quizzes-shortcode" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-quiz-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Recent Quizzes Settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Display the sum of the quiz points',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features pro_features_popup pro_features_background_bolder">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("How to set the Display the sum of the quiz points shortcode with WordPress Quiz Plugin", $this->plugin_name); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/ZbP1V2UXD-o">
                                                <p>
                                                    <?php echo sprintf( __("With this shortcode, you can display the %s sum of the points %s for both one quiz and several quizzes. There are two possible ways to sum the points: %s By All and By Best %s", $this->plugin_name),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo sprintf( __("By choosing the All mode, the shortcode will display the %s sum of all the user's points. %s By choosing the Best mode, the shortcode will display the %s sum of all the maximum points of the user. %s", $this->plugin_name),
                                                        "<strong>",
                                                        "</strong>",
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("Use this shortcode to assess the overall performance of the quiz.", $this->plugin_name); ?>
                                                </p>
                                                <div>
                                                    <a href="https://ays-pro.com/wordpress-quiz-maker-user-manual" target="_blank"><?php echo __("See Documentation", $this->plugin_name); ?></a>
                                                </div>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-sum-quiz-points-shortcode">
                                                <?php echo __("Upgrade PRO NOW", $this->plugin_name); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_display_questions">
                                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste into any post.  Insert the IDs of the Quizzes to receive the sum of the quiz points.',$this->plugin_name); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="ays_quiz_points_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_points_count id="Your_Quiz_ID(s)" mode="all/best"]'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <blockquote>
                                        <ul class="ays-quiz-general-settings-blockquote-ul">
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s ID %s', $this->plugin_name ) . ' - ' . __( 'Select the ID of the quiz. You can write more than one ID.', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( '%s Mode %s', $this->plugin_name ) . ' - ' . __( 'Choose the way to sum the points. Example: mode="all".', $this->plugin_name ),
                                                        '<b>',
                                                        '</b>'
                                                    );
                                                ?>
                                                <ul class='ays-quiz-general-settings-ul'>
                                                    <li>
                                                        <?php
                                                            echo sprintf(
                                                                __( '%s All %s', $this->plugin_name ) . ' - ' . __( "It will display the sum of all the user's points.", $this->plugin_name ),
                                                                '<b>',
                                                                '</b>'
                                                            );
                                                        ?>
                                                    </li>
                                                    <li>
                                                        <?php
                                                            echo sprintf(
                                                                __( '%s Best %s', $this->plugin_name ) . ' - ' . __( ' It will display the sum of all the maximum points of the user.', $this->plugin_name ),
                                                                '<b>',
                                                                '</b>'
                                                            );
                                                        ?>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-sum-quiz-points-shortcode" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-quiz-new-watch-video-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Display the sum of the quiz points -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Show Quiz Orders',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features pro_features_popup pro_features_background_bolder">
                                        <div class="pro-features-popup-conteiner">
                                            <div class="pro-features-popup-title">
                                                <?php echo __("How to set Show Quiz Orders shortcode with WordPress Quiz Plugin", $this->plugin_name); ?>
                                            </div>
                                            <div class="pro-features-popup-content" data-link="https://youtu.be/kbgfDnJ5t_o">
                                                <p>
                                                    <?php echo sprintf( __("Use this shortcode to display the %s payment history of the current user %s on the Front-end. The shortcode displays the Quiz Name, Payment Date, the Amount for the Quiz, and the Payment Type (PayPal or Stripe) Table Columns in one place.", $this->plugin_name),
                                                        "<strong>",
                                                        "</strong>"
                                                    ); ?>
                                                </p>
                                                <p>
                                                    <?php echo __("So, the quiz takers can see what quizzes they passed and made a purchase for and the amount they paid at once. ", $this->plugin_name); ?>
                                                </p>
                                                <div>
                                                    <a href="https://ays-pro.com/wordpress-quiz-maker-user-manual" target="_blank"><?php echo __("See Documentation", $this->plugin_name); ?></a>
                                                </div>
                                            </div>
                                            <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-orders-shortcode">
                                                <?php echo __("Upgrade PRO NOW", $this->plugin_name); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_all_results">
                                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can copy the shortcode and insert it into any post or page and display the Quiz Name, Payment Date, Amount and Type',$this->plugin_name); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="ays_quiz_all_results" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_paid_quizzes]'>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label>
                                                        <?php echo __( "Table columns", $this->plugin_name ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can sort table columns and select which columns must display on the front-end.',$this->plugin_name); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                    <div class="ays-show-user-page-table-wrap">
                                                        <ul class="ays-show-user-page-table">
                                                            <?php
                                                                foreach ($quiz_all_orders_columns_order as $key => $val) {
                                                                    $checked = 'checked';

                                                                    $default_all_orders_column_names_label = '';
                                                                    if( isset( $default_all_orders_columns_names[$val] ) && $default_all_orders_columns_names[$val] != '' ){
                                                                        $default_all_orders_column_names_label = $default_all_orders_columns_names[$val];
                                                                    }

                                                                    if( $default_all_orders_column_names_label == '' ){
                                                                        continue;
                                                                    }

                                                                    ?>
                                                                    <li class="ays-user-page-option-row ui-state-default">
                                                                        <input type="hidden" value="<?php echo $val; ?>"/>
                                                                        <input type="checkbox" id="ays_show_order<?php echo $val; ?>" value="<?php echo $val; ?>" class="ays-checkbox-input" <?php echo $checked; ?>/>
                                                                        <label for="ays_show_order<?php echo $val; ?>">
                                                                            <?php echo $default_all_orders_column_names_label; ?>
                                                                        </label>
                                                                    </li>
                                                                    <?php
                                                                }
                                                             ?>
                                                        </ul>
                                                   </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-quiz-orders-shortcode" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box ays-quiz-new-upgrade-button-box-mobile-style">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade to Developer/Agency", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                    <div class="ays-quiz-new-watch-video-button-box ays-quiz-new-watch-video-button-box-mobile-style">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/video_24x24_hover.svg'?>" class="ays-quiz-new-watch-video-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-watch-video-button"><?php echo __("Watch Video", "quiz-maker"); ?></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Show Quiz Orders -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Quiz multilanguage',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="">

                                    </div>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_all_results">
                                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Write your desired text in any WordPress language. It will be translated in the front-end. The languages must be included in the ISO 639-1 Code column.',$this->plugin_name); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="ays_quiz_multilanugage_shortcode" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[:en]Hello[:es]Hola[:]'>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                    <blockquote>
                                        <ul class="ays-quiz-general-settings-blockquote-ul">
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( "In this shortcode you can add your desired text and its translation. The translated version of the text will be displayed in the front-end. The languages must be written in the %sLanguage Code%s", $this->plugin_name ),
                                                        '<a href="https://www.loc.gov/standards/iso639-2/php/code_list.php" target="_blank">',
                                                        '</a>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </blockquote>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Quiz multilanguage -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Quiz intervals chart',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12 only_pro" style="padding:20px;">
                                    <div class="pro_features" style="">

                                    </div>
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_quiz_interval_chart">
                                                        <?php echo __( "Shortcode", $this->plugin_name ); ?>
                                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You can copy the shortcode and paste it into your desired page/post to display a chart based on keywords on the Front-end. Don't forget to change YOUR_QUIZ_ID with the corresponding Quiz ID.",$this->plugin_name) ); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="ays_quiz_interval_chart" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_interval_chart id="Your_Quiz_ID"]'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                        <div class="ays-quiz-new-upgrade-button-box">
                                            <div>
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                            </div>
                                            <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </fieldset> <!-- Quiz multilanguage -->
                        <?php
                            if(has_action('ays_qm_settings_page_integrations')){
                                echo "<hr/>";
                                do_action( 'ays_qm_settings_page_integrations' );
                            }

                            if(has_action('ays_qm_settings_page_extra_shortcodes')){
                                echo "<hr/>";
                                do_action( 'ays_qm_settings_page_extra_shortcodes' );
                            }

                            if(has_action('ays_qm_advanced_user_dashboard')){
                                echo "<hr/>";
                                do_action( 'ays_qm_advanced_user_dashboard' );
                            }
                        ?>
                    </div>
                    <div id="tab4" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab4') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Message variables',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<p style='margin-bottom:3px;'><?php echo __( 'You can copy these variables and paste them in the following options from the quiz settings', $this->plugin_name ); ?>:</p>
                                <p style='padding-left:10px;margin:0;'>- <?php echo __( 'Result message', $this->plugin_name ); ?></p>
                                <p style='padding-left:10px;margin:0;'>- <?php echo __( 'Quiz pass message', $this->plugin_name ); ?></p>
                                <p style='padding-left:10px;margin:0;'>- <?php echo __( 'Quiz fail message', $this->plugin_name ); ?></p>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <blockquote>
                            <p><?php echo __( "You can copy these variables and paste them in the following options from the quiz settings", $this->plugin_name ); ?>:</p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Result message", $this->plugin_name ); ?></p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Quiz pass message", $this->plugin_name ); ?></p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Quiz fail message", $this->plugin_name ); ?></p>
                        </blockquote>
                        <hr class="ays-quiz-bolder-hr">
                        <div class="form-group row">
                            <div class="col-sm-12"> 
                                <div class="ays-quiz-heading-box ays-quiz-unset-float ays-quiz-unset-margin">
                                    <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
                                        <a href="https://www.youtube.com/watch?v=nzQEHzmUBc8" target="_blank">
                                            <?php echo __("How message variables works - video", $this->plugin_name); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('General Message Variables',$this->plugin_name); ?></h5>
                            </legend>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_date%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The date of the passing quiz", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%home_page_url%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "The URL of the home page.", $this->plugin_name) ); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%admin_email%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "Shows the admin's email that was filled in their WordPress profile.", $this->plugin_name) ); ?>
                                </span>
                            </p>
                        </fieldset> <!-- General Message Variables -->
                        <hr />
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('User Message Variables',$this->plugin_name); ?></h5>
                            </legend>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_name%%"/>
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The name the user entered into information form", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_email%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The E-mail the user entered into information form", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_phone%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The phone the user entered into information form", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_first_name%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's first name that was filled in their WordPress site during registration.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_last_name%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's last name that was filled in their WordPress site during registration.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_nickname%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's nickname that was filled in their WordPress profile.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_display_name%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's display name that was filled in their WordPress profile.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_wordpress_email%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's email that was filled in their WordPress profile.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_wordpress_roles%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's role(s) when logged-in. In case the user is not logged-in, the field will be empty.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_wordpress_website%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The user's website that was filled in their WordPress profile.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_pass_time%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The time which spent that the user passed the quiz", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_corrects_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The number of correct answers of the user", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%wrong_answers_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The number of wrong answers of the user.", $this->plugin_name) ." ". __( "(skipped questions are included)", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%only_wrong_answers_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The number of only wrong answers of the user.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%skipped_questions_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The count of unanswered questions of the user.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%answered_questions_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The count of answered questions of the user.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_id%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "The ID of the current user.", $this->plugin_name) ); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_user_ip%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "Shows the current user's IP no matter whether they are a logged-in user or a guest. Please note, that this message variable will return empty, if 'Do not store IP addresses' is ticked from General Settings>General>Users IP addresses.", $this->plugin_name) ); ?>
                                </span>
                            </p>
                        </fieldset> <!-- User Message Variables -->
                        <hr />
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Score Message Variables',$this->plugin_name); ?></h5>
                            </legend>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%score%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The score of quiz which got the user", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_score%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The average score of the quiz of all time", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%score_by_answered_questions%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The score of those questions which the given user answered(%). Skipped or unanswered questions will not be included in the calculation.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%results_by_cats%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The score of the quiz by a question categories which got the user", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_score_by_category%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The average score by the question category of the given quiz of the given user.", $this->plugin_name); ?>
                                </span>
                            </p>
                        </fieldset> <!-- Score Message Variables -->
                        <hr />
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Quiz Message Variables',$this->plugin_name); ?></h5>
                            </legend>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_name%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The title of the quiz", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_id%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "The ID of the quiz.", $this->plugin_name) ); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_rate%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The average rate of the quiz of all time", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_time%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The time which must spend the user to the quiz", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%questions_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The number of questions that the user must pass.", $this->plugin_name); ?>
                                </span>
                            </p>
                            
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_creation_date%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "The exact date/time of the quiz creation.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_quiz_author%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "It will show the author of the current quiz.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_quiz_page_link%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "Prints the webpage link where the current quiz is posted.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_quiz_author_email%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "Shows the current quiz author's email that was filled in their WordPress profile.", $this->plugin_name) ); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_quiz_author_nickname%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "It will show the author nickname of the current quiz.", $this->plugin_name); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%result_id%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo esc_attr( __( "The current user result ID.", $this->plugin_name) ); ?>
                                </span>
                            </p>
                            <p class="vmessage">
                                <strong>
                                    <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%current_quiz_question_categories_count%%" />
                                </strong>
                                <span> - </span>
                                <span style="font-size:18px;">
                                    <?php echo __( "It will display the number of question categories displayed in the front end.", $this->plugin_name); ?>
                                </span>
                            </p>
                        </fieldset> <!-- Quiz Message Variables -->
                        <hr />
                        <div class="form-group row">
                            <div class="col-sm-12 only_pro" style="padding-top: 10px;;">
                                <div class="pro_features pro_features_popup_only_hover" style="">

                                </div>
                            
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_points%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The points of quiz which got the user", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%max_points%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "Maximum points which can get the user", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_logo%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The quiz image which used for quiz start page", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%interval_message%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The message which must display on the result page depending from score", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <!-- ///// -->
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%unique_code%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "You can use this unique code as an identifier. It is unique for every attempt.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%download_certificate%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "You can use this variable to allow users to download their certificate after quiz completion.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%keyword_count_{keyword}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The count of the selected keyword that the user answers during the quiz. For instance, %%keyword_count_A%%.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%keyword_percentage_{keyword}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The percentage of the selected keyword that the user answers during the quiz. For instance, %%keyword_percentage_A%%.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%top_keywords_count_{count}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "Top keywords of answers selected by the user during the quiz. Each keyword will be displayed with the count of selected keywords. For instance, %%top_keywords_count_3%%.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%top_keywords_percentage_{count}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "Top keywords of answers selected by the user during the quiz. Each keyword will be displayed with the percentage of selected keywords. For instance, %%top_keywords_percentage_3%%.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%quiz_coupon%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "You can use this message variable for showing coupons to your users. This message variable won't work unless you enable the Enable quiz coupons option.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_keyword_point_{keyword}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the total count of the keyword. For instance, %%user_keyword_point_A%%.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%max_point_keyword_{keyword}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It displays the maximum point of the keywords. For instance, %%max_point_keyword_A%%.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_keyword_percentage_{keyword}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the percentage of the chosen keyword from the maximum.For instance, %%user_keyword_percentage_A%%.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_user_points%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the average score of the user.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_res_by_cats%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the average rate of the Question Category (for example: Copywriting: 2.7/5 ...).", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%score_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The score of quiz which got the user displayed as a bar chart. Note: This message variable will not work in the email fields.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_points_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The points of quiz which got the user displayed as a bar chart. Note: This message variable will not work in the email fields.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_pass_time_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The time which must spend the user to the quiz displayed as a bar chart. Note: This message variable will only work if Quiz Timer option is set, and will not work in the email fields.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%results_by_cats_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The score of the quiz by a question categories which got the user displayed as a bar chart. Note: This message variable will not work in the email fields.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%results_by_tags_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The score the user got for the quiz by question tags displayed as a bar chart. Note: This message variable will not work in the email fields.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%detailed_result_column_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "Detailed result of answered questions of the user displayed as a column chart. Note: This message variable will not work in the email fields.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%user_corrects_count_pie_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "The count of answered questions of the user displayed as a pie chart. Note: This message variable will not work in the email fields.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%avg_res_by_cats_bar_chart%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the average rate of the Question Category as a bar chart. Note: This message variable will not work in the email fields.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <p class="vmessage">
                                    <strong>
                                        <input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="%%personality_result_by_question_ids_{CatID_1,CatID_2,CatID_3,CatID_4}%%" />
                                    </strong>
                                    <span> - </span>
                                    <span style="font-size:18px;">
                                        <?php echo __( "It will display the Question Category title and the description. Moreover, it displays in which percentage you match the particular Question Category Keyword. The message variable is designed to create Myers Personality Test. For instance, %%personality_result_by_question_ids_3,5,16,2%%.", $this->plugin_name); ?>
                                    </span>
                                </p>
                                <a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                    <div class="ays-quiz-new-upgrade-button-box">
                                        <div>
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>">
                                            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">
                                        </div>
                                        <div class="ays-quiz-new-upgrade-button"><?php echo __("Upgrade", "quiz-maker"); ?></div>
                                    </div>
                                </a>
                                <div class="ays-quiz-center-big-main-button-box ays-quiz-new-big-button-flex">
                                    <div class="ays-quiz-center-big-upgrade-button-box">
                                        <a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-new-upgrade-button-link">
                                            <div class="ays-quiz-center-new-big-upgrade-button">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/locked_24x24.svg'?>" class="ays-quiz-new-button-img-hide">
                                                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/icons/unlocked_24x24.svg'?>" class="ays-quiz-new-upgrade-button-hover">  
                                                <?php echo __("Upgrade", "quiz-maker"); ?>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab5" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab5') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Buttons texts',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<p style='margin-bottom:3px;'><?php echo __( 'If you make a change here, these words will not be translated either․', $this->plugin_name ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <blockquote>
                            <p>
                                <?php echo __( "You can change the buttons' texts and write the words you prefer for them.", $this->plugin_name ); ?>
                                <span class="ays-quiz-blockquote-span"><?php echo __( "Please note, that if you change the default texts, these words will not be translated with Translation plugins or the Poedit app.", $this->plugin_name ); ?></span>
                            </p>
                        </blockquote>
                        <hr class="ays-quiz-bolder-hr">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_start_button">
                                    <?php echo __( "Start button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_start_button" name="ays_start_button" class="ays-text-input ays-text-input-short"  value='<?php echo $start_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_next_button">
                                    <?php echo __( "Next button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_next_button" name="ays_next_button" class="ays-text-input ays-text-input-short"  value='<?php echo $next_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_previous_button">
                                    <?php echo __( "Previous button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_previous_button" name="ays_previous_button" class="ays-text-input ays-text-input-short"  value='<?php echo $previous_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_clear_button">
                                    <?php echo __( "Clear button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_clear_button" name="ays_clear_button" class="ays-text-input ays-text-input-short"  value='<?php echo $clear_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_finish_button">
                                    <?php echo __( "Finish button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_finish_button" name="ays_finish_button" class="ays-text-input ays-text-input-short"  value='<?php echo $finish_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_see_result_button">
                                    <?php echo __( "See Result button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_see_result_button" name="ays_see_result_button" class="ays-text-input ays-text-input-short"  value='<?php echo $see_result_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_restart_quiz_button">
                                    <?php echo __( "Restart quiz button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_restart_quiz_button" name="ays_restart_quiz_button" class="ays-text-input ays-text-input-short"  value='<?php echo $restart_quiz_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_send_feedback_button">
                                    <?php echo __( "Send feedback button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_send_feedback_button" name="ays_send_feedback_button" class="ays-text-input ays-text-input-short"  value='<?php echo $send_feedback_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_load_more_button">
                                    <?php echo __( "Load more button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_load_more_button" name="ays_load_more_button" class="ays-text-input ays-text-input-short"  value='<?php echo $load_more_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_exit_button">
                                    <?php echo __( "Exit button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_exit_button" name="ays_exit_button" class="ays-text-input ays-text-input-short"  value='<?php echo $exit_button ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_check_button">
                                    <?php echo __( "Check button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_check_button" name="ays_check_button" class="ays-text-input ays-text-input-short"  value='<?php echo $check_button; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_login_button">
                                    <?php echo __( "Log In button", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_login_button" name="ays_login_button" class="ays-text-input ays-text-input-short"  value='<?php echo $login_button; ?>'>
                            </div>
                        </div>
                    </div>
                    <div id="tab6" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab6') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Fields texts',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<p style='margin-bottom:3px;'><?php echo __( 'If you make a change here, these words will not be translated either.', $this->plugin_name ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <blockquote>
                            <p>
                                <?php echo __( "With the help of this section, you can change the fields' placeholders and labels of the Information form. Find the available fields in the User data tab of your quizzes.", $this->plugin_name ); ?>
                                <span class="ays-quiz-blockquote-span"><?php echo __( "Please note, that if you change the default texts, these words will not be translated with Translation plugins or the Poedit app.", $this->plugin_name ); ?></span>
                            </p>
                        </blockquote>
                        <hr class="ays-quiz-bolder-hr">
                        <div class="form-group row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4">
                                <span><?php echo __( "Placeholders", $this->plugin_name ); ?></span>
                            </div>
                            <div class="col-sm-4">
                                <span><?php echo __( "Labels", $this->plugin_name ); ?></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="ays_quiz_fields_placeholder_name">
                                    <?php echo __( "Name", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_placeholder_name" name="ays_quiz_fields_placeholder_name" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_placeholder_name; ?>'>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_label_name" name="ays_quiz_fields_label_name" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_label_name; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="ays_quiz_fields_placeholder_eamil">
                                    <?php echo __( "Email", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_placeholder_eamil" name="ays_quiz_fields_placeholder_eamil" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_placeholder_eamil; ?>'>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_label_eamil" name="ays_quiz_fields_label_eamil" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_label_eamil; ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="ays_quiz_fields_placeholder_phone">
                                    <?php echo __( "Phone", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_placeholder_phone" name="ays_quiz_fields_placeholder_phone" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_placeholder_phone; ?>'>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ays_quiz_fields_label_phone" name="ays_quiz_fields_label_phone" class="ays-text-input ays-text-input-short"  value='<?php echo $quiz_fields_label_phone; ?>'>
                            </div>
                        </div>
                    </div>
                    <div id="tab7" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab7') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Shortcodes',$this->plugin_name)?></p>
                        <hr class="ays-quiz-bolder-hr">
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Extra shortcodes',$this->plugin_name); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_avg_score">
                                                <?php echo __( "Average score", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the given shortcode and paste it in posts. Insert the Quiz ID  to see the average score of participants of that quiz.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_avg_score" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_avg_score id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_passed_users_count">
                                                <?php echo __( "Passed users count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste it in posts. Insert the Quiz ID to receive the number of participants of the quiz.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_passed_users_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_passed_users_count id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_passed_users_count_by_score">
                                                <?php echo __( "Passed users count by score", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste it into posts. Insert the Quiz ID to receive the number of passed users of the quiz. The pass score has to be determined in the Quiz Settings.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_passed_users_count_by_score" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_passed_users_count_by_score id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_failed_users_count_by_score">
                                                <?php echo __( "Failed users count by score", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste it into posts. Insert the Quiz ID to receive the number of failed users of the quiz. The pass score has to be determined in the Quiz Settings.',$this->plugin_name); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_failed_users_count_by_score" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_failed_users_count_by_score id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_unread_results_count">
                                                <?php echo __( "Show quiz unread results count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the unread results count of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_unread_results_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_unread_results_count id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_read_results_count">
                                                <?php echo __( "Show quiz read results count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the read results count of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_read_results_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_read_results_count id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_passed_quizzes_count">
                                                <?php echo __( "Passed quizzes count per user", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the number of passed quizzes of the current user. For instance, the current user has passed 20 quizzes. If the user is not logged in shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_passed_quizzes_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_passed_quizzes_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_all_passed_quizzes_count">
                                                <?php echo __( "All passed quizzes count per user", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the total sum of how many times the particular user has passed all the quizzes. For instance, the current user has passed 20 quizzes 500 times in total. If the user is not logged in shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_all_passed_quizzes_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_all_passed_quizzes_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_first_name">
                                                <?php echo __( "Show User First Name", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's First Name. If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_first_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_first_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_last_name">
                                                <?php echo __( "Show User Last Name", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's Last Name. If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_last_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_last_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_nickname">
                                                <?php echo __( "Show User Nickname", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's Nickname. If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_nickname" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_nickname]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_display_name">
                                                <?php echo __( "Show User Display name", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's Display name. If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_display_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_display_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_email">
                                                <?php echo __( "Show User Email", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's Email. If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_email" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_email]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_roles">
                                                <?php echo __( "Show user roles", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's role(s). If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_roles" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_roles]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_website">
                                                <?php echo __( "Show user website", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's website. If the user is not logged-in, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_website" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_website]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_user_duration">
                                                <?php echo __( "Show user quiz duration", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total time the user spent to pass quizzes. It includes all the quizzes in the user history.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_user_duration" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_user_duration]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_creation_date">
                                                <?php echo __( "Show quiz creation date", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the creation date of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_creation_date" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_creation_date id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_current_author">
                                                <?php echo __( "Show current quiz author", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the current author of the particular quiz. If there is no quiz or questions available/found with that particular Quiz ID, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_current_author" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_current_author id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_questions_count">
                                                <?php echo __( "Show quiz questions count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the questions count of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_questions_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_questions_count id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_category_title">
                                                <?php echo __( "Show quiz category title", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the cateogry title of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_category_title" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_category_title id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_category_description">
                                                <?php echo __( "Show quiz category description", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Quiz ID in the shortcode. It will show the cateogry description of the particular quiz. If there is no quiz available/found with that particular Quiz ID, the shortcode will be empty.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_category_description" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_category_description id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_quizzes_count">
                                                <?php echo __( "Show quizzes count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total count of quizzes.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_quizzes_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_quizzes_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_categories_count">
                                                <?php echo __( "Show quiz categories count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total count of quiz categories.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_categories_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_categories_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_all_results_count">
                                                <?php echo __( "Show quizzes total results count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total results count of all quizzes of all users.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_all_results_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_all_results_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_question_categories_count">
                                                <?php echo __( "Show question categories count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total count of question categories.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_question_categories_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_question_categories_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_all_questions_count">
                                                <?php echo __( "Show questions total count", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Put this shortcode on a page to show the total count of questions.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_all_questions_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_all_questions_count]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_quiz_avg_rate">
                                                <?php echo __( "Show quiz average rate", $this->plugin_name ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Replace Your_Quiz_ID with the corresponding Quiz ID to show the Average Rate for the quiz.",$this->plugin_name) ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_quiz_avg_rate" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_quiz_avg_rate id="Your_Quiz_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Extra shortcodes -->
                    </div>
                    <div id="tab8" class="ays-quiz-tab-content <?php echo ($ays_quiz_tab == 'tab8') ? 'ays-quiz-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('User Information',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose what user information you want to be displayed. Tick on the needed options for making them visible in the detailed result. Note that the information will be available in the exported version. So, even if the option is hided, it will be displayed in the exported PDF and XLSX files.', $this->plugin_name); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <hr class="ays-quiz-bolder-hr">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user_ip">
                                    <?php echo __( "Show User IP", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user_ip" name="ays_quiz_show_result_info_user_ip" value="on" <?php echo $ays_quiz_show_result_info_user_ip ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user_id">
                                    <?php echo __( "Show User ID", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user_id" name="ays_quiz_show_result_info_user_id" value="on" <?php echo $ays_quiz_show_result_info_user_id ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user">
                                    <?php echo __( "Show User", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user" name="ays_quiz_show_result_info_user" value="on" <?php echo $ays_quiz_show_result_info_user ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user_email">
                                    <?php echo __( "Show User Email", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user_email" name="ays_quiz_show_result_info_user_email" value="on" <?php echo $ays_quiz_show_result_info_user_email ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user_name">
                                    <?php echo __( "Show User Name", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user_name" name="ays_quiz_show_result_info_user_name" value="on" <?php echo $ays_quiz_show_result_info_user_name ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_user_phone">
                                    <?php echo __( "Show User Phone", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_user_phone" name="ays_quiz_show_result_info_user_phone" value="on" <?php echo $ays_quiz_show_result_info_user_phone ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <hr>
                        <p class="ays-subtitle">
                            <?php echo __('Quiz Information',$this->plugin_name)?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose what Quiz information you want to be displayed. Tick on the needed options for making them visible in the detailed result. Note that the information will be available in the exported version. So, even if the option is hided, it will be displayed in the exported PDF and XLSX files.', $this->plugin_name); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <hr class="ays-quiz-bolder-hr">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_start_date">
                                    <?php echo __( "Show Start date", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_start_date" name="ays_quiz_show_result_info_start_date" value="on" <?php echo $ays_quiz_show_result_info_start_date ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_duration">
                                    <?php echo __( "Show Duration", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_duration" name="ays_quiz_show_result_info_duration" value="on" <?php echo $ays_quiz_show_result_info_duration ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_score">
                                    <?php echo __( "Show Score", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_score" name="ays_quiz_show_result_info_score" value="on" <?php echo $ays_quiz_show_result_info_score ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_quiz_show_result_info_rate">
                                    <?php echo __( "Show Rate", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="checkbox" class="ays-checkbox-input" id="ays_quiz_show_result_info_rate" name="ays_quiz_show_result_info_rate" value="on" <?php echo $ays_quiz_show_result_info_rate ? 'checked' : ''; ?> />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div style="position:sticky;padding:15px 0px;bottom:0;">
            <?php
                wp_nonce_field('settings_action', 'settings_action');

                $other_attributes = array(
                    'title' => 'Ctrl + s',
                    'data-toggle' => 'tooltip',
                    'data-delay'=> '{"show":"1000"}'
                );
                
                submit_button(__('Save changes', $this->plugin_name), 'primary ays-quiz-loader-banner', 'ays_submit', true, $other_attributes);
                echo $loader_iamge;
            ?>
            </div>
        </form>

        <div class="ays-modal" id="pro-features-popup-modal">
            <div class="ays-modal-content">
                <!-- Modal Header -->
                <div class="ays-modal-header">
                    <span class="ays-close-pro-popup">&times;</span>
                    <!-- <h2></h2> -->
                </div>

                <!-- Modal body -->
                <div class="ays-modal-body">
                   <div class="row">
                        <div class="col-sm-6 pro-features-popup-modal-left-section">
                        </div>
                        <div class="col-sm-6 pro-features-popup-modal-right-section">
                           <div class="pro-features-popup-modal-right-box">
                                <div class="pro-features-popup-modal-right-box-icon"><i class="ays_fa ays_fa_lock"></i></div>

                                <div class="pro-features-popup-modal-right-box-title"></div>

                                <div class="pro-features-popup-modal-right-box-content"></div>

                                <div class="pro-features-popup-modal-right-box-button">
                                    <a href="#" class="pro-features-popup-modal-right-box-link" target="_blank"></a>
                                </div>
                           </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="ays-modal-footer" style="display:none">
                </div>
            </div>
        </div>

    </div>
</div>
