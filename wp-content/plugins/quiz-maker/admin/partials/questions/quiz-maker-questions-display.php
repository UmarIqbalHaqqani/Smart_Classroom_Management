<?php
global $wpdb;

$tab = 'tab1';

$action = ( isset($_GET['action']) ) ? sanitize_text_field( $_GET['action'] ) : '';
$id     = ( isset($_GET['question']) ) ? sanitize_text_field( $_GET['question'] ) : null;

if($action == 'duplicate'){
    $this->questions_obj->duplicate_question($id);
}
$example_export_path = AYS_QUIZ_ADMIN_URL . '/partials/questions/export_file/';
$plus_icon_svg = "<span class=''><img src='". AYS_QUIZ_ADMIN_URL ."/images/icons/plus=icon.svg'></span>";

?>

<div class="wrap ays-quiz-list-table ays_questions_list_table">
    <div class="ays-quiz-heading-box">
        <div class="ays-quiz-wordpress-user-manual-box">
            <a href="https://ays-pro.com/wordpress-quiz-maker-user-manual" target="_blank"><?php echo __("View Documentation", $this->plugin_name); ?></a>
        </div>
    </div>
    <h1 class="wp-heading-inline">
        <?php
            echo __(esc_html(get_admin_page_title()),$this->plugin_name);
        ?>
    </h1>


    <div class="question-action-butons">
        <!--<div class="ays-quiz-export-import-box">
            <div class="ays-quiz-export-import-video-tutorial">
                <a href="https://www.youtube.com/watch?v=RldosodJItI&" target="_blank">
                    <?php echo __('Video tutorial',$this->plugin_name); ?>
                </a>
            </div>
        </div> -->
        <div class="ays-quiz-export-import-tooltip">
            <a class="ays_help mr-2" style="font-size:20px;" data-toggle="tooltip" title="<?php echo __("For import XLSX file your version of PHP must be over than 5.6.", $this->plugin_name); ?>">
                <i class="ays_fa ays_fa_info_circle"></i>
            </a>
        </div>
        <div class="dropdown ays-export-dropdown" style="">
            <a href="javascript:void(0);" data-toggle="dropdown" class="button mr-2 dropdown-toggle">
                <span class="ays-wp-loading d-none"></span>
                <?php echo __('Example', $this->plugin_name); ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right ays-dropdown-menu">
                <a href="<?php echo $example_export_path; ?>example_questions_export.csv"                    
                   download="example_questions_export.csv" class="dropdown-item">
                    CSV
                </a>
                <a href="<?php echo $example_export_path; ?>example_questions_export.xlsx"
                   download="example_questions_export.xlsx" class="dropdown-item">
                    XLSX
                </a>
                <a href="<?php echo $example_export_path; ?>example_questions_export.json"
                   download="example_questions_export.json" class="dropdown-item">
                    JSON
                </a>
                <a href="<?php echo $example_export_path; ?>example_questions_export_simple.xlsx"
                   download="example_questions_export_simple.xlsx" class="dropdown-item">
                    Simple XLSX
                </a>
            </div>
        </div>
        <div class="ays-quiz-pro-features-box" style="position: relative; margin-right: 10px;" title="<?php echo __('This property available only in pro version',$this->plugin_name); ?>">
            <div class="pro_features pro_features_popup">
                <div class="pro-features-popup-conteiner">
                    <div class="pro-features-popup-title">
                        <?php echo __("Export/import questions", $this->plugin_name); ?>
                    </div>
                    <div class="pro-features-popup-content" data-link="https://youtu.be/_lGi6PBamGg">
                        <p>
                            <?php echo  sprintf( __("The feature allows you to save time by exporting and importing already-created questions quickly and easily. You can download the example file formats %s (XLSX, CSV, JSON, Simple XLSX)%s, add your questions/answers there, and import the file to the plugin. You just need to make sure that the file you are importing has the same structure as our example file.", $this->plugin_name), 
                                '<strong>',
                                '</strong>'
                            ); ?>
                        </p>
                        <div>
                            <a href="https://ays-pro.com/wordpress-quiz-maker-user-manual" target="_blank"><?php echo __("See Documentation", $this->plugin_name); ?></a>
                        </div>
                    </div>
                    <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=question-export-import">
                        <?php echo __("Upgrade PRO NOW", $this->plugin_name); ?>
                    </div>
                </div>
            </div>
            <div> 
                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=question-export-import" target="_blank" title="<?php echo __('This property available only in pro version',$this->plugin_name); ?>" class="button disabled-button"><?php echo __('Export to', $this->plugin_name); ?></a>
            </div>
        </div>
        <div class="ays-quiz-pro-features-box" style="position: relative; margin-right: 10px;" title="<?php echo __('This property available only in pro version',$this->plugin_name); ?>">
            <div class="pro_features pro_features_popup">
                <div class="pro-features-popup-conteiner">
                    <div class="pro-features-popup-title">
                        <?php echo __("Export/import questions", $this->plugin_name); ?>
                    </div>
                    <div class="pro-features-popup-content" data-link="https://youtu.be/_lGi6PBamGg">
                        <p>
                            <?php echo __("The feature allows you to save time by exporting and importing already-created questions quickly and easily. You can download the example file formats (XLSX, CSV, JSON, Simple XLSX), add your questions/answers there, and import the file to the plugin. You just need to make sure that the file you are importing has the same structure as our example file.", $this->plugin_name); ?>
                        </p>
                        <div>
                            <a href="https://ays-pro.com/wordpress-quiz-maker-user-manual" target="_blank"><?php echo __("See Documentation", $this->plugin_name); ?></a>
                        </div>
                    </div>
                    <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=question-export-import">
                        <?php echo __("Upgrade PRO NOW", $this->plugin_name); ?>
                    </div>
                </div>
            </div>
            <div>
                <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=question-export-import" target="_blank" title="<?php echo __('This property available only in pro version',$this->plugin_name); ?>" class="button disabled-button" aria-expanded="false"><?= __('Import', $this->plugin_name); ?></a>
            </div>
        </div>
    </div>
    <div class="ays-quiz-heading-box ays-quiz-unset-float">
        <div class="ays-quiz-wordpress-user-manual-box ays-quiz-wordpress-text-align">
            <a href="https://www.youtube.com/watch?v=_lGi6PBamGg" target="_blank">
                <?php echo __("How to export/import questions - video", $this->plugin_name); ?>
            </a>
        </div>
    </div>

    <div class="nav-tab-wrapper">
        <a href="#tab1" class="nav-tab <?php echo ($tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
            <?php echo __("Questions", $this->plugin_name);?>
        </a>
        <a href="#tab2" class="nav-tab <?php echo ($tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
            <?php echo __("Reports", $this->plugin_name); ?>
        </a>
    </div>

    <hr/>
    <div id="tab1" class="ays-quiz-tab-content <?php echo ($tab == 'tab1') ? 'ays-quiz-tab-content-active' : ''; ?>">
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
                            $this->questions_obj->views();
                        ?>
                        <form method="post">
                            <?php
                                $this->questions_obj->prepare_items();
                                $this->questions_obj->search_box('Search', $this->plugin_name);
                                $this->questions_obj->display();
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
    </div>

    <div id="tab2" class="ays-quiz-tab-content <?php echo ($tab == 'tab2') ? 'ays-quiz-tab-content-active' : ''; ?>">
        <div class="row" style="margin: 0; margin-top:20px;">
            <div class="col-sm-12 only_pro">
                <div class="pro_features pro_features_popup_only_hover">

                </div>
                <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/question_reports_screen.png'?>" alt="<?php echo __( "Question Reports", $this->plugin_name ) ?>" style="width:100%;" >
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
