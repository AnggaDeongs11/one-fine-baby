<?php
if (!defined('ABSPATH'))
    exit (); // Exit if accessed directly

if (!class_exists('WC_Settings_Page'))
    include_once dirname(GIFTREGISTRY_PATH) . '/woocommerce/includes/admin/settings/class-wc-settings-page.php';

class Magenest_Giftregistry_Setting extends WC_Settings_Page
{
    public function __construct()
    {
        $this->id = 'giftregistry';
        $this->label = __('Gift registry', GIFTREGISTRY_TEXT_DOMAIN);

        add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_page'), 20);
        add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));
        add_action('woocommerce_settings_' . $this->id, array($this, 'output'));
        add_action('admin_enqueue_scripts', function () {
            if (is_admin())
                wp_enqueue_media();
        });
    }

    public function output()
    {
//        parent::output();
        $template_path = $default_path = GIFTREGISTRY_PATH . '/admin/view/';
        wc_get_template('html-giftregistry-setting.php', array(), $template_path, $default_path);
    }

    /**
     * Get settings array
     *
     * @return array
     */
    public function get_settings()
    {


        $options = apply_filters('woocommerce_giftregistry_settings', array(

                array(
                    'title' => __('Gift Registry Settings', GIFTREGISTRY_TEXT_DOMAIN),
                    'type' => 'title',
                    'id' => 'giftregistry_options_title'
                ),
//                array(
//                    'title' => __('Enable full mode', GIFTREGISTRY_TEXT_DOMAIN),
//                    'desc' => __('Enable full mode will allow customer upload images in their gift registry', GIFTREGISTRY_TEXT_DOMAIN),
//                    'id' => 'giftregistry_allow_fullmode',
//                    'type' => 'checkbox',
//                    'autoload' => false,
//                ),
                array(
                    'title' => __('Guest permission', GIFTREGISTRY_TEXT_DOMAIN),
                    'desc' => __('Allow guest to add products to gift registry', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_enable_permission',
                    'type' => 'checkbox',
                    'autoload' => false,
                ),
                /*Multiple GR*/
//                array(
//                    'title' => __('Multiple gift registry', GIFTREGISTRY_TEXT_DOMAIN),
//                    'desc' => __('Allow guest to add products from muliple gift registry to cart', GIFTREGISTRY_TEXT_DOMAIN),
//                    'id' => 'multi_gift_registry',
//                    'type' => 'checkbox',
//                    'autoload' => false,
//                ),

                /*Button add gift registry in shop page*/
		        array(
			        'title' => __('Add to gift registry from product list page', GIFTREGISTRY_TEXT_DOMAIN),
			        'desc' => __('Add the "Add to gift registry" button for each product on category page', GIFTREGISTRY_TEXT_DOMAIN),
			        'id' => 'giftregistry_enable_button',
			        'type' => 'checkbox',
			        'autoload' => false,
		        ),
                array(
                    'title' => __('Send notification email of gift registry new orders to', GIFTREGISTRY_TEXT_DOMAIN),
                    'desc' => __('Registry\'s  owner', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_notify_owner',
                    'type' => 'checkbox',
                    'checkboxgroup' => 'start',
                    'autoload' => false,
                ),
                array(
                    'desc' => __('Registrant', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_notify_registrant',
                    'type' => 'checkbox',
                    'autoload' => false,
                ),
                array(
                    'desc' => __('CoRegistrant', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_notify_coregistrant',
                    'type' => 'checkbox',
                    'autoload' => false,
                ),
                array(
                    'desc' => __('Admin', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_notify_admin',
                    'type' => 'checkbox',
                    'autoload' => false,
                ),
                array(
                    'checkboxgroup' => 'end'
                ),
                array(
                    'name' => __('Email subject for Registry\'s  owner', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_notify_email_subject_owner',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Email template for Registry\'s  owner', GIFTREGISTRY_TEXT_DOMAIN),
                    'desc' => (__('Available shortcodes for email template: {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}', GIFTREGISTRY_TEXT_DOMAIN)),
                    'id' => 'giftregistry_notify_email_content_owner',
                    'type' => 'textarea',
                ),
                array(
                    'name' => __('Email subject for Registrant', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_notify_email_subject_registrant',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Email template for Registrant', GIFTREGISTRY_TEXT_DOMAIN),
                    'desc' => (__('Available shortcodes for email template: {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}', GIFTREGISTRY_TEXT_DOMAIN)),
                    'id' => 'giftregistry_notify_email_content_registrant',
                    'type' => 'textarea',
                ),
                array(
                    'name' => __('Email subject for CoRegistrant', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_notify_email_subject_coregistrant',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Email template for CoRegistrant', GIFTREGISTRY_TEXT_DOMAIN),
                    'desc' => (__('Available shortcodes for email template: {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}', GIFTREGISTRY_TEXT_DOMAIN)),
                    'id' => 'giftregistry_notify_email_content_coregistrant',
                    'type' => 'textarea',
                ),
                array(
                    'name' => __('Email subject for Admin', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_notify_email_subject_admin',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Email template for Admin', GIFTREGISTRY_TEXT_DOMAIN),
                    'desc' => (__('Available shortcodes for email template: {{buyer_name}},{{store_url}},{{store_name}},{{order_items}},{{break_line}}', GIFTREGISTRY_TEXT_DOMAIN)),
                    'id' => 'giftregistry_notify_email_content_admin',
                    'type' => 'textarea',
                ),

                array(
                    'title' => __('Make shipping address mandatory', GIFTREGISTRY_TEXT_DOMAIN),
                    'desc' => __("Gift registry's owner is NOT allowed to add products to gift registry unless he/she fills in the shipping address when 
                    creating the gift registry", GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_shipping_restrict',
                    'type' => 'checkbox',
                    'default' => 'yes',
                    'autoload' => false,
                ),
                array(
                    'title' => __('Default message for social sharing', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_share_text',
                    'desc' => __('This message will be used as email content for email sharing and as default caption for social sharing {Facebook,Twitter,Google+}.
                    You can use the shortcode {giftregistry_url} to add gift registry URL to email template', GIFTREGISTRY_PATH),
                    'type' => 'textarea',
                    'autoload' => false
                ),
                array(
                    'title' => __('Image attached for social sharing', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_share_image_url',
                    'type' => 'text',
                    'autoload' => false
                ),
                array(
                    'title' => __('Share gift registry via ', GIFTREGISTRY_TEXT_DOMAIN),
                    'id' => 'giftregistry_share_facebook',
                    'type' => 'checkbox',
                    'checkboxgroup' => 'start',
                    'desc' => 'Facebook',
                    'autoload' => false,
                ),
                array(
                    'id' => 'giftregistry_share_twitter',
                    'type' => 'checkbox',
                    'desc' => 'Twitter',
                    'autoload' => false,
                ),
                array(
                    'id' => 'giftregistry_share_email',
                    'type' => 'checkbox',
                    'desc' => 'Mail',
                    'autoload' => false,
                ),
                array(
                    'checkboxgroup' => 'end',
                ),
                array('type' => 'sectionend', 'id' => 'test-options')
            )
        );

        return $options;
    }
}

return new Magenest_Giftregistry_Setting();
