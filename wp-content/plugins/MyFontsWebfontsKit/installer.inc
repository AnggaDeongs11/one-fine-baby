<?php

class MyFontsInstaller_3769676 {

    protected static $buildID = 3769676;

    //tell the user we got everything installed, but don't bug 'em
    public static function installWarning()
    {
        echo "<div id='message' class='notice notice-success is-dismissible'>";
        echo "<p>You've installed a MyFonts Webfonts Kit for Wordpress.</p>";
        echo "<p>Check out your <a href='themes.php?page=mf_settings_page_".esc_html(self::$buildID)."'>settings</a> to add it to your Wordpress theme!</p>";
        echo "</div>";
    }

    //check if installation is all fine and dandy
    public static function checkInstallation()
    {
        if (!get_option("mffonts_install_notice_".self::$buildID) ) {
            add_action("admin_notices", array("MyFontsInstaller_".self::$buildID,"installWarning"));
            update_option("mffonts_install_notice_".self::$buildID, "true");
        }
    }

    public static function mffonts_loadupWebfonts()
    {
        $css = plugins_url() . "/MyFontsWebfontsKit/MyFontsWebfontsKit.css";
        wp_enqueue_style("mf_webfonts_".self::$buildID, $css);
        add_action("wp_head", array("MyFontsInstaller_".self::$buildID,"mffonts_loadInline"), 100);
    }

    public static function mffonts_loadInline()
    {
        $options[".site-title"] = get_option("mffonts_site_title_".self::$buildID);
        $options[".site-description"] = get_option("mffonts_site_desc_".self::$buildID);
        $options[".entry-title"] = get_option("mffonts_post_title_".self::$buildID);
        $options[".entry-content"] = get_option("mffonts_post_body_".self::$buildID);
        $data = "<style type='text/css'>";
        foreach ($options as $key => $option) {
            if ($option !== false && $option != "none" && $option != "") {
                $data .= esc_html($key)." {font-family:'".esc_html($option)."';} ";
            }
        }
        $data .= "</style>";
        echo $data;
    }
}