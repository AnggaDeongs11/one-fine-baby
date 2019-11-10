<?php
/**
 * Settings Template
 *
 * This template can be overridden by copying it to yourtheme/wc-vendors/dashboard/settings/settings.php
 *
 * @author        Jamie Madden, WC Vendors
 * @package       WCVendors/Templates/Emails/HTML
 * @version       2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php
if ( function_exists( 'wc_print_notices' ) ) {
	wc_print_notices();
}

global $wp;
$readonly = $wp->request !== 'my-account/edit-account' ;
?>
<<?php echo $readonly ? 'div class="readonly"' : 'form method="post"' ;?>>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#account" data-toggle="tab" role="tab" aria-controls="account" aria-selected="true">Account</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#payment" data-toggle="tab" role="tab" aria-controls="payment" aria-selected="false">Payment</a>
        </li>
    </ul>
    <div style="height: 40px;clear: both"></div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="home-tab">
            <div class="section-header">
                Information
            </div>
            <div class="form-group">
                <div class="form-row">

                    <div class="col">

                        <?php
                        wc_get_template(
                            'shop-name.php', array(
                            'user_id' => $user_id,
                            'readonly' => $readonly
                        ), 'wc-vendors/dashboard/settings/', wcv_plugin_dir . 'templates/dashboard/settings/'
                        );

                        do_action( 'wcvendors_settings_after_shop_name' );
                        ?>
                    </div>
                    <div class="col">
                        <?php
                        $value = get_user_meta( get_current_user_id(), '_wcv_company_url', true );

                        // Company URL
                        WCVendors_Pro_Form_Helper::input(
                            apply_filters(
                                'wcv_vendor_company_url',
                                array(
                                    'id'          => '_wcv_company_url',
                                    'label'       => __( 'Website URL', 'wcvendors-pro' ),
                                    'placeholder' => __( 'Enter URL', 'wcvendors-pro' ),
                                    'type'        => 'url',
                                    'value'       => $value,
                                    'class'       => 'form-control'
                                )
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="col">
                        <label for="pv_shop_name">Shop Description</label>
                        <?php

                        if ( $global_html || $has_html ) {
                            $old_post        = $GLOBALS['post'];
                            $GLOBALS['post'] = 0;
                            wp_editor( $description, 'pv_shop_description' );
                            $GLOBALS['post'] = $old_post;
                        } else {
                        ?>
                        <textarea class="form-control" rows="10" id="pv_shop_description_unhtml"
                                  name="pv_shop_description"><?php echo $description; ?></textarea>
                        <?php
                        }

                        ?>
                    </div>
                    <div class="col">
                        <label for="pv_shop_name">Social Platforms</label>
                        <input class="form-control" type="text" name="pv_shop_instagram" id="pv_shop_instagram" placeholder="Instagram"
                               value="<?php echo get_user_meta( $user_id, 'pv_shop_instagram', true ); ?>"/>
                        <input class="form-control" type="text" name="pv_shop_facebook" id="pv_shop_facebook" placeholder="Facebook"
                               value="<?php echo get_user_meta( $user_id, 'pv_shop_facebook', true ); ?>"/>
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
                        <input class="form-control" type="text" name="person_title" id="person_title" placeholder="Title"
                               value="<?php echo get_user_meta( $user_id, 'person_title', true ); ?>"/>
                        <input class="form-control" type="text" name="person_firstname" id="person_firstname" placeholder="First Name"
                               value="<?php echo get_user_meta( $user_id, 'person_firstname', true ); ?>"/>
                        <input class="form-control" type="text" name="person_lastname" id="person_lastname" placeholder="First Name"
                               value="<?php echo get_user_meta( $user_id, 'person_lastname', true ); ?>"/>
                    </div>
                    <div class="col">
                        <label for="pv_shop_name">Business details</label>
                        <input class="form-control" type="text" name="business_regnum" id="business_regnum" placeholder="Business Registration Number"
                               value="<?php echo get_user_meta( $user_id, 'business_regnum', true ); ?>"/>
                        <input class="form-control" type="text" name="business_abn" id="business_abn" placeholder="ABN"
                               value="<?php echo get_user_meta( $user_id, 'business_abn', true ); ?>"/>
                        <input class="form-control" type="text" name="business_tin" id="business_tin" placeholder="Tax ID Number"
                               value="<?php echo get_user_meta( $user_id, 'business_tin', true ); ?>"/>
                    </div>
                </div>
            </div>

            <div class="section-header mt-60">
                Account Settings
            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="col-6">
                        <?php do_action('wcvendors_settings_before_vacation_mode'); ?>
                        <?php WCVendors_Pro_Store_Form::vacation_mode(); ?>
                        <?php do_action('wcvendors_settings_after_vacation_mode'); ?>
                    </div>
                </div>
                <div class="light-border-1 my-4"></div>

                <div class="form-row">
                    <div class="col-6">
                        <label for="return_date">Return Date</label>
                        <input class="form-control" type="text" name="return_date" id="return_date" placeholder="Date"
                               value="<?php echo get_user_meta( $user_id, 'return_date', true ); ?>"/>
                    </div>
                </div>
            </div>

        </div>
        <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="profile-tab">
            <div class="section-header">
                Information
            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="col">
                        <label for="wcv_bank_account_name">Bank Account Name</label>
                        <input class="form-control"
                               type="text" name="wcv_bank_account_name" id="wcv_bank_account_name"
                                value="<?php echo get_user_meta( $user_id, 'wcv_bank_account_name', true ); ?>"/>
                    </div>
                    <div class="col">
                        <label for="wcv_bank_account_number">Bank Account Number</label>
                        <input class="form-control"
                               type="text" name="wcv_bank_account_number" id="wcv_bank_account_number"
                                value="<?php echo get_user_meta( $user_id, 'wcv_bank_account_number', true ); ?>"/>
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
                               value="<?php echo get_user_meta( $user_id, 'wcv_bank_name', true ); ?>"/>
                    </div>
                    <div class="col">
                        <label for="wcv_bank_bsb">BSB</label>
                        <input class="form-control" type="text"
                               name="wcv_bank_bsb"
                               id="wcv_bank_bsb"
                               value="<?php echo get_user_meta( $user_id, 'wcv_bank_bsb', true ); ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php wp_nonce_field( 'save-shop-settings', 'wc-product-vendor-nonce' ); ?>
<?php WCVendors_Pro_Store_Form::save_button( __( 'Save Changes', 'wcvendors-pro' ) ); ?>
</<?php echo $readonly ? 'div' : 'form' ;?>>
