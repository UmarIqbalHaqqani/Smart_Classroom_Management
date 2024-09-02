<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/admin
 * @author     AYS Pro LLC <info@ays-pro.com>
 */

class Quiz_Maker_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;


    private $quizes_obj;
    private $quiz_categories_obj;
    private $questions_obj;
    private $question_categories_obj;
    private $results_obj;
    private $settings_obj;
    private $all_reviews_obj;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
        $per_page_array = array(
            'quizes_per_page',
            'questions_per_page',
            'quiz_categories_per_page',
            'question_categories_per_page',
            'quiz_results_per_page',
            'quiz_all_reviews_per_page',
        );
        foreach($per_page_array as $option_name){
            add_filter('set_screen_option_'.$option_name, array(__CLASS__, 'set_screen'), 10, 3);
        }
    }

    /**
     * Register the styles for the admin menu area.
     *
     * @since    1.0.0
     */
    public function admin_menu_styles(){
        
        echo "<style>
            .ays_menu_badge{
                color: #fff;
                display: inline-block;
                font-size: 10px;
                line-height: 14px;
                text-align: center;
                background: #ca4a1f;
                margin-left: 5px;
                border-radius: 20px;
                padding: 2px 5px;
            }

            #adminmenu a.toplevel_page_quiz-maker div.wp-menu-image img {
                width: 32px;
                padding: 1px 0 0;
                transition: .3s ease-in-out;
            }
        </style>";

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook_suffix){
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-sweetalert-css', AYS_QUIZ_PUBLIC_URL . '/css/quiz-maker-sweetalert2.min.css', array(), $this->version, 'all');

        if (false === strpos($hook_suffix, $this->plugin_name))
            return;

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Quiz_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Quiz_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style('wp-color-picker');
        // You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
        // wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
        // wp_enqueue_style( 'jquery-ui' );

        wp_enqueue_style($this->plugin_name . '-animate.css', plugin_dir_url(__FILE__) . 'css/animate.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-sweetalert-css', AYS_QUIZ_PUBLIC_URL . '/css/quiz-maker-sweetalert2.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-font-awesome', AYS_QUIZ_PUBLIC_URL . '/css/quiz-maker-font-awesome.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-select2', AYS_QUIZ_PUBLIC_URL .  '/css/quiz-maker-select2.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-data-bootstrap', plugin_dir_url(__FILE__) . 'css/dataTables.bootstrap4.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-jquery-datetimepicker', plugin_dir_url(__FILE__) . 'css/jquery-ui-timepicker-addon.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/quiz-maker-admin.css', array(), time()/*$this->version*/, 'all');
        wp_enqueue_style($this->plugin_name . '-banner', plugin_dir_url(__FILE__) . 'css/quiz-maker-banner.css', array(), time(), 'all');
        wp_enqueue_style($this->plugin_name . "-loaders", plugin_dir_url(__FILE__) . 'css/loaders.css', array(), time()/*$this->version*/, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook_suffix){
        global $wp_version;

        $version1 = $wp_version;
        $operator = '>=';
        $version2 = '5.5';
        $versionCompare = $this->versionCompare($version1, $operator, $version2);

        if ($versionCompare) {
            wp_enqueue_script( $this->plugin_name.'-wp-load-scripts', plugin_dir_url(__FILE__) . 'js/ays-wp-load-scripts.js', array(), $this->version, true);
        }

        if (false !== strpos($hook_suffix, "plugins.php")){
            wp_enqueue_script($this->plugin_name . '-sweetalert-js', AYS_QUIZ_PUBLIC_URL . '/js/quiz-maker-sweetalert2.all.min.js', array('jquery'), $this->version, true );
            wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), $this->version, true);
            wp_localize_script($this->plugin_name . '-admin',  'quiz_maker_admin_ajax', array(
                'ajax_url'              => admin_url('admin-ajax.php'),
                'errorMsg'              => __( "Error", $this->plugin_name ),
                'loadResource'          => __( "Can't load resource.", $this->plugin_name ),
                'somethingWentWrong'    => __( "Maybe something went wrong.", $this->plugin_name ),
            ));
        }

        if (false === strpos($hook_suffix, $this->plugin_name))
            return;

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Quiz_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Quiz_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        $quiz_banner_date = $this->ays_quiz_update_banner_time();

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-effects-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-datepicker');

        wp_enqueue_script( $this->plugin_name . '-color-picker-alpha', plugin_dir_url(__FILE__) . 'js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), $this->version, true );
        $color_picker_strings = array(
            'clear'            => __( 'Clear', $this->plugin_name ),
            'clearAriaLabel'   => __( 'Clear color', $this->plugin_name ),
            'defaultString'    => __( 'Default', $this->plugin_name ),
            'defaultAriaLabel' => __( 'Select default color', $this->plugin_name ),
            'pick'             => __( 'Select Color', $this->plugin_name ),
            'defaultLabel'     => __( 'Color value', $this->plugin_name ),
        );
        wp_localize_script( $this->plugin_name . '-color-picker-alpha', 'wpColorPickerL10n', $color_picker_strings );

        wp_enqueue_media();
        wp_enqueue_script('ays_quiz_popper', plugin_dir_url(__FILE__) . 'js/popper.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script('ays_quiz_bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '-select2js', AYS_QUIZ_PUBLIC_URL . '/js/quiz-maker-select2.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-sweetalert-js', AYS_QUIZ_PUBLIC_URL . '/js/quiz-maker-sweetalert2.all.min.js', array('jquery'), $this->version, true );
        wp_enqueue_script($this->plugin_name . '-datatable-min', AYS_QUIZ_PUBLIC_URL . '/js/quiz-maker-datatable.min.js', array('jquery'), $this->version, true);
		wp_enqueue_script($this->plugin_name . "-db4.min.js", plugin_dir_url( __FILE__ ) . 'js/dataTables.bootstrap4.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script($this->plugin_name . "-jquery.datetimepicker.js", plugin_dir_url( __FILE__ ) . 'js/jquery-ui-timepicker-addon.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script($this->plugin_name . '-ajax', plugin_dir_url(__FILE__) . 'js/quiz-maker-admin-ajax.js', array('jquery'), time(),/*$this->version,*/ true);
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/quiz-maker-admin.js',
            array('jquery', 'wp-color-picker'),
            time(), /*$this->version,*/
            true
        );
        wp_localize_script($this->plugin_name . '-ajax', 'quiz_maker_ajax', array(
            'ajax_url'                      => admin_url('admin-ajax.php'),
            "emptyEmailError"               => __( 'Email field is empty', $this->plugin_name),
            "invalidEmailError"             => __( 'Invalid Email address', $this->plugin_name),
            'selectUser'                    => __( 'Select user', $this->plugin_name),
            'pleaseEnterMore'               => __( "Please enter 1 or more characters", $this->plugin_name ),
            'searching'                     => __( "Searching...", $this->plugin_name ),
            'activated'                     => __( "Activated", $this->plugin_name ),
            'errorMsg'                      => __( "Error", $this->plugin_name ),
            'loadResource'                  => __( "Can't load resource.", $this->plugin_name ),
            'somethingWentWrong'            => __( "Maybe something went wrong.", $this->plugin_name ),
            'youCanUuseThisShortcode'       => __( 'Copy the generated shortcode and paste it into any post or page to display Quiz.', $this->plugin_name),
            'youQuizIsCreated'              => __( 'Your Quiz is Created!', $this->plugin_name),
            'greateJob'                     => __( 'Great job', $this->plugin_name),
            'formMoreDetailed'              => __( 'For more detailed configuration visit', $this->plugin_name),
            'editQuizPage'                  => __( 'edit quiz page', $this->plugin_name),
            'greate'                        => __( 'Great!', $this->plugin_name),
        ));
        wp_localize_script( $this->plugin_name, 'quizLangObj', array(
            'quizBannerDate'                => $quiz_banner_date,
            'AYS_QUIZ_ADMIN_URL'            => AYS_QUIZ_ADMIN_URL,

            'questionTitle'                 => __( 'Question Default Title', $this->plugin_name),
            'radio'                         => __( 'Radio', $this->plugin_name),
            'checkbox'                      => __( 'Checkbox', $this->plugin_name),
            'dropdawn'                      => __( 'Dropdown', $this->plugin_name),
            'emptyAnswer'                   => __( 'Empty Answer', $this->plugin_name),
            'addGif'                        => __( 'Add Gif', $this->plugin_name),
            'textType'                      => __( 'Text', $this->plugin_name),
            'answerText'                    => __( 'Answer text', $this->plugin_name),
            'copied'                        => __( 'Copied!', $this->plugin_name),
            'clickForCopy'                  => __( 'Click for copy.', $this->plugin_name),
            'shortTextType'                 => __( 'Short Text', $this->plugin_name),
            'true'                          => __( 'True', $this->plugin_name),
            'false'                         => __( 'False', $this->plugin_name),
            'number'                        => __( 'Number', $this->plugin_name),
            'trueOrFalse'                   => __( 'True/False', $this->plugin_name),
            'date'                          => __( 'Date', $this->plugin_name),
            'currentTime'                   => current_time( 'Y-m-d' ),
            'nextQustionPage'               => __( 'Are you sure you want to go to the next question page?', $this->plugin_name),
            'areYouSureButton'              => __( 'Are you sure you want to redirect to another quiz? Note that the changes made in this quiz will not be saved.', $this->plugin_name),
            'deleteQuestion'                => __( 'Are you sure you want to delete question ?', $this->plugin_name),
            'deleteElementFromListTable'    => __( 'Are you sure you want to delete?', $this->plugin_name),
            'youCanUuseThisShortcode'       => __( 'Copy the generated shortcode and paste it into any post or page to display Quiz.', $this->plugin_name),
            'youQuizIsCreated'              => __( 'Your Quiz is Created!', $this->plugin_name),
            'greateJob'                     => __( 'Great job', $this->plugin_name),
            'formMoreDetailed'              => __( 'For more detailed configuration visit', $this->plugin_name),
            'editQuizPage'                  => __( 'edit quiz page', $this->plugin_name),
            'greate'                        => __( 'Done', $this->plugin_name),
            'thumbsUpGreat'                 => __( 'Thumbs up, great!', $this->plugin_name),

            "all"                           => __( "All", $this->plugin_name ),
            "selectCategory"                => __( "Select Category", $this->plugin_name ),
            "selectTags"                    => __( "Select Tags", $this->plugin_name ),
        ) );

        $question_categories = $this->get_question_categories();
        wp_localize_script( $this->plugin_name, 'aysQuizCatObj', array(
            'category' => $question_categories,
        ) );

    }

    /**
     * De-register JavaScript files for the admin area.
     *
     * @since    1.0.0
     */
    public function disable_scripts($hook_suffix) {
        if (false !== strpos($hook_suffix, $this->plugin_name)) {
            if (is_plugin_active('ai-engine/ai-engine.php')) {
                wp_deregister_script('mwai');
                wp_deregister_script('mwai-vendor');
                wp_dequeue_script('mwai');
                wp_dequeue_script('mwai-vendor');
            }
        }
    }


    function codemirror_enqueue_scripts($hook) {
        if (false === strpos($hook, $this->plugin_name)){
            return;
        }
        if(function_exists('wp_enqueue_code_editor')){
            $cm_settings['codeEditor'] = wp_enqueue_code_editor(array(
                'type' => 'text/css',
                'codemirror' => array(
                    'inputStyle' => 'contenteditable',
                    'theme' => 'cobalt',
                )
            ));

            wp_enqueue_script('wp-theme-plugin-editor');
            wp_localize_script('wp-theme-plugin-editor', 'cm_settings', $cm_settings);
        
            wp_enqueue_style('wp-codemirror');
        }
    }

    function versionCompare($version1, $operator, $version2) {
   
        $_fv = intval ( trim ( str_replace ( '.', '', $version1 ) ) );
        $_sv = intval ( trim ( str_replace ( '.', '', $version2 ) ) );
       
        if (strlen ( $_fv ) > strlen ( $_sv )) {
            $_sv = str_pad ( $_sv, strlen ( $_fv ), 0 );
        }
       
        if (strlen ( $_fv ) < strlen ( $_sv )) {
            $_fv = str_pad ( $_fv, strlen ( $_sv ), 0 );
        }
       
        return version_compare ( ( string ) $_fv, ( string ) $_sv, $operator );
    }


    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu(){

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        $setting_actions = new Quiz_Maker_Settings_Actions($this->plugin_name);
        $options = ($setting_actions->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes( $setting_actions->ays_get_setting('options') ), true);

        // Disable Quiz maker menu item notification
        $options['quiz_disable_quiz_menu_notification'] = isset($options['quiz_disable_quiz_menu_notification']) ? esc_attr( $options['quiz_disable_quiz_menu_notification'] ) : 'off';
        $quiz_disable_quiz_menu_notification = (isset($options['quiz_disable_quiz_menu_notification']) && esc_attr( $options['quiz_disable_quiz_menu_notification'] ) == "on") ? true : false;

        if( $quiz_disable_quiz_menu_notification ){
            $menu_item = 'Quiz Maker';
        } else {
            global $wpdb;
            $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports WHERE `read` = 0";
            $unread_results_count = $wpdb->get_var($sql);        
            $menu_item = ($unread_results_count == 0) ? 'Quiz Maker' : 'Quiz Maker' . '<span class="ays_menu_badge ays_results_bage">' . $unread_results_count . '</span>';
        }
        
        add_menu_page(
            'Quiz Maker', 
            $menu_item, 
            'manage_options', 
            $this->plugin_name, 
            array($this, 'display_plugin_quiz_page'), 
            AYS_QUIZ_ADMIN_URL . '/images/icons/icon-quiz maker-128x128.svg', 
            '6.20'
        );
    }
    
    public function add_plugin_quizzes_submenu(){
        $hook_quiz_maker = add_submenu_page(
            $this->plugin_name,
            __('Quizzes', $this->plugin_name),
            __('Quizzes', $this->plugin_name),
            'manage_options',
            $this->plugin_name,
            array($this, 'display_plugin_quiz_page')
        );

        add_action("load-$hook_quiz_maker", array($this, 'screen_option_quizes'));
        add_action("load-$hook_quiz_maker", array( $this, 'add_tabs' ));
    }

    public function add_plugin_questions_submenu(){
        $hook_questions = add_submenu_page(
            $this->plugin_name,
            __('Questions', $this->plugin_name),
            __('Questions', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-questions',
            array($this, 'display_plugin_questions_page')
        );

        add_action("load-$hook_questions", array($this, 'screen_option_questions'));
        add_action("load-$hook_questions", array( $this, 'add_tabs' ));
    }

    public function add_plugin_quiz_categories_submenu(){
        $hook_quiz_categories = add_submenu_page(
            $this->plugin_name,
            __('Quiz Categories', $this->plugin_name),
            __('Quiz Categories', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-quiz-categories',
            array($this, 'display_plugin_quiz_categories_page')
        );

        add_action("load-$hook_quiz_categories", array($this, 'screen_option_quiz_categories'));
        add_action("load-$hook_quiz_categories", array( $this, 'add_tabs' ));
    }

    public function add_plugin_questions_categories_submenu(){
        $hook_questions_categories = add_submenu_page(
            $this->plugin_name,
            __('Question Categories', $this->plugin_name),
            __('Question Categories', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-question-categories',
            array($this, 'display_plugin_question_categories_page')
        );

        add_action("load-$hook_questions_categories", array($this, 'screen_option_questions_categories'));
        add_action("load-$hook_questions_categories", array( $this, 'add_tabs' ));
    }

    public function add_plugin_custom_fields_submenu(){
        $hook_quiz_categories = add_submenu_page(
            $this->plugin_name,
            __('Custom Fields', $this->plugin_name),
            __('Custom Fields', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-quiz-attributes',
            array($this, 'display_plugin_quiz_attributes_page')
        );
        add_action("load-$hook_quiz_categories", array( $this, 'add_tabs' ));
    }

    public function add_plugin_orders_submenu(){
        $hook_quiz_orders = add_submenu_page(
            $this->plugin_name,
            __('Orders', $this->plugin_name),
            __('Orders', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-quiz-orders',
            array($this, 'display_plugin_orders_page')
        );
        add_action("load-$hook_quiz_orders", array( $this, 'add_tabs' ));
    }

    public function add_plugin_results_submenu(){
        global $wpdb;

        $setting_actions = new Quiz_Maker_Settings_Actions($this->plugin_name);
        $options = ($setting_actions->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes( $setting_actions->ays_get_setting('options') ), true);

        // Disable Results menu item notification
        $options['quiz_disable_results_menu_notification'] = isset($options['quiz_disable_results_menu_notification']) ? esc_attr( $options['quiz_disable_results_menu_notification'] ) : 'off';
        $quiz_disable_results_menu_notification = (isset($options['quiz_disable_results_menu_notification']) && esc_attr( $options['quiz_disable_results_menu_notification'] ) == "on") ? true : false;

        if( $quiz_disable_results_menu_notification ){
            $results_text = __('Results', $this->plugin_name);
            $menu_item = __('Results', $this->plugin_name);
        } else {
            $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports WHERE `read` = 0";
            $unread_results_count = $wpdb->get_var($sql);
            $results_text = __('Results', $this->plugin_name);
            $menu_item = ($unread_results_count == 0) ? $results_text : $results_text . '<span class="ays_menu_badge ays_results_bage">' . $unread_results_count . '</span>';
        }
        
        $hook_results = add_submenu_page(
            $this->plugin_name,
            $results_text,
            $menu_item,
            'manage_options',
            $this->plugin_name . '-results',
            array($this, 'display_plugin_results_page')
        );

        add_action("load-$hook_results", array($this, 'screen_option_results'));
        add_action("load-$hook_results", array( $this, 'add_tabs' ));

        $hook_all_reviews = add_submenu_page(
            'all_reviews_slug',
            __('Reviews', $this->plugin_name),
            null,
            'manage_options',
            $this->plugin_name . '-all-reviews',
            array($this, 'display_plugin_all_reviews_page')
        );

        add_action("load-$hook_all_reviews", array($this, 'screen_option_all_quiz_reviews'));
        add_action("load-$hook_all_reviews", array( $this, 'add_tabs' ));

        add_filter('parent_file', array($this,'quiz_maker_select_submenu'));
    }

    public function add_plugin_dashboard_submenu(){
        $hook_quizes = add_submenu_page(
            $this->plugin_name,
            __('How to use', $this->plugin_name),
            __('How to use', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-dashboard',
            array($this, 'display_plugin_setup_page')
        );
        add_action("load-$hook_quizes", array( $this, 'add_tabs' ));
    }

    public function add_plugin_general_settings_submenu(){
        $hook_settings = add_submenu_page( $this->plugin_name,
            __('General Settings', $this->plugin_name),
            __('General Settings', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-settings',
            array($this, 'display_plugin_settings_page') 
        );
        add_action("load-$hook_settings", array($this, 'screen_option_settings'));
        add_action("load-$hook_settings", array( $this, 'add_tabs' ));
    }

    public function add_plugin_featured_plugins_submenu(){
        $hook_our_products = add_submenu_page( $this->plugin_name,
            __('Our products', $this->plugin_name),
            __('Our products', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-featured-plugins',
            array($this, 'display_plugin_featured_plugins_page') 
        );

        add_action("load-$hook_our_products", array( $this, 'add_tabs' ));
    }

    public function add_plugin_addons_submenu(){

        $menu_item =  __('Addons', $this->plugin_name) . '<span class="ays_menu_badge ays_results_bage">' .  __('New', $this->plugin_name) . '</span>';

        $hook_addons = add_submenu_page( 
            $this->plugin_name,
            __('Addons', $this->plugin_name),
            $menu_item,
            'manage_options',
            $this->plugin_name . '-addons',
            array($this, 'display_plugin_addons_page')
        );
        add_action("load-$hook_addons", array( $this, 'add_tabs' ));
    }

    public function add_plugin_quiz_features_submenu(){
        $hook_pro_features = add_submenu_page(
            $this->plugin_name,
            __('PRO Features', $this->plugin_name),
            __('PRO Features', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-quiz-features',
            array($this, 'display_plugin_quiz_features_page')
        );

        add_action("load-$hook_pro_features", array( $this, 'add_tabs' ));
    }

    public function add_plugin_subscribe_email(){
        $hook_grab_your_gift = add_submenu_page(
            $this->plugin_name,
            __('Grab your GIFT', $this->plugin_name),
            __('Grab your GIFT', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-quiz-subscribe-email',
            array($this, 'display_plugin_subscribe_email')
        );

        add_action("load-$hook_grab_your_gift", array( $this, 'add_tabs' ));
    }

    public function add_plugin_integrations_submenu(){
        $hook_integrations = add_submenu_page( $this->plugin_name,
            __('Integrations', $this->plugin_name),
            __('Integrations', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '-integrations',
            array($this, 'display_plugin_integrations_page') 
        );
        
        // add_action("load-$hook_integrations", array($this, 'screen_option_settings'));
        add_action("load-$hook_integrations", array( $this, 'add_tabs' ));
    }

    public function quiz_maker_select_submenu($file) {
        global $plugin_page;
        if ( $this->plugin_name . "-all-reviews" == $plugin_page ) {
            $plugin_page = $this->plugin_name."-results";
        }

        return $file;
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links){
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */

        $quiz_ajax_deactivate_plugin_nonce = wp_create_nonce( 'quiz-maker-ajax-deactivate-plugin-nonce' );

        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
            '<a href="https://quiz-plugin.com/wordpress-quiz-plugin-free-demo/" target="_blank">' . __('Demo', $this->plugin_name) . '</a>',
            '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=plugins-buy-now-button" id="ays-quiz-plugins-buy-now-button" target="_blank">' . __('Upgrade', $this->plugin_name) . '</a>
            <input type="hidden" id="ays_quiz_ajax_deactivate_plugin_nonce" name="ays_quiz_ajax_deactivate_plugin_nonce" value="' . $quiz_ajax_deactivate_plugin_nonce .'">',
            );
        return array_merge($settings_link, $links);

    }

    public function add_quiz_row_meta( $links, $file ) {
        if ( AYS_QUIZ_BASENAME == $file ) {
            $row_meta = array(
                'ays-quiz-support'    => '<a href="' . esc_url( 'https://wordpress.org/support/plugin/quiz-maker/' ) . '" target="_blank">' . esc_html__( 'Free Support', $this->plugin_name ) . '</a>'
                );

            return array_merge( $links, $row_meta );
        }
        return $links;
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page(){
        include_once('partials/quiz-maker-admin-display.php');
    }

    public function display_plugin_quiz_categories_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/quizes/actions/quiz-maker-quiz-categories-actions.php');
                break;
            case 'edit':
                include_once('partials/quizes/actions/quiz-maker-quiz-categories-actions.php');
                break;
            default:
                include_once('partials/quizes/quiz-maker-quiz-categories-display.php');
        }
    }
    
    public function display_plugin_quiz_attributes_page(){
        include_once('partials/attributes/quiz-maker-attributes-display.php');
    }
    
    public function display_plugin_quiz_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/quizes/actions/quiz-maker-quizes-actions.php');
                break;
            case 'edit':
                include_once('partials/quizes/actions/quiz-maker-quizes-actions.php');
                break;
            default:
                include_once('partials/quizes/quiz-maker-quizes-display.php');
        }
    }

    public function display_plugin_question_categories_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/questions/actions/quiz-maker-questions-categories-actions.php');
                break;
            case 'edit':
                include_once('partials/questions/actions/quiz-maker-questions-categories-actions.php');
                break;
            default:
                include_once('partials/questions/quiz-maker-question-categories-display.php');
        }
    }

    public function display_plugin_questions_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/questions/actions/quiz-maker-questions-actions.php');
                break;
            case 'edit':
                include_once('partials/questions/actions/quiz-maker-questions-actions.php');
                break;
            default:
                include_once('partials/questions/quiz-maker-questions-display.php');
        }
    }
    
    public function display_plugin_orders_page(){

        include_once('partials/orders/quiz-maker-orders-display.php');
    }
    
    public function display_plugin_settings_page(){        
        include_once('partials/settings/quiz-maker-settings.php');
    }

    public function display_plugin_quiz_features_page(){
        include_once('partials/features/quiz-maker-features-display.php');
    }

    public function display_plugin_subscribe_email(){
        include_once('partials/subscribe/quiz-maker-subscribe-email-display.php');
    }

    public function display_plugin_featured_plugins_page(){
        include_once('partials/features/quiz-maker-plugin-featured-display.php');
    }

    public function display_plugin_addons_page(){
        include_once('partials/features/quiz-maker-addons-display.php');
    }

    public function display_plugin_results_page(){
        include_once('partials/results/quiz-maker-results-display.php');
    }

    public function display_plugin_all_reviews_page(){
        include_once('partials/results/quiz-maker-all-reviews-display.php');
    }

    public function display_plugin_integrations_page(){        
        include_once('partials/integrations/quiz-maker-integrations.php');
    }

    public static function set_screen($status, $option, $value){
        return $value;
    }

    public function screen_option_quizes(){
        $option = 'per_page';
        $args = array(
            'label' => __('Quizzes', $this->plugin_name),
            'default' => 20,
            'option' => 'quizes_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }

        $this->quizes_obj = new Quizes_List_Table($this->plugin_name);
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_quiz_categories(){
        $option = 'per_page';
        $args = array(
            'label' => __('Quiz Categories', $this->plugin_name),
            'default' => 20,
            'option' => 'quiz_categories_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }

        $this->quiz_categories_obj = new Quiz_Categories_List_Table($this->plugin_name);
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_questions(){
        $option = 'per_page';
        $args = array(
            'label' => __('Questions', $this->plugin_name),
            'default' => 20,
            'option' => 'questions_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }

        $this->questions_obj = new Questions_List_Table($this->plugin_name);
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_questions_categories(){
        $option = 'per_page';
        $args = array(
            'label' => __('Question Categories', $this->plugin_name),
            'default' => 20,
            'option' => 'question_categories_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }
        
        $this->question_categories_obj = new Question_Categories_List_Table($this->plugin_name);
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_results(){
        $option = 'per_page';
        $args = array(
            'label' => __('Results', $this->plugin_name),
            'default' => 50,
            'option' => 'quiz_results_per_page'
        );

        add_screen_option($option, $args);
        $this->results_obj = new Results_List_Table($this->plugin_name);
    }

     public function screen_option_all_quiz_reviews(){
        $option = 'per_page';
        $args = array(
            'label' => __('All Reviews', $this->plugin_name),
            'default' => 50,
            'option' => 'quiz_all_reviews_per_page'
        );

        add_screen_option($option, $args);
        $this->all_reviews_obj = new All_Reviews_List_Table($this->plugin_name);
    }

    public function screen_option_settings(){
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
    }

    /**
     * Adding questions from modal to table
     */
    public function add_question_rows(){

        // Run a security check.
        check_ajax_referer( 'quiz-maker-ajax-add-question-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        // Check for permissions.
        if ( ! current_user_can( 'manage_options' ) ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
                'rows' => '',
                'ids' => array()
            ));
            wp_die();
        }

        if( !is_user_logged_in() ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
                'rows' => '',
                'ids' => array()
            ));
            wp_die();
        }

        if ( (isset($_REQUEST["action"]) && $_REQUEST["action"] == "add_question_rows") || (isset($_REQUEST["action"]) && $_REQUEST["action"] == "add_question_rows_top") ) {

            $question_ids = (isset($_REQUEST["ays_questions_ids"]) && !empty($_REQUEST["ays_questions_ids"])) ? array_map( 'sanitize_text_field', $_REQUEST['ays_questions_ids'] ) : array();

            $rows = array();
            $ids = array();
            if (!empty($question_ids)) {
                $question_categories = $this->get_questions_categories();
                $question_categories_array = array();
                foreach($question_categories as $cat){
                    $question_categories_array[$cat['id']] = $cat['title'];
                }
                foreach ($question_ids as $question_id) {
                    $data = $this->get_published_questions_by('id', absint(intval($question_id)));
                    $table_question = (strip_tags(stripslashes($data['question'])));
                    if(isset($data['question']) && strlen($data['question']) != 0){

                        $is_exists_ruby = Quiz_Maker_Admin::ays_quiz_is_exists_needle_tag( $data['question'] , '<ruby>' );

                        if ( $is_exists_ruby ) {
                            $table_question = strip_tags( stripslashes($data['question']), '<ruby><rbc><rtc><rb><rt>' );
                        } else {
                            $table_question = strip_tags(stripslashes($data['question']));
                        }

                    }elseif ((isset($data['question_image']) && $data['question_image'] !='')){
                        $table_question = 'Image question';
                    }

                    switch ( $data['type'] ) {
                        case 'short_text':
                            $ays_question_type = 'short text';
                            break;
                        case 'true_or_false':
                            $ays_question_type = 'true/false';
                            break;
                        default:
                            $ays_question_type = $data['type'];
                            break;
                    }

                    $table_question = $this->ays_restriction_string("word", $table_question, 8);
                    $edit_question_url = "?page=".$this->plugin_name."-questions&action=edit&question=".$data['id'];
                    $rows[] = '<tr class="ays-question-row ui-state-default" data-id="' . $data['id'] . '">
                        <td class="ays-sort"><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>
                        <td>                        
                            <a href="'. $edit_question_url .'" target="_blank" class="ays-edit-question" title="'. __('Edit question', $this->plugin_name) .'">
                                ' . $table_question . '
                            </a> 
                        </td>
                        <td>' . $ays_question_type . '</td>
                        <td>' . $question_categories_array[$data['category_id']] . '</td>
                        <td>' . stripslashes($data['id']) . '</td>
                        <td>
                            <input type="checkbox" class="ays_del_tr">
                            <a href="'. $edit_question_url .'" target="_blank" class="ays-edit-question" title="'. __('Edit question', $this->plugin_name) .'">
                                <i class="ays_fa ays_fa_pencil_square" aria-hidden="true"></i>
                            </a>
                            <a href="javascript:void(0)" class="ays-delete-question" data-id="' . $data['id'] . '">
                                <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>
                            </a>
                        </td>
                   </tr>';
                    $ids[] = $data['id'];
                }
                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                echo json_encode(array(
                    'status' => true,
                    'rows' => $rows,
                    'ids' => $ids
                ));
                wp_die();
            } else {
                ob_end_clean();
                $ob_get_clean = ob_get_clean();
                echo json_encode(array(
                    'status' => true,
                    'rows' => '',
                    'ids' => array()
                ));
                wp_die();
            }
        } else {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => true,
                'rows' => '',
                'ids' => array()
            ));
            wp_die();
        }
    }

    /**
     * @return string
     */
    public function ays_quick_start(){
        global $wpdb;

        // Run a security check.
        check_ajax_referer( 'quiz-maker-ajax-quick-quiz-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        // Check for permissions.
        if ( ! current_user_can( 'manage_options' ) ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
                'quiz_id' => 0
            ));
            wp_die();
        }

        if( !is_user_logged_in() ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
                'quiz_id' => 0
            ));
            wp_die();
        }

        $quiz_title = stripslashes( sanitize_text_field( $_REQUEST['ays_quiz_title'] ) );
        $quiz_description = (isset( $_REQUEST['ays_quick_quiz_description'] ) && $_REQUEST['ays_quick_quiz_description'] != "") ? stripslashes( wp_kses_post( $_REQUEST['ays_quick_quiz_description'] ) ) : "";
        $quiz_cat_id = sanitize_text_field( $_REQUEST['ays_quiz_category'] );
        $questions = array_map( 'sanitize_text_field', $_REQUEST['ays_quick_question'] );
        $questions_type = array_map( 'sanitize_text_field', $_REQUEST['ays_quick_question_type'] );
        $questions_cat = array_map( 'sanitize_text_field', $_REQUEST['ays_quick_question_cat'] );
        $answers_correct = self::recursive_sanitize_text_field( $_REQUEST['ays_quick_answer_correct'] );
        $answers = self::recursive_sanitize_text_field( $_REQUEST['ays_quick_answer'] );
        $quick_quiz_publish = isset($_REQUEST['ays_quick_quiz_publish']) && sanitize_text_field( $_REQUEST['ays_quick_quiz_publish'] ) != "" ? sanitize_text_field( $_REQUEST['ays_quick_quiz_publish'] ) : 1;

        $answers_table = esc_sql( $wpdb->prefix . 'aysquiz_answers' );
        $questions_table = esc_sql( $wpdb->prefix . 'aysquiz_questions' );
        $quizes_table = esc_sql( $wpdb->prefix . 'aysquiz_quizes' );

        $max_id = $this->get_max_id('quizes');
        $ordering = ( $max_id != NULL ) ? ( $max_id + 1 ) : 1;
        
        $questions_ids = '';
        $create_date = current_time( 'mysql' );
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        $author = array(
            'id' => $user->ID,
            'name' => $user->data->display_name
        );
        $options = array(
            'author' => $author,
        );

        $setting_actions = new Quiz_Maker_Settings_Actions(AYS_QUIZ_NAME);

        // Buttons Text
        $buttons_texts_res      = ($setting_actions->ays_get_setting('buttons_texts') === false) ? json_encode(array()) : $setting_actions->ays_get_setting('buttons_texts');
        $buttons_texts          = json_decode( stripcslashes( $buttons_texts_res ) , true);

        $gen_start_button       = (isset($buttons_texts['start_button']) && $buttons_texts['start_button'] != '') ? stripslashes( esc_attr( $buttons_texts['start_button'] ) ) : 'Start';
        $gen_next_button        = (isset($buttons_texts['next_button']) && $buttons_texts['next_button'] != '') ? stripslashes( esc_attr( $buttons_texts['next_button'] ) ) : 'Next';
        $gen_previous_button    = (isset($buttons_texts['previous_button']) && $buttons_texts['previous_button'] != '') ? stripslashes( esc_attr( $buttons_texts['previous_button'] ) ) : 'Prev' ;

        // Enable Quiz Options
        $quiz_enable_options = (isset( $_REQUEST['ays_quick_quiz_enable_options'] ) && $_REQUEST['ays_quick_quiz_enable_options'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_options'] ) ) : "off";
        
        // Settings
        $quick_quiz_enable_randomize_questions              = 'off';
        $quick_quiz_enable_randomize_answers                = 'off';
        $quick_quiz_display_all_questions                   = 'off';
        $quick_quiz_enable_correction                       = 'on';
        $quick_quiz_show_question_category                  = 'off';
        $quick_quiz_enable_pass_count                       = 'off';
        $quick_quiz_show_category                           = 'off';
        $quick_quiz_enable_rate_avg                         = 'off';
        $quick_quiz_show_author                             = 'off';
        $quick_quiz_show_create_date                        = 'off';
        $quick_quiz_enable_next_button                      = 'on';
        $quick_quiz_enable_previous_button                  = 'on';
        $quick_quiz_enable_early_finish                     = 'off';
        $quick_quiz_enable_clear_answer                     = 'off';
        $quick_quiz_enable_enter_key                        = 'on';
        $quick_quiz_display_messages_before_buttons         = 'off';
        $quick_quiz_enable_audio_autoplay                   = 'off';
        $quick_quiz_enable_rtl_direction                    = 'off';
        $quick_quiz_enable_questions_counter                = 'on';
        $quick_quiz_enable_question_image_zoom              = 'off';
        $quick_quiz_enable_leave_page                       = 'on';
        $quick_quiz_enable_see_result_confirm_box           = 'off';
        $quick_quiz_enable_rw_asnwers_sounds                = 'off';
        $quick_quiz_enable_custom_texts_for_buttons         = 'off';

        $quick_quiz_custom_texts_start_button               = $gen_start_button;
        $quick_quiz_custom_texts_next_button                = $gen_next_button;
        $quick_quiz_custom_texts_prev_button                = $gen_previous_button;

        // Results Settings
        $quick_quiz_hide_score                              = 'off';
        $quick_quiz_enable_restart_button                   = 'on';
        $quick_quiz_enable_progress_bar                     = 'on';
        $quick_quiz_enable_average_statistical              = 'on';
        $quick_quiz_disable_store_data                      = 'off';

        // User Data
        $quick_quiz_show_information_form                   = 'on';
        $quick_quiz_autofill_user_data                      = 'off';
        $quick_quiz_display_fields_labels                   = 'off';


        if($quiz_enable_options == 'on'){
            $quick_quiz_enable_randomize_questions = (isset( $_REQUEST['ays_quick_quiz_enable_randomize_questions'] ) && $_REQUEST['ays_quick_quiz_enable_randomize_questions'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_randomize_questions'] ) ) : "off";
            $quick_quiz_enable_randomize_answers = (isset( $_REQUEST['ays_quick_quiz_enable_randomize_answers'] ) && $_REQUEST['ays_quick_quiz_enable_randomize_answers'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_randomize_answers'] ) ) : "off";
            $quick_quiz_display_all_questions = (isset( $_REQUEST['ays_quick_quiz_display_all_questions'] ) && $_REQUEST['ays_quick_quiz_display_all_questions'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_display_all_questions'] ) ) : "off";
            $quick_quiz_enable_correction = (isset( $_REQUEST['ays_quick_quiz_enable_correction'] ) && $_REQUEST['ays_quick_quiz_enable_correction'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_correction'] ) ) : "off";
            $quick_quiz_show_question_category = (isset( $_REQUEST['ays_quick_quiz_show_question_category'] ) && $_REQUEST['ays_quick_quiz_show_question_category'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_question_category'] ) ) : "off";
            $quick_quiz_enable_pass_count = (isset( $_REQUEST['ays_quick_quiz_enable_pass_count'] ) && $_REQUEST['ays_quick_quiz_enable_pass_count'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_pass_count'] ) ) : "off";
            $quick_quiz_show_category = (isset( $_REQUEST['ays_quick_quiz_show_category'] ) && $_REQUEST['ays_quick_quiz_show_category'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_category'] ) ) : "off";
            $quick_quiz_enable_rate_avg = (isset( $_REQUEST['ays_quick_quiz_enable_rate_avg'] ) && $_REQUEST['ays_quick_quiz_enable_rate_avg'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_rate_avg'] ) ) : "off";
            $quick_quiz_show_author = (isset( $_REQUEST['ays_quick_quiz_show_author'] ) && $_REQUEST['ays_quick_quiz_show_author'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_author'] ) ) : "off";
            $quick_quiz_show_create_date = (isset( $_REQUEST['ays_quick_quiz_show_create_date'] ) && $_REQUEST['ays_quick_quiz_show_create_date'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_create_date'] ) ) : "off";
            $quick_quiz_enable_next_button = (isset( $_REQUEST['ays_quick_quiz_enable_next_button'] ) && $_REQUEST['ays_quick_quiz_enable_next_button'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_next_button'] ) ) : "off";
            $quick_quiz_enable_previous_button = (isset( $_REQUEST['ays_quick_quiz_enable_previous_button'] ) && $_REQUEST['ays_quick_quiz_enable_previous_button'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_previous_button'] ) ) : "off";
            $quick_quiz_enable_early_finish = (isset( $_REQUEST['ays_quick_quiz_enable_early_finish'] ) && $_REQUEST['ays_quick_quiz_enable_early_finish'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_early_finish'] ) ) : "off";
            $quick_quiz_enable_clear_answer = (isset( $_REQUEST['ays_quick_quiz_enable_clear_answer'] ) && $_REQUEST['ays_quick_quiz_enable_clear_answer'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_clear_answer'] ) ) : "off";
            $quick_quiz_enable_enter_key = (isset( $_REQUEST['ays_quick_quiz_enable_enter_key'] ) && $_REQUEST['ays_quick_quiz_enable_enter_key'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_enter_key'] ) ) : "off";
            $quick_quiz_display_messages_before_buttons = (isset( $_REQUEST['ays_quick_quiz_display_messages_before_buttons'] ) && $_REQUEST['ays_quick_quiz_display_messages_before_buttons'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_display_messages_before_buttons'] ) ) : "off";
            $quick_quiz_enable_audio_autoplay = (isset( $_REQUEST['ays_quick_quiz_enable_audio_autoplay'] ) && $_REQUEST['ays_quick_quiz_enable_audio_autoplay'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_audio_autoplay'] ) ) : "off";
            $quick_quiz_enable_rtl_direction = (isset( $_REQUEST['ays_quick_quiz_enable_rtl_direction'] ) && $_REQUEST['ays_quick_quiz_enable_rtl_direction'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_rtl_direction'] ) ) : "off";
            $quick_quiz_enable_questions_counter = (isset( $_REQUEST['ays_quick_quiz_enable_questions_counter'] ) && $_REQUEST['ays_quick_quiz_enable_questions_counter'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_questions_counter'] ) ) : "off";
            $quick_quiz_enable_question_image_zoom = (isset( $_REQUEST['ays_quick_quiz_enable_question_image_zoom'] ) && $_REQUEST['ays_quick_quiz_enable_question_image_zoom'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_question_image_zoom'] ) ) : "off";
            $quick_quiz_enable_leave_page = (isset( $_REQUEST['ays_quick_quiz_enable_leave_page'] ) && $_REQUEST['ays_quick_quiz_enable_leave_page'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_leave_page'] ) ) : "off";
            $quick_quiz_enable_see_result_confirm_box = (isset( $_REQUEST['ays_quick_quiz_enable_see_result_confirm_box'] ) && $_REQUEST['ays_quick_quiz_enable_see_result_confirm_box'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_see_result_confirm_box'] ) ) : "off";
            $quick_quiz_enable_rw_asnwers_sounds = (isset( $_REQUEST['ays_quick_quiz_enable_rw_asnwers_sounds'] ) && $_REQUEST['ays_quick_quiz_enable_rw_asnwers_sounds'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_rw_asnwers_sounds'] ) ) : "off";
            $quick_quiz_hide_score = (isset( $_REQUEST['ays_quick_quiz_hide_score'] ) && $_REQUEST['ays_quick_quiz_hide_score'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_hide_score'] ) ) : "off";
            $quick_quiz_enable_restart_button = (isset( $_REQUEST['ays_quick_quiz_enable_restart_button'] ) && $_REQUEST['ays_quick_quiz_enable_restart_button'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_restart_button'] ) ) : "off";
            $quick_quiz_enable_progress_bar = (isset( $_REQUEST['ays_quick_quiz_enable_progress_bar'] ) && $_REQUEST['ays_quick_quiz_enable_progress_bar'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_progress_bar'] ) ) : "off";
            $quick_quiz_enable_average_statistical = (isset( $_REQUEST['ays_quick_quiz_enable_average_statistical'] ) && $_REQUEST['ays_quick_quiz_enable_average_statistical'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_average_statistical'] ) ) : "off";
            $quick_quiz_disable_store_data = (isset( $_REQUEST['ays_quick_quiz_disable_store_data'] ) && $_REQUEST['ays_quick_quiz_disable_store_data'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_disable_store_data'] ) ) : "off";

            $quick_quiz_show_information_form = (isset( $_REQUEST['ays_quick_quiz_show_information_form'] ) && $_REQUEST['ays_quick_quiz_show_information_form'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_show_information_form'] ) ) : "off";
            $quick_quiz_autofill_user_data    = (isset( $_REQUEST['ays_quick_quiz_autofill_user_data'] ) && $_REQUEST['ays_quick_quiz_autofill_user_data'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_autofill_user_data'] ) ) : "off";
            $quick_quiz_display_fields_labels = (isset( $_REQUEST['ays_quick_quiz_display_fields_labels'] ) && $_REQUEST['ays_quick_quiz_display_fields_labels'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_display_fields_labels'] ) ) : "off";
            
            $quick_quiz_enable_custom_texts_for_buttons = (isset( $_REQUEST['ays_quick_quiz_enable_custom_texts_for_buttons'] ) && $_REQUEST['ays_quick_quiz_enable_custom_texts_for_buttons'] == "on") ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_enable_custom_texts_for_buttons'] ) ) : "off";

            // Start button
            $quick_quiz_custom_texts_start_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_start_button']) && $_REQUEST['ays_quick_quiz_custom_texts_start_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_start_button'] ) ) : $gen_start_button;

            // Next button
            $quick_quiz_custom_texts_next_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_next_button']) && $_REQUEST['ays_quick_quiz_custom_texts_next_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_next_button'] ) ) : $gen_next_button;

            // Prev button
            $quick_quiz_custom_texts_prev_button  = (isset($_REQUEST['ays_quick_quiz_custom_texts_prev_button']) && $_REQUEST['ays_quick_quiz_custom_texts_prev_button'] != '') ? stripslashes( sanitize_text_field( $_REQUEST['ays_quick_quiz_custom_texts_prev_button'] ) ) : $gen_previous_button;
        }
        
        foreach ($questions as $question_key => $question) {

            $cat_key = array_search( $question_key, $questions_cat );
            $q_category_id = (isset( $questions_cat[$cat_key] ) && $questions_cat[$cat_key] != "") ? esc_sql( $questions_cat[$cat_key] ) : 1;

            if ( !isset( $questions_type[$question_key] ) || is_null( $questions_type[$question_key] ) ) {
                continue;
            }

            $wpdb->insert($questions_table, array(
                'category_id'   => $q_category_id,
                'question'      => esc_sql( stripslashes($question) ),
                'published'     => 1,
                'type'          => $questions_type[$question_key],
                'create_date'   => esc_sql( $create_date ),
                'options'       => json_encode($options)
            ));
            $question_id = $wpdb->insert_id;
            $questions_ids .= $question_id . ',';
            if ( isset( $answers[$question_key] ) && ! empty( $answers[$question_key] ) ) {

                foreach ($answers[$question_key] as $key => $answer) {
                    $type = $questions_type[$question_key];

                    if($type == "text" || $type == "short_text"){
                        $correct = 1;
                    }else{
                        $correct = ($answers_correct[$question_key][$key] == "true") ? 1 : 0;
                    }
                    $placeholder = '';

                    $wpdb->insert($answers_table, array(
                        'question_id' => esc_sql( $question_id ),
                        'answer' => esc_sql( trim( stripslashes($answer) ) ),
                        'correct' => $correct,
                        'ordering' => $key,
                        'placeholder' => $placeholder
                    ));
                }
            }
        }


        $default_options = array(
            'quiz_theme'                                => 'classic_light',
            'color'                                     => '#5d6cf9',
            'bg_color'                                  => '#fff',
            'text_color'                                => '#000000',
            'height'                                    => 450,
            'width'                                     => 800,
            'timer'                                     => 100,
            'information_form'                          => 'disable',
            'form_name'                                 => '',
            'form_email'                                => '',
            'form_phone'                                => '',
            'enable_logged_users'                       => 'off',
            'image_width'                               => '',
            'image_height'                              => '',
            'enable_correction'                         => $quick_quiz_enable_correction,
            'enable_questions_counter'                  => $quick_quiz_enable_questions_counter,
            'limit_users'                               => 'off',
            'limitation_message'                        => '',
            'redirect_url'                              => '',
            'redirection_delay'                         => '',
            'enable_progress_bar'                       => $quick_quiz_enable_progress_bar,
            'randomize_questions'                       => $quick_quiz_enable_randomize_questions,
            'randomize_answers'                         => $quick_quiz_enable_randomize_answers,
            'enable_questions_result'                   => 'on',
            'enable_average_statistical'                => $quick_quiz_enable_average_statistical,
            'enable_next_button'                        => $quick_quiz_enable_next_button,
            'enable_previous_button'                    => $quick_quiz_enable_previous_button,
            'custom_css'                                => '',
            'enable_restriction_pass'                   => 'off',
            'restriction_pass_message'                  => '',
            'user_role'                                 => '',
            'result_text'                               => '',
            'enable_result'                             => 'off',
            'enable_timer'                              => 'off',
            'enable_pass_count'                         => $quick_quiz_enable_pass_count,
            'enable_quiz_rate'                          => 'off',
            'enable_rate_avg'                           => $quick_quiz_enable_rate_avg,
            'enable_rate_comments'                      => 'off',
            'hide_score'                                => $quick_quiz_hide_score,
            'rate_form_title'                           => '',
            'enable_box_shadow'                         => 'on',
            'box_shadow_color'                          => '#c9c9c9',
            'quiz_border_radius'                        => '8',
            'quiz_bg_image'                             => '',
            'enable_border'                             => 'off',
            'quiz_border_width'                         => '1',
            'quiz_border_style'                         => 'solid',
            'quiz_border_color'                         => '#000',
            'quiz_timer_in_title'                       => 'off',
            'enable_restart_button'                     => $quick_quiz_enable_restart_button,
            'quiz_loader'                               => 'default',
            'create_date'                               => $create_date,
            'author'                                    => $author,
            'autofill_user_data'                        => $quick_quiz_autofill_user_data,
            'display_fields_labels'                     => $quick_quiz_display_fields_labels,
            'quest_animation'                           => 'shake',
            'form_title'                                => '',
            'enable_bg_music'                           => 'off',
            'quiz_bg_music'                             => '',
            'answers_font_size'                         => '15',
            'show_create_date'                          => $quick_quiz_show_create_date,
            'show_author'                               => $quick_quiz_show_author,
            'enable_early_finish'                       => $quick_quiz_enable_early_finish,
            'answers_rw_texts'                          => 'on_passing',
            'disable_store_data'                        => $quick_quiz_disable_store_data,
            'enable_background_gradient'                => 'off',
            'background_gradient_color_1'               => '#000',
            'background_gradient_color_2'               => '#fff',
            'quiz_gradient_direction'                   => 'vertical',
            'redirect_after_submit'                     => 'off',
            'submit_redirect_url'                       => '',
            'submit_redirect_delay'                     => '',
            'progress_bar_style'                        => 'third',
            'enable_exit_button'                        => 'off',
            'exit_redirect_url'                         => '',
            'image_sizing'                              => 'cover',
            'quiz_bg_image_position'                    => 'center center',
            'custom_class'                              => '',
            'enable_social_buttons'                     => 'off',
            'enable_social_links'                       => 'off',
            'social_links' => array(
                'linkedin_link'     => '',
                'facebook_link'     => '',
                'twitter_link'      => '',
                'vkontakte_link'    => '',
                'instagram_link'    => '',
                'youtube_link'      => '',
                'behance_link'      => '',
            ),
            'show_quiz_title'                           => 'on',
            'show_quiz_desc'                            => 'on',
            'show_login_form'                           => 'off',
            'mobile_max_width'                          => '',
            'limit_users_by'                            => 'ip',
            'activeInterval'                            => '',
            'deactiveInterval'                          => '',
            'active_date_check'                         => 'off',
            'active_date_pre_start_message'             => __("The quiz will be available soon!", 'quiz-maker'),
            'active_date_message'                       => __("The quiz has expired!", 'quiz-maker'),
            'explanation_time'                          => '4',
            'enable_clear_answer'                       => $quick_quiz_enable_clear_answer,
            'show_category'                             => $quick_quiz_show_category,
            'show_question_category'                    => $quick_quiz_show_question_category,
            'display_score'                             => 'by_percantage',
            'enable_rw_asnwers_sounds'                  => $quick_quiz_enable_rw_asnwers_sounds,
            'ans_right_wrong_icon'                      => 'none',
            'quiz_bg_img_in_finish_page'                => 'off',
            'finish_after_wrong_answer'                 => 'off',
            'enable_enter_key'                          => $quick_quiz_enable_enter_key,
            'buttons_text_color'                        => '#ffffff',
            'buttons_position'                          => 'center',
            'show_questions_explanation'                => 'on_results_page',
            'enable_audio_autoplay'                     => $quick_quiz_enable_audio_autoplay,
            'buttons_size'                              => 'large',
            'buttons_font_size'                         => '18',
            'buttons_width'                             => '',
            'buttons_left_right_padding'                => '36',
            'buttons_top_bottom_padding'                => '14',
            'buttons_border_radius'                     => '8',
            'enable_leave_page'                         => $quick_quiz_enable_leave_page,
            'enable_see_result_confirm_box'             => $quick_quiz_enable_see_result_confirm_box,
            'enable_tackers_count'                      => 'off',
            'pass_score'                                => '0',
            'question_font_size'                        => '16',
            'quiz_width_by_percentage_px'               => 'pixels',
            'questions_hint_icon_or_text'               => 'default',
            'enable_early_finsh_comfirm_box'            => 'on',
            'enable_questions_ordering_by_cat'          => 'off',
            'show_schedule_timer'                       => 'off',
            'show_timer_type'                           => 'countdown',
            'quiz_loader_text_value'                    => '',
            'hide_correct_answers'                      => 'off',
            'show_information_form'                     => $quick_quiz_show_information_form,
            'quiz_loader_custom_gif'                    => '',
            'disable_hover_effect'                      => 'off',
            'quiz_loader_custom_gif_width'              => 100,
            'show_answers_numbering'                    => 'none',
            'quiz_box_shadow_x_offset'                  => 0,
            'quiz_box_shadow_y_offset'                  => 0,
            'quiz_box_shadow_z_offset'                  => 15,
            'quiz_question_text_alignment'              => 'center',
            'quiz_arrow_type'                           => 'default',
            'quiz_show_wrong_answers_first'             => 'off',
            'quiz_display_all_questions'                => $quick_quiz_display_all_questions,
            'enable_rtl_direction'                      => $quick_quiz_enable_rtl_direction,
            'quiz_enable_question_image_zoom'           => $quick_quiz_enable_question_image_zoom,
            'quiz_timer_red_warning'                    => 'off',
            'quiz_schedule_timezone'                    => get_option( 'timezone_string' ),
            'questions_hint_button_value'               => '',
            'quiz_tackers_message'                      => __( "This quiz is expired!", 'quiz-maker' ),
            'quiz_enable_linkedin_share_button'         => 'on',
            'quiz_enable_facebook_share_button'         => 'on',
            'quiz_enable_twitter_share_button'          => 'on',
            'quiz_make_responses_anonymous'             => 'off',
            'quiz_make_all_review_link'                 => 'off',
            'show_questions_numbering'                  => 'none',
            'quiz_message_before_timer'                 => '',
            'enable_full_screen_mode'                   => 'off',
            'quiz_enable_password_visibility'           => 'off',
            'question_mobile_font_size'                 => 16,
            'answers_mobile_font_size'                  => 15,
            'social_buttons_heading'                    => '',
            'quiz_enable_vkontakte_share_button'        => 'on',
            'answers_border'                            => 'on',
            'answers_border_width'                      => '1',
            'answers_border_style'                      => 'solid',
            'answers_border_color'                      => '#dddddd',
            'social_links_heading'                      => '',
            'quiz_enable_question_category_description' => 'off',
            'answers_margin'                            => '12',
            'quiz_message_before_redirect_timer'        => '',
            'buttons_mobile_font_size'                  => 18,
            'answers_box_shadow'                        => 'off',
            'answers_box_shadow_color'                  => '#000',
            'quiz_answer_box_shadow_x_offset'           => 0,
            'quiz_answer_box_shadow_y_offset'           => 0,
            'quiz_answer_box_shadow_z_offset'           => 10,
            'quiz_create_author'                        => $user_id,
            'quiz_create_author'                        => $user_id,
            'quiz_enable_title_text_shadow'             => "off",
            'quiz_title_text_shadow_color'              => "#333",
            'quiz_title_text_shadow_x_offset'           => 2,
            'quiz_title_text_shadow_y_offset'           => 2,
            'quiz_title_text_shadow_z_offset'           => 2,
            'quiz_title_font_size'                      => 28,
            'quiz_title_mobile_font_size'               => 20,
            'quiz_password_width'                       => "",
            'quiz_review_placeholder_text'              => "",
            'quiz_enable_results_toggle'                => "off",
            'quiz_review_thank_you_message'             => "",
            'quiz_review_enable_comment_field'          => "on",
            'quest_explanation_font_size'               => "16",
            'quest_explanation_mobile_font_size'        => "16",
            'wrong_answers_font_size'                   => "16",
            'wrong_answers_mobile_font_size'            => "16",
            'right_answers_font_size'                   => "16",
            'right_answers_mobile_font_size'            => "16",
            'note_text_font_size'                       => "14",
            'note_text_mobile_font_size'                => "14",
            'quiz_questions_numbering_by_category'      => "off",
            'quiz_enable_custom_texts_for_buttons'      => $quick_quiz_enable_custom_texts_for_buttons,
            'quiz_custom_texts_start_button'            => $quick_quiz_custom_texts_start_button,
            'quiz_custom_texts_next_button'             => $quick_quiz_custom_texts_next_button,
            'quiz_custom_texts_prev_button'             => $quick_quiz_custom_texts_prev_button,
            'quiz_custom_texts_clear_button'            => "Clear",
            'quiz_custom_texts_finish_button'           => "Finish",
            'quiz_custom_texts_see_results_button'      => "See Result",
            'quiz_custom_texts_restart_quiz_button'     => "Restart quiz",
            'quiz_custom_texts_send_feedback_button'    => "Send feedback",
            'quiz_custom_texts_load_more_button'        => "Load more",
            'quiz_custom_texts_exit_button'             => "Exit",
            'quiz_display_messages_before_buttons'      => $quick_quiz_display_messages_before_buttons,
        );


        $questions_ids = rtrim($questions_ids, ",");
        $wpdb->insert($quizes_table, array(
            'title'             => $quiz_title,//esc_sql( $quiz_title ),
            'description'       => $quiz_description,//esc_sql( $quiz_description ),
            'question_ids'      => $questions_ids,
            'published'         => $quick_quiz_publish,
            'options'           => json_encode($default_options),
            'quiz_category_id'  => $quiz_cat_id,
            'ordering'          => $ordering,
        ));
        $quiz_id = $wpdb->insert_id;
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode(array(
            'status' => true,
            'quiz_id' => $quiz_id
        ));
        wp_die();
    }
    
    /**
     * Recursive sanitation for an array
     * 
     * @param $array
     *
     * @return mixed
     */
    public static function recursive_sanitize_text_field($array) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = self::recursive_sanitize_text_field($value);
            } else {
                $value = sanitize_text_field( $value );
            }
        }

        return $array;
    }
    
    public static function get_max_id($table) {
        global $wpdb;
        $quiz_table = $wpdb->prefix . 'aysquiz_'.$table;

        $sql = "SELECT max(id) FROM {$quiz_table}";

        $result = intval($wpdb->get_var($sql));

        return $result;
    }
    
    public function ays_show_results(){
        global $wpdb;
        $results_table = $wpdb->prefix . "aysquiz_reports";
        $questions_table = $wpdb->prefix . "aysquiz_questions";

        // Run a security check.
        check_ajax_referer( 'quiz-maker-ajax-results-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        // Check for permissions.
        if ( ! current_user_can( 'manage_options' ) ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
                "rows" => ""
            ));
            wp_die();
        }

        if (isset($_REQUEST['action']) && sanitize_text_field( $_REQUEST['action'] ) == 'ays_show_results') {
            $id = absint(intval($_REQUEST['result']));
            $results = $wpdb->get_row("SELECT * FROM {$results_table} WHERE id={$id}", "ARRAY_A");            
            $score = $results['score'];
            // $user_id = intval($results['user_id']);
            $user_id = isset($results['user_id']) ? intval($results['user_id']) : null;
            $quiz_id = isset($results['quiz_id']) ? intval($results['quiz_id']) : null;
            
            $user = get_user_by('id', $user_id);

            if( !$user ){
                $user_display_name = __( "Deleted user", $this->plugin_name );
            } else {
                $user_display_name = isset( $user->data->display_name ) && $user->data->display_name != "" ? $user->data->display_name : __( "Deleted user", $this->plugin_name );
            }
            
            $user_ip = $results['user_ip'];
            $options = json_decode($results['options']);
            $user_attributes = isset( $options->attributes_information ) ? $options->attributes_information : null;
            $start_date = $results['start_date'];
            $duration = isset( $options->passed_time ) ? $options->passed_time : '';
            $rate_id = isset($options->rate_id) ? $options->rate_id : null;
            $rate = $this->ays_quiz_rate($rate_id);
            $calc_method = isset($options->calc_method) ? $options->calc_method : 'by_correctness';
            
            $from = self::get_user_country_by_ip( $user_ip );

            $note_text = ( isset($options->note_text) && $options->note_text != '' ) ? sanitize_text_field( stripslashes( $options->note_text ) ) : '';

            $settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);
            $settings_options = ($settings_obj->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes($settings_obj->ays_get_setting('options') ), true);
            
            $ays_quiz_show_result_info_user_ip = isset($settings_options['ays_quiz_show_result_info_user_ip']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user_ip'] ) : 'on';
            $ays_quiz_show_result_info_user_id = isset($settings_options['ays_quiz_show_result_info_user_id']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user_id'] ) : 'on';
            $ays_quiz_show_result_info_user = isset($settings_options['ays_quiz_show_result_info_user']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user'] ) : 'on';
            $ays_quiz_show_result_info_user_email = isset($settings_options['ays_quiz_show_result_info_user_email']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user_email'] ) : 'on';
            $ays_quiz_show_result_info_user_name = isset($settings_options['ays_quiz_show_result_info_user_name']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user_name'] ) : 'on';
            $ays_quiz_show_result_info_user_phone = isset($settings_options['ays_quiz_show_result_info_user_phone']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_user_phone'] ) : 'on';

            $ays_quiz_show_result_info_start_date = isset($settings_options['ays_quiz_show_result_info_start_date']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_start_date'] ) : 'on';
            $ays_quiz_show_result_info_duration = isset($settings_options['ays_quiz_show_result_info_duration']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_duration'] ) : 'on';
            $ays_quiz_show_result_info_score = isset($settings_options['ays_quiz_show_result_info_score']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_score'] ) : 'on';
            $ays_quiz_show_result_info_rate = isset($settings_options['ays_quiz_show_result_info_rate']) ? sanitize_text_field( $settings_options['ays_quiz_show_result_info_rate'] ) : 'on';


            $pro_content = array();

            $pro_content[] = '<div class="only_pro only_pro_save_as_default" style="margin: 0 10px;" title="'.__("This property available only in pro version",$this->plugin_name) .'">';
                $pro_content[] = '<div class="pro_features pro_features_popup">';
                    $pro_content[] = '<div class="pro-features-popup-conteiner">';
                        $pro_content[] = '<div class="pro-features-popup-title">';
                        $pro_content[] = __("Export Individual Result", $this->plugin_name);
                        $pro_content[] = '</div>';

                        $pro_content[] = '<div class="pro-features-popup-content" data-link="https://www.youtube.com/watch?v=vrKgo74ZMzI">';
                            $pro_content[] = '<p>';
                                $pro_content[] = sprintf( __("With the excellent plugin Quiz Maker, you can %s export a Detailed report of the Individual Result %s with just a few clicks.", $this->plugin_name),
                                    "<strong>",
                                    "</strong>"
                                );
                            $pro_content[] = '</p>';

                            $pro_content[] = '<p>';
                                $pro_content[] = sprintf( __("You can export detailed reports whether as a %s PDF file %s or as an %s XLSX file. %s", $this->plugin_name),
                                    "<strong>",
                                    "</strong>",
                                    "<strong>",
                                    "</strong>"
                                );
                            $pro_content[] = '</p>';

                            $pro_content[] = '<p>';
                                $pro_content[] = __("So choose the variant that suits you and easily export the detailed report.", $this->plugin_name);
                            $pro_content[] = '</p>';

                            $pro_content[] = '<div>';
                                $pro_content[] = '<a href="https://ays-pro.com/wordpress-quiz-maker-user-manual" target="_blank"> '. __("See Documentation", $this->plugin_name). '</a>';
                            $pro_content[] = '</div>';
                        $pro_content[] = '</div>';

                        $pro_content[] = '<div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-xlsx-export">';
                            $pro_content[] = __("Upgrade PRO NOW", $this->plugin_name);
                        $pro_content[] = '</div>';
                    $pro_content[] = '</div>';
                $pro_content[] = '</div>';
                $pro_content[] = '<div>';

                    $pro_content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-pdf-export" target="_blank" class="ays-pro-a" style="margin: 0 10px;">';
                        $pro_content[] = '<span type="button" class="disabled-button" title="This property available only in pro version">'.__("PDF", $this->plugin_name).'</span>';
                    $pro_content[] = '</a>';

                    $pro_content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-xlsx-export" target="_blank" class="ays-pro-a">';
                        $pro_content[] = '<span type="button" class="disabled-button" title="This property available only in pro version">'.__("XLSX", $this->plugin_name).'</span>';
                    $pro_content[] = '</a>';
                $pro_content[] = '</div>';
            $pro_content[] = '</div>';

            $pro_content = implode('', $pro_content);


            $admin_note_content = array();

            $admin_note_content[] = '<div class="only_pro only_pro_save_as_default" style="margin: 0 10px;" title="'.__("This property available only in pro version",$this->plugin_name) .'">';
                $admin_note_content[] = '<div class="pro_features pro_features_popup">';
                    $admin_note_content[] = '<div class="pro-features-popup-conteiner">';
                        $admin_note_content[] = '<div class="pro-features-popup-title">';
                        $admin_note_content[] = __("Admin Note", $this->plugin_name);
                        $admin_note_content[] = '</div>';

                        $admin_note_content[] = '<div class="pro-features-popup-content" data-link="https://youtu.be/MdbEsayrPUc">';
                            $admin_note_content[] = '<p>';
                                $admin_note_content[] = sprintf( __("The Admin Note feature of the WordPress quiz plugin is a great tool for %s customizing each detailed report. %s", $this->plugin_name),
                                    "<strong>",
                                    "</strong>"
                                );
                            $admin_note_content[] = '</p>';

                            $admin_note_content[] = '<p>';
                                $admin_note_content[] = sprintf( __("This feature allows you %s to add additional information %s about each user on the detailed report page.", $this->plugin_name),
                                    "<strong>",
                                    "</strong>"
                                );
                            $admin_note_content[] = '</p>';

                            $admin_note_content[] = '<p>';
                                $admin_note_content[] = __("Just type your text in the Admin Note field and save it on detailed reports easily.", $this->plugin_name);
                            $admin_note_content[] = '</p>';

                            $admin_note_content[] = '<div>';
                                $admin_note_content[] = '<a href="https://ays-pro.com/wordpress-quiz-maker-user-manual" target="_blank"> '. __("See Documentation", $this->plugin_name). '</a>';
                            $admin_note_content[] = '</div>';
                        $admin_note_content[] = '</div>';

                        $admin_note_content[] = '<div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-admin-note">';
                            $admin_note_content[] = __("Upgrade PRO NOW", $this->plugin_name);
                        $admin_note_content[] = '</div>';
                    $admin_note_content[] = '</div>';
                $admin_note_content[] = '</div>';
                $admin_note_content[] = '<div>';

                    $admin_note_content[] = '<div class="ays-quiz-click-for-admin-note">';
                        $admin_note_content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=pro-popup-admin-note" target="_blank" class="ays-pro-a">';
                            $admin_note_content[] = '<button class="button button-primary disabled-button" style="color:#ffffff !important; font-weight:normal;" title="This property available only in pro version">';
                                $admin_note_content[] = __( 'Click For Admin Note', $this->plugin_name );
                            $admin_note_content[] = '</button>';
                        $admin_note_content[] = '</a>';
                    $admin_note_content[] = '</div>';

                $admin_note_content[] = '</div>';
            $admin_note_content[] = '</div>';

            $admin_note_content = implode('', $admin_note_content);

            
            $row = "<table id='ays-results-table'>";

            $row .= '<tr class="ays_result_element">
                        <td colspan="3">
                            <div class="ays-quiz-admin-note">
                                '. $admin_note_content .'
                            </div>
                        </td>
                    </tr>';
            
            $row .= '<tr class="ays_result_element">
                        <td colspan="3"><h1>' . __('User Information',$this->plugin_name) . '</h1></td>
                        <td style="text-align: right; display:flex; justify-content: flex-end;">
                            <span style="min-width: 70px;">'.__("Export to", $this->plugin_name).'</span>
                            '. $pro_content .'   
                        </td>
                    </tr>';       
            
            if ($ays_quiz_show_result_info_user_ip == 'on') {
                if ($user_ip != '') {
                    $row .= '<tr class="ays_result_element">
                                <td>User IP</td>
                                <td colspan="3">' . $from . '</td>
                            </tr>';
                }
            }
            
            $user_name = $user_id === 0 ? __( "Guest", $this->plugin_name ) : $user_display_name;

            if( $ays_quiz_show_result_info_user_id == 'on' ){
                if($user_id !== 0){
                    $row .= '<tr class="ays_result_element">
                            <td>User ID</td>
                            <td colspan="3">' . $user_id . '</td>                    
                        </tr>';
                }
            }
            if ($ays_quiz_show_result_info_user == 'on') {
                $row .= '<tr class="ays_result_element">
                        <td>User</td>
                        <td colspan="3">' . $user_name . '</td>
                    </tr>';
            }
            
            if(isset($results['user_email']) && $results['user_email'] !== '' && $ays_quiz_show_result_info_user_email == 'on'){
                $row .= "<tr class=\"ays_result_element\">
                        <td>".__('Email',$this->plugin_name)."</td>
                        <td colspan='3'>".stripslashes($results['user_email'])."</td>
                     </tr>";
            }
            if(isset($results['user_name']) && $results['user_name'] !== '' && $ays_quiz_show_result_info_user_name == 'on'){
                $row .= "<tr class=\"ays_result_element\">
                        <td>".__('Name',$this->plugin_name)."</td>
                        <td colspan='3'>".stripslashes($results['user_name'])."</td>
                     </tr>";
            }
            if(isset($results['user_phone']) && $results['user_phone'] !== '' && $ays_quiz_show_result_info_user_phone == 'on'){
                $row .= "<tr class=\"ays_result_element\">
                        <td>".__('Phone',$this->plugin_name)."</td>
                        <td colspan='3'>".stripslashes($results['user_phone'])."</td>
                     </tr>";
            }
            if ($user_attributes !== null) {

                foreach ($user_attributes as $name => $value) {
                    $attr_value = stripslashes($value) == '' ? '-' : stripslashes($value);
                    $row .= '<tr class="ays_result_element">
                            <td>' . stripslashes($name) . '</td>
                            <td colspan="3">' . $attr_value . '</td>
                        </tr>';
                }
            }

            $row .= apply_filters( 'ays_qm_track_users_contents', '', $id );
            
            $row .= '<tr class="ays_result_element">
                        <td colspan="4"><h1>' . __('Quiz Information',$this->plugin_name) . '</h1></td>
                    </tr>';
            if(isset($rate['score'])){
                $rate_html = '<tr style="vertical-align: top;" class="ays_result_element">
                    <td>'.__('Rate',$this->plugin_name).'</td>
                    <td>'. __("Rate Score", $this->plugin_name).":<br>" . $rate['score'] . '</td>
                    <td colspan="2" style="max-width: 200px;">'. __("Review", $this->plugin_name).":<br>" . nl2br($rate['review']) . '</td>
                </tr>';
            }else{
                $rate_html = '<tr class="ays_result_element">
                    <td>'.__('Rate',$this->plugin_name).'</td>
                    <td colspan="3">' . nl2br($rate['review']) . '</td>
                </tr>';
            }

            if ($ays_quiz_show_result_info_start_date == 'on') {
                $row .= '<tr class="ays_result_element">
                            <td>'.__('Start date',$this->plugin_name).'</td>
                            <td colspan="3">' . $start_date . '</td>
                        </tr>';                        
            }

            if( $ays_quiz_show_result_info_duration == 'on' ) {
                $row .= '                      
                    <tr class="ays_result_element">
                        <td>'.__('Duration',$this->plugin_name).'</td>
                        <td colspan="3">' . $duration . '</td>
                    </tr>';
            }

            if( $ays_quiz_show_result_info_score == 'on' ){
                $row .= '
                    <tr class="ays_result_element">
                        <td>'.__('Score',$this->plugin_name).'</td>
                        <td colspan="3">' . $score . '%</td>
                    </tr>';
            }

            if( $ays_quiz_show_result_info_rate == 'on' ){
                $row .= $rate_html;
            }



                    
            if(! empty($options->correctness)){
                $row .= '<tr class="ays_result_element">
                            <td colspan="3"><h1>' . __('Questions',$this->plugin_name) . '</h1></td>
                            <td>
                                <div class="ays_result_toogle_block">
                                    <span class="ays-show-quest-toggle quest-toggle-all">'. __("All", $this->plugin_name) .'</span>
                                    <input type="checkbox" class="ays_toggle ays_toggle_slide" id="ays_show_questions_toggle" checked>
                                    <label for="ays_show_questions_toggle" class="ays_switch_toggle">Toggle</label>
                                    <span class="ays-show-quest-toggle quest-toggle-failed">'. __("Failed", $this->plugin_name) .'</span>
                                </div>
                            </td>
                        </tr>';                
                $index = 1;
                //$user_exp = array();
                //if( isset( $results['user_explanation'] ) && $results['user_explanation'] != '' || $results['user_explanation'] !== null){
                //    $user_exp = json_decode($results['user_explanation'], true);
                //}
                
                foreach ($options->correctness as $key => $option) {
                    if (strpos($key, 'question_id_') !== false) {
                        $question_id = absint(intval(explode('_', $key)[2]));
                        $question = $wpdb->get_row("SELECT * FROM {$questions_table} WHERE id={$question_id}", "ARRAY_A");
                        
                        if ( is_null( $question ) || empty( $question )  ) {
                            continue;
                        }

                        $qoptions = isset($question['options']) && $question['options'] != '' ? json_decode($question['options'], true) : array();
                        $use_html = isset($qoptions['use_html']) && $qoptions['use_html'] == 'on' ? true : false;
                        $correct_answers = $this->get_correct_answers($question_id);
                        $is_text_type = $this->question_is_text_type($question_id);
                        $text_type = $this->text_answer_is($question_id);
                        $not_multiple_text_types = array("number", "date");
                        if($text_type){
                            $user_answered = $this->get_user_text_answered($options->user_answered, $key);                            
                        }else{
                            $user_answered = $this->get_user_answered($options->user_answered, $key);
                        }
                        $ans_point = $option;
                        $ans_point_class = 'success';
                        if(is_array($user_answered)){
                            $user_answered = $user_answered['message'];
                            $ans_point = '-';
                            $ans_point_class = 'error';
                        }
                        $tr_class = "ays_result_element";
                        //if(isset($user_exp[$question_id])){
                        //    $tr_class = "";
                        //}

                        $not_influance_check = isset($question['not_influence_to_score']) && $question['not_influence_to_score'] == 'on' ? false : true;
                        if (!$not_influance_check) {
                            $not_influance_check_td = ' colspan="2" ';
                        }else{
                            $not_influance_check_td = '';                            
                        }

                        $correct_row = $option == true ? 'tr_success' : '';

                        $question_image = isset( $question["question_image"] ) && $question["question_image"] != '' ? $question["question_image"] : '';
                        $question_title = isset( $question["question"] ) && $question["question"] != '' ? $question["question"] : '';
                        if($calc_method == 'by_correctness'){
                            if ($option == true) {
                                $row .= '<tr class="'.$tr_class.' '.$correct_row.'">
                                            <td>'.__('Question',$this->plugin_name).' ' . $index . ' :<br/>';
                                if( $question_image != '' ){
                                    $row .= '<img class="ays-quiz-question-image-in-report" src="' . $question_image . '"><br/>';
                                }
                                $row .= (stripslashes($question["question"])) . 
                                    '</td>';
                                if($is_text_type && ! in_array($text_type, $not_multiple_text_types)){
                                    $c_answers = explode('%%%', $correct_answers);
                                    $c_answer = $c_answers[0];
                                    foreach($c_answers as $c_ans){
                                        if(strtolower(trim($user_answered)) == strtolower(trim($c_ans))){
                                            $c_answer = $c_ans;
                                            break;
                                        }
                                    }
                                    $row .='<td>'.__('Correct answer',$this->plugin_name).':<br/>';
                                    $row .= '<p class="success">' . esc_attr(stripslashes($c_answer)) . '</p>';
                                    $row .='</td>';
                                }else{
                                    if($text_type == 'date'){
                                        $correct_answers = date( 'm/d/Y', strtotime( $correct_answers ) );
                                    }
                                    $correct_answer_content = esc_attr( stripslashes( $correct_answers ) );
                                    if($use_html){
                                        $correct_answer_content = stripslashes( $correct_answers );
                                    }
                                    $row .='<td>'.__('Correct answer',$this->plugin_name).':<br/><p class="success">' . $correct_answer_content . '</p></td>';
                                }
                                
                                if($text_type == 'date'){
                                    if(self::validateDate($user_answered, 'Y-m-d')){
                                        $user_answered = date( 'm/d/Y', strtotime( $user_answered ) );
                                    }
                                }
                                $user_answer_content = esc_attr( stripslashes( $user_answered ) );
                                if($use_html){
                                    $user_answer_content = stripslashes( $user_answered );
                                }
                                $row .='<td '.$not_influance_check_td.'>'.__('User answered',$this->plugin_name).':<br/><p class="success">' . $user_answer_content . '</p></td>';
                                if ($not_influance_check) {
                                    $row .='<td>
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                                    <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                                    <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                                </svg>
                                                <p class="success">'.__('Succeed',$this->plugin_name).'!</p>
                                            </td>';
                                }
                                $row .= '</tr>';
                            } else {
                                $row .= '<tr class="'.$tr_class.'">
                                            <td>'.__('Question',$this->plugin_name).' ' . $index . ' :<br/>';
                                if( $question_image != '' ){
                                    $row .= '<img class="ays-quiz-question-image-in-report" src="' . $question_image . '"><br/>';
                                }
                                $row .= (stripslashes($question_title)) . 
                                    '</td>';
                                if($is_text_type && ! in_array($text_type, $not_multiple_text_types)){
                                    $c_answers = explode('%%%', $correct_answers);
                                    $row .= '<td>'.__('Correct answer',$this->plugin_name).':<br/>';
                                    $row .= '<p class="success">' . esc_attr(stripslashes($c_answers[0])) . '</p>';
                                    $row .= '</td>';
                                }else{
                                    if($text_type == 'date'){
                                        $correct_answers = date( 'm/d/Y', strtotime( $correct_answers ) );
                                    }
                                    $correct_answer_content = esc_attr( stripslashes( $correct_answers ) );
                                    if($use_html){
                                        $correct_answer_content = stripslashes( $correct_answers );
                                    }
                                    $row .= '<td>'.__('Correct answer',$this->plugin_name).':<br/><p class="success">' . $correct_answer_content . '</p></td>';
                                }
                                                
                                if($text_type == 'date'){
                                    if(self::validateDate($user_answered, 'Y-m-d')){
                                        $user_answered = date( 'm/d/Y', strtotime( $user_answered ) );
                                    }
                                }
                                $user_answer_content = esc_attr( stripslashes( $user_answered ) );
                                if($use_html){
                                    $user_answer_content = stripslashes( $user_answered );
                                }
                                $row .= '<td '.$not_influance_check_td.'>'.__('User answered',$this->plugin_name).':<br/><p class="error">' . $user_answer_content . '</p></td>';
                                if ($not_influance_check) {
                                    $row .='<td>
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                            <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                            <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/>
                                            <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/>
                                        </svg>
                                        <p class="error">'.__('Failed',$this->plugin_name).'!</p>
                                    </td>';
                                }
                                $row .= '</tr>';
                            }
                        }elseif($calc_method == 'by_points'){
                            $row .= '<tr class="'.$tr_class.'">
                                        <td>'.__('Question',$this->plugin_name).' ' . $index . ' :<br/>';
                            if( $question_image != '' ){
                                $row .= '<img class="ays-quiz-question-image-in-report" src="' . $question_image . '"><br/>';
                            }
                            $row .= (stripslashes($question["question"])) . 
                                    '</td>';
                            $row .= '<td>'.__('User answered',$this->plugin_name).':<br/><p class="'.$ans_point_class.'">' . esc_attr(stripslashes($user_answered)) . '</p></td>
                                    <td>'.__('Answer point',$this->plugin_name).':<br/><p class="'.$ans_point_class.'">' . esc_attr($ans_point) . '</p></td>
                                </tr>';
                            
                        }
                        $index++;
                        //if(isset($user_exp[$question_id])){
                        //    $row .= '<tr class="ays_result_element">
                        //        <td>'.__('User explanation for this question',$this->plugin_name).'</td>
                        //        <td colspan="3">'.$user_exp[$question_id].'</td>
                        //    </tr>';
                        //}
                    }
                }
            }
            $row .= "</table>";
            
            $sql = "UPDATE $results_table SET `read`=1 WHERE `id`=$id";
            $wpdb->get_var($sql);

            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                "status" => true,
                "rows" => $row
            ));
            wp_die();
        }
    }
    
    protected function ays_quiz_rate( $id ) {
        global $wpdb;
        if($id === '' || $id === null){
            $reason = __("No rate provided", $this->plugin_name);
            $output = array(
                "review" => $reason,
            );
        }else{
            $rate = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}aysquiz_rates WHERE id={$id}", "ARRAY_A");
            $output = array();
            if($rate !== null){
                $review = $rate['review'];
                $reason = stripslashes($review);
                if($reason == ''){
                    $reason = __("No review provided", $this->plugin_name);
                }
                $score = $rate['score'];
                $output = array(
                    "score" => $score,
                    "review" => $reason,
                );
            }else{
                $reason = __("No rate provided", $this->plugin_name);
                $output = array(
                    "review" => $reason,
                );
            }
        }
        return $output;
    }

    public function get_correct_answers($id){
        global $wpdb;
        $answers_table = $wpdb->prefix . "aysquiz_answers";
        $correct_answers = $wpdb->get_results("SELECT answer FROM {$answers_table} WHERE correct=1 AND question_id={$id}");
        $text = "";
        foreach ($correct_answers as $key => $correct_answer) {
            if ($key == (count($correct_answers) - 1))
                $text .= $correct_answer->answer;
            else
                $text .= $correct_answer->answer . ',';
        }
        return $text;
    }

    public function get_user_answered($user_choice, $key){
        global $wpdb;
        $answers_table = $wpdb->prefix . "aysquiz_answers";
        $choices = $user_choice->$key;
        
        if($choices == ''){
            return array(
                'message' => __( "The user has not answered this question.", $this->plugin_name ),
                'status' => false
            );
        }
        $text = array();
        if (is_array($choices)) {
            foreach ($choices as $choice) {
                $result = $wpdb->get_row("SELECT answer FROM {$answers_table} WHERE id={$choice}", 'ARRAY_A');
                $text[] = $result['answer'];
            }
            $text = implode(', ', $text);
        } else {
            $result = $wpdb->get_row("SELECT answer FROM {$answers_table} WHERE id={$choices}", 'ARRAY_A');
            $text = $result['answer'];
        }
        return $text;
    }

    public function get_user_text_answered($user_choice, $key){
        
        if($user_choice->$key == ""){
            $choices = array(
                'message' => __( "The user has not answered this question.", $this->plugin_name ),
                'status' => false
            );
        }else{
            $choices = trim($user_choice->$key);
        }
        
        return $choices;
    }
    
    public function question_is_text_type($question_id){
        global $wpdb;
        $questions_table = $wpdb->prefix . "aysquiz_questions";
        $question_id = absint(intval($question_id));
        $text_types = array('text', 'short_text', 'number', 'date');
        $get_answers = $wpdb->get_var("SELECT type FROM {$questions_table} WHERE id={$question_id}");
        if (in_array($get_answers, $text_types)) {
            return $get_answers;
        }
        return false;
    }

    public function text_answer_is($question_id){
        global $wpdb;
        $questions_table = $wpdb->prefix . "aysquiz_questions";
        $question_id = absint(intval($question_id));

        $text_types = array('text', 'short_text', 'number', 'date');
        $get_answers = $wpdb->get_var("SELECT type FROM {$questions_table} WHERE id={$question_id}");

        if (in_array($get_answers, $text_types)) {
            return $get_answers;
        }
        return false;
    }

    /**
     * Changes previos version db structure
     */
    public function ays_change_db_questions(){
        global $wpdb;
        $quiz_table = $wpdb->prefix . 'aysquiz_quizes';

        $sql = "SELECT id, question_ids FROM {$quiz_table}";

        $rows = $wpdb->get_results($sql, 'ARRAY_A');


        foreach ($rows as $key => $row) {
            if (strpos($row['question_ids'], '***') !== false) {
                $question_ids = implode(',', explode('***', $row['question_ids']));
                $wpdb->update(
                    $quiz_table,
                    array('question_ids' => $question_ids),
                    array('id' => $row['id']),
                    array('%s'),
                    array('%d')
                );
            }
        }
    }
    
    public function get_questions_categories(){
        global $wpdb;
        $categories_table = $wpdb->prefix . "aysquiz_categories";
        $get_cats = $wpdb->get_results("SELECT * FROM {$categories_table}", ARRAY_A);
        return $get_cats;
    }
    
    public function get_published_questions_by($key, $value) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_questions WHERE {$key} = {$value};";

        $results = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $results;

    }

    public static function ays_get_quiz_options(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'aysquiz_quizes';
        $res = $wpdb->get_results("SELECT id, title FROM $table_name");
        $aysGlobal_array = array();

        foreach ($res as $ays_res_options) {
            $aysStatic_array = array();
            $aysStatic_array[] = $ays_res_options->id;
            $aysStatic_array[] = $ays_res_options->title;
            $aysGlobal_array[] = $aysStatic_array;
        }
        return $aysGlobal_array;
    }

    function ays_quiz_register_tinymce_plugin($plugin_array){
        $this->settings_obj = new Quiz_Maker_Settings_Actions($this->plugin_name);

        // General Settings | options
        $gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode( stripcslashes($this->settings_obj->ays_get_setting('options') ), true);

        // Show quiz button to Admins only
        $gen_options['quiz_show_quiz_button_to_admin_only'] = isset($gen_options['quiz_show_quiz_button_to_admin_only']) ? sanitize_text_field( $gen_options['quiz_show_quiz_button_to_admin_only'] ) : 'off';
        $quiz_show_quiz_button_to_admin_only = (isset($gen_options['quiz_show_quiz_button_to_admin_only']) && sanitize_text_field( $gen_options['quiz_show_quiz_button_to_admin_only'] ) == "on") ? true : false;

        if ( $quiz_show_quiz_button_to_admin_only ) {

            if( current_user_can( 'manage_options' ) ){
                $plugin_array['ays_quiz_button_mce'] = AYS_QUIZ_BASE_URL . 'ays_quiz_shortcode.js';
            }

        } else {
            $plugin_array['ays_quiz_button_mce'] = AYS_QUIZ_BASE_URL . 'ays_quiz_shortcode.js';
        }

        return $plugin_array;
    }

    function ays_quiz_add_tinymce_button($buttons){
        $buttons[] = "ays_quiz_button_mce";
        return $buttons;
    }

    function gen_ays_quiz_shortcode_callback(){

        if(!is_user_logged_in()){
            die();
        }

        $shortcode_data = $this->ays_get_quiz_options();
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <title><?php echo __('Quiz Maker', $this->plugin_name); ?></title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <script language="javascript" type="text/javascript"
                    src="<?php echo site_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
            <script language="javascript" type="text/javascript"
                    src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
            <script language="javascript" type="text/javascript"
                    src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>

            <?php
            wp_print_scripts('jquery');
            ?>
            <base target="_self">
        </head>
        <body id="link" onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" dir="ltr"
              class="forceColors">
        <div class="select-sb">

            <table align="center">
                <tr>
                    <td><label for="ays_quiz">Quiz Maker</label></td>
                    <td>
                     <span>
                               <select id="ays_quiz" style="padding: 2px; height: 25px; font-size: 16px;width:100%;">
                           <option>--Select Quiz--</option>
                                   <?php
                                   echo "<pre>";
                                   print_r($shortcode_data);
                                   echo "</pre>";
                                   ?>
                                   <?php foreach ($shortcode_data as $index => $data)
                                       echo '<option id="' . $data[0] . '" value="' . $data[0] . '"  class="ays_quiz_options">' . $data[1] . '</option>';
                                   ?>
                               </select>
                           </span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="mceActionPanel">
            <input type="submit" id="insert" name="insert" value="Insert" onClick="quiz_insert_shortcode();"/>
        </div>
        <script>

        </script>
        <script type="text/javascript">
            function quiz_insert_shortcode() {
                var tagtext = '[ays_quiz id="' + document.getElementById('ays_quiz')[document.getElementById('ays_quiz').selectedIndex].id + '"]';
                window.tinyMCE.execCommand('mceInsertContent', false, tagtext);
                tinyMCEPopup.close();
            }
        </script>
        </body>
        </html>
        <?php
        die();
    }
    
    public function vc_before_init_actions() {
        require_once( AYS_QUIZ_DIR.'pb_templates/quiz_maker_wpbvc.php' );
    }

    public function quiz_maker_el_widgets_registered() {
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
        // We check if the Elementor plugin has been installed / activated.
        if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
            // get our own widgets up and running:
            // copied from widgets-manager.php
            if ( class_exists( 'Elementor\Plugin' ) ) {
                if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {
                    $elementor = Elementor\Plugin::instance();
                    if ( isset( $elementor->widgets_manager ) ) {
                        if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
                            if ( method_exists( $elementor->widgets_manager, 'register' ) ) {
                                $widget_file   = 'plugins/elementor/quiz_maker_elementor.php';
                                $template_file = locate_template( $widget_file );
                                if ( !$template_file || !is_readable( $template_file ) ) {
                                    $template_file = AYS_QUIZ_DIR.'pb_templates/quiz_maker_elementor.php';
                                }
                                if ( $template_file && is_readable( $template_file ) ) {
                                    require_once $template_file;
                                    Elementor\Plugin::instance()->widgets_manager->register( new Elementor\Widget_Quiz_Maker_Elementor() );
                                }
                            }
                        } else { 
                            if ( method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {
                                $widget_file   = 'plugins/elementor/quiz_maker_elementor.php';
                                $template_file = locate_template( $widget_file );
                                if ( !$template_file || !is_readable( $template_file ) ) {
                                    $template_file = AYS_QUIZ_DIR.'pb_templates/quiz_maker_elementor.php';
                                }
                                if ( $template_file && is_readable( $template_file ) ) {
                                    require_once $template_file;
                                    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Quiz_Maker_Elementor() );
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function deactivate_plugin_option(){

        // Run a security check.
        check_ajax_referer( 'quiz-maker-ajax-deactivate-plugin-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        // Check for permissions.
        if ( ! current_user_can( 'manage_options' ) ) {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'option' => ''
            ));
            wp_die();
        }

        if( is_user_logged_in() ) {
            $request_value = esc_sql( sanitize_text_field( $_REQUEST['upgrade_plugin'] ) );
            $upgrade_option = get_option('ays_quiz_maker_upgrade_plugin','');
            if($upgrade_option === ''){
                add_option('ays_quiz_maker_upgrade_plugin',$request_value);
            }else{
                update_option('ays_quiz_maker_upgrade_plugin',$request_value);
            }
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'option' => get_option('ays_quiz_maker_upgrade_plugin', '')
            ));
            wp_die();
        } else {
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'option' => ''
            ));
            wp_die();
        }

    }
    
    public static function ays_restriction_string($type, $x, $length){
        $output = "";
        switch($type){
            case "char":                
                if(strlen($x)<=$length){
                    $output = $x;
                } else {
                    $output = substr($x,0,$length) . '...';
                }
                break;
            case "word":
                $res = explode(" ", $x);
                if(count($res)<=$length){
                    $output = implode(" ",$res);
                } else {
                    $res = array_slice($res,0,$length);
                    $output = implode(" ",$res) . '...';
                }
            break;
        }
        return $output;
    }
    
    public static function validateDate($date, $format = 'Y-m-d H:i:s'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    // Title change function in dashboard
    public function change_dashboard_title( $admin_title ) {
        
        global $current_screen;
        global $wpdb;
        
        if(strpos($current_screen->id, $this->plugin_name) === false){
            return $admin_title;
        }
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';        
        $quiz_id = (isset($_GET['quiz'])) ? absint(intval($_GET['quiz'])) : null;
        $question_id = (isset($_GET['question'])) ? absint(intval($_GET['question'])) : null;
        $question_cat_id = (isset($_GET['question_category'])) ? absint(intval($_GET['question_category'])) : null;
        $quiz_cat_id = (isset($_GET['quiz_category'])) ? absint(intval($_GET['quiz_category'])) : null;
        
        if($quiz_id !== null){
            $id = $quiz_id;
        }elseif($question_id !== null){
            $id = $question_id;
        }elseif($question_cat_id !== null){
            $id = $question_cat_id;
        }elseif($quiz_cat_id !== null){
            $id = $quiz_cat_id;
        }else{
            $id = null;
        }
        
        $current = explode($this->plugin_name, $current_screen->id);
        $current = trim($current[count($current)-1], "-");
        $sql = '';
        switch($current){
            case "":
                $page = __("Quiz", $this->plugin_name);
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_quizes WHERE id=".$id;
                }
                break;
            case "questions":
                $page = __("Question", $this->plugin_name);;
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_questions WHERE id=".$id;
                }
                break;
            case "quiz-categories":
                $page = __("Category", $this->plugin_name);;
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_quizcategories WHERE id=".$id;
                }
                break;
            case "question-categories":
                $page = __("Category", $this->plugin_name);;
                if($id !== null){
                    $sql = "SELECT * FROM ".$wpdb->prefix."aysquiz_categories WHERE id=".$id;
                }
                break;
            default:
                $page = '';
                $sql = '';
                break;
        }
        $results = null;
        if($sql != ""){
            $results = $wpdb->get_row($sql, "ARRAY_A");
        }
        $change_title = null;
        switch($action){
            case "add":
                $change_title = __("Add New", $this->plugin_name) ." ‹ ".$page;
                break;
            case "edit":
                if($results !== null){
                    $title = "";
                    if($current == "questions"){
                        if(isset($results['question']) && strlen($results['question']) != 0){
                            $title = strip_tags(stripslashes($results['question']));
                        }elseif ((isset($results['question_image']) && $results['question_image'] !='')){
                            $title = 'Image question';
                        }
                    }else{                        
                        $title = stripslashes($results['title']);
                    }
                    $title = strip_tags($title);
                    $change_title = $this->ays_restriction_string("word", $title, 5) ." ‹ ". __("Edit", $this->plugin_name) . " ".$page;
                }
                break;
            default:
                $change_title = $admin_title;
                break;
        }
        if($change_title === null){
            $change_title = $admin_title;
        }
        
        return $change_title;

    }    

    public static function get_listtables_title_length( $listtable_name ) {
        global $wpdb;

        $settings_table = $wpdb->prefix . "aysquiz_settings";
        $sql = "SELECT meta_value FROM ".$settings_table." WHERE meta_key = 'options'";
        $result = $wpdb->get_var($sql);
        $options = ($result == "") ? array() : json_decode(stripcslashes($result), true);

        $listtable_title_length = 5;
        if(! empty($options) ){
            switch ( $listtable_name ) {
                case 'questions':
                    $listtable_title_length = (isset($options['question_title_length']) && intval($options['question_title_length']) != 0) ? absint(intval($options['question_title_length'])) : 5;
                    break;
                case 'quizzes':
                    $listtable_title_length = (isset($options['quizzes_title_length']) && intval($options['quizzes_title_length']) != 0) ? absint(intval($options['quizzes_title_length'])) : 5;
                    break;
                case 'results':
                    $listtable_title_length = (isset($options['results_title_length']) && intval($options['results_title_length']) != 0) ? absint(intval($options['results_title_length'])) : 5;
                    break;   
                case 'question_categories':
                    $listtable_title_length = (isset($options['question_categories_title_length']) && intval($options['question_categories_title_length']) != 0) ? absint(sanitize_text_field($options['question_categories_title_length'])) : 5;
                    break;
                case 'quiz_categories':
                    $listtable_title_length = (isset($options['quiz_categories_title_length']) && intval($options['quiz_categories_title_length']) != 0) ? absint(sanitize_text_field($options['quiz_categories_title_length'])) : 5;
                    break;
                case 'quiz_reviews':
                    $listtable_title_length = (isset($options['quiz_reviews_title_length']) && intval($options['quiz_reviews_title_length']) != 0) ? absint(sanitize_text_field($options['quiz_reviews_title_length'])) : 5;
                    break;        
                default:
                    $listtable_title_length = 5;
                    break;
            }
            return $listtable_title_length;
        }
        return $listtable_title_length;
    }
    
    public function quiz_maker_add_dashboard_widgets() {
        if(current_user_can('manage_options')){ // Administrator
            wp_add_dashboard_widget( 
                'quiz-maker', 
                'Quiz Maker Status', 
                array( $this, 'quiz_maker_dashboard_widget' )
            );

            // Globalize the metaboxes array, this holds all the widgets for wp-admin
            global $wp_meta_boxes;

            // Get the regular dashboard widgets array 
            // (which has our new widget already but at the end)
            $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

            // Backup and delete our new dashboard widget from the end of the array
            $example_widget_backup = array( 
                'quiz-maker' => $normal_dashboard['quiz-maker'] 
            );
            unset( $normal_dashboard['example_dashboard_widget'] );

            // Merge the two arrays together so our widget is at the beginning
            $sorted_dashboard = array_merge( $example_widget_backup, $normal_dashboard );

            // Save the sorted array back into the original metaboxes 
            $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
        }
    } 

    /**
     * Create the function to output the contents of our Dashboard Widget.
     */
    public function quiz_maker_dashboard_widget() {
        global $wpdb;
        $questions_count = Questions_List_Table::record_count();
        $quizzes_count = Quizes_List_Table::record_count();
        $results_count = Results_List_Table::unread_records_count();
        
        $questions_label = intval($questions_count) == 1 ? "question" : "questions";
        $quizzes_label = intval($quizzes_count) == 1 ? "quiz" : "quizzes";
        $results_label = intval($results_count) == 1 ? "new result" : "new results";
        
        // Display whatever it is you want to show.
        ?>
        <ul class="ays_quiz_maker_dashboard_widget">
            <li class="ays_dashboard_widget_item">
                <a href="<?php echo "admin.php?page=".$this->plugin_name; ?>">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL."/images/icons/icon-128x128.png"; ?>" alt="Quizzes">
                    <span><?php echo $quizzes_count; ?></span>
                    <span><?php echo __($quizzes_label, $this->plugin_name); ?></span>
                </a>
            </li>
            <li class="ays_dashboard_widget_item">
                <a href="<?php echo "admin.php?page=".$this->plugin_name."-questions" ?>">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL."/images/icons/question2.png"; ?>" alt="Questions">
                    <span><?php echo $questions_count; ?></span>
                    <span><?php echo __($questions_label, $this->plugin_name); ?></span>
                </a>
            </li>
            <li class="ays_dashboard_widget_item">
                <a href="<?php echo "admin.php?page=".$this->plugin_name."-results" ?>">
                    <img src="<?php echo AYS_QUIZ_ADMIN_URL."/images/icons/users2.png"; ?>" alt="Results">
                    <span><?php echo $results_count; ?></span>
                    <span><?php echo __($results_label, $this->plugin_name); ?></span>
                </a>
            </li>
        </ul>
        <div style="padding:10px;font-size:14px;border-top:1px solid #ccc;">
            <?php echo "Works version ".AYS_QUIZ_VERSION." of "; ?>
            <a href="<?php echo "admin.php?page=".$this->plugin_name ?>">Quiz Maker</a>
        </div>
    <?php
    }
    
    public static function ays_query_string($remove_items){
        $query_string = $_SERVER['QUERY_STRING'];
        $query_items = explode( "&", $query_string );
        foreach($query_items as $key => $value){
            $item = explode("=", $value);
            foreach($remove_items as $k => $i){
                if(in_array($i, $item)){
                    unset($query_items[$key]);
                }
            }
        }
        return implode( "&", $query_items );
    }

    public function get_question_categories(){
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}aysquiz_categories";

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }
    
    public function quiz_maker_admin_footer($a){
        if(isset($_REQUEST['page'])){
            if(false !== strpos( sanitize_text_field( $_REQUEST['page'] ), $this->plugin_name)){
                ?>
                <p style="font-size:13px;text-align:center;font-style:italic;">
                    <span style="margin-left:0px;margin-right:10px;" class="ays_heart_beat"><i class="ays_fa ays_fa_heart_o animated"></i></span>
                    <span><?php echo __( "If you love our plugin, please do big favor and rate us on", $this->plugin_name); ?></span> 
                    <a target="_blank" href='https://wordpress.org/support/plugin/quiz-maker/reviews/?rate=5#new-post'>WordPress.org</a>
                    <span class="ays_heart_beat"><i class="ays_fa ays_fa_heart_o animated"></i></span>
                </p>
            <?php
            }
        }
    }
    
    public static function get_user_country_by_ip( $ip ){
        
        $url = AYS_QUIZ_ADMIN_URL . "/quiz-maker-countries-names.json";
        $response = wp_remote_get( $url );        
        $body = wp_remote_retrieve_body( $response );
        $countries = json_decode( $body );
        
        $headers = array(
            "headers" => array(            
                "Content-Type"  => "application/json",
                "cache-control" => "no-cache",
            )
        );

        $url = "https://ipinfo.io/". $ip ."/json";
        $response = wp_remote_get($url, $headers);
        $body     = wp_remote_retrieve_body( $response );
        
        if( empty( $body ) ){
            $from = $ip;
        }else{
            $body = json_decode( $body );
            
            $from = array();
            
            if( !empty( $body->city ) ){
                $from[] = $body->city;
            }
            if( !empty( $body->region ) ){
                $from[] = $body->region;
            }
            if( !empty( $body->country ) ){
                $country = $body->country;
                $from[] = $countries->$country;
            }
            
            $from[] = $ip;
            
            $from = implode( ', ', $from );
        }
        
        return $from;
    }

    public static function ays_quiz_is_exists_needle_tag( $str, $needle ) {

        $exists_flag = false;

        if ( isset( $str ) && ! is_null( $str ) && $str != '' ) {

            if ( isset( $needle ) && ! is_null( $needle ) && $needle != '' ) {

                $is_exists_needle = strpos( $str, $needle );

                if ( $is_exists_needle !== false ) {
                    $exists_flag = true;
                }
            }
        }

        return $exists_flag;
    }

    /*
    ==========================================
        Sale Banner | Start
    ==========================================
    */

    public function ays_quiz_sale_baner(){
        // if(isset($_POST['ays_quiz_sale_btn']) && (isset( $_POST[$this->plugin_name . '-sale-banner'] ) && wp_verify_nonce( $_POST[$this->plugin_name . '-sale-banner'], $this->plugin_name . '-sale-banner' )) &&
        //       current_user_can( 'manage_options' )){
        //     update_option('ays_quiz_sale_btn', 1); 
        //     update_option('ays_quiz_sale_date', current_time( 'mysql' ));
        // }

        if(isset($_POST['ays_quiz_sale_btn_for_two_months'])){
            update_option('ays_quiz_sale_btn_for_two_months', 1);
            update_option('ays_quiz_sale_date', current_time( 'mysql' ));
        }

        $ays_quiz_sale_date = get_option('ays_quiz_sale_date');
        $ays_quiz_sale_two_months = get_option('ays_quiz_sale_btn_for_two_months');

        $val = 60*60*24*5;
        if($ays_quiz_sale_two_months == 1){
            $val = 60*60*24*61;
        }

        $current_date = current_time( 'mysql' );
        $date_diff = strtotime($current_date) -  intval(strtotime($ays_quiz_sale_date));

        $days_diff = $date_diff / $val;

        if(intval($days_diff) > 0 ){
            update_option('ays_quiz_sale_btn', 0); 
            update_option('ays_quiz_sale_btn_for_two_months', 0);
        }

        $ays_quiz_ishmar = intval(get_option('ays_quiz_sale_btn'));
        $ays_quiz_ishmar += intval(get_option('ays_quiz_sale_btn_for_two_months'));
        if($ays_quiz_ishmar == 0 ){
            if (isset($_GET['page']) && strpos($_GET['page'], AYS_QUIZ_NAME) !== false) {
                if ( sanitize_text_field($_GET['page']) == 'quiz-maker-settings' ) {
                    $this->ays_quiz_chart_bulider_message($ays_quiz_ishmar);
                } else {
                    // $this->ays_quiz_sale_message($ays_quiz_ishmar);
                    $this->ays_quiz_new_mega_bundle_message($ays_quiz_ishmar);
                    // $this->ays_quiz_black_friday_message($ays_quiz_ishmar);
                    // $this->ays_quiz_helloween_message($ays_quiz_ishmar);
                }
                // $this->ays_quiz_spring_bundle_small_message($ays_quiz_ishmar);
            }
        }
    }

    public function ays_quiz_dismiss_button(){

        $data = array(
            'status' => false,
        );

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ays_quiz_dismiss_button') { 
            if( (isset( $_REQUEST['_ajax_nonce'] ) && wp_verify_nonce( $_REQUEST['_ajax_nonce'], AYS_QUIZ_NAME . '-sale-banner' )) && current_user_can( 'manage_options' )){
                update_option('ays_quiz_sale_btn', 1); 
                update_option('ays_quiz_sale_date', current_time( 'mysql' ));
                $data['status'] = true;
            }
        }

        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode($data);
        wp_die();

    }

    // Mega Bundle
    public function ays_quiz_sale_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';
                    $content[] = '<a href="https://ays-pro.com/mega-bundle" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/mega_bundle_logo_box.png"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<strong style="font-weight: bold;">';
                            $content[] = __( "Limited Time <span style='color:#E85011;'>50%</span> SALE on <br><span><a href='https://ays-pro.com/mega-bundle' target='_blank' style='color:#E85011; text-decoration: underline;'>Mega Bundle</a></span> (Quiz + Survey + Poll)!", AYS_QUIZ_NAME );
                        $content[] = '</strong>';

                        $content[] = '<br>';

                        $content[] = '<strong>';
                                $content[] = "Hurry up! <a href='https://ays-pro.com/mega-bundle' target='_blank'>Check it out!</a>";
                        $content[] = '</strong>';

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                    $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';
                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';
                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<a href="https://ays-pro.com/mega-bundle" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank" style="height: 32px; display: flex; align-items: center; font-weight: 500; " >' . __( 'Buy Now !', AYS_QUIZ_NAME ) . '</a>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    public function ays_quiz_sale_message2($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';
                    $content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/icons/quiz-maker-logo.png"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<strong style="font-weight: bold;">';
                            $content[] = __( "Limited Time <span class='ays-quiz-dicount-wrap-color'>20%</span> SALE on <br><span><a href='https://ays-pro.com/wordpress/quiz-maker' target='_blank' class='ays-quiz-dicount-wrap-color ays-quiz-dicount-wrap-text-decoration' style='display:block;'>Quiz Maker Premium Versions</a></span>", AYS_QUIZ_NAME );
                        $content[] = '</strong>';

                        // $content[] = '<br>';

                        $content[] = '<strong>';
                                $content[] = "Hurry up! <a href='https://ays-pro.com/wordpress/quiz-maker' target='_blank'>Check it out!</a>";
                        $content[] = '</strong>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';
                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';

                            // $content[] = '<form action="" method="POST">';
                            //     $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                            //         $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                            //         $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_for_two_months" style="height: 32px; padding-left: 0">Dismiss ad for 2 months</button>';
                            //     $content[] = '</div>';
                            // $content[] = '</form>';

                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-buy-now-button-box">';
                        $content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker" class="button button-primary ays-buy-now-button" id="ays-button-top-buy-now" target="_blank" style="" >' . __( 'Buy Now !', AYS_QUIZ_NAME ) . '</a>';
                    $content[] = '</div>';

                    // $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-opacity-box">';
                    //     $content[] = '<a href="https://ays-pro.com/great-bundle" class="ays-buy-now-opacity-button" target="_blank">' . __( 'link', AYS_QUIZ_NAME ) . '</a>';
                    // $content[] = '</div>';

                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                            $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                            // $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_for_two_months" style="height: 32px; padding-left: 0">Dismiss ad for 2 months</button>';
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';

            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    // Christmas bundle
    public function ays_quiz_sale_message_christmas_bundle(){
        $content = array();

        $content = array();
        $content[] = '<div id="ays-quiz-winter-dicount-main">';
            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';
                    $content[] = '<a href="https://ays-pro.com/mega-bundle" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/mega_bundle_christmas_box.png"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<strong>';
                            $content[] = __( "The BIGGEST <span class='ays-quiz-dicount-wrap-color' style='color:#000;'>50%</span> SALE on <br><span><a href='https://ays-pro.com/mega-bundle' target='_blank' class='ays-quiz-dicount-wrap-color ays-quiz-dicount-wrap-text-decoration' style='display:block;color:#000'>Christmas Bundle</a></span> (Quiz+Survey+Poll)!", AYS_QUIZ_NAME );
                        $content[] = '</strong>';

                        $content[] = '<br>';

                        $content[] = '<strong>';
                                $content[] = "Hurry up! Ending on. <a href='https://ays-pro.com/mega-bundle' target='_blank' style='color:#000;'>Check it out!</a>";
                        $content[] = '</strong>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';
                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_winter" style="height: 32px;color:#000" value="winter_bundle">Dismiss ad</button>';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_winter_for_two_months" style="height: 32px; padding-left: 0;color:#000" value="winter_bundle">Dismiss ad for 2 months</button>';
                            $content[] = '</form>';

                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    
                    $content[] = '<a href="https://ays-pro.com/mega-bundle" class="button button-primary ays-button" id="ays-quiz-button-top-buy-now" target="_blank">' . __( 'Buy Now !', AYS_QUIZ_NAME ) . '</a>';
                $content[] = '</div>';
            $content[] = '</div>';
        // $content[] = '</div>';

        $content = implode( '', $content );
        echo $content;
    }

    public static function ays_quiz_spring_bundle_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';
                    $content[] = '<a href="https://ays-pro.com/spring-bundle" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/spring_bundle_logo.png"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';
                        $content[] = '<p style="margin-right:25%;">';
                            $content[] = '<strong>';
                                $content[] = __( "Spring is here! <span class='ays-quiz-dicount-wrap-color'>40%</span> SALE on <br><span><a href='https://ays-pro.com/spring-bundle' target='_blank' class='ays-quiz-dicount-wrap-color ays-quiz-dicount-wrap-text-decoration' style='display:block;'>Spring Bundle</a></span> (Quiz+Copy+Popup) !", AYS_QUIZ_NAME );
                            $content[] = '</strong>';
                            $content[] = '<br>';
                            $content[] = '<strong>';
                                    $content[] = "Hurry up! Ending on. <a href='https://ays-pro.com/spring-bundle' target='_blank'>Check it out!</a>";
                            $content[] = '</strong>';
                        $content[] = '</p>';
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<div id="ays-quiz-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';
                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';

                            $content[] = '<form action="" method="POST" class="ays-quiz-btn-form">';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_spring" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn_spring_for_two_months" style="height: 32px; padding-left: 0">Dismiss ad for 2 months</button>';
                            $content[] = '</form>';

                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<a href="https://ays-pro.com/spring-bundle" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now !', AYS_QUIZ_NAME ) . '</a>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    public static function ays_quiz_spring_bundle_small_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';
                    $content[] = '<a href="https://ays-pro.com/spring-bundle" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/spring_bundle_logo_box.png"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';
                        $content[] = '<p>';
                            $content[] = '<strong>';
                                $content[] = __( "Spring is here! <span class='ays-quiz-dicount-wrap-color'>50%</span> SALE on <span><a href='https://ays-pro.com/spring-bundle' target='_blank' class='ays-quiz-dicount-wrap-color ays-quiz-dicount-wrap-text-decoration'>Spring Bundle</a></span><span style='display: block;'>Quiz + Popup + Copy</span>", AYS_QUIZ_NAME );
                            $content[] = '</strong>';
                        $content[] = '</p>';
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<div id="ays-quiz-countdown-main-container">';

                            $content[] = '<form action="" method="POST" class="ays-quiz-btn-form">';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_spring_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_spring_btn_for_two_months" style="height: 32px; padding-left: 0">Dismiss ad for 2 months</button>';
                            $content[] = '</form>';

                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<a href="https://ays-pro.com/spring-bundle" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now !', AYS_QUIZ_NAME ) . '</a>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    // Helloween bundle
    public static function ays_quiz_helloween_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main-helloween" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month-helloween" class="ays_quiz_dicount_month_helloween">';
                    $content[] = '<div class="ays-quiz-dicount-wrap-box-helloween-limited">';

                        $content[] = '<p>';
                            $content[] = __( "Limited Time <span class='ays-quiz-dicount-wrap-color-helloween' style='color:#b2ff00;'>30%</span> <span> SALE on</span><br><span style='' class='ays-quiz-helloween-bundle'><a href='https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=helloween-sale-banner' target='_blank' class='ays-quiz-dicount-wrap-color-helloween ays-quiz-dicount-wrap-text-decoration-helloween' style='display:block; color:#b2ff00;margin-right:6px;'>Quiz Maker</a>
                            </span>", AYS_QUIZ_NAME );
                        $content[] = '</p>';
                        $content[] = '<p>';
                                $content[] = "Hurry up! 
                                                <a href='https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=helloween-sale-banner' target='_blank' style='color:#ffc700;'>
                                                    Check it out!
                                                </a>";
                        $content[] = '</p>';
                            
                    $content[] = '</div>';

                    
                    $content[] = '<div class="ays-quiz-helloween-bundle-buy-now-timer">';
                        $content[] = '<div class="ays-quiz-dicount-wrap-box-helloween-timer">';
                            $content[] = '<div id="ays-quiz-countdown-main-container" class="ays-quiz-countdown-main-container-helloween">';
                                $content[] = '<div class="ays-quiz-countdown-container-helloween">';
                                    $content[] = '<div id="ays-quiz-countdown">';
                                        $content[] = '<ul>';
                                            $content[] = '<li><p><span id="ays-quiz-countdown-days"></span><span>days</span></p></li>';
                                            $content[] = '<li><p><span id="ays-quiz-countdown-hours"></span><span>Hours</span></p></li>';
                                            $content[] = '<li><p><span id="ays-quiz-countdown-minutes"></span><span>Mins</span></p></li>';
                                            $content[] = '<li><p><span id="ays-quiz-countdown-seconds"></span><span>Secs</span></p></li>';
                                        $content[] = '</ul>';
                                    $content[] = '</div>';

                                    $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                        $content[] = '<span>🚀</span>';
                                        $content[] = '<span>⌛</span>';
                                        $content[] = '<span>🔥</span>';
                                        $content[] = '<span>💣</span>';
                                    $content[] = '</div>';

                                $content[] = '</div>';

                            $content[] = '</div>';
                                
                        $content[] = '</div>';
                        $content[] = '<div class="ays-quiz-dicount-wrap-box ays-buy-now-button-box-helloween">';
                            $content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=helloween-sale-banner" class="button button-primary ays-buy-now-button-helloween" id="ays-button-top-buy-now-helloween" target="_blank" style="" >' . __( 'Buy Now !', AYS_QUIZ_NAME ) . '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';

                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-quiz-dismiss-buttons-container-for-form-helloween">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-quiz-dismiss-buttons-content-helloween">';
                        if( current_user_can( 'manage_options' ) ){
                            $content[] = '<button class="btn btn-link ays-button-helloween" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                            $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                        }
                        $content[] = '</div>';
                    $content[] = '</form>';

                $content[] = '</div>';
                // $content[] = '<button type="button" class="notice-dismiss">';
                // $content[] = '</button>';
            $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
        }
    }

    // Christmas banner
    public static function ays_quiz_christmas_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-christmas-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-christmas-month" class="ays_quiz_dicount_month">';
                    $content[] = '<div class="ays-quiz-dicount-christmas-box">';
                        $content[] = '<div class="ays-quiz-dicount-christmas-wrap-box ays-quiz-dicount-christmas-wrap-box-80">';
                            $content[] = '<div class="ays-quiz-dicount-christmas-title-row">' . __( 'Limited Time', AYS_QUIZ_NAME ) .' '. '<a href="https://ays-pro.com/mega-bundle" class="ays-quiz-dicount-christmas-button-sale" target="_blank">' . __( '50%', AYS_QUIZ_NAME ) . '</a>' . ' SALE on</div>';
                            $content[] = '<div class="ays-quiz-dicount-christmas-title-row">'. '<a href="https://ays-pro.com/mega-bundle" class="ays-quiz-dicount-christmas-button-sale ays-quiz-dicount-christmas-button-block" target="_blank">' . __( 'Mega Bundle', AYS_QUIZ_NAME ) . '</a>' . __( '(Quiz + Survey + Poll)!', AYS_QUIZ_NAME ) . '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-dicount-christmas-wrap-box" style="width: 25%;">';
                            $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                                $content[] = '<div class="ays-quiz-countdown-container">';
                                    $content[] = '<div id="ays-quiz-countdown" style="display: block;">';
                                        $content[] = '<ul>';
                                            $content[] = '<li><span id="ays-quiz-countdown-days"></span>' . __( 'Days', AYS_QUIZ_NAME ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-hours"></span>' . __( 'Hours', AYS_QUIZ_NAME ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>' . __( 'Minutes', AYS_QUIZ_NAME ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>' . __( 'Seconds', AYS_QUIZ_NAME ) . '</li>';
                                        $content[] = '</ul>';
                                    $content[] = '</div>';
                                    $content[] = '<div id="ays-quiz-countdown-content" class="emoji" style="display: none;">';
                                        $content[] = '<span>🚀</span>';
                                        $content[] = '<span>⌛</span>';
                                        $content[] = '<span>🔥</span>';
                                        $content[] = '<span>💣</span>';
                                    $content[] = '</div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-dicount-christmas-wrap-box" style="width: 25%;">';
                            $content[] = '<a href="https://ays-pro.com/mega-bundle" class="ays-quiz-dicount-christmas-button-buy-now" target="_blank">' . __( 'BUY NOW!', AYS_QUIZ_NAME ) . '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';
                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-quiz-dismiss-buttons-container-for-form-christmas">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-quiz-dismiss-buttons-content-christmas">';
                            $content[] = '<button class="btn btn-link ays-button-christmas" name="ays_quiz_sale_btn" style="">' . __( 'Dismiss ad', AYS_QUIZ_NAME ) . '</button>';
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
        }
    }

    // Chart Builder
    public function ays_quiz_chart_bulider_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info ays_quiz_chart_bulider_container">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month ays_quiz_chart_bulider_box">';
                    $content[] = '<a href="https://wordpress.org/plugins/chart-builder/" target="_blank" class="ays-quiz-sale-banner-link"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box">';

                        $content[] = '<strong style="font-weight: bold;">';
                            $content[] = __( "New Integration with <span><a href='https://wordpress.org/plugins/chart-builder/' target='_blank' style='color:#98FBA6; text-decoration: underline;'>Chart Builder</a></span> plugin", AYS_QUIZ_NAME );
                        $content[] = '</strong>';
                        $content[] = '<br>';
                        $content[] = __( "The integration will allow you to create beautiful charts based on your quiz data and share them with your users.", AYS_QUIZ_NAME );

                        $content[] = '<br>';

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                if( current_user_can( 'manage_options' ) ){
                                    $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0;">Dismiss ad</button>';
                                    $content[] = wp_nonce_field( $this->plugin_name . '-sale-banner' ,  $this->plugin_name . '-sale-banner' );
                                }
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<a href="https://wordpress.org/plugins/chart-builder/" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank" >' . __( 'Install Now !', AYS_QUIZ_NAME ) . '</a>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    // New Mega Bundle
    public function ays_quiz_new_mega_bundle_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-new-mega-bundle-dicount-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-month" class="ays_quiz_dicount_month">';
                    // $content[] = '<a href="https://ays-pro.com/mega-bundle" target="_blank" class="ays-quiz-sale-banner-link"><img src="' . AYS_QUIZ_ADMIN_URL . '/images/mega_bundle_logo_box.png"></a>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-text-box">';
                        $content[] = '<div>';

                            $content[] = '<span class="ays-quiz-new-mega-bundle-title">';
                                // $content[] = __( "<span><a href='https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=sale-banner' target='_blank' style='color:#ffffff; text-decoration: underline;'>Quiz Maker</a></span>", AYS_QUIZ_NAME );
                                $content[] = __( "<span><a href='https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=mega-bundle-sale-banner' target='_blank' style='color:#ffffff; text-decoration: underline;'>Mega Bundle</a></span> (Quiz + Survey + Poll)", AYS_QUIZ_NAME );
                            $content[] = '</span>';
                            $content[] = '<div style="display: inline-block;">';
                                $content[] = '<img src="' . AYS_QUIZ_ADMIN_URL . '/images/ays-quiz-banner-50.svg" class="ays-quiz-new-mega-bundle-mobile-image-display-none" style="width: 70px;">';
                            $content[] = '</div>';
                            $content[] = '</br>';
                            $content[] = '<div class="ays-quiz-new-mega-bundle-mobile-image-display-block display_none">';
                                $content[] = '<img src="' . AYS_QUIZ_ADMIN_URL . '/images/ays-quiz-banner-50.svg" style="width: 70px;">';
                            $content[] = '</div>';

                            $content[] = '<span class="ays-quiz-new-mega-bundle-desc">';
                                $content[] = '<img class="ays-quiz-new-mega-bundle-guaranteeicon" src="' . AYS_QUIZ_ADMIN_URL . '/images/quiz-maker-guaranteeicon.svg" style="width: 30px;">';
                                $content[] = __( "30 Day Money Back Guarantee", AYS_QUIZ_NAME );
                            $content[] = '</span>';
                        $content[] = '</div>';

                        // $content[] = '<br>';

                        // $content[] = '<strong>';
                        //         $content[] = "Hurry up! <a href='https://ays-pro.com/mega-bundle' target='_blank'>Check it out!</a>";
                        // $content[] = '</strong>';

                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-quiz-dismiss-buttons-container-for-form">';

                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                                if( current_user_can( 'manage_options' ) ){
                                    $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">'. __( "Dismiss ad", AYS_QUIZ_NAME ) .'</button>';
                                    $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                                }
                                $content[] = '</div>';
                            $content[] = '</form>';
                            
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-countdown-box">';

                        $content[] = '<div id="ays-quiz-maker-countdown-main-container">';
                            $content[] = '<div class="ays-quiz-maker-countdown-container">';

                                $content[] = '<div id="ays-quiz-countdown">';

                                    $content[] = '<div>';
                                        $content[] = __( "Offer ends in:", AYS_QUIZ_NAME );
                                    $content[] = '</div>';

                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-quiz-countdown-days"></span>'. __( "Days", AYS_QUIZ_NAME ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-hours"></span>'. __( "Hours", AYS_QUIZ_NAME ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-minutes"></span>'. __( "Minutes", AYS_QUIZ_NAME ) .'</li>';
                                        $content[] = '<li><span id="ays-quiz-countdown-seconds"></span>'. __( "Seconds", AYS_QUIZ_NAME ) .'</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-quiz-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';
                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-dicount-wrap-box ays-quiz-dicount-wrap-button-box">';
                        $content[] = '<a href="https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=mega-bundle-sale-banner" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now', AYS_QUIZ_NAME ) . '</a>';
                        $content[] = '<span class="ays-quiz-dicount-one-time-text">';
                            $content[] = __( "One-time payment", AYS_QUIZ_NAME );
                        $content[] = '</span>';
                    $content[] = '</div>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    // Black Friday
    public function ays_quiz_black_friday_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-quiz-dicount-black-friday-month-main" class="notice notice-success is-dismissible ays_quiz_dicount_info">';
                $content[] = '<div id="ays-quiz-dicount-black-friday-month" class="ays_quiz_dicount_month">';
                    $content[] = '<div class="ays-quiz-dicount-black-friday-box">';
                        $content[] = '<div class="ays-quiz-dicount-black-friday-wrap-box ays-quiz-dicount-black-friday-wrap-box-80" style="width: 70%;">';
                            $content[] = '<div class="ays-quiz-dicount-black-friday-title-row">' . __( 'Limited Time', "quiz-maker" ) .' '. '<a href="https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=black-friday-sale-banner" class="ays-quiz-dicount-black-friday-button-sale" target="_blank">' . __( 'Sale', "quiz-maker" ) . '</a>' . '</div>';
                            $content[] = '<div class="ays-quiz-dicount-black-friday-title-row">' . __( 'Mega bundle', "quiz-maker" ) . ' (Quiz+Survey+Poll)!</div> ';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-dicount-black-friday-wrap-box ays-quiz-dicount-black-friday-wrap-text-box">';
                            $content[] = '<div class="ays-quiz-dicount-black-friday-text-row">' . '50% off' . '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-dicount-black-friday-wrap-box" style="width: 25%;">';
                            $content[] = '<div id="ays-quiz-countdown-main-container">';
                                $content[] = '<div class="ays-quiz-countdown-container">';
                                    $content[] = '<div id="ays-quiz-countdown" style="display: block;">';
                                        $content[] = '<ul>';
                                            $content[] = '<li><span id="ays-quiz-countdown-days">0</span>' . __( 'Days', "quiz-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-hours">0</span>' . __( 'Hours', "quiz-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-minutes">0</span>' . __( 'Minutes', "quiz-maker" ) . '</li>';
                                            $content[] = '<li><span id="ays-quiz-countdown-seconds">0</span>' . __( 'Seconds', "quiz-maker" ) . '</li>';
                                        $content[] = '</ul>';
                                    $content[] = '</div>';
                                    $content[] = '<div id="ays-quiz-countdown-content" class="emoji" style="display: none;">';
                                        $content[] = '<span>🚀</span>';
                                        $content[] = '<span>⌛</span>';
                                        $content[] = '<span>🔥</span>';
                                        $content[] = '<span>💣</span>';
                                    $content[] = '</div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-dicount-black-friday-wrap-box" style="width: 25%;">';
                            $content[] = '<a href="https://ays-pro.com/mega-bundle?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=black-friday-sale-banner" class="ays-quiz-dicount-black-friday-button-buy-now" target="_blank">' . __( 'Get Your Deal', "quiz-maker" ) . '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';
                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-quiz-dismiss-buttons-container-for-form-black-friday">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-quiz-dismiss-buttons-content">';
                            if( current_user_can( 'manage_options' ) ){
                                $content[] = '<button class="btn btn-link ays-button" name="ays_quiz_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                                $content[] = wp_nonce_field( AYS_QUIZ_NAME . '-sale-banner' ,  AYS_QUIZ_NAME . '-sale-banner' );
                            }
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
        }
    }

    /*
    ==========================================
        Sale Banner | End
    ==========================================
    */

    public function ays_quiz_subscribe_email(){

        if( !current_user_can( 'manage_options' ) ){
            echo json_encode(array(
                "status" => false,
                "message" => "Something went wrong. Please try again"
            ));
            wp_die();
        }

        $subscribe_email = isset($_REQUEST['email']) && $_REQUEST['email'] != "" ? sanitize_email($_REQUEST['email']) : "";
        // $url = "http://localhost/add-on-email/quiz_test_email";
        $url = "https://ays-pro.com/add-on-email/";
        if($subscribe_email != ""){
            $current_date = date("Y-m-d");
            $current_user_ip = $this->get_user_ip();
            $send_request = wp_remote_post($url, array(
                'headers'     => array("Content-Type: application/json; charset=UTF-8"),
                'body'        => json_encode( array(
                    "email"   => $subscribe_email,
                    "user_ip" => $current_user_ip,
                    "subscirbe_date" => $current_date
                ) ),
            ) );
            $response = wp_remote_retrieve_body($send_request);
            $response = json_decode($response, true);
            if(isset($response) && is_array($response)){
                $response_code = isset($response['code']) && $response['code'] != "" ? $response['code'] : "";
                $response_message = isset($response['msg']) && $response['msg'] != "" ? $response['msg'] : "";
                $response_status = $response_code > 0 ? true : false;
                echo json_encode(array(
                    "status" => $response_status,
                    "message" => $response_message
                ));
                wp_die();
            }       
            else{
                echo json_encode(array(
                    "status" => false,
                    "message" => "Something went wrong. Please try again"
                ));
                wp_die();
            }
        }
    }

    public function get_user_ip(){
        $ipaddress = '';
        if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        elseif (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function add_tabs() {
        $screen = get_current_screen();
    
        if ( ! $screen) {
            return;
        }

        /*
        ==========================================
            General information Tab | Start
        ==========================================
        */
        
        $general_tab_title   = __( 'General Information', $this->plugin_name);

        $content = array();

        $content[] = '<div class="ays-quiz-help-tab-conatiner">';

            $content[] = '<div class="ays-quiz-help-tab-title">';
                $content[] = __( "Quiz Maker Information", AYS_QUIZ_NAME );
            $content[] = '</div>';


            $content[] = '<div class="ays-quiz-help-tab-row">';

                $content[] = '<div class="ays-quiz-help-tab-box">';
                    $content[] = '<span>';

                        $content[] = sprintf(
                            __( 'Create engaging quizzes, tests, and exams within a few minutes with the help of the WordPress Quiz Maker plugin. The Quiz Maker has a user-friendly interface and responsive design.%s With this plugin, you are free to add as many questions as needed with the following question types: %sRadio, Checkbox, Dropdown, Text, Short Text, Number, Date.%s In order, to activate Integrations, send Certificates via Email, or create Intervals for your quiz results you will need to download and install the Pro Versions of the WordPress Quiz plugin.', AYS_QUIZ_NAME ),
                            '<br>',
                            '<em>',
                            '</em><br><br>'
                        );

                    $content[] = '</span>';
                $content[] = '</div>';

            $content[] = '</div>';
        $content[] = '</div>';

        $content_genereal_info = implode( '', $content );

        /*
        ==========================================
            General information Tab | End
        ==========================================
        */

        /*
        ==========================================
            Premium information Tab | Start
        ==========================================
        */

        $premium_tab_title   = __( 'Premium version', $this->plugin_name);

        $content = array();

        $content[] = '<div class="ays-quiz-help-tab-conatiner">';

            $content[] = '<div class="ays-quiz-help-tab-title">';
                $content[] = __( "Premium versions' overview", AYS_QUIZ_NAME );
            $content[] = '</div>';

                $content[] = '<div class="ays-quiz-dicount-wrap-box">';
                    $content[] = '<span>';

                        $content[] = sprintf(
                            __( 'By activating the pro version, you will get all the features to strive your WordPress website’s quizzes to an advanced level.%sWith the WordPress Quiz plugin, it is easy to generate the quiz types like %sTrivia quiz, Assessment quiz, Personality test,  Multiple-choice quiz, Knowledge quiz, IQ test, Yes-or-no quiz, True-or-false quiz, This-or-that quiz(with images), Diagnostic quiz, Scored quiz, Buzzfeed quiz, Viral Quiz%s and etc.%sMotivate your visitors with Certificates and Advanced Leaderboards, prevent cheating during online exams with Timer-Based quizzes, earn money with Paid Quizzes.', $this->plugin_name ),
                            '<br>',
                            '<em>',
                            '</em>',
                            '</br></br>'
                        );

                    $content[] = '</span>';
            $content[] = '</div>';

        $content[] = '</div>';

        $content_premium_info = implode( '', $content );

        /*
        ==========================================
            Premium information Tab | End
        ==========================================
        */

        /*
        ==========================================
            Sidebar information | Start
        ==========================================
        */

        $sidebar_content = '
        <p><strong>' . __( 'For more information:', AYS_QUIZ_NAME ) . '</strong></p>' .
        '<p><a href="https://www.youtube.com/watch?v=oKPOdbZahK0" target="_blank">' . __( 'YouTube video tutorials' , AYS_QUIZ_NAME ) . '</a></p>' .
        '<p><a href="https://ays-pro.com/wordpress-quiz-maker-user-manual" target="_blank">' . __( 'Documentation', AYS_QUIZ_NAME ) . '</a></p>' .
        '<p><a href="https://ays-pro.com/wordpress/quiz-maker" target="_blank">' . __( 'Quiz Maker plugin premium version', AYS_QUIZ_NAME ) . '</a></p>' .
        '<p><a href="https://quiz-plugin.com/wordpress-quiz-plugin-free-demo" target="_blank">' . __( 'Quiz Maker plugin free demo', AYS_QUIZ_NAME ) . '</a></p>';

        /*
        ==========================================
            Sidebar information | End
        ==========================================
        */


        $general_tab_content = array(
            'id'      => 'quiz-maker-general-tab',
            'title'   => $general_tab_title,
            'content' => $content_genereal_info
        );

        $premium_tab_content = array(
            'id'      => 'quiz-maker-premium-tab',
            'title'   => $premium_tab_title,
            'content' => $content_premium_info
        );
        
        $screen->add_help_tab($general_tab_content);
        $screen->add_help_tab($premium_tab_content);

        $screen->set_help_sidebar($sidebar_content);
    }

    public function get_next_or_prev_row_by_id( $id, $type = "next", $table = "aysquiz_questions" ) {
        global $wpdb;

        if ( is_null( $table ) || empty( $table ) ) {
            return null;
        }

        $ays_table = esc_sql( $wpdb->prefix . $table );

        $where = array();
        $where_condition = "";

        $id     = (isset( $id ) && $id != "" && absint($id) != 0) ? absint( sanitize_text_field( $id ) ) : null;
        $type   = (isset( $type ) && $type != "") ? sanitize_text_field( $type ) : "next";

        if ( is_null( $id ) || $id == 0 ) {
            return null;
        }

        switch ( $type ) {
            case 'prev':
                $where[] = ' `id` < ' . $id . ' ORDER BY `id` DESC ';
                break;
            case 'next':
            default:
                $where[] = ' `id` > ' . $id;
                break;
        }

        if( ! empty($where) ){
            $where_condition = " WHERE " . implode( " AND ", $where );
        }

        $sql = "SELECT `id` FROM {$ays_table} ". $where_condition ." LIMIT 1;";

        $results = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $results;

    }

    public static function ays_quiz_ays_quiz_get_quizzes(){
        global $wpdb;
        $quiz_table = esc_sql( $wpdb->prefix . "aysquiz_quizes" );

        $sql = "SELECT id,title
                FROM {$quiz_table} WHERE `published` = 1";

        $quizzes = $wpdb->get_results( $sql , "ARRAY_A" );

        return $quizzes;
    }

    public function ays_quiz_update_banner_time(){

        $date = time() + ( 3 * 24 * 60 * 60 ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
        // $date = time() + ( 60 ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS); // for testing | 1 min
        $next_3_days = date('M d, Y H:i:s', $date);

        $ays_quiz_banner_time = get_option('ays_quiz_mega_bundle_banner_time');

        if ( !$ays_quiz_banner_time || is_null( $ays_quiz_banner_time ) ) {
            update_option('ays_quiz_mega_bundle_banner_time', $next_3_days ); 
        }

        $get_ays_quiz_banner_time = get_option('ays_quiz_mega_bundle_banner_time');

        $val = 60*60*24*0.5; // half day
        // $val = 60; // for testing | 1 min

        $current_date = current_time( 'mysql' );
        $date_diff = strtotime($current_date) - intval(strtotime($get_ays_quiz_banner_time));

        $days_diff = $date_diff / $val;
        if(intval($days_diff) > 0 ){
            update_option('ays_quiz_mega_bundle_banner_time', $next_3_days);
        }

        return $get_ays_quiz_banner_time;
    }

    public function ays_quiz_author_user_search() {

        $content_text = array(
            'results' => array()
        );

        if( current_user_can( 'manage_options' ) ){
            $search = isset($_REQUEST['search']) && $_REQUEST['search'] != '' ? sanitize_text_field( $_REQUEST['search'] ) : null;
            $checked = isset($_REQUEST['val']) && $_REQUEST['val'] !='' ? sanitize_text_field( $_REQUEST['val'] ) : null;

            $args = 'search=';
            if($search !== null){
                $args .= '*';
                $args .= $search;
                $args .= '*';
            }

            $users = get_users($args);

            foreach ($users as $key => $value) {
                if ($checked !== null) {
                    if ( !is_array( $checked ) ) {
                        $checked2 = $checked;
                        $checked = array();
                        $checked[] = absint($checked2);
                    }
                    if (in_array($value->ID, $checked)) {
                        continue;
                    }else{
                        $content_text['results'][] = array(
                            'id' => $value->ID,
                            'text' => $value->data->display_name,
                        );
                    }
                }else{
                    $content_text['results'][] = array(
                        'id' => $value->ID,
                        'text' => $value->data->display_name,
                    );
                }
            }
        }

        ob_end_clean();
        echo json_encode($content_text);
        wp_die();
    }

    public function ays_quiz_generate_message_vars_html( $quiz_message_vars ) {
        $content = array();
        $var_counter = 0; 

        $content[] = '<div class="ays-quiz-message-vars-box">';
            $content[] = '<div class="ays-quiz-message-vars-icon">';
                $content[] = '<div>';
                    $content[] = '<i class="ays_fa ays_fa_link"></i>';
                $content[] = '</div>';
                $content[] = '<div>';
                    $content[] = '<span>'. __("Message Variables" , $this->plugin_name) .'</span>';
                    $content[] = '<a class="ays_help" data-toggle="tooltip" data-html="true" title="'. __("Insert your preferred message variable into the editor by clicking." , $this->plugin_name) .'">';
                        $content[] = '<i class="ays_fa ays_fa_info_circle"></i>';
                    $content[] = '</a>';
                $content[] = '</div>';
            $content[] = '</div>';
            $content[] = '<div class="ays-quiz-message-vars-data">';
                foreach($quiz_message_vars as $var => $var_name){
                    $var_counter++;
                    $content[] = '<label class="ays-quiz-message-vars-each-data-label">';
                        // $content[] = '<input type="radio" class="ays-quiz-message-vars-each-data-checker" hidden id="ays_quiz_message_var_count_'. $var_counter .'" name="ays_quiz_message_var_count">';
                        $content[] = '<div class="ays-quiz-message-vars-each-data">';
                            $content[] = '<input type="hidden" class="ays-quiz-message-vars-each-var" value="'. $var .'">';
                            $content[] = '<span>'. $var_name .'</span>';
                        $content[] = '</div>';
                    $content[] = '</label>';
                }
            $content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    /**
     * Determine if the plugin/addon installations are allowed.
     *
     * @since 6.4.0.4
     *
     * @param string $type Should be `plugin` or `addon`.
     *
     * @return bool
     */
    public static function ays_quiz_can_install( $type ) {

        return self::ays_quiz_can_do( 'install', $type );
    }

    /**
     * Determine if the plugin/addon activations are allowed.
     *
     * @since 6.4.0.4
     *
     * @param string $type Should be `plugin` or `addon`.
     *
     * @return bool
     */
    public static function ays_quiz_can_activate( $type ) {

        return self::ays_quiz_can_do( 'activate', $type );
    }

    /**
     * Determine if the plugin/addon installations/activations are allowed.
     *
     * @since 6.4.0.4
     *
     * @param string $what Should be 'activate' or 'install'.
     * @param string $type Should be `plugin` or `addon`.
     *
     * @return bool
     */
    public static function ays_quiz_can_do( $what, $type ) {

        if ( ! in_array( $what, array( 'install', 'activate' ), true ) ) {
            return false;
        }

        if ( ! in_array( $type, array( 'plugin', 'addon' ), true ) ) {
            return false;
        }

        $capability = $what . '_plugins';

        if ( ! current_user_can( $capability ) ) {
            return false;
        }

        // Determine whether file modifications are allowed and it is activation permissions checking.
        if ( $what === 'install' && ! wp_is_file_mod_allowed( 'ays_quiz_can_install' ) ) {
            return false;
        }

        // All plugin checks are done.
        if ( $type === 'plugin' ) {
            return true;
        }
        return false;
    }

    /**
     * Activate plugin.
     *
     * @since 1.0.0
     * @since 6.4.0.4 Updated the permissions checking.
     */
    public function ays_quiz_activate_plugin() {

        // Run a security check.
        check_ajax_referer( $this->plugin_name . '-install-plugin-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        // Check for permissions.
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( esc_html__( 'Plugin activation is disabled for you on this site.', 'quiz-maker' ) );
        }

        $type = 'addon';

        if ( isset( $_POST['plugin'] ) ) {

            if ( ! empty( $_POST['type'] ) ) {
                $type = sanitize_key( $_POST['type'] );
            }

            $plugin   = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
            $activate = activate_plugins( $plugin );

            if ( ! is_wp_error( $activate ) ) {
                if ( $type === 'plugin' ) {
                    wp_send_json_success( esc_html__( 'Plugin activated.', 'quiz-maker' ) );
                } else {
                        ( esc_html__( 'Addon activated.', 'quiz-maker' ) );
                }
            }
        }

        if ( $type === 'plugin' ) {
            wp_send_json_error( esc_html__( 'Could not activate the plugin. Please activate it on the Plugins page.', 'quiz-maker' ) );
        }

        wp_send_json_error( esc_html__( 'Could not activate the addon. Please activate it on the Plugins page.', 'quiz-maker' ) );
    }

    /**
     * Install addon.
     *
     * @since 1.0.0
     * @since 6.4.0.4 Updated the permissions checking.
     */
    public function ays_quiz_install_plugin() {

        // Run a security check.
        check_ajax_referer( $this->plugin_name . '-install-plugin-nonce', sanitize_key( $_REQUEST['_ajax_nonce'] ) );

        $generic_error = esc_html__( 'There was an error while performing your request.', 'quiz-maker' );
        $type          = ! empty( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '';

        // Check if new installations are allowed.
        if ( ! self::ays_quiz_can_install( $type ) ) {
            wp_send_json_error( $generic_error );
        }

        $error = $type === 'plugin'
            ? esc_html__( 'Could not install the plugin. Please download and install it manually.', 'quiz-maker' )
            : "";

        $plugin_url = ! empty( $_POST['plugin'] ) ? esc_url_raw( wp_unslash( $_POST['plugin'] ) ) : '';

        if ( empty( $plugin_url ) ) {
            wp_send_json_error( $error );
        }

        // Prepare variables.
        $url = esc_url_raw(
            add_query_arg(
                [
                    'page' => 'quiz-maker-featured-plugins',
                ],
                admin_url( 'admin.php' )
            )
        );

        ob_start();
        $creds = request_filesystem_credentials( $url, '', false, false, null );

        // Hide the filesystem credentials form.
        ob_end_clean();

        // Check for file system permissions.
        if ( $creds === false ) {
            wp_send_json_error( $error );
        }
        
        if ( ! WP_Filesystem( $creds ) ) {
            wp_send_json_error( $error );
        }

        /*
         * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
         */
        require_once AYS_QUIZ_DIR . 'includes/admin/class-quiz-maker-upgrader.php';
        require_once AYS_QUIZ_DIR . 'includes/admin/class-quiz-maker-install-skin.php';
        require_once AYS_QUIZ_DIR . 'includes/admin/class-quiz-maker-skin.php';


        // Do not allow WordPress to search/download translations, as this will break JS output.
        remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

        // Create the plugin upgrader with our custom skin.
        $installer = new QuizMaker\Helpers\QuizMakerPluginSilentUpgrader( new Quiz_Maker_Install_Skin() );

        // Error check.
        if ( ! method_exists( $installer, 'install' ) ) {
            wp_send_json_error( $error );
        }

        $installer->install( $plugin_url );

        // Flush the cache and return the newly installed plugin basename.
        wp_cache_flush();

        $plugin_basename = $installer->plugin_info();

        if ( empty( $plugin_basename ) ) {
            wp_send_json_error( $error );
        }

        $result = array(
            'msg'          => $generic_error,
            'is_activated' => false,
            'basename'     => $plugin_basename,
        );

        // Check for permissions.
        if ( ! current_user_can( 'activate_plugins' ) ) {
            $result['msg'] = $type === 'plugin' ? esc_html__( 'Plugin installed.', 'quiz-maker' ) : "";

            wp_send_json_success( $result );
        }

        // Activate the plugin silently.
        $activated = activate_plugin( $plugin_basename );
        remove_action( 'activated_plugin', array( 'ays_sccp_activation_redirect_method', 'gallery_p_gallery_activation_redirect_method', 'poll_maker_activation_redirect_method' ), 100 );

        if ( ! is_wp_error( $activated ) ) {

            $result['is_activated'] = true;
            $result['msg']          = $type === 'plugin' ? esc_html__( 'Plugin installed and activated.', 'quiz-maker' ) : esc_html__( 'Addon installed and activated.', 'quiz-maker' );

            wp_send_json_success( $result );
        }

        // Fallback error just in case.
        wp_send_json_error( $result );
    }

    /**
     * List of AM plugins that we propose to install.
     *
     * @since 6.4.0.4
     *
     * @return array
     */
    protected function get_am_plugins() {
        if ( !isset( $_SESSION ) ) {
            session_start();
        }

        $images_url = AYS_QUIZ_ADMIN_URL . '/images/icons/';

        $plugin_slug = array(
            'poll-maker',
            'survey-maker',
            'ays-popup-box',
            'gallery-photo-gallery',
            'gallery-photo-gallery',
            'secure-copy-content-protection',
            'personal-dictionary',
        );

        $plugin_url_arr = array();
        foreach ($plugin_slug as $key => $slug) {
            if ( isset( $_SESSION['ays_quiz_our_product_links'] ) && !empty( $_SESSION['ays_quiz_our_product_links'] ) 
                && isset( $_SESSION['ays_quiz_our_product_links'][$slug] ) && !empty( $_SESSION['ays_quiz_our_product_links'][$slug] ) ) {
                $plugin_url = (isset( $_SESSION['ays_quiz_our_product_links'][$slug] ) && $_SESSION['ays_quiz_our_product_links'][$slug] != "") ? esc_url( $_SESSION['ays_quiz_our_product_links'][$slug] ) : "";
            } else {
                $latest_version = $this->ays_quiz_get_latest_plugin_version($slug);
                $plugin_url = 'https://downloads.wordpress.org/plugin/'. $slug .'.zip';
                if ( $latest_version != '' ) {
                    $plugin_url = 'https://downloads.wordpress.org/plugin/'. $slug .'.'. $latest_version .'.zip';
                    $_SESSION['ays_quiz_our_product_links'][$slug] = $plugin_url;
                }
            }

            $plugin_url_arr[$slug] = $plugin_url;
        }

        $plugins_array = array(
           'poll-maker/poll-maker-ays.php'        => array(
                'icon'        => $images_url . 'icon-poll-128x128.png',
                'name'        => __( 'Poll Maker', 'quiz-maker' ),
                'desc'        => __( 'Create amazing online polls for your WordPress website super easily.', 'quiz-maker' ),
                'desc_hidden' => __( 'Build up various types of polls in a minute and get instant feedback on any topic or product.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/poll-maker/',
                'buy_now'     => 'https://ays-pro.com/wordpress/poll-maker/',
                'url'         => $plugin_url_arr['poll-maker'],
            ),
            'survey-maker/survey-maker.php'        => array(
                'icon'        => $images_url . 'icon-survey-128x128.png',
                'name'        => __( 'Survey Maker', 'quiz-maker' ),
                'desc'        => __( 'Make amazing online surveys and get real-time feedback quickly and easily.', 'quiz-maker' ),
                'desc_hidden' => __( 'Learn what your website visitors want, need, and expect with the help of Survey Maker. Build surveys without limiting your needs.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/survey-maker/',
                'buy_now'     => 'https://ays-pro.com/wordpress/survey-maker',
                'url'         => $plugin_url_arr['survey-maker'],
            ),
            'ays-popup-box/ays-pb.php'        => array(
                'icon'        => $images_url . 'icon-popup-128x128.png',
                'name'        => __( 'Popup Box', 'quiz-maker' ),
                'desc'        => __( 'Popup everything you want! Create informative and promotional popups all in one plugin.', 'quiz-maker' ),
                'desc_hidden' => __( 'Attract your visitors and convert them into email subscribers and paying customers.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/ays-popup-box/',
                'buy_now'     => 'https://ays-pro.com/wordpress/popup-box/',
                'url'         => $plugin_url_arr['ays-popup-box'],
            ),
            'gallery-photo-gallery/gallery-photo-gallery.php'        => array(
                'icon'        => $images_url . 'icon-gallery-128x128.jpg',
                'name'        => __( 'Gallery Photo Gallery', 'quiz-maker' ),
                'desc'        => __( 'Create unlimited galleries and include unlimited images in those galleries.', 'quiz-maker' ),
                'desc_hidden' => __( 'Represent images in an attractive way. Attract people with your own single and multiple free galleries from your photo library.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/gallery-photo-gallery/',
                'buy_now'     => 'https://ays-pro.com/wordpress/photo-gallery/',
                'url'         => $plugin_url_arr['gallery-photo-gallery'],
            ),
            'secure-copy-content-protection/secure-copy-content-protection.php'        => array(
                'icon'        => $images_url . 'icon-sccp-128x128.png',
                'name'        => __( 'Secure Copy Content Protection', 'quiz-maker' ),
                'desc'        => __( 'Disable the right click, copy paste, content selection and copy shortcut keys on your website.', 'quiz-maker' ),
                'desc_hidden' => __( 'Protect web content from being plagiarized. Prevent plagiarism from your website with this easy to use plugin.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/secure-copy-content-protection/',
                'buy_now'     => 'https://ays-pro.com/wordpress/secure-copy-content-protection/',
                'url'         => $plugin_url_arr['secure-copy-content-protection'],
            ),
            'personal-dictionary/personal-dictionary.php'        => array(
                'icon'        => $images_url . 'pd-logo-128x128.png',
                'name'        => __( 'Personal Dictionary', 'quiz-maker' ),
                'desc'        => __( 'Allow your students to create personal dictionary, study and memorize the words.', 'quiz-maker' ),
                'desc_hidden' => __( 'Allow your users to create their own digital dictionaries and learn new words and terms as fastest as possible.', 'quiz-maker' ),
                'wporg'       => 'https://wordpress.org/plugins/personal-dictionary/',
                'buy_now'     => 'https://ays-pro.com/wordpress/personal-dictionary/',
                'url'         => $plugin_url_arr['personal-dictionary'],
                // 'pro'   => array(
                //     'plug' => '',
                //     'icon' => '',
                //     'name' => '',
                //     'desc' => '',
                //     'url'  => '',
                //     'act'  => 'go-to-url',
                // ),
            ),
        );

        return $plugins_array;
    }

    protected function ays_quiz_get_latest_plugin_version( $slug ){

        if ( is_null( $slug ) || empty($slug) ) {
            return "";
        }

        $version_latest = "";

        if ( ! function_exists( 'plugins_api' ) ) {
              require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        }

        // set the arguments to get latest info from repository via API ##
        $args = array(
            'slug' => $slug,
            'fields' => array(
                'version' => true,
            )
        );

        /** Prepare our query */
        $call_api = plugins_api( 'plugin_information', $args );

        /** Check for Errors & Display the results */
        if ( is_wp_error( $call_api ) ) {
            $api_error = $call_api->get_error_message();
        } else {

            //echo $call_api; // everything ##
            if ( ! empty( $call_api->version ) ) {
                $version_latest = $call_api->version;
            }
        }

        return $version_latest;
    }

    /**
     * Get AM plugin data to display in the Addons section of About tab.
     *
     * @since 6.4.0.4
     *
     * @param string $plugin      Plugin slug.
     * @param array  $details     Plugin details.
     * @param array  $all_plugins List of all plugins.
     *
     * @return array
     */
    protected function get_plugin_data( $plugin, $details, $all_plugins ) {

        $have_pro = ( ! empty( $details['pro'] ) && ! empty( $details['pro']['plug'] ) );
        $show_pro = false;

        $plugin_data = array();

        if ( $have_pro ) {
            if ( array_key_exists( $plugin, $all_plugins ) ) {
                if ( is_plugin_active( $plugin ) ) {
                    $show_pro = true;
                }
            }
            if ( array_key_exists( $details['pro']['plug'], $all_plugins ) ) {
                $show_pro = true;
            }
            if ( $show_pro ) {
                $plugin  = $details['pro']['plug'];
                $details = $details['pro'];
            }
        }

        if ( array_key_exists( $plugin, $all_plugins ) ) {
            if ( is_plugin_active( $plugin ) ) {
                // Status text/status.
                $plugin_data['status_class'] = 'status-active';
                $plugin_data['status_text']  = esc_html__( 'Active', 'quiz-maker' );
                // Button text/status.
                $plugin_data['action_class'] = $plugin_data['status_class'] . ' ays-quiz-card__btn-info disabled';
                $plugin_data['action_text']  = esc_html__( 'Activated', 'quiz-maker' );
                $plugin_data['plugin_src']   = esc_attr( $plugin );
            } else {
                // Status text/status.
                $plugin_data['status_class'] = 'status-installed';
                $plugin_data['status_text']  = esc_html__( 'Inactive', 'quiz-maker' );
                // Button text/status.
                $plugin_data['action_class'] = $plugin_data['status_class'] . ' ays-quiz-card__btn-info';
                $plugin_data['action_text']  = esc_html__( 'Activate', 'quiz-maker' );
                $plugin_data['plugin_src']   = esc_attr( $plugin );
            }
        } else {
            // Doesn't exist, install.
            // Status text/status.
            $plugin_data['status_class'] = 'status-missing';

            if ( isset( $details['act'] ) && 'go-to-url' === $details['act'] ) {
                $plugin_data['status_class'] = 'status-go-to-url';
            }
            $plugin_data['status_text'] = esc_html__( 'Not Installed', 'quiz-maker' );
            // Button text/status.
            $plugin_data['action_class'] = $plugin_data['status_class'] . ' ays-quiz-card__btn-info';
            $plugin_data['action_text']  = esc_html__( 'Install Plugin', 'quiz-maker' );
            $plugin_data['plugin_src']   = esc_url( $details['url'] );
        }

        $plugin_data['details'] = $details;

        return $plugin_data;
    }

    /**
     * Display the Addons section of About tab.
     *
     * @since 6.4.0.4
     */
    public function output_about_addons() {

        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $all_plugins          = get_plugins();
        $am_plugins           = $this->get_am_plugins();
        $can_install_plugins  = self::ays_quiz_can_install( 'plugin' );
        $can_activate_plugins = self::ays_quiz_can_activate( 'plugin' );

        $content = '';
        $content.= '<div class="ays-quiz-cards-block">';
        foreach ( $am_plugins as $plugin => $details ){

            $plugin_data              = $this->get_plugin_data( $plugin, $details, $all_plugins );
            $plugin_ready_to_activate = $can_activate_plugins
                && isset( $plugin_data['status_class'] )
                && $plugin_data['status_class'] === 'status-installed';
            $plugin_not_activated     = ! isset( $plugin_data['status_class'] )
                || $plugin_data['status_class'] !== 'status-active';

            $plugin_action_class = ( isset( $plugin_data['action_class'] ) && esc_attr( $plugin_data['action_class'] ) != "" ) ? esc_attr( $plugin_data['action_class'] ) : "";

            $plugin_action_class_disbaled = "";
            if ( strpos($plugin_action_class, 'status-active') !== false ) {
                $plugin_action_class_disbaled = "disbaled='true'";
            }

            $content .= '
                <div class="ays-quiz-card">
                    <div class="ays-quiz-card__content flexible">
                        <div class="ays-quiz-card__content-img-box">
                            <img class="ays-quiz-card__img" src="'. esc_url( $plugin_data['details']['icon'] ) .'" alt="'. esc_attr( $plugin_data['details']['name'] ) .'">
                        </div>
                        <div class="ays-quiz-card__text-block">
                            <h5 class="ays-quiz-card__title">'. esc_html( $plugin_data['details']['name'] ) .'</h5>
                            <p class="ays-quiz-card__text">'. wp_kses_post( $plugin_data['details']['desc'] ) .'
                                <span class="ays-quiz-card__text-hidden">
                                    '. wp_kses_post( $plugin_data['details']['desc_hidden'] ) .'
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="ays-quiz-card__footer">';
                        if ( $can_install_plugins || $plugin_ready_to_activate || ! $details['wporg'] ) {
                            $content .= '<button class="'. esc_attr( $plugin_data['action_class'] ) .'" data-plugin="'. esc_attr( $plugin_data['plugin_src'] ) .'" data-type="plugin" '. $plugin_action_class_disbaled .'>
                                '. wp_kses_post( $plugin_data['action_text'] ) .'
                            </button>';
                        }
                        elseif ( $plugin_not_activated ) {
                            $content .= '<a href="'. esc_url( $details['wporg'] ) .'" target="_blank" rel="noopener noreferrer">
                                '. esc_html_e( 'WordPress.org', 'quiz-maker' ) .'
                                <span aria-hidden="true" class="dashicons dashicons-external"></span>
                            </a>';
                        }
            $content .='
                        <a target="_blank" href="'. esc_url( $plugin_data['details']['buy_now'] ) .'" class="ays-quiz-card__btn-primary">'. __('Buy Now', $this->plugin_name) .'</a>
                    </div>
                </div>';
        }
        $install_plugin_nonce = wp_create_nonce( $this->plugin_name . '-install-plugin-nonce' );
        $content.= '<input type="hidden" id="ays_quiz_ajax_install_plugin_nonce" name="ays_quiz_ajax_install_plugin_nonce" value="'. $install_plugin_nonce .'">';
        $content.= '</div>';

        echo $content;
    }


    public function ays_quiz_footer_popup_box_banner() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}aysquiz_reports";
        $results_count = $wpdb->get_var($sql);

        if( current_user_can( 'manage_options' ) && $results_count >= 1000 ){

            $content = array();

            $content[] = '<div class="ays-quiz-popup-box-main-conatiner">';
                $content[] = '<div class="ays-quiz-popup-box-conatiner">';

                    $content[] = '<div class="ays-quiz-popup-box-header">';
                        $content[] = '<div class="ays-quiz-popup-box-header-title-row">';
                            $content[] = '<div class="ays-quiz-popup-box-header-title">';
                                $content[] = sprintf( __( "You've got %s quiz results!", 'quiz-maker' ),
                                            $results_count
                                            );
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-popup-box-header-desc-row">';
                            $content[] = '<div class="ays-quiz-popup-box-header-desc">';
                                $content[] ='Upgrade now and enjoy a ' . '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=result-popup-box-banner" class="ays-quiz-popup-box-header-desc-a" target="blank"><span class="ays-quiz-popup-box-header-desc-span">' . '20%'. '</a></span>' .' discount.';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-popup-box-header-img-row">';
                            $content[] = '<div class="ays-quiz-popup-box-header-img">';
                                $content[] = '<img src="'.  AYS_QUIZ_ADMIN_URL.'/images/banner/ays-quiz-popup-banner-party-popper-icon.webp">';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-quiz-popup-box-header-close-button">';
                                $content[] = '<img src="'.  AYS_QUIZ_ADMIN_URL.'/images/banner/ays-quiz-popup-banner-close-icon.svg">';
                        $content[] = '</div>';

                    $content[] = '</div>';


                    $content[] = '<div class="ays-quiz-popup-box-content">';

                        $content[] = '<div class="ays-quiz-popup-box-content-option-list-row">';
                            $content[] = '<div class="ays-quiz-popup-box-content-option-list">';
                                $content[] = '<ul class="ays-quiz-popup-box-content-option-list-ul">';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Export Results", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Custom Fields", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Leaderboards", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Send Email & Certificate", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("10+ Integrations", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Premium Support", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Personalize Result", 'quiz-maker') .'</li>';
                                    $content[] = '<li class="ays-quiz-popup-box-content-option-list-ul-li">'. __("Lifetime Usage", 'quiz-maker') .'</li>';
                                $content[] = '</ul>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                    $content[] = '</div>';


                    $content[] = '<div class="ays-quiz-popup-box-action">';
                        $content[] = '<div class="ays-quiz-popup-box-action-button-row">';
                            $content[] = '<a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=result-popup-box-banner" target="blank" class="ays-quiz-popup-box-action-button">';
                                $content[] = __( "Upgrade", 'quiz-maker' );
                            $content[] = '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';

                    $content[] = '<div class="ays-quiz-popup-box-footer">';

                    $content[] = '</div>';

                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
        }

    }

    public static function ays_quiz_check_if_current_image_exists( $image_url ) {
        global $wpdb;

        $res = true;
        if( !isset($image_url) ){
            $res = false;
        }

        if ( isset($image_url) && !empty( $image_url ) ) {

            $re = '/-\d+[Xx]\d+\./';
            $subst = '.';

            $image_url = preg_replace($re, $subst, $image_url, 1);

            $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
            if ( is_null( $attachment ) || empty( $attachment ) ) {
                $res = false;
            }
        }

        return $res; 
    }

    // Integrations settings page action hook
    public function ays_quiz_settings_page_integrations_content( $args ){

        $integrations_contents = apply_filters( 'ays_qm_settings_page_integrations_contents', array(), $args );

        $integrations = array();

        foreach ($integrations_contents as $key => $integrations_content) {
            $content = '<fieldset>';
            if(isset($integrations_content['title'])){
                $content .= '<legend>';
                if(isset($integrations_content['icon'])){
                    $content .= '<img class="ays_integration_logo" src="'. $integrations_content['icon'] .'" alt="" style="margin-right: 10px;">';
                }
                $content .= '<h5>'. $integrations_content['title'] .'</h5></legend>';
            }
            if(isset($integrations_content['content'])){
                $content .= $integrations_content['content'];
            }

            $content .= '</fieldset>';

            $integrations[] = $content;
        }

        echo implode('<hr/>', $integrations);
    }
    
}
