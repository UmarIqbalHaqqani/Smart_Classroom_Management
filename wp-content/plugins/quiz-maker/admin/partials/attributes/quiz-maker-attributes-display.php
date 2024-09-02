<br>
<div class="wrap ays-quiz-custom-fields-list-table">
    <h1 class="wp-heading-inline">
        <?php echo __(esc_html(get_admin_page_title()), $this->plugin_name); ?>
    </h1>
    <div class="ays-quiz-how-to-user-custom-fields-box" style="margin: 30px auto;">
        <div class="ays-quiz-how-to-user-custom-fields-title">
            <h4><?php echo __( "Learn How to Use Custom Fields in Quiz Maker", $this->plugin_name ); ?></h4>
        </div>
        <div class="ays-quiz-how-to-user-custom-fields-youtube-video">
            <iframe width="560" height="315" class="ays-quiz-responsive-with-for-iframe" src="https://www.youtube.com/embed/Guq_SncdCMo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
        </div>
    </div>
    <div class="row" style="margin:0;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features pro_features_popup">
                <div class="pro-features-popup-conteiner">
                    <div class="pro-features-popup-title">
                        <?php echo __("Custom Field", $this->plugin_name); ?>
                    </div>
                    <div class="pro-features-popup-content" data-link="https://youtu.be/SEv7ZY7idtE">
                        <p>
                            <?php echo sprintf( __("Custom Fields will allow you to create various fields with %s 8 available field types, %s including text, number, telephone. With just two simple steps, you can get any information you wish from the Quiz takers and add  %s GDPR %s checkbox as well. Get personal data, such as gender, country, age etc.", $this->plugin_name),
                                "<strong>",
                                "</strong>",
                                "<strong>",
                                "</strong>"
                            ); ?>
                        </p>
                        <div>
                            <a href="https://ays-pro.com/wordpress-quiz-maker-user-manual" target="_blank"><?php echo __("See Documentation", $this->plugin_name); ?></a>
                        </div>
                    </div>
                    <div class="pro-features-popup-button" data-link="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=custom-field-list-table">
                        <?php echo __("Upgrade PRO NOW", $this->plugin_name); ?>
                    </div>
                </div>
            </div>
            <img src="<?php echo AYS_QUIZ_ADMIN_URL.'/images/attributes_screen.png'?>" alt="Statistics" style="width:100%;" >
            <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=custom-field-list-table" target="_blank">
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
                    <a href="https://ays-pro.com/wordpress/quiz-maker?utm_source=dashboard&utm_medium=quiz-free&utm_campaign=custom-field-list-table" target="_blank" class="ays-quiz-new-upgrade-button-link">
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