<?php
defined('ABSPATH') || die();

$school_id = $current_school['id'];

$classes = WLSM_M_Staff_Class::fetch_classes($school_id);
?>
<div class="row">
    <div class="col-md-12">
        <div class="mt-2 text-center wlsm-section-heading-block">
            <span class="wlsm-section-heading">
                <i class="fas fa-file-invoice"></i>
                <?php esc_html_e('Print Invoices in Bulk', 'school-management'); ?>
            </span>
        </div>
        <div class="wlsm-students-block wlsm-form-section">
            <div class="row">
                <div class="col-md-12">
                    <form action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" id="wlsm-print-bulk-invoices-form" class="mb-3">
                        <?php
                        $nonce_action = 'print-invoices';
                        ?>
                        <?php $nonce = wp_create_nonce($nonce_action); ?>
                        <input type="hidden" name="<?php echo esc_attr($nonce_action); ?>" value="<?php echo esc_attr($nonce); ?>">

                        <input type="hidden" name="action" value="wlsm-print-bulk-invoices">

                        <div class="pt-2">
                            <div class="row">
                                <div class="col-md-8 mb-1">
                                    <div class="h6">
                                        <span class="text-secondary border-bottom">
                                            <?php esc_html_e('Search Students By Class', 'school-management'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="wlsm_class" class="wlsm-font-bold">
                                        <?php esc_html_e('Class', 'school-management'); ?>:
                                    </label>
                                    <select name="class_id" class="form-control selectpicker" data-nonce="<?php echo esc_attr(wp_create_nonce('get-class-sections')); ?>" id="wlsm_class" data-live-search="true">
                                        <option value=""><?php esc_html_e('Select Class', 'school-management'); ?></option>
                                        <?php foreach ($classes as $class) { ?>
                                            <option value="<?php echo esc_attr($class->ID); ?>">
                                                <?php echo esc_html(WLSM_M_Class::get_label_text($class->label)); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="wlsm_section" class="wlsm-font-bold">
                                        <?php esc_html_e('Section', 'school-management'); ?>:
                                    </label>
                                    <select name="section_id" class="form-control selectpicker" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e('All Sections', 'school-management'); ?>" data-all-sections="1">
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="wlsm_paid" class="wlsm-font-bold">
                                        <?php esc_html_e('Status', 'school-management'); ?>:
                                    </label>
                                    <select name="paid" class="form-control selectpicker" id="wlsm_paid">
                                        <option value="paid"><?php esc_html_e('Paid', 'school-management'); ?></option>
                                        <option value="partially_paid"><?php esc_html_e('Partially Paid', 'school-management'); ?></option>
                                        <option value="unpaid"><?php esc_html_e('Unpaid', 'school-management'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-sm btn-outline-primary" id="wlsm-print-bulk-invoices-btn">
                                    <i class="fas fa-print"></i>&nbsp;
                                    <?php esc_html_e('Print Invoices ', 'school-management'); ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>