<?php
defined('ABSPATH') || die();

$nonce_action = 'wlsm-school-register';

?>

<div class="wlsm wlsm-grid">
    <div id="wlsm-school-register-section">
        <div class="wlsm-header-title wlsm-font-bold wlsm-mb-3">
            <span class="wlsm-border-bottom wlsm-pb-1">
            </span>
        </div>

        <form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-school-register-form">
            <?php $nonce = wp_create_nonce($nonce_action); ?>
            <input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">
            <input type="hidden" name="action" value="wlsm-p-submit-school_register">

            <div class="wlsm-row">
                <div class="wlsm-col-6">

                    <div class="wlsm-form-group wlsm-col">
                        <label for="wlsm_school_name" class="wlsm-font-bold">
                            <span class="wlsm-important">*</span> <?php esc_html_e('School Name', 'school-management'); ?>:
                        </label>
                        <input type="text" name="name" class="wlsm-form-control" id="wlsm_school_name" placeholder="<?php esc_attr_e('Enter School Name', 'school-management'); ?>" value="">
                    </div>

                    <div class="wlsm-form-group wlsm-col">
                        <label for="wlsm_school_phone" class="wlsm-font-bold">
                            <?php esc_html_e('School Phone', 'school-management'); ?>:
                        </label>
                        <input type="text" name="school_phone" class="wlsm-form-control" id="wlsm_school_phone" placeholder="<?php esc_attr_e('Enter School Phone', 'school-management'); ?>" value="">
                    </div>

                    <div class="wlsm-form-group wlsm-col">
                        <label for="wlsm_school_email" class="wlsm-font-bold">
                            <?php esc_html_e('School Email', 'school-management'); ?>:
                        </label>
                        <input type="text" name="school_email" class="wlsm-form-control" id="wlsm_school_email" placeholder="<?php esc_attr_e('Enter School Email', 'school-management'); ?>" value="">
                    </div>

                    <div class="wlsm-form-group wlsm-col">
                        <label for="wlsm_school_address" class="wlsm-font-bold">
                            <?php esc_html_e('School Address', 'school-management'); ?>:
                        </label>
                        <input type="text" name="school_address" class="wlsm-form-control" id="wlsm_school_address" placeholder="<?php esc_attr_e('Enter School Address', 'school-management'); ?>" value="">
                    </div>
                </div>

                <div class="wlsm-col-6">
                    <div class="wlsm-form-group wlsm-col">
                        <label for="wlsm_user_name" class="wlsm-font-bold">
                            <?php esc_html_e('Username', 'school-management'); ?>:
                        </label>
                        <input type="text" name="user_name" class="wlsm-form-control" id="wlsm_user_name" placeholder="<?php esc_attr_e('Enter Username', 'school-management'); ?>" value="">
                    </div>

                    <div class="wlsm-form-group wlsm-col">
                        <label for="wlsm_user_email" class="wlsm-font-bold">
                            <?php esc_html_e('User Email', 'school-management'); ?>:
                        </label>
                        <input type="text" name="login_email" class="wlsm-form-control" id="wlsm_user_email" placeholder="<?php esc_attr_e('Enter User Email', 'school-management'); ?>" value="">
                    </div>

                    <div class="wlsm-form-group wlsm-col">
                        <label for="wlsm_password" class="wlsm-font-bold">
                            <?php esc_html_e('School Password', 'school-management'); ?>:
                        </label>
                        <input type="text" name="password" class="wlsm-form-control" id="wlsm_password" placeholder="<?php esc_attr_e('Enter School Password', 'school-management'); ?>" value="">
                    </div>
                </div>
            </div>

            <div class="wlsm-border-top wlsm-pt-2 wlsm-mt-1">
                <button class="button wlsm-btn btn btn-primary" type="submit" id="wlsm-school-register-btn">
                    <?php esc_html_e('Submit', 'school-management'); ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?php
return ob_get_clean();
