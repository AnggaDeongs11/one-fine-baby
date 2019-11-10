<h3> <?php echo __('Create Gift Registry', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>
<form class="giftregistry-form" method="POST">
    <input name="create_giftregistry" id="create_giftregistry" type="hidden" value="1"/>

    <div class="form-field">
        <label for="title"><?php echo __('Title', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input name="title" id="title" type="text" value="" size="40">
    </div>
    <hr>
    <h3> <?php echo __('Registrant', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>
    <div class="form-field">
        <label for="registrant_firstname"><?php echo __('First name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input name="registrant_firstname" id="registrant_firstname"
               type="text" value="" size="40">
    </div>
    <div class="form-field">
        <label for="registrant_lastname"><?php echo __('Last name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input name="registrant_lastname" id="registrant_lastname" type="text"
               value="" size="40">
    </div>
    <div class="form-field">
        <label for="registrant_email"><?php echo __('Email', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input name="registrant_email" id="registrant_firstname" type="text"
               value="" size="40">
    </div>

    <hr>
    <h3> <?php echo __('CoRegistrant', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>
    <div class="form-field">
        <label for="coregistrant_firstname"><?php echo __('First name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input name="coregistrant_firstname" id="coregistrant_firstname"
               type="text" value="" size="40">
    </div>
    <div class="form-field">
        <label for="coregistrant_lastname"><?php echo __('Last name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input name="coregistrant_lastname" id="coregistrant_lastname"
               type="text" value="" size="40">
    </div>
    <div class="form-field">
        <label for="coregistrant_email"><?php echo __('Email', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input name="coregistrant_email" id="coregistrant_email"
               type="text" value="" size="40">
    </div>
    <hr>
    <h3> <?php echo __('Event', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>
    <div class="form-field">
        <label for="event_datetime"><?php echo __('Event date', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input name="event_date_time" id="event_date_time" type="text"
               value="" size="40">
    </div>
    <div class="form-field">
        <label for="event_location"><?php echo __('Event location', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input name="event_location" id="event_location" type="text" value=""
               size="40">
    </div>
    <div class="form-field">
        <label for="message"><?php echo __('Message for guests', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <textarea id="message" name="message" rows="" cols=""></textarea>
    </div>

    <hr/>
    <h3><?php echo __('Add a photo', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

    <hr>
    <h3><?php echo __('Gift Registry status') ?></h3>
    <div class="form-field">
        <label for="publ_registry"><?php echo __('Public', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input type="radio" id="publ_registry" name="status" value="0"
               checked="checked">
    </div>
    <div class="form-field">
        <label for="priv_registry"><?php echo __('Private', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input type="radio" id="priv_registry" name="status" value="0"
               checked="checked">
    </div>

    <hr>
    <h3><?php echo __('Shipping address', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

    <div class="form-field">
        <label for="shipping_first_name"><?php echo __('First name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input type="text" id="shipping_first_name" name="shipping_first_name" value="0"/>

    </div>
    <div class="form-field">
        <label for="shipping_last_name"><?php echo __('Last name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input type="text" id="shipping_last_name" name="shipping_last_name" value="0"/>

    </div>
    <div class="form-field">
        <label for="shipping_company"><?php echo __('Company', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input type="text" id="shipping_company" name="shipping_company" value="0"/>

    </div>

    <div class="form-field">
        <label for="shipping_address_1"><?php echo __('Address', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input type="text" id="shipping_address_1" name="shipping_address_1" value="0"/>

    </div>
    <div class="form-field">
        <label for="shipping_address_2"><?php echo __('Address', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input type="text" id="shipping_address_2" name="shipping_address_2" value="0"/>

    </div>

    <div class="form-field">
        <label for="shipping_city"><?php echo __('City', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input type="text" id="shipping_city" name="shipping_city" value="0"/>

    </div>
    <div class="form-field">
        <label for="shipping_state"><?php echo __('State', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
        <input type="text" id="shipping_state" name="shipping_state" value="0"/>

    </div>

    <!-- Country -->


    <?php

    $key = 'shipping_country';
    $value = 'vn';
    $required = true;
    $after = '';
    $args = array();
    $args['class'] = array('ab');
    $args['description'] = '';
    $args['label_class'] = array('ab');
    $args['label'] = '';
    $args['id'] = '';
    $custom_attributes = array('ab');
    //$countries = $key == 'shipping_country' ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();
    $countries = WC()->countries->get_allowed_countries();
    if (sizeof($countries) == 1) {

        $field = '<p class="form-row ' . esc_attr(implode(' ', $args['class'])) . '" id="' . esc_attr($args['id']) . '_field">';

        if ($args['label'])
            $field .= '<label class="' . esc_attr(implode(' ', $args['label_class'])) . '">' . $args['label'] . '</label>';

        $field .= '<strong>' . current(array_values($countries)) . '</strong>';

        $field .= '<input type="hidden" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="' . current(array_keys($countries)) . '" ' . implode(' ', $custom_attributes) . ' class="country_to_state" />';

        if ($args['description'])
            $field .= '<span class="description">' . esc_attr($args['description']) . '</span>';

        $field .= '</p>' . $after;

    } else {

        $field = '<p class="form-row ' . esc_attr(implode(' ', $args['class'])) . '" id="' . esc_attr($args['id']) . '_field">'
            . '<label for="' . esc_attr($args['id']) . '" class="' . esc_attr(implode(' ', $args['label_class'])) . '">' . $args['label'] . $required . '</label>'
            . '<select name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" class="country_to_state country_select" ' . implode(' ', $custom_attributes) . '>'
            . '<option value="">' . __('Select a country&hellip;', 'woocommerce') . '</option>';

        foreach ($countries as $ckey => $cvalue)
            $field .= '<option value="' . esc_attr($ckey) . '" ' . selected($value, $ckey, false) . '>' . __($cvalue, 'woocommerce') . '</option>';

        $field .= '</select>';

        $field .= '<noscript><input type="submit" name="woocommerce_checkout_update_totals" value="' . __('Update country', 'woocommerce') . '" /></noscript>';

        if ($args['description'])
            $field .= '<span class="description">' . esc_attr($args['description']) . '</span>';

        $field .= '</p>' . $after;

    }
    ?>

    <input type="submit" value="Submit">
</form>
<?php
