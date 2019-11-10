<?php
/**
 * The template for displaying the store settings form
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/
 *
 * @package    WCVendors_Pro
 * @version    1.6.2
 */

$settings_social = (array)get_option('wcvendors_hide_settings_social');
$social_total = count($settings_social);
$social_count = 0;
foreach ($settings_social as $value) {
    if (1 == $value) {
        $social_count += 1;
    }
}

?>

    <?php do_action('wcvendors_settings_before_form'); ?>
<div class="form-actions">

    <?php WCVendors_Pro_Store_Form::save_button(__('Save', 'wcvendors-pro')); ?>
</div>
    <form method="post" id="wcv-store-settings" action="#" class="wcv-form">
        <ul class="nav nav-tabs" role="tablist" style="padding-left: 0;">
            <li class="nav-item">
                <a class="nav-link active" href="#account" data-toggle="tab" role="tab" aria-controls="account"
                   aria-selected="true">Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#payment" data-toggle="tab" role="tab" aria-controls="payment"
                   aria-selected="false">Payment</a>
            </li>
        </ul>
        <div style="height: 40px;clear: both"></div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                <div class="section-header">
                    Information
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-6">
                            <?php get_store_name() ;?>
                        </div>
                        <div class="col-6">
                            <?php do_action('wcvendors_settings_before_company_url'); ?>
                            <?php WCVendors_Pro_Store_Form::company_url(); ?>
                            <?php do_action('wcvendors_settings_after_company_url'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-6">
                            <?php WCVendors_Pro_Store_Form::store_description($store_description); ?>
                        </div>
                        <div class="col-6">
                            <?php get_store_instagram(); ?>
                            <?php get_store_facebook(); ?>
                        </div>
                    </div>
                </div>
                <div class="section-header mt-60">
                    Contact Person
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col">
                            <label for="pv_shop_name">Person details</label>
                            <?php get_owner_title(); ?>
                            <?php get_owner_firstname(); ?>
                            <?php get_owner_lastname(); ?>
                            <?php get_owner_contact(); ?>
                        </div>
                        <div class="col">
                            <label for="pv_shop_name">Business details</label>
                            <?php get_business_abn(); ?>
                        </div>
                    </div>
                </div>
                <div class="section-header mt-60">
                    Account Settings
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="_wcv_vacation_mode"
                                <?php if (get_user_meta( get_current_user_id(), '_wcv_vacation_mode', true ) == 'on') {echo 'checked="checked"';}; ?>
                                   name="_wcv_vacation_mode">
                            <label class="custom-control-label" for="_wcv_vacation_mode">Enable Vacation mode</label>
                        </div>
                    </div>
                    <div class="light-border-1 my-4"></div>

                    <div class="form-row">
                        <div class="col-6">
                            <label for="return_date">Return Date</label>
                            <input class="form-control wcv-datepicker" type="text" name="return_date" id="return_date" placeholder="Enter Date"
                                   value="<?php echo get_user_meta( get_current_user_id(), 'return_date', true ); ?>"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                <div class="section-header">
                    Information
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col">
                            <label for="wcv_bank_account_name">Bank Account Name</label>
                            <input class="form-control"
                                   type="text" name="wcv_bank_account_name" id="wcv_bank_account_name"
                                   value="<?php echo get_user_meta( get_current_user_id(), 'wcv_bank_account_name', true ); ?>"/>
                        </div>
                        <div class="col">
                            <label for="wcv_bank_account_number">Bank Account Number</label>
                            <input class="form-control"
                                   type="text" name="wcv_bank_account_number" id="wcv_bank_account_number"
                                   value="<?php echo get_user_meta( get_current_user_id(), 'wcv_bank_account_number', true ); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col">
                            <label for="wcv_bank_account_name">Bank Name</label>
                            <input class="form-control" type="text"
                                   name="wcv_bank_name"
                                   id="wcv_bank_name"
                                   value="<?php echo get_user_meta( get_current_user_id(), 'wcv_bank_name', true ); ?>"/>
                        </div>
                        <div class="col">
                            <label for="wcv_bank_bsb">BSB</label>
                            <input class="form-control" type="text"
                                   name="wcv_bank_bsb"
                                   id="wcv_bank_bsb"
                                   value="<?php echo get_user_meta( get_current_user_id(), 'wcv_bank_bsb', true ); ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php WCVendors_Pro_Store_Form::form_data(); ?>


            <!-- Submit Button -->
            <!-- DO NOT REMOVE THE FOLLOWING TWO LINES -->
    </form>
<?php
do_action('wcvendors_settings_after_form');
