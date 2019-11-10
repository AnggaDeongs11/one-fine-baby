<?php
if (!is_admin()) {
    ?>
    <style>
        .giftregistry-form input, textarea {
            width: 70% !important;
            margin: 5px;
        }

        .giftregistry-form select.shipping_country,select#option_quantity{
            width: 70% !important;
            height: 35px;
            margin-top: 10px;
        }
    </style>
    <?php
} else {
    ?>
    <style>
        div input, textarea {
            width: 25em !important;
            margin: 5px;
            font-size: 14px;
        }

        .form-field label {
            width: 25% !important;
        }

        div select.shipping_country, select#option_quantity {
            width: 25em !important;
            height: 24px;
            margin-top: 10px;
        }
    </style>
    <?php
}
$jquery_version = isset ($wp_scripts->registered ['jquery-ui-core']->ver) ? $wp_scripts->registered ['jquery-ui-core']->ver : '1.9.2';
wp_enqueue_style('jquery-ui', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
wp_enqueue_style('my-bootstrap');
wp_enqueue_script('bootstrap');

wp_enqueue_style('jquery-ui-style');
wp_enqueue_style('jquery-ui-core');
wp_enqueue_style('jquery-ui-accordion');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_script('moment-with-locales.js');

wp_enqueue_script('jquery-ui-accordion');
wp_enqueue_script('add-giftregistry');

$wishlist = '';
if ($wid) {
    $wishlist = Magenest_Giftregistry_Model::get_wishlist($wid);
}

/* enable full mode */

$enable_full_mode = get_option('giftregistry_allow_fullmode');

/*require shipping information*/
$require_shipping = get_option('giftregistry_shipping_restrict','no') == 'yes' ? true :false;
?>

    <div class="container" style="width:100%">
    <ul class="nav nav-tabs" style="margin-left:0px;">
        <li class="active"><a href="#tab1" data-toggle="tab"><?= __('Information', GIFTREGISTRY_TEXT_DOMAIN) ?></a></li>
        <li><a href="#tab2" data-toggle="tab"><?= __('Item', GIFTREGISTRY_TEXT_DOMAIN) ?></a></li>
        <?php if (!is_admin()) {
            ?>
            <li><a href="#tab3" data-toggle="tab"><?= __('Share', GIFTREGISTRY_TEXT_DOMAIN) ?></a></li>
            <?php
        }
        ?>
    </ul>
    <hr width="100%"/>
    <div class="tab-content">
    <div class="tab-pane active" id="tab1">

        <h3> <?php echo __('Gift Registry Info', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

        <div id="accordion-giftregisty-content" <?php if (is_admin()) : ?> class="admin-gift-table" <?php endif; ?>>
            <form class="giftregistry-form" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="giftregistry_id" id="giftregistry_id"
                       value="<?php if (is_object($wishlist)) : echo $wishlist->id; endif; ?>"/>
                <input name="create_giftregistry" id="create_giftregistry" type="hidden" value="1"/>

                <div class="form-field">
                    <label for="title"><?php echo __('Title', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                    <input name="title" id="title" type="text"
                           value="<?php if (is_object($wishlist)) : echo $wishlist->title; endif; ?>" size="40">
                </div>

                <h3> <?php echo __('Registrant', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

                <div class="form-field">
                    <label for="registrant_firstname"><?php echo __('First name', GIFTREGISTRY_TEXT_DOMAIN) ?>
                        <span
                                class="required" title="required">*</span></label>
                    <input required name="registrant_firstname" id="registrant_firstname"
                           type="text" size="40"
                           value="<?php if (is_object($wishlist)) : echo $wishlist->registrant_firstname; endif; ?>">
                </div>

                <div class="form-field">
                    <label for="registrant_lastname"><?php echo __('Last name', GIFTREGISTRY_TEXT_DOMAIN) ?>
                        <span
                                class="required">*</span></label>
                    <input required name="registrant_lastname" id="registrant_lastname"
                           value="<?php if (is_object($wishlist)) : echo $wishlist->registrant_lastname; endif; ?>"
                           type="text"
                           value="" size="40">
                </div>

                <div class="form-field">
                    <label for="registrant_email"><?php echo __('Email', GIFTREGISTRY_TEXT_DOMAIN) ?><span
                                class="required">*</span></label>
                    <input required name="registrant_email" id="registrant_firstname" type="text"
                           value="<?php if (is_object($wishlist)) : echo $wishlist->registrant_email; endif; ?>"
                           value="" size="40">
                </div>

                <?php if ($enable_full_mode == 'yes') { ?>
                    <div class="form-field">
                        <input type="file" name="registrant_image" id="registrant_image" multiple="false"/>
                    </div>
                    <div class="form-field">
                        <?php echo wp_get_attachment_image($wishlist->registrant_image, 'large') ?>
                    </div>
                    <div class="form-field">
                        <label for="registrant_description"><?php echo __('Description', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                        <textarea rows="6"
                                  name="registrant_description"><?php if (is_object($wishlist)) : echo stripslashes($wishlist->registrant_description); endif; ?></textarea>
                    </div>
                <?php } ?>

                <h3 style="margin-top: 40px;"> <?php echo __('Are there CoRegistrants?', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

                <div class="form-field" style="margin-bottom: 40px;">
                    <select name="co_r" id="co_r">
                        <?php
                        if (empty($wishlist->enable_coregistrant)) {
                            ?>
                            <option value="1"><?= __('Yes', GIFTREGISTRY_TEXT_DOMAIN) ?></option>
                            <option value="0" selected><?= __('No', GIFTREGISTRY_TEXT_DOMAIN) ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="1" selected><?= __('Yes', GIFTREGISTRY_TEXT_DOMAIN) ?></option>
                            <option value="0"><?= __('No', GIFTREGISTRY_TEXT_DOMAIN) ?></option>
                            <?php
                        }
                        ?>

                    </select>
                </div>
                <div id="co_registrants">
                    <h3> <?php echo __('CoRegistrant', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

                    <div class="form-field">
                        <label for="coregistrant_firstname"><?php echo __('First name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                        <input name="coregistrant_firstname" id="coregistrant_firstname"
                               type="text"
                               value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_firstname; endif; ?>"
                               size="40">
                    </div>

                    <div class="form-field">
                        <label for="coregistrant_lastname"><?php echo __('Last name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                        <input name="coregistrant_lastname" id="coregistrant_lastname"
                               type="text"
                               value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_lastname; endif; ?>"
                               size="40">
                    </div>

                    <div class="form-field">
                        <label for="coregistrant_email"><?php echo __('Email', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                        <input name="coregistrant_email" id="coregistrant_email"
                               type="text"
                               value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_email; endif; ?>"
                               size="40">
                    </div>

                    <?php if ($enable_full_mode == 'yes') { ?>
                        <div class="form-field">
                            <input type="file" name="coregistrant_image" id="coregistrant_image"
                                   multiple="false"/>
                            <?php echo wp_get_attachment_image($wishlist->coregistrant_image, 'large') ?>
                        </div>
                        <div class="form-field">
                            <label for="coregistrant_description"><?php echo __('Description', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                            <textarea rows="6"
                                      name="coregistrant_description"><?php if (is_object($wishlist)) : echo stripslashes($wishlist->coregistrant_description); endif; ?></textarea>
                        </div>
                    <?php } ?>
                </div>
                <hr>
                <h3> <?php echo __('Event', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

                <div class="form-field">
                    <label for="event_datetime"><?php echo __('Event date', GIFTREGISTRY_TEXT_DOMAIN) ?><span
                                class="required">*</span></label>
                    <input required name="event_date_time" id="event_date_time" class="date-picker" type="text"
                           value="<?php if (is_object($wishlist)) {
                               $eventdate = new DateTime($wishlist->event_date_time);
                               echo $eventdate->format('d-m-Y');
                           } ?>" size="40">
                </div>

                <div class="form-field">
                    <label for="event_location"><?php echo __('Event location', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                    <input name="event_location" id="event_location" type="text"
                           value="<?php if (is_object($wishlist)) : echo $wishlist->event_location; endif; ?>"
                           size="40">
                </div>

                <div class="form-field">
                    <label for="message"><?php echo __('Message for guests', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                    <textarea id="message" name="message" rows="" cols=""><?php
                        if (is_object($wishlist)) : echo $wishlist->message; endif;
                        ?></textarea>
                </div>

                <!--                    --><?php //if ($enable_full_mode) { ?>
                <!--                        <div class="form-field">-->
                <!--                            <input type="file" name="background_image" id="background_image" multiple="false"/>-->
                <!--                            --><?php //if (is_object($wishlist)) : echo wp_get_attachment_image($wishlist->background_image, 'large');endif; ?>
                <!--                        </div>-->
                <!---->
                <!--                    --><?php //} ?>


                <h3 style="margin-top: 40px;"> <?php echo __('Gift Registry Privacy', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

                <div class="form-field" style="margin-bottom: 40px;">
                    <label for="role"><?php echo __('Privacy', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                    <select name="role" id="role">
                        <?php
                        if (is_object($wishlist)) {
                            $role = $wishlist->role;
                        } else $role = 0;
                        if ($role == 1) {
                            ?>
                            <option value="0"><?= __('Public', GIFTREGISTRY_TEXT_DOMAIN) ?></option>
                            <option value="1"
                                    selected="selected"><?= __('Private', GIFTREGISTRY_TEXT_DOMAIN) ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="0"
                                    selected="selected"><?= __('Public', GIFTREGISTRY_TEXT_DOMAIN) ?></option>
                            <option value="1"><?= __('Private', GIFTREGISTRY_TEXT_DOMAIN) ?></option>
                            <?php
                        }
                        ?>

                    </select>
                </div>
                <div class="form-field" style="margin-bottom: 40px;" id="check_pass">
                    <label for="role"><?php echo __('Password', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                    <input type="text" name="password" id="password"
                           value="<?= isset($wishlist->password) ? $wishlist->password : ''; ?>"/>
                </div>

                <hr/>
                <h3 style="margin-top: 40px;"> <?php echo __('Shipping Address', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

                <div class="form-field">
                    <label for="shipping_first_name"><?php echo __('First name', GIFTREGISTRY_TEXT_DOMAIN) ?>
                        <?php
                            if($require_shipping){
                                ?>
                                <span class="required">*</span>
                                <?php
                            }
                        ?>
                    </label>
                    <input <?= $require_shipping ? 'required' : ''?> type="text" id="shipping_first_name" name="shipping_first_name"
                           value="<?php if (is_object($wishlist)) : echo $wishlist->shipping_first_name; endif; ?>"/>

                </div>
                <div class="form-field">
                    <label for="shipping_last_name"><?php echo __('Last name', GIFTREGISTRY_TEXT_DOMAIN) ?>
                        <?php
                        if($require_shipping){
                            ?>
                            <span class="required">*</span>
                            <?php
                        }
                        ?>
                    </label>
                    <input <?= $require_shipping ? 'required' : ''?> type="text" id="shipping_last_name" name="shipping_last_name"
                           value="<?php if (is_object($wishlist)) : echo $wishlist->shipping_last_name; endif; ?>"/>

                </div>
                <div class="form-field">
                    <label for="shipping_company"><?php echo __('Company', GIFTREGISTRY_TEXT_DOMAIN)?>
                        </label>
                    <input type="text" id="shipping_company" name="shipping_company"
                           value="<?php if (is_object($wishlist)) : echo $wishlist->shipping_company; endif; ?>"/>

                </div>

                <div class="form-field">
                    <label for="shipping_city"><?php echo __('Country', GIFTREGISTRY_TEXT_DOMAIN) ?>
                        <?php
                        if($require_shipping){
                            ?>
                            <span class="required">*</span>
                            <?php
                        }
                        ?>
                    </label>
                    <select name="shipping_country" id="shipping_country" class="shipping_country">
                        <option value="Select a country…"><?=__('Select a country…',GIFTREGISTRY_TEXT_DOMAIN)?></option>
                        <?php
                        if (is_object($wishlist)) {
                            $selected_country = $wishlist->shipping_country;
                        }
                        if (isset($selected_country)) {
                            foreach (WC()->countries->get_shipping_countries() as $key => $value) {
                                echo '<option value="' . esc_attr($key) . '"' . selected($selected_country, esc_attr($key), false) . '>' . esc_html($value) . '</option>';
                            }
                        } else {
                            foreach (WC()->countries->get_shipping_countries() as $key => $value) {
                                echo '<option value="' . esc_attr($key) . '">' . esc_html($value) . '</option>';
                            }
                        }
                        ?>
                    </select>

                </div>

                <div class="form-field">
                    <label for="shipping_address"><?php echo __('Street Address', GIFTREGISTRY_TEXT_DOMAIN) ?>
                        <?php
                        if($require_shipping){
                            ?>
                            <span class="required">*</span>
                            <?php
                        }
                        ?>
                    </label>
                    <input <?= $require_shipping ? 'required' : ''?> type="text" id="shipping_address" name="shipping_address"
                           value="<?php if (is_object($wishlist)) : echo $wishlist->shipping_address; endif; ?>"/>

                </div>

                <div class="form-field">
                    <label for="shipping_postcode"><?php echo __('Postcode / ZIP', GIFTREGISTRY_TEXT_DOMAIN) . ' ' . __('(optional)', GIFTREGISTRY_TEXT_DOMAIN) ?>
                        <?php
                        if($require_shipping){
                            ?>
                            <span class="required">*</span>
                            <?php
                        }
                        ?>
                    </label>
                    <input <?= $require_shipping ? 'required' : ''?> type="text" id="shipping_postcode" name="shipping_postcode"
                           value="<?php if (is_object($wishlist)) : echo $wishlist->shipping_postcode; endif; ?>"/>

                </div>

                <div class="form-field">
                    <label for="shipping_city"><?php echo __('Town / City', GIFTREGISTRY_TEXT_DOMAIN) ?>
                        <?php
                        if($require_shipping){
                            ?>
                            <span class="required">*</span>
                            <?php
                        }
                        ?>
                    </label>
                    <input <?= $require_shipping ? 'required' : ''?> type="text" id="shipping_city" name="shipping_city"
                           value="<?php if (is_object($wishlist)) : echo $wishlist->shipping_city; endif; ?>"/>

                </div>
                <br>
                <hr/>
                <div class="form-field">
                    <label for="option_quantity"><?= __('Quantity display', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                    <select name="option_quantity" id="option_quantity" style=""
                            class="">
                        <option value="0" class="dropdownlist"<?php if(is_object($wishlist)) { if($wishlist->option_quantity == "0") echo 'selected';}  ?>>
                            <?= __('Drop-down list', GIFTREGISTRY_TEXT_DOMAIN) ?></option>
                        <option value="1" class="textfield" <?php if(is_object($wishlist)) { if($wishlist->option_quantity != "0") echo 'selected';}?>>
                            <?= __('Text field', GIFTREGISTRY_TEXT_DOMAIN) ?></option>
                    </select>
                    <?php
                    if(is_object($wishlist)) {
                        if($wishlist->option_quantity == "0"){
                            ?>
                            <p id="dropdownlist" style="display: block;"><?=__('Drop-down list: Your friends must purchase within the "desired quantity" (ie: Desired quantity = 10; buyers can purchase maximum 10 units of that product).',GIFTREGISTRY_TEXT_DOMAIN)?> </p>
                            <p id="textfield" style="display: none"><?=__('Text field: Your friends can purchase more than the "desired quantity".(ie: Desired quantity = 10; buyers can purchase more than 10 units of that product).',GIFTREGISTRY_TEXT_DOMAIN)?></p>
                            <?php
                        }else{
                            ?>
                            <p id="dropdownlist" style="display: none;"><?=__('Drop-down list: Your friends must purchase within the "desired quantity" (ie: Desired quantity = 10; buyers can purchase maximum 10 units of that product).',GIFTREGISTRY_TEXT_DOMAIN)?> </p>
                            <p id="textfield" style="display: block"><?=__('Text field: Your friends can purchase more than the "desired quantity".(ie: Desired quantity = 10; buyers can purchase more than 10 units of that product).',GIFTREGISTRY_TEXT_DOMAIN)?></p>
                            <?php
                        }

                    }
                    ?>
                </div>
                <input type="submit" style="margin-left:25%" class="button button-primary" value="<?= __('Save', GIFTREGISTRY_TEXT_DOMAIN) ?>">
            </form>
        </div>
    </div>
<?php
if (isset($wid)) {
    if (function_exists('jquery_html5_file_upload_hook')) {
        jquery_html5_file_upload_hook();
    }
}
//wp_dequeue_style('my-style.css');
?>