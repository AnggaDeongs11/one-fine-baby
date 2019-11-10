<?php

if (!class_exists('rtTPGLicence')):

    class rtTPGLicence {

        function __construct()
        {
            add_action('wp_ajax_rtTPG_active_Licence', array($this, 'rtTPG_active_Licence'));
            add_action('wp_ajax_rtTPGManageLicencing', array($this, 'rtTPGManageLicencing'));
            add_action('admin_init', array($this, 'tpg_licence'));
        }

        function tpg_licence()
        {
            $settings = get_option(rtTPG()->options['settings']);
            $license = !empty($settings['license_key']) ? trim($settings['license_key']) : null;
            $edd_updater = new EDD_RT_TPG_Plugin_Updater(EDD_RT_TPG_STORE_URL, RT_THE_POST_GRID_PRO_PLUGIN_ACTIVE_FILE_NAME, array(
                'version' => RT_TPG_PRO_VERSION,        // current version number
                'license' => $license,    // license key (used get_option above to retrieve from DB)
                'item_id' => EDD_RT_TPG_ITEM_ID,    // id of this plugin
                'author'  => RT_TPG_PRO_AUTHOR,    // author of this plugin
                'url'     => home_url(),
                'beta'    => false
            ));
        }


        function rtTPG_active_Licence()
        {

            $error = true;
            $html = $message = null;
            if (rtTPG()->verifyNonce()) {
                $settings = get_option(rtTPG()->options['settings']);
                $license = !empty($settings['license_key']) ? trim($settings['license_key']) : null;
                $api_params = array(
                    'edd_action' => 'activate_license',
                    'license'    => $license,
                    'item_id'    => EDD_RT_TPG_ITEM_ID,
                    'url'        => home_url()
                );
                $response = wp_remote_post(EDD_RT_TPG_STORE_URL,
                    array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));
                if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
                    $err = $response->get_error_message();
                    $message = (is_wp_error($response) && !empty($err)) ? $err : __('An error occurred, please try again.', 'the-post-grid-pro');
                } else {
                    $license_data = json_decode(wp_remote_retrieve_body($response));
                    if (false === $license_data->success) {
                        switch ($license_data->error) {
                            case 'expired' :
                                $message = sprintf(
                                    __('Your license key expired on %s.', 'the-post-grid-pro'),
                                    date_i18n(get_option('date_format'),
                                        strtotime($license_data->expires, current_time('timestamp')))
                                );
                                break;
                            case 'revoked' :
                                $message = __('Your license key has been disabled.', 'the-post-grid-pro');
                                break;
                            case 'missing' :
                                $message = __('Invalid license.', 'the-post-grid-pro');
                                break;
                            case 'invalid' :
                            case 'site_inactive' :
                                $message = __('Your license is not active for this URL.', 'the-post-grid-pro');
                                break;
                            case 'item_name_mismatch' :
                                $message = sprintf(__('This appears to be an invalid license key for %s.', 'the-post-grid-pro'),
                                    EDD_RT_TPG_ITEM_ITEM_NAME);
                                break;
                            case 'no_activations_left':
                                $message = __('Your license key has reached its activation limit.', 'the-post-grid-pro');
                                break;
                            default :
                                $message = __('An error occurred, please try again.', 'the-post-grid-pro');
                                break;
                        }
                    }
                    // Check if anything passed on a message constituting a failure
                    if (empty($message)) {
                        $settings['license_status'] = $license_data->license;
                        update_option(rtTPG()->options['settings'], $settings);
                        $error = false;
                        $message = __('Successfully activated', 'the-post-grid-pro');
                    }
                    $html = ($license_data->license === 'valid') ? "<input type='submit' class='button-secondary rt-licensing-btn danger' name='license_deactivate' value='" . __("Deactivate License", 'the-post-grid-pro') . "'/>"
                        : "<input type='submit' class='button-secondary rt-licensing-btn button-primary' name='license_activate' value='" . __("Activate License", 'the-post-grid-pro') . "'/>";
                }
            } else {
                $message = __('Session Error !!', 'the-post-grid-pro');
            }
            $response = array(
                'error' => $error,
                'msg'   => $message,
                'html'  => $html,
            );
            wp_send_json($response);
            die();
        }

        function rtTPGManageLicencing()
        {

            $error = true;
            $name = $value = $data = $class = $message = null;
            if (rtTPG()->verifyNonce()) {
                $settings = get_option(rtTPG()->options['settings']);
                $license = !empty($settings['license_key']) ? trim($settings['license_key']) : null;
                if (!empty($_REQUEST['type']) && $_REQUEST['type'] == "license_activate") {
                    $api_params = array(
                        'edd_action' => 'activate_license',
                        'license'    => $license,
                        'item_id'    => EDD_RT_TPG_ITEM_ID,
                        'url'        => home_url()
                    );
                    $response = wp_remote_post(EDD_RT_TPG_STORE_URL,
                        array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));
                    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
                        $err = $response->get_error_message();
                        $message = (is_wp_error($response) && !empty($err)) ? $err : __('An error occurred, please try again.', 'the-post-grid-pro');
                    } else {
                        $license_data = json_decode(wp_remote_retrieve_body($response));
                        if (false === $license_data->success) {
                            switch ($license_data->error) {
                                case 'expired' :
                                    $message = sprintf(
                                        __('Your license key expired on %s.', 'the-post-grid-pro'),
                                        date_i18n(get_option('date_format'),
                                            strtotime($license_data->expires, current_time('timestamp')))
                                    );
                                    break;
                                case 'revoked' :
                                    $message = __('Your license key has been disabled.', 'the-post-grid-pro');
                                    break;
                                case 'missing' :
                                    $message = __('Invalid license.', 'the-post-grid-pro');
                                    break;
                                case 'invalid' :
                                case 'site_inactive' :
                                    $message = __('Your license is not active for this URL.', 'the-post-grid-pro');
                                    break;
                                case 'item_name_mismatch' :
                                    $message = sprintf(__('This appears to be an invalid license key for %s.', 'the-post-grid-pro'),
                                        EDD_RT_TPG_ITEM_ITEM_NAME);
                                    break;
                                case 'no_activations_left':
                                    $message = __('Your license key has reached its activation limit.', 'the-post-grid-pro');
                                    break;
                                default :
                                    $message = __('An error occurred, please try again.', 'the-post-grid-pro');
                                    break;
                            }
                        }
                        // Check if anything passed on a message constituting a failure
                        if (empty($message)) {
                            $settings['license_status'] = $license_data->license;
                            update_option(rtTPG()->options['settings'], $settings);
                            $error = false;
                            $name = 'license_deactivate';
                            $value = 'Deactivate License';
                            $class = 'button-primary';
                        }
                    }
                }
                if (!empty($_REQUEST['type']) && $_REQUEST['type'] == "license_deactivate") {
                    $api_params = array(
                        'edd_action' => 'deactivate_license',
                        'license'    => $license,
                        'item_id'    => EDD_RT_TPG_ITEM_ID,
                        'url'        => home_url()
                    );
                    $response = wp_remote_post(EDD_RT_TPG_STORE_URL,
                        array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

                    // Make sure there are no errors
                    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
                        $err = $response->get_error_message();
                        $message = (is_wp_error($response) && !empty($err)) ? $err : __('An error occurred, please try again.', 'the-post-grid-pro');
                    } else {
//						$license_data = json_decode( wp_remote_retrieve_body( $response ) );
                        unset($settings['license_status']);
                        update_option(rtTPG()->options['settings'], $settings);
                        $error = false;
                        $name = 'license_activate';
                        $value = 'Activate License';
                        $class = 'button-primary';
                    }
                }
            } else {
                $message = __('Session Error !!', 'the-post-grid-pro');
            }
            $response = array(
                'error' => $error,
                'msg'   => $message,
                'name'  => $name,
                'value' => $value,
                'class' => $class,
                'data'  => $data
            );
            wp_send_json($response);
            die();
        }
    }

endif;