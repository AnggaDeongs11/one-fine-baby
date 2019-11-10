<?php
// create custom plugin settings menu
add_action("admin_menu", ["MyFontsSettings_3769676","addOptionsPage"]);
add_action("admin_init", ["MyFontsSettings_3769676","adminInit"]);

class MyFontsSettings_3769676
{

	protected static $buildID = 3769676;
	protected static $fonts = ['Sailec-Bold', 'Sailec-Medium', 'Sailec-Regular'];

	public static function addOptionsPage()
	{
		add_theme_page(
			"MyFonts Webfont Kit Settings",
			"MyFonts Webfonts (".self::$buildID.")",
			"manage_options",
			"mf_settings_page_".self::$buildID,
			[__CLASS__, 'settingsPage']
		);
	}

    public static function adminInit()
	{

		$groupName = "mf_settings_group_".self::$buildID;
		$pageName = "mf_settings_page_".self::$buildID;
		$className = __CLASS__;

		$settingArray = [
							"mffonts_site_title_".self::$buildID,
							"mffonts_site_desc_".self::$buildID,
							"mffonts_post_title_".self::$buildID,
							"mffonts_post_body_".self::$buildID
						];

		foreach($settingArray as $setting) {
			register_setting($groupName, $setting, [$className,'sanitizeOption']);
		}

        add_settings_section(
        	'site_section',
        	'Site Settings',
        	[$className,'siteSectionText'],
        	$pageName
        );

        add_settings_section(
        	'post_section',
        	'Post Settings',
        	[$className,'postSectionText'],
        	$pageName
        );

        add_settings_field(
    		'mffonts_site_title_'.self::$buildID,
    		'Site Title:',
    		[$className,'addInput'],
    		$pageName,
    		'site_section',
    		'mffonts_site_title_'.self::$buildID
		);

		add_settings_field(
    		'mffonts_site_desc_'.self::$buildID,
    		'Site Description:',
    		[$className,'addInput'],
    		$pageName,
    		'site_section',
    		'mffonts_site_desc_'.self::$buildID
		);

		add_settings_field(
    		'mffonts_post_title_'.self::$buildID,
    		'Post Title:',
    		[$className,'addInput'],
    		$pageName,
    		'post_section',
    		'mffonts_post_title_'.self::$buildID
		);

		add_settings_field(
    		'mffonts_post_body_'.self::$buildID,
    		'Post Body:',
    		[$className,'addInput'],
    		$pageName,
    		'post_section',
    		'mffonts_post_body_'.self::$buildID
		);
	}

	public function siteSectionText()
	{
		echo '<p>Webfont changes for top of WordPress pages</p>';
    }

    public function postSectionText()
    {
        echo '<p>Webfont changes for each individual post on WordPress</p>';
    }

    public function addInput($name)
    {
        $option = get_option($name);
        echo "<select name='$name'>";
        echo "<option value='none'>none</option>";

        foreach(self::$fonts as $fontName) {
            echo "<option value='$fontName' ".esc_html(selected($option,$fontName)).">$fontName</option>";
        }

        echo "</select>";
    }

    public function sanitizeOption($selection) {
        if(!in_array($selection, self::$fonts) && $selection != "none") {
            return "none";
    } else {
            return $selection;
        }
    }

    public static function settingsPage()
    {
        echo "<img src='".plugins_url('header_logo.png',__FILE__)."' alt='MyFonts'>";
        echo "<h2>WordPress Plugin Build ID# ".esc_html(self::$buildID)."</h2>";

        if( isset($_GET["settings-updated"]) ) {
            echo "<div id='message' class='updated'>";
            echo "<p><strong>Settings saved.</strong></p></div>";
        }

        echo "<form method='post' action='options.php'>";
        settings_fields("mf_settings_group_".self::$buildID);
        do_settings_sections("mf_settings_page_".self::$buildID);
        submit_button();
        echo "</form></div>";
    }
}