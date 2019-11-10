<?php

if (!class_exists('rtTPGTemplate')):

    /**
     *
     */
    class rtTPGTemplate {

        function __construct()
        {
            add_filter('template_include', array($this, 'template_loader'), 99);
            add_action('wp_enqueue_scripts', array($this, 'load_rt_tpg_template_scripts'));
        }

        public static function template_loader($template)
        {
            $settings = get_option(rtTPG()->options['settings']);
            $oLayoutAuthor = !empty($settings['template_author']) ? $settings['template_author'] : null;
            $oLayoutCategory = !empty($settings['template_category']) ? $settings['template_category'] : null;
            $oLayoutSearch = !empty($settings['template_search']) ? $settings['template_search'] : null;
            $oLayoutTag = !empty($settings['template_tag']) ? $settings['template_tag'] : null;

            $file = null;
            if (is_tag() && $oLayoutTag) {
                $file = 'tag-archive.php';
            } elseif (is_category() && $oLayoutCategory) {
                $file = 'category-archive.php';
            } elseif (is_author() && $oLayoutAuthor) {
                $file = 'author-archive.php';
            } elseif (is_search() && $oLayoutSearch) {
                $file = 'search.php';
            }
            if ($file) {
                $template = locate_template(array('templates/' . $file));
                if (!$template) {
                    $template = rtTPG()->templatePath . $file;
                }
            }

            return $template;
        }

        public function load_rt_tpg_template_scripts()
        {
            $settings = get_option(rtTPG()->options['settings']);
            $oLayoutAuthor = !empty($settings['template_author']) ? $settings['template_author'] : null;
            $oLayoutCategory = !empty($settings['template_category']) ? $settings['template_category'] : null;
            $oLayoutSearch = !empty($settings['template_search']) ? $settings['template_search'] : null;
            $oLayoutTag = !empty($settings['template_tag']) ? $settings['template_tag'] : null;

            if ((is_tag() && $oLayoutTag) || (is_category() && $oLayoutCategory) || (is_author() && $oLayoutAuthor) || (is_search() && $oLayoutSearch)) {
                $script = array(
                    'jquery',
                    'rt-image-load-js',
                    'rt-isotope-js',
                    'rt-owl-carousel',
                    'rt-scrollbar',
                    'rt-actual-height-js',
                    'rt-tpg'
                );
                $style = array(
                    'rt-owl-carousel',
                    'rt-owl-carousel-theme',
                    'rt-scrollbar',
                    'rt-fontawsome',
                );
                if (class_exists('WooCommerce')) {
                    array_push($script, 'rt-jzoom');
                }
                if (is_rtl()) {
                    array_push($style, 'rt-tpg-rtl');
                }
                wp_enqueue_style($style);
                wp_enqueue_script($script);
                $nonce = wp_create_nonce(rtTPG()->nonceText());
                $ajaxurl = '';
                if (in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins'))) {
                    $ajaxurl .= admin_url('admin-ajax.php?lang=' . ICL_LANGUAGE_CODE);
                } else {
                    $ajaxurl .= admin_url('admin-ajax.php');
                }
                wp_localize_script('rt-tpg', 'rttpg',
                    array(
                        'nonceID' => rtTPG()->nonceId(),
                        'nonce'   => $nonce,
                        'ajaxurl' => $ajaxurl
                    ));
            }
        }


    }

endif;
