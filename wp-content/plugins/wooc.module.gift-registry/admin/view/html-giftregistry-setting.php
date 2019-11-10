<?php
/**
 * Created by PhpStorm.
 * User: mdq
 * Date: 16/08/2018
 * Time: 14:45
 */
wp_enqueue_script('GR-setting');
?>
<h1 class="screen-reader-text"><?= __('Gift registry', GIFTREGISTRY_TEXT_DOMAIN) ?></h1>
<h2><?= __('Gift Registry Settings', GIFTREGISTRY_TEXT_DOMAIN) ?></h2>
<table class="form-table">

    <tbody>
    <!--    <tr valign="top" class="">-->
    <!--        <th scope="row" class="titledesc">Enable full mode</th>-->
    <!--        <td class="forminp forminp-checkbox">-->
    <!--            <fieldset>-->
    <!--                <legend class="screen-reader-text"><span>Enable full mode</span></legend>-->
    <!--                <label for="giftregistry_allow_fullmode">-->
    <!--                    <input name="giftregistry_allow_fullmode" id="giftregistry_allow_fullmode" type="checkbox" class=""-->
    <!--                           value="1" -->
    <?php //echo get_option('giftregistry_allow_fullmode') == 'yes'? 'checked' :'';?><!-- > Enable full mode will allow customer upload images in their gift registry </label>-->
    <!--            </fieldset>-->
    <!--        </td>-->
    <!--    </tr>-->
    <tr valign="top" class="">
        <th scope="row" class="titledesc"><?= __('Guest permission', GIFTREGISTRY_TEXT_DOMAIN) ?></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <legend
                        class="screen-reader-text"><span><? __('Guest permission', GIFTREGISTRY_TEXT_DOMAIN) ?></span>
                </legend>
                <label for="giftregistry_enable_permission">
                    <input name="giftregistry_enable_permission" id="giftregistry_enable_permission"
                           type="checkbox"
                           class=""
                           value="1" <?php echo get_option('giftregistry_enable_permission') == 'yes' ? 'checked' : ''; ?>>
                    <?= __("Allow guest to add products to gift registry", GIFTREGISTRY_TEXT_DOMAIN) ?>
            </fieldset>
        </td>
    </tr>
    <!--Multiple GR-->
    <!--    <tr valign="top" class="">-->
    <!--        <th scope="row" class="titledesc">-->
    <? //=__('Multiple gift registry',GIFTREGISTRY_TEXT_DOMAIN)?><!--</th>-->
    <!--        <td class="forminp forminp-checkbox">-->
    <!--            <fieldset>-->
    <!--                <legend class="screen-reader-text"><span>-->
    <? //=__('Multiple gift registry',GIFTREGISTRY_TEXT_DOMAIN)?><!--</span></legend>-->
    <!--                <label for="multi_gift_registry">-->
    <!--                    <input name="multi_gift_registry" id="multi_gift_registry" type="checkbox" class="" value="1"-->
    <!--                        --><?php //echo get_option('multi_gift_registry') == 'yes' ? 'checked' : ''; ?><!-->
    <!--                   -->
    <? //=__('Allow guest to add products from muliple gift registry to cart',GIFTREGISTRY_TEXT_DOMAIN)?><!--</label> 																</fieldset>-->
    <!--        </td>-->
    <!--    </tr>-->
    <!-- Button add gift registry in shop page -->
    <tr valign="top" class="">
        <th scope="row" class="titledesc"><?=__('Add to gift registry from product list page',GIFTREGISTRY_TEXT_DOMAIN)?></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <legend
                        class="screen-reader-text"><span><?=__('Add to gift registry from product list page',GIFTREGISTRY_TEXT_DOMAIN)?></span></legend>
                <label for="giftregistry_enable_button">
                    <input name="giftregistry_enable_button" id="giftregistry_enable_button"
                           type="checkbox" class="" value="1"
                        <?php echo get_option('giftregistry_enable_button') == 'yes' ? 'checked' : ''; ?> > <?=__('Add the "Add to gift registry" button for each product on category page',GIFTREGISTRY_TEXT_DOMAIN)?></label></fieldset>
        </td>
    </tr>
    <tr valign="top" class="">
        <th scope="row"
            class="titledesc"><?= __('Send notification email of gift registry new orders to', GIFTREGISTRY_TEXT_DOMAIN) ?></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <legend
                        class="screen-reader-text">
                    <span><?= __('Send notification email of gift registry new orders to', GIFTREGISTRY_TEXT_DOMAIN) ?></span>
                </legend>
                <label for="giftregistry_notify_owner">
                    <input name="giftregistry_notify_owner" id="giftregistry_notify_owner" type="checkbox"
                           class="giftregistry_notify"
                           value="1" <?php echo get_option('giftregistry_notify_owner') == 'yes' ? 'checked' : ''; ?>>
                    <?= __("Registry's owner", GIFTREGISTRY_TEXT_DOMAIN) ?> </label></fieldset>
        </td>
    </tr>
    <tr valign="top" class="">
        <th scope="row" class="titledesc"></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <label for="giftregistry_notify_registrant">
                    <input name="giftregistry_notify_registrant" id="giftregistry_notify_registrant"
                           type="checkbox"
                           class="giftregistry_notify"
                           value="1" <?php echo get_option('giftregistry_notify_registrant') == 'yes' ? 'checked' : ''; ?>>
                    <?= __('Registrant', GIFTREGISTRY_TEXT_DOMAIN) ?> </label></fieldset>
        </td>
    </tr>
    <tr valign="top" class="">
        <th scope="row" class="titledesc"></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <label for="giftregistry_notify_coregistrant">
                    <input name="giftregistry_notify_coregistrant" id="giftregistry_notify_coregistrant"
                           type="checkbox"
                           class="giftregistry_notify"
                           value="1" <?php echo get_option('giftregistry_notify_coregistrant') == 'yes' ? 'checked' : ''; ?> >
                    <?=__('CoRegistrant',GIFTREGISTRY_TEXT_DOMAIN)?></label></fieldset>															</fieldset>
        </td>
    </tr>
    <tr valign="top" class="">
        <th scope="row" class="titledesc"></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <label for="giftregistry_notify_admin">
                    <input name="giftregistry_notify_admin" id="giftregistry_notify_admin" type="checkbox"
                           class="giftregistry_notify"
                           value="1" <?php echo get_option('giftregistry_notify_admin') == 'yes' ? 'checked' : ''; ?>>
                    <?= __('Admin', GIFTREGISTRY_TEXT_DOMAIN) ?> </label></fieldset>
        </td>
    </tr>
    <!--    <tr valign="top">-->
    <!--        <th scope="row" class="titledesc">-->
    <!--            <label for="giftregistry_notify_email_subject">Email subject </label>-->
    <!--        </th>-->
    <!--        <td class="forminp forminp-text">-->
    <!--            <input name="giftregistry_notify_email_subject" id="giftregistry_notify_email_subject" type="text" style=""-->
    <!--                   value="-->
    <?php //echo get_option('giftregistry_notify_email_subject'); ?><!--" class="" placeholder=""></td>-->
    <!--    </tr>-->
    <!--    <tr valign="top">-->
    <!--        <th scope="row" class="titledesc">-->
    <!--            <label for="giftregistry_notify_email_content">Email content </label>-->
    <!--        </th>-->
    <!--        <td class="forminp forminp-textarea">-->
    <!--            <p style="margin-top:0">You can use variables-->
    <!--                {{buyer_name}},{{store_url}},{{store_name}},{{order_items}}</p>-->
    <!--            <textarea name="giftregistry_notify_email_content" id="giftregistry_notify_email_content" style="" class=""-->
    <!--                      placeholder="">-->
    <?php //echo get_option('giftregistry_notify_email_content'); ?><!--</textarea>-->
    <!--        </td>-->
    <!--    </tr>-->
    <?php
    if (get_option('giftregistry_notify_owner') == 'yes') {
        ?>
        <tr valign="top" class="owner">
            <th scope="row" class="titledesc">
                <label for="giftregistry_notify_email_subject_owner"><?= __("Email subject for Registry's
                    owner", GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-text">
                <input name="giftregistry_notify_email_subject_owner"
                       id="giftregistry_notify_email_subject_owner" type="text" style=""
                       value="<?php echo get_option('giftregistry_notify_email_subject_owner'); ?>"
                       class="" placeholder=""></td>
        </tr>
        <tr valign="top" class="owner">
            <th scope="row" class="titledesc">
                <label for="giftregistry_notify_email_content_owner"><?= __("Email template for Registry's
                    owner", GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-textarea">
                <p style="margin-top:0"><?= __('Available shortcodes for email template:
                    {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}', GIFTREGISTRY_TEXT_DOMAIN) ?></p>
                <textarea name="giftregistry_notify_email_content_owner"
                          id="giftregistry_notify_email_content_owner" style="" class=""
                          placeholder=""><?php echo get_option('giftregistry_notify_email_content_owner'); ?></textarea>
            </td>
        </tr>
        <?php
    } else {
        ?>
        <tr valign="top" class="owner" style="display: none;">
            <th scope="row" class="titledesc">
                <label for="giftregistry_notify_email_subject_owner"><?= __("Email subject for Registry's
                    owner", GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-text">
                <input name="giftregistry_notify_email_subject_owner"
                       id="giftregistry_notify_email_subject_owner" type="text" style=""
                       value="<?php echo get_option('giftregistry_notify_email_subject_owner'); ?>"
                       class="" placeholder=""></td>
        </tr>
        <tr valign="top" class="owner" style="display: none;">
            <th scope="row" class="titledesc">
                <label for="giftregistry_notify_email_content_owner"><?= __("Email template for Registry's
                    owner", GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-textarea">
                <p style="margin-top:0"><?= __("Available shortcodes for email template:
                    {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}", GIFTREGISTRY_TEXT_DOMAIN) ?></p>
                <textarea name="giftregistry_notify_email_content_owner"
                          id="giftregistry_notify_email_content_owner" style="" class=""
                          placeholder=""><?php echo get_option('giftregistry_notify_email_content_owner'); ?></textarea>
            </td>
        </tr>
        <?php
    }
    ?>
    <?php
    if (get_option('giftregistry_notify_registrant') == 'yes') {
        ?>
        <tr valign="top" class="registrant">
            <th scope="row" class="titledesc">
                <label
                        for="giftregistry_notify_email_subject_registrant"><?= __('Email subject for Registrant', GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-text">
                <input name="giftregistry_notify_email_subject_registrant"
                       id="giftregistry_notify_email_subject_registrant" type="text" style=""
                       value="<?php echo get_option('giftregistry_notify_email_subject_registrant'); ?>" class=""
                       placeholder=""></td>
        </tr>
        <tr valign="top" class="registrant">
            <th scope="row" class="titledesc">
                <label
                        for="giftregistry_notify_email_content_registrant"><?= __('Email template for Registrant', GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-textarea">
                <p style="margin-top:0"><?= __("Available shortcodes for email template:
                    {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}", GIFTREGISTRY_TEXT_DOMAIN) ?></p>
                <textarea name="giftregistry_notify_email_content_registrant"
                          id="giftregistry_notify_email_content_registrant" style="" class=""
                          placeholder=""><?php echo get_option('giftregistry_notify_email_content_registrant'); ?></textarea>
            </td>
        </tr>
        <?php
    } else {
        ?>
        <tr valign="top" class="registrant" style="display: none">
            <th scope="row" class="titledesc">
                <label
                        for="giftregistry_notify_email_subject_registrant"><?= __('Email subject for Registrant', GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-text">
                <input name="giftregistry_notify_email_subject_registrant"
                       id="giftregistry_notify_email_subject_registrant" type="text" style=""
                       value="<?php echo get_option('giftregistry_notify_email_subject_registrant'); ?>" class=""
                       placeholder=""></td>
        </tr>
        <tr valign="top" class="registrant" style="display: none">
            <th scope="row" class="titledesc">
                <label
                        for="giftregistry_notify_email_content_registrant"><?= __('Email template for Registrant', GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-textarea">
                <p style="margin-top:0"><?
                    __('Available shortcodes for email template:
                    {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}', GIFTREGISTRY_TEXT_DOMAIN) ?></p>
                <textarea name="giftregistry_notify_email_content_registrant"
                          id="giftregistry_notify_email_content_registrant" style="" class=""
                          placeholder=""><?php echo get_option('giftregistry_notify_email_content_registrant'); ?></textarea>
            </td>
        </tr>
        <?php
    }
    ?>
    <?php
    if (get_option('giftregistry_notify_coregistrant') == 'yes') {
        ?>
        <tr valign="top" class="coregistrant">
            <th scope="row" class="titledesc">
                <label for="giftregistry_notify_email_subject_coregistrant"><?=__('Email subject for CoRegistrant ',GIFTREGISTRY_TEXT_DOMAIN)?></label>
            </th>
            <td class="forminp forminp-text">
                <input name="giftregistry_notify_email_subject_coregistrant" id="giftregistry_notify_email_subject_coregistrant" type="text" style=""
                       value="<?php echo get_option('giftregistry_notify_email_subject_coregistrant'); ?>" class="" placeholder=""> 							</td>
        </tr>
        <tr valign="top" class="coregistrant">
            <th scope="row" class="titledesc">
                <label for="giftregistry_notify_email_content_coregistrant"><?=__('Email template for CoRegistrant ',GIFTREGISTRY_TEXT_DOMAIN)?></label>
            </th>
            <td class="forminp forminp-textarea">
                <p style="margin-top:0"><?=__('Available shortcodes for email template: {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}',GIFTREGISTRY_TEXT_DOMAIN)?></p>
                <textarea name="giftregistry_notify_email_content_coregistrant" id="giftregistry_notify_email_content_coregistrant" style="" class=""
                          placeholder=""><?php echo get_option('giftregistry_notify_email_content_coregistrant'); ?>
                </textarea>
            </td>
        </tr>
        <?php
    } else {
        ?>
        <tr valign="top" class="coregistrant" style="display: none">
            <th scope="row" class="titledesc">
                <label
                        for="giftregistry_notify_email_subject_coregistrant"><?= __('Email subject for CoRegistrant', GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-text">
                <input name="giftregistry_notify_email_subject_coregistrant"
                       id="giftregistry_notify_email_subject_coregistrant" type="text" style=""
                       value="<?php echo get_option('giftregistry_notify_email_subject_coregistrant'); ?>" class=""
                       placeholder=""></td>
        </tr>
        <tr valign="top" class="coregistrant" style="display: none">
            <th scope="row" class="titledesc">
                <label
                        for="giftregistry_notify_email_content_coregistrant"><?= __('Email template for CoRegistrant', GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-textarea">
                <p style="margin-top:0"><?
                    __('Available shortcodes for email template:
                    {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}', GIFTREGISTRY_TEXT_DOMAIN) ?></p>
                <textarea name="giftregistry_notify_email_content_coregistrant"
                          id="giftregistry_notify_email_content_coregistrant" style="" class=""
                          placeholder=""><?php echo get_option('giftregistry_notify_email_content_coregistrant'); ?></textarea>
            </td>
        </tr>
        <?php
    }
    ?>
    <?php
    if (get_option('giftregistry_notify_admin') == 'yes') {
        ?>
        <tr valign="top" class="admin">
            <th scope="row" class="titledesc">
                <label
                        for="giftregistry_notify_email_subject_admin"><?= __('Email subject for Admin', GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-text">
                <input name="giftregistry_notify_email_subject_admin" id="giftregistry_notify_email_subject_admin"
                       type="text" style=""
                       value="<?php echo get_option('giftregistry_notify_email_subject_admin'); ?>"
                       class="" placeholder=""></td>
        </tr>
        <tr valign="top" class="admin">
            <th scope="row" class="titledesc">
                <label
                        for="giftregistry_notify_email_content_admin"><?= __('Email template for Admin', GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-textarea">
                <p style="margin-top:0"><?= __('Available shortcodes for email template:
                    {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}', GIFTREGISTRY_TEXT_DOMAIN) ?></p>
                <textarea name="giftregistry_notify_email_content_admin"
                          id="giftregistry_notify_email_content_admin"
                          style="" class=""
                          placeholder=""><?php echo get_option('giftregistry_notify_email_content_admin'); ?></textarea>
            </td>
        </tr>
        <?php
    } else {
        ?>
        <tr valign="top" class="admin" style="display: none">
            <th scope="row" class="titledesc">
                <label
                        for="giftregistry_notify_email_subject_admin"><?= __('Email subject for Admin', GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-text">
                <input name="giftregistry_notify_email_subject_admin" id="giftregistry_notify_email_subject_admin"
                       type="text" style=""
                       value="<?php echo get_option('giftregistry_notify_email_subject_admin'); ?>"
                       class="" placeholder=""></td>
        </tr>
        <tr valign="top" class="admin" style="display: none">
            <th scope="row" class="titledesc">
                <label
                        for="giftregistry_notify_email_content_admin"><?= __('Email template for Admin', GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
            </th>
            <td class="forminp forminp-textarea">
                <p style="margin-top:0"><?
                    __('Available shortcodes for email template:
                    {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}', GIFTREGISTRY_TEXT_DOMAIN) ?></p>
                <textarea name="giftregistry_notify_email_content_admin"
                          id="giftregistry_notify_email_content_admin"
                          style="" class=""
                          placeholder=""><?php echo get_option('giftregistry_notify_email_content_admin'); ?></textarea>
            </td>
        </tr>
        <?php
    }
    ?>
    <tr valign="top" class="">
        <th scope="row"
            class="titledesc"><?= __('Make shipping address mandatory', GIFTREGISTRY_TEXT_DOMAIN) ?></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <legend
                        class="screen-reader-text">
                    <span><?= __('Make shipping address mandatory', GIFTREGISTRY_TEXT_DOMAIN) ?></span>
                </legend>
                <label for="giftregistry_shipping_restrict">
                    <input name="giftregistry_shipping_restrict" id="giftregistry_shipping_restrict"
                           type="checkbox"
                           class=""
                           value="1" <?php echo get_option('giftregistry_shipping_restrict') == 'yes' ? 'checked' : ''; ?>>
                    <?= __("Gift registry's owner is NOT allowed to add products to gift registry unless he/she fills in the shipping address when 
                    creating the gift registry", GIFTREGISTRY_TEXT_DOMAIN) ?> </label></fieldset>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row" class="titledesc">
            <label for="giftregistry_share_text"><?= __('Default message for social sharing', GIFTREGISTRY_TEXT_DOMAIN) ?> </label>
        </th>
        <td class="forminp forminp-textarea">
            <p style="margin-top:0"><?= __('This message will be used as email content for email sharing and as default caption for social sharing {Facebook,Twitter,Google+}.
                    You can use the shortcode {giftregistry_url} to add gift registry URL to email template', GIFTREGISTRY_TEXT_DOMAIN) ?></p>
            <textarea name="giftregistry_share_text" id="giftregistry_share_text" style="" class=""
                      placeholder=""><?php echo get_option('giftregistry_share_text'); ?></textarea>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row" class="titledesc">
            <label for="giftregistry_share_image_url"><?= __('Image attached for social sharing', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        </th>
        <td class="forminp forminp-text">
            <button type="button" id="insert-my-media" class="button insert-media add_media"
                    data-editor="content">
                <span class="wp-media-buttons-icon"></span> <?= __('Add Image', GIFTREGISTRY_TEXT_DOMAIN) ?>
            </button>
            <br/>
            <input name="giftregistry_share_image_url" id="giftregistry_share_image_url" type="text"
                   style="display: none;" value="<?php echo get_option('giftregistry_share_image_url'); ?>">
            <br/>
            <span id="social_image">
                <?php if (!empty(get_option('giftregistry_share_image_url'))) : ?>
                    <img id="social_image" src="<?php echo get_option('giftregistry_share_image_url'); ?>" width="30%">
                <?php endif; ?>
            </span>

        </td>
    </tr>
    <tr valign="top" class="">
        <th scope="row" class="titledesc"><?= __('Share gift registry via', GIFTREGISTRY_TEXT_DOMAIN) ?></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <legend
                        class="screen-reader-text">
                    <span><?= __('Share gift registry via', GIFTREGISTRY_TEXT_DOMAIN) ?> </span></legend>
                <label for="giftregistry_share_facebook">
                    <input name="giftregistry_share_facebook" id="giftregistry_share_facebook" type="checkbox"
                           class=""
                           value="1" <?php echo get_option('giftregistry_share_facebook') == 'yes' ? 'checked' : ''; ?>>
                    <?= __('Facebook', GIFTREGISTRY_TEXT_DOMAIN) ?> </label></fieldset>
        </td>
    </tr>
    <tr valign="top" class="">
        <th scope="row" class="titledesc"></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <label for="giftregistry_share_twitter">
                    <input name="giftregistry_share_twitter" id="giftregistry_share_twitter" type="checkbox"
                           class=""
                           value="1" <?php echo get_option('giftregistry_share_twitter') == 'yes' ? 'checked' : ''; ?>>
                    <?= __('Twitter', GIFTREGISTRY_TEXT_DOMAIN) ?> </label></fieldset>
        </td>
    </tr>
    <tr valign="top" class="">
        <th scope="row" class="titledesc"></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <label for="giftregistry_share_email">
                    <input name="giftregistry_share_email" id="giftregistry_share_email" type="checkbox"
                           class=""
                           value="1" <?php echo get_option('giftregistry_share_email') == 'yes' ? 'checked' : ''; ?>>
                    <?= __('Mail', GIFTREGISTRY_TEXT_DOMAIN) ?> </label></fieldset>
        </td>
    </tr>
    </tbody>
</table>