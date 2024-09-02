<?php    
$action = ( isset($_GET['action']) ) ? sanitize_text_field( $_GET['action'] ) : '';
$id     = ( isset($_GET['quiz']) ) ? sanitize_text_field( $_GET['quiz'] ) : null;

if($action == 'duplicate'){
    $this->quizes_obj->duplicate_quizzes($id);
}
$max_id = $this->get_max_id('questions');
$quiz_max_id = $this->get_max_id('quizes');
$user_id = get_current_user_id();

$gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode(stripcslashes($this->settings_obj->ays_get_setting('options')), true);

$question_default_type = isset($gen_options['question_default_type']) && $gen_options['question_default_type'] != '' ? $gen_options['question_default_type'] : null;

$options = array(
    'bg_image' => "",
    'use_html' => 'off',
);
$question = array(
    'category_id'               => '1',
    'author_id'                 => $user_id,
    'question'                  => '',
    'question_image'            => '',
    'type'                      => $question_default_type,
    'published'                 => '',
    'user_explanation'          => 'off',
    'wrong_answer_text'         => '',
    'right_answer_text'         => '',
    'explanation'               => '',
    'create_date'               => current_time( 'mysql' ),
    'not_influence_to_score'    => 'off',
    'weight'                    => floatval(1),
    'options'                   => json_encode($options),
);

$question_categories = $this->quizes_obj->get_question_categories();
$quiz_categories = $this->quizes_obj->get_quiz_categories();

$plus_icon_svg = "<span class=''><img src='". AYS_QUIZ_ADMIN_URL ."/images/icons/plus=icon.svg'></span>";
$youtube_icon_svg = "<span class=''><img src='". AYS_QUIZ_ADMIN_URL ."/images/icons/youtube-video-icon.svg'></span>";

$quick_quiz_plugin_nonce = wp_create_nonce( 'quiz-maker-ajax-quick-quiz-nonce' );

// Buttons Text
$buttons_texts_res      = ($this->settings_obj->ays_get_setting('buttons_texts') === false) ? json_encode(array()) : $this->settings_obj->ays_get_setting('buttons_texts');
$buttons_texts          = json_decode( stripcslashes( $buttons_texts_res ) , true);

$start_button           = (isset($buttons_texts['start_button']) && $buttons_texts['start_button'] != '') ? stripslashes( esc_attr( $buttons_texts['start_button'] ) ) : 'Start';
$next_button            = (isset($buttons_texts['next_button']) && $buttons_texts['next_button'] != '') ? stripslashes( esc_attr( $buttons_texts['next_button'] ) ) : 'Next';
$previous_button        = (isset($buttons_texts['previous_button']) && $buttons_texts['previous_button'] != '') ? stripslashes( esc_attr( $buttons_texts['previous_button'] ) ) : 'Prev' ;


// Enable custom texts for buttons
$quiz_custom_texts_start_button = (isset($options['quiz_custom_texts_start_button']) && $options['quiz_custom_texts_start_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_start_button'] ) ) : $start_button;

$quiz_custom_texts_next_button = (isset($options['quiz_custom_texts_next_button']) && $options['quiz_custom_texts_next_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_next_button'] ) ) : $next_button;

$quiz_custom_texts_prev_button = (isset($options['quiz_custom_texts_prev_button']) && $options['quiz_custom_texts_prev_button'] != '') ? stripslashes( esc_attr( $options['quiz_custom_texts_prev_button'] ) ) : $previous_button;
?>

<div class="wrap ays-quiz-list-table ays_quizzes_list_table">
    <button style="width:50px;height:50px;" class="ays-pulse-button ays-quizzes-table-quick-start" id="ays_quick_start" title="Quick quiz" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="<?php echo __('Build your quiz in a few minutes',$this->plugin_name)?>"></button>
    <h1 class="wp-heading-inline">
        <?php
            echo __(esc_html(get_admin_page_title()),$this->plugin_name);
            // echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action button-primary ays-quiz-add-new-button ays-quiz-add-new-button-new-design"> %s '  . __('Add New', $this->plugin_name) . '</a>', esc_attr( $_REQUEST['page'] ), 'add', $plus_icon_svg);
        ?>
    </h1>

    <?php if($max_id <= 3): ?>
    <div class="notice notice-success is-dismissible">
        <p style="font-size:14px;">
            <strong>
                <?php echo __( "If you haven't created questions yet, you need to do it first.", $this->plugin_name ); ?> 
            </strong>
            <br>
            <strong>
                <em>
                    <?php echo __( "For creating a question go", $this->plugin_name ); ?> 
                    <a href="<?php echo admin_url('admin.php') . "?page=".$this->plugin_name . "-questions"; ?>" target="_blank">
                        <?php echo __( "here", $this->plugin_name ); ?>.
                    </a>
                </em>
            </strong>
        </p>
    </div>
    <?php endif; ?>
    <?php if($quiz_max_id <= 3): ?>
    <div class="ays-quiz-heading-box ays-quiz-unset-float">
        <div class="ays-quiz-wordpress-user-manual-box">
            <a href="https://www.youtube.com/watch?v=gKjzOsn_yDo" target="_blank">
                <?php echo __("Gettings started with Quiz Maker plugin - video", $this->plugin_name); ?>
            </a>
        </div>
    </div>
    <?php endif; ?>
    <div class="ays-quiz-add-new-button-box">
        <?php
            echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action button-primary ays-quiz-add-new-button ays-quiz-add-new-button-new-design"> %s '  . __('Add New', $this->plugin_name) . '</a>', esc_attr( $_REQUEST['page'] ), 'add', $plus_icon_svg);
        ?>
    </div>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
                        $this->quizes_obj->views();
                    ?>
                    <form method="post">
                        <?php
                        $this->quizes_obj->prepare_items();
                        $search = __( "Search", $this->plugin_name );
                        $this->quizes_obj->search_box($search, $this->plugin_name);
                        $this->quizes_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
    <div class="ays-quiz-add-new-button-box">
        <?php
            echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action button-primary ays-quiz-add-new-button ays-quiz-add-new-button-new-design"> %s '  . __('Add New', $this->plugin_name) . '</a>', esc_attr( $_REQUEST['page'] ), 'add', $plus_icon_svg);
        ?>
    </div>
    <?php if($quiz_max_id <= 3): ?>
    <div class="ays-quiz-create-survey-video-box" style="margin: 0px auto 30px;">
        <div class="ays-quiz-create-survey-title">
            <h4><?php echo __( "Create quiz with Quiz Maker plugin in One Minute", $this->plugin_name ); ?></h4>
        </div>
        <div class="ays-quiz-create-survey-youtube-video">
            <iframe width="560" height="315" class="ays-quiz-responsive-with-for-iframe" src="https://www.youtube.com/embed/AUHZrVcBrMU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
        </div>
        <div class="ays_quiz_small_hint_text_for_message_variables" style="text-align: center;">
            <?php echo __( 'Please note that this video will disappear once you created 4 quizzes.', 'quiz-maker' ); ?>
        </div>
        <div class="ays-quiz-create-survey-youtube-video-button-box">
            <?php echo sprintf( '<a href="?page=%s&action=%s" class="ays-quiz-add-new-button-video ays-quiz-add-new-button-new-design"> %s ' . __('Add New', $this->plugin_name) . '</a>', esc_attr( $_REQUEST['page'] ), 'add', $plus_icon_svg);?>
        </div>
    </div>
    <?php else: ?>
    <div class="ays-quiz-create-survey-video-box ays-quiz-create-survey-video-box-only-link" style="margin: auto;">
        <div class="ays-quiz-create-survey-title">
            <?php echo $youtube_icon_svg; ?>
            <a href="https://www.youtube.com/watch?v=AUHZrVcBrMU" target="_blank" title="YouTube video player"><?php echo __("How to create a quiz in one minute?", $this->plugin_name); ?></a>
        </div>
    </div>
    <?php endif; ?>
    <div id="ays-quick-modal" tabindex="-1" class="ays-modal">
        <!-- Modal content -->
        <div class="ays-modal-content fadeInDown" id="ays-quick-modal-content">
            <div class="ays-quiz-preloader">
                <img src="<?php echo AYS_QUIZ_ADMIN_URL; ?>/images/loaders/tail-spin.svg">
            </div>
            <div class="ays-modal-header">
                <span class="ays-close">&times;</span>
                <h4><?php echo __('Build your quiz in few seconds', $this->plugin_name); ?></h4>
            </div>
            <div class="ays-modal-body">
                <form method="post" id="ays_quick_popup">
                    <div class="ays_modal_element">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class='ays-label ays_quiz_title' for='ays-quiz-title'><?php echo __('Quiz Title', $this->plugin_name); ?></label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="ays-text-input" id='ays-quiz-title' name='ays_quiz_title' value=""/>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class='ays-label ays_quick_quiz_description' for='ays-quick-quiz-description'><?php echo __('Quiz Description', $this->plugin_name); ?></label>
                            </div>
                            <div class="col-sm-10">
                                <textarea class="ays-text-input ays-textarea-height-100" id='ays-quick-quiz-description' name='ays_quick_quiz_description'></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class='ays-label ays_quiz_title' for='ays-quiz-category'><?php echo __('Quiz Category', $this->plugin_name); ?></label>
                            </div>
                            <div class="col-sm-10">
                                <select id="ays-quiz-category" class="ays-text-input ays-text-input-short" name="ays_quiz_category">
                                    <?php
                                        foreach ($quiz_categories as $key => $quiz_category) {
                                            echo "<option value='" . $quiz_category['id'] . "'>" . esc_attr( $quiz_category['title'] ) . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class='ays-label ays_quiz_title' for='ays-quiz-category'><?php echo __('Status', $this->plugin_name); ?></label>
                            </div>
                            <div class="col-sm-10" style="display: flex;">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="ays-quick-quiz-publish" name="ays_quick_quiz_publish" value="1" checked />
                                    <label class="form-check-label" for="ays-quick-quiz-publish"> <?php echo __('Published', $this->plugin_name); ?> </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="ays-quick-quiz-unpublish" name="ays_quick_quiz_publish" value="0"/>
                                    <label class="form-check-label" for="ays-quick-quiz-unpublish"> <?php echo __('Unpublished', $this->plugin_name); ?> </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row ays_toggle_parent">
                            <div class="col-sm-2">
                                <label for="ays_quick_quiz_enable_options">
                                    <?php echo __('Quiz Options',$this->plugin_name); ?>
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timerl ays_toggle_checkbox" id="ays_quick_quiz_enable_options" name="ays_quick_quiz_enable_options" value="on" />
                            </div>
                            <div class="col-sm-9 ays_toggle_target ays_divider_left display_none">
                                <p class="ays-subtitle" style="margin-top: 0;"><?php echo __('Settings',$this->plugin_name); ?></p>
                                <hr class="ays-quiz-bolder-hr"/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_randomize_questions">
                                           <?php echo __('Enable randomize questions',$this->plugin_name)?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timerl" id="ays_quick_quiz_enable_randomize_questions" name="ays_quick_quiz_enable_randomize_questions" value="on" />
                                    </div>
                                </div> <!-- Enable randomize questions -->
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_randomize_answers">
                                           <?php echo __('Enable randomize answers',$this->plugin_name)?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timerl" id="ays_quick_quiz_enable_randomize_answers" name="ays_quick_quiz_enable_randomize_answers" value="on" />
                                    </div>
                                </div> <!-- Enable randomize answers -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_display_all_questions">
                                            <?php echo __('Display all questions on one page',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timerl" id="ays_quick_quiz_display_all_questions" name="ays_quick_quiz_display_all_questions" value="on" />
                                    </div>
                                </div> <!-- Display all questions on one page -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_correction">
                                            <?php echo __('Show correct answers',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_correction" name="ays_quick_quiz_enable_correction" value="on" checked />
                                    </div>
                                </div> <!-- Show correct answers -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_show_question_category">
                                            <?php echo __('Show question category',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quick_quiz_show_question_category" name="ays_quick_quiz_show_question_category" value="on" />
                                    </div>
                                </div> <!-- Show question category -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_pass_count">
                                            <?php echo __('Show passed users count',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" id="ays_quick_quiz_enable_pass_count" name="ays_quick_quiz_enable_pass_count" value="on" />
                                    </div>
                                </div> <!-- Show passed users count -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_show_category">
                                            <?php echo __('Show quiz category',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" id="ays_quick_quiz_show_category" name="ays_quick_quiz_show_category" value="on" />
                                    </div>
                                </div> <!-- Show quiz category -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_rate_avg">
                                            <?php echo __('Show average rate',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" id="ays_quick_quiz_enable_rate_avg" name="ays_quick_quiz_enable_rate_avg" value="on" />
                                    </div>
                                </div> <!-- Show average rate -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_show_author">
                                            <?php echo __('Show quiz author',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" id="ays_quick_quiz_show_author" name="ays_quick_quiz_show_author" value="on" />
                                    </div>
                                </div> <!-- Show quiz author -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_show_create_date">
                                            <?php echo __('Show creation date',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" id="ays_quick_quiz_show_create_date" name="ays_quick_quiz_show_create_date" value="on" />
                                    </div>
                                </div> <!-- Show creation date -->
                                <hr />
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_next_button">
                                            <?php echo __('Enable next button',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" id="ays_quick_quiz_enable_next_button" value="on" name="ays_quick_quiz_enable_next_button" checked>
                                    </div>
                                </div> <!-- Enable next button -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_previous_button">
                                            <?php echo __('Enable previous button',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" id="ays_quick_quiz_enable_previous_button" value="on" name="ays_quick_quiz_enable_previous_button" checked>
                                    </div>
                                </div> <!-- Enable previous button -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_early_finish">
                                            <?php echo __('Enable finish button',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_early_finish" name="ays_quick_quiz_enable_early_finish" value="on"/>
                                    </div>
                                </div> <!-- Enable finish button -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_clear_answer">
                                            <?php echo __('Enable clear answer button',$this->plugin_name)?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_clear_answer" name="ays_quick_quiz_enable_clear_answer" value="on" />
                                    </div>
                                </div> <!-- Enable clear answer button -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_enter_key">
                                            <?php echo __('Enable to go next by pressing Enter key',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_enter_key" name="ays_quick_quiz_enable_enter_key" value="on" checked/>
                                    </div>
                                </div> <!-- Enable to go next by pressing Enter key -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_display_messages_before_buttons">
                                            <?php echo __('Display messages before the buttons',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_display_messages_before_buttons" name="ays_quick_quiz_display_messages_before_buttons" value="on" />
                                    </div>
                                </div> <!-- Display messages before the buttons -->
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_audio_autoplay">
                                            <?php echo __('Enable audio autoplay',$this->plugin_name)?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_audio_autoplay" name="ays_quick_quiz_enable_audio_autoplay" value="on" />
                                    </div>
                                </div> <!-- Enable audio autoplay -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_rtl_direction">
                                            <?php echo __('Use RTL Direction',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timerl" id="ays_quick_quiz_enable_rtl_direction" name="ays_quick_quiz_enable_rtl_direction" value="on" />
                                    </div>
                                </div> <!-- Use RTL Direction -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_questions_counter">
                                            <?php echo __('Show questions counter',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timerl" id="ays_quick_quiz_enable_questions_counter" name="ays_quick_quiz_enable_questions_counter" value="on" checked/>
                                    </div>
                                </div> <!-- Show questions counter -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_question_image_zoom">
                                            <?php echo __('Question Image Zoom',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_question_image_zoom" name="ays_quick_quiz_enable_question_image_zoom" value="on" />
                                    </div>
                                </div> <!-- Question Image Zoom -->
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_leave_page">
                                            <?php echo __('Enable confirmation box for leaving the page',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_leave_page" name="ays_quick_quiz_enable_leave_page" value="on" checked/>
                                    </div>
                                </div> <!-- Enable confirmation box for leaving the page -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_see_result_confirm_box">
                                            <?php echo __('Enable confirmation box for the See Result button',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_see_result_confirm_box" name="ays_quick_quiz_enable_see_result_confirm_box" value="on" />
                                    </div>
                                </div> <!-- Enable confirmation box for the See Result button  -->
                                <hr/>
                                <div class="form-group row ays_toggle_parent">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_rw_asnwers_sounds">
                                            <?php echo __('Enable sounds for right/wrong answers',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" id="ays_quick_quiz_enable_rw_asnwers_sounds" name="ays_quick_quiz_enable_rw_asnwers_sounds" class="ays_toggle_checkbox" value="on"/>
                                    </div>
                                </div> <!-- Enable sounds for right/wrong answers -->
                                <hr>
                                <div class="form-group row ays_toggle_parent">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_custom_texts_for_buttons">
                                            <?php echo __('Enable custom texts for buttons',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_quick_quiz_enable_custom_texts_for_buttons" name="ays_quick_quiz_enable_custom_texts_for_buttons" value="on" />
                                    </div>
                                    <div class="col-sm-7 ays_toggle_target ays_divider_left display_none">
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_custom_texts_start_button">
                                                    <?php echo __('Start button',$this->plugin_name); ?>
                                                </label> 
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_start_button" id="ays_quick_quiz_custom_texts_start_button" value="<?php echo esc_attr($quiz_custom_texts_start_button); ?>"/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_custom_texts_next_button">
                                                    <?php echo __('Next button',$this->plugin_name); ?>
                                                </label> 
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_next_button" id="ays_quick_quiz_custom_texts_next_button" value="<?php echo esc_attr($quiz_custom_texts_next_button); ?>"/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label for="ays_quick_quiz_custom_texts_prev_button">
                                                    <?php echo __('Previous button',$this->plugin_name); ?>
                                                </label> 
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="ays-text-input" name="ays_quick_quiz_custom_texts_prev_button" id="ays_quick_quiz_custom_texts_prev_button" value="<?php echo esc_attr($quiz_custom_texts_prev_button); ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- Enable custom texts for buttons -->
                                <p class="ays-subtitle"><?php echo __('Results Settings',$this->plugin_name); ?></p>
                                <hr class="ays-quiz-bolder-hr"/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_hide_score">
                                            <?php echo __('Hide score',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_hide_score" name="ays_quick_quiz_hide_score" value="on" />
                                    </div>
                                </div><!-- Hide score -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_restart_button">
                                            <?php echo __('Enable restart button',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_restart_button" name="ays_quick_quiz_enable_restart_button" value="on" checked />
                                    </div>
                                </div><!-- Enable restart button -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_progress_bar">
                                            <?php echo __('Enable progress bar',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_progress_bar" name="ays_quick_quiz_enable_progress_bar" value="on" checked />
                                    </div>
                                </div><!-- Enable progress bar -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_enable_average_statistical">
                                            <?php echo __('Show the statistical average',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_enable_average_statistical" name="ays_quick_quiz_enable_average_statistical" value="on" checked />
                                    </div>
                                </div><!-- Show the statistical average -->
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_disable_store_data">
                                            <?php echo __('Disable data storing in database',$this->plugin_name); ?>
                                        </label>
                                        <p class="ays_quiz_small_hint_text_for_not_recommended">
                                            <span><?php echo __( "Not recommended" , $this->plugin_name ); ?></span>
                                        </p>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" class="ays-enable-timer1" id="ays_quick_quiz_disable_store_data" name="ays_quick_quiz_disable_store_data" value="on" />
                                    </div>
                                </div><!-- Disable data storing in database -->
                                <p class="ays-subtitle"><?php echo __('User Data',$this->plugin_name); ?></p>
                                <hr class="ays-quiz-bolder-hr"/>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_show_information_form">
                                            <?php echo __('Show Information Form to logged-in users', $this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="information_form_settings">
                                            <input type="checkbox" id="ays_quick_quiz_show_information_form" name="ays_quick_quiz_show_information_form" value="on" checked />
                                        </div>
                                    </div>
                                </div><!-- Show Information Form to logged-in users -->
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_autofill_user_data">
                                            <?php echo __('Autofill logged-in user data',$this->plugin_name); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="information_form_settings">
                                            <input type="checkbox" id="ays_quick_quiz_autofill_user_data" name="ays_quick_quiz_autofill_user_data" value="on" />
                                        </div>
                                    </div>
                                </div><!-- Autofill logged-in user data -->
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_quick_quiz_display_fields_labels">
                                            <?php echo __('Display form fields with labels',"quiz-maker"); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="information_form_settings">
                                            <input type="checkbox" id="ays_quick_quiz_display_fields_labels" name="ays_quick_quiz_display_fields_labels" value="on" />
                                        </div>
                                    </div>
                                </div><!-- Display form fields with labels -->
                            </div>
                        </div> <!-- Quiz Options -->
                    </div>
                    <hr>
                    <div class="ays-quick-questions-container">
                        <div>
                            <p class="ays_questions_title"><?php echo __('Questions',$this->plugin_name)?></p>
                            <!-- <a href="javascript:void(0)" class="ays_add_question">
                                <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                            </a> -->
                            <div>
                                <a href="javascript:void(0)" class="ays_add_question ays-quick-quiz-add-question">
                                    <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                                    <?php echo __('Add question', 'quiz-maker'); ?>
                                </a>
                            </div>
                        </div>
                        <hr/>
                        <div tabindex="0" class="ays_modal_element ays_modal_question active_question_border" id="ays_question_id_1">
                            <div class="form-group row">
                                <div class="col-sm-9">
                                    <input type="text" value="<?php echo __( 'Question Default Title' , $this->plugin_name); ?>" class="ays_question_input">
                                </div>
                                <div class="col-sm-3" style="text-align: right;">
                                    <select class="ays_quick_question_type" name="ays_quick_question_type[]" style="width: 120px;">
                                        <option value="radio"><?php echo __("Radio", $this->plugin_name); ?></option>
                                        <option value="checkbox"><?php echo __("Checkbox", $this->plugin_name); ?></option>
                                        <option value="select"><?php echo __("Dropdown", $this->plugin_name); ?></option>
                                        <option value="text"><?php echo __("Text", $this->plugin_name); ?></option>
                                        <option value="short_text"><?php echo __("Short Text", $this->plugin_name); ?></option>
                                        <option value="number"><?php echo __("Number", $this->plugin_name); ?></option>
                                        <option value="true_or_false"><?php echo __("True/False", $this->plugin_name); ?></option>
                                        <option value="date"><?php echo __("Date", $this->plugin_name); ?></option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="ays_question_overlay"></div> -->
                            <div class="form-group row">
                                <div class="col-sm-12" style="text-align: right;">
                                    <select class="ays_quick_question_cat" name="ays_quick_question_cat[]" style="width: 120px;">
                                        <?php
                                            $cat = 0;
                                            foreach ($question_categories as $k => $question_category) {
                                                $checked = (intval($question_category['id']) == intval($question['category_id'])) ? "selected" : "";
                                                if ($cat == 0 && intval($question['category_id']) == 0) {
                                                    $checked = 'selected';
                                                }
                                                echo "<option value='" . $question_category['id'] . "' " . $checked . ">" . stripslashes($question_category['title']) . "</option>";
                                                $cat++;
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="ays-modal-flexbox flex-end">
                                <table class="ays_answers_table">
                                    <tr>
                                        <td>
                                            <input class="ays_answer_unique_id" type="radio" name="ays_answer_radio[1]" checked>
                                        </td>
                                        <td class="ays_answer_td">
                                            <p class="ays_answer"><?php echo __('Answer',$this->plugin_name)?></p>
                                        </td>
                                        <td class="show_remove_answer">
                                            <i class="ays_fa ays_fa_times" aria-hidden="true"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="ays_answer_unique_id" type="radio" name="ays_answer_radio[1]">
                                        </td>
                                        <td class="ays_answer_td">
                                            <p class="ays_answer"><?php echo __('Answer',$this->plugin_name)?></p>
                                        </td>
                                        <td class="show_remove_answer">
                                            <i class="ays_fa ays_fa_times" aria-hidden="true"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="ays_answer_unique_id" type="radio" name="ays_answer_radio[1]">
                                        </td>
                                        <td class="ays_answer_td">
                                            <p class="ays_answer"><?php echo __('Answer',$this->plugin_name)?></p>
                                        </td>
                                        <td class="show_remove_answer">
                                            <i class="ays_fa ays_fa_times" aria-hidden="true"></i>
                                        </td>
                                    </tr>
                                    <tr class="ays_quiz_add_answer_box show_add_answer">
                                        <td colspan="3">
                                            <a href="javascript:void(0)" class="ays_add_answer">
                                                <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                                <table class="ays_quick_quiz_text_type_table display_none">
                                    <tr>
                                        <td>
                                            <input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>
                                            <textarea type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value ays-text-question-type-value" placeholder="<?php echo __( 'Answer text', $this->plugin_name ); ?>"></textarea>
                                        </td>
                                    </tr>
                                </table>
                                <div class="ays-quick-quiz-icons-box">
                                    <a href="javascript:void(0)" class="ays_question_clone_icon">
                                        <i class="ays_fa ays_fa_clone" aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="ays_trash_icon">
                                        <i class="ays_fa ays_fa_trash_o" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="ays-modal-flexbox">
                        <!-- <a href="javascript:void(0)" class="ays_add_question">
                            <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                        </a> -->
                        <a href="javascript:void(0)" class="ays_add_question ays-quick-quiz-add-question">
                            <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                            <?php echo __('Add question', 'quiz-maker'); ?>
                        </a>
                    </div>
                    <input type="button" class="btn btn-primary ays_submit_button" id="ays_quick_submit_button" value="<?php echo __('Submit',$this->plugin_name)?>"/>
                    <input type="hidden" id="ays_quick_question_max_id" value="1"/>
                    <input type="hidden" id="ays_quiz_ajax_quick_quiz_nonce" name="ays_quiz_ajax_quick_quiz_nonce" value="<?php echo $quick_quiz_plugin_nonce; ?>">
                </form>
            </div>
        </div>
    </div>
</div>
