<?php

if (!class_exists('rtTPGOptions')):

    class rtTPGOptions
    {

        function rtPostTypes() {
            $post_types = get_post_types(
                array(
                    'public' => true,
                    //                    '_builtin' => true
                )
            );
            $exclude = array('attachment', 'revision', 'nav_menu_item');
            foreach ($exclude as $ex) {
                unset($post_types[$ex]);
            }

            return $post_types;
        }

        function rtPostOrders() {
            return array(
                "ASC"  => __("Ascending", 'the-post-grid-pro'),
                "DESC" => __("Descending", 'the-post-grid-pro')
            );
        }

        function rtTermOperators() {
            return array(
                'IN'     => __("IN — show posts which associate with one or more of selected terms",
                    'the-post-grid-pro'),
                'NOT IN' => __("NOT IN — show posts which do not associate with any of selected terms",
                    'the-post-grid-pro'),
                'AND'    => __("AND — show posts which associate with all of selected terms", 'the-post-grid-pro')
            );
        }

        function rtTermRelations() {
            return array(
                'AND' => __("AND — show posts which match all settings", 'the-post-grid-pro'),
                'OR'  => __("OR — show posts which match one or more settings", 'the-post-grid-pro')
            );
        }

        function rtMetaKeyType() {
            return array(
                'meta_value'          => __('Meta value', 'the-post-grid-pro'),
                'meta_value_num'      => __('Meta value number', 'the-post-grid-pro'),
                'meta_value_datetime' => __('Meta value datetime', 'the-post-grid-pro'),
            );
        }

        function rtPostOrderBy($isWoCom = false, $metaOrder = false) {
            $orderBy = array(
                "ID"            => __("ID", 'the-post-grid-pro'),
                "title"         => __("Title", 'the-post-grid-pro'),
                "date"          => __("Created date", 'the-post-grid-pro'),
                "modified"      => __("Modified date", 'the-post-grid-pro'),
                "rand"          => __("Random", 'the-post-grid-pro'),
                "comment_count" => __("Number of comments", 'the-post-grid-pro'),
                "menu_order"    => __("Menu Order", 'the-post-grid-pro')
            );
            $wooOrder = array(
                "price"  => __("Price", 'the-post-grid-pro'),
                "rating" => __("AVG Rating", 'the-post-grid-pro')
            );
            $orderBy = $isWoCom ? array_merge($orderBy, $wooOrder) : $orderBy;
            $orderBy = $metaOrder ? array_merge($orderBy, $this->rtMetaKeyType()) : $orderBy;

            return $orderBy;
        }

        function rtTPGSettingsCustomScriptFields() {
            $settings = get_option(rtTPG()->options['settings']);

            return array(
                "custom_css"              => array(
                    "label"       => __("Custom CSS", 'the-post-grid-pro'),
                    'type'        => 'textarea',
                    'holderClass' => 'rt-script-wrapper full',
                    'id'          => 'custom-css',
                    'value'       => isset($settings['custom_css']) ? stripslashes($settings['custom_css']) : null
                ),
                "script_before_item_load" => array(
                    "label"       => __("Script before item load", 'the-post-grid-pro'),
                    'type'        => 'textarea',
                    'holderClass' => 'rt-script-wrapper full',
                    'id'          => 'script-before-item-load',
                    'value'       => isset($settings['script_before_item_load']) ? stripslashes($settings['script_before_item_load']) : null
                ),
                "script_after_item_load"  => array(
                    "label"       => __("Script After item load", 'the-post-grid-pro'),
                    'type'        => 'textarea',
                    'holderClass' => 'rt-script-wrapper full',
                    'id'          => 'script-after-item-load',
                    'value'       => isset($settings['script_after_item_load']) ? stripslashes($settings['script_after_item_load']) : null
                ),
                "script_loaded"           => array(
                    "label"       => __("After Loaded script", 'the-post-grid-pro'),
                    'type'        => 'textarea',
                    'holderClass' => 'rt-script-wrapper full',
                    'id'          => 'script-loaded',
                    'value'       => isset($settings['script_loaded']) ? stripslashes($settings['script_loaded']) : null
                )
            );
        }

        function rtTPGSettingsOtherSettingsFields() {
            $settings = get_option(rtTPG()->options['settings']);

            return array(
                'template_author'   => array(
                    'type'    => 'select',
                    'name'    => 'template_author',
                    'label'   => 'Template Author',
                    'id'      => 'template_author',
                    'class'   => 'select2',
                    'blank'   => 'Select a layout',
                    'options' => rtTPG()->getTPGShortCodeList(),
                    'value'   => isset($settings['template_author']) ? $settings['template_author'] : array(),
                ),
                'template_category' => array(
                    'type'    => 'select',
                    'name'    => 'template_category',
                    'label'   => 'Template Category',
                    'id'      => 'template_category',
                    'class'   => 'select2',
                    'blank'   => 'Select a layout',
                    'options' => rtTPG()->getTPGShortCodeList(),
                    'value'   => isset($settings['template_category']) ? $settings['template_category'] : array(),
                ),
                'template_search'   => array(
                    'type'    => 'select',
                    'name'    => 'template_search',
                    'label'   => 'Template Search',
                    'id'      => 'template_search',
                    'class'   => 'select2',
                    'blank'   => 'Select a layout',
                    'options' => rtTPG()->getTPGShortCodeList(),
                    'value'   => isset($settings['template_search']) ? $settings['template_search'] : array(),
                ),
                'template_tag'      => array(
                    'type'    => 'select',
                    'name'    => 'template_tag',
                    'label'   => 'Template Tag',
                    'id'      => 'template_tag',
                    'class'   => 'select2',
                    'blank'   => 'Select a layout',
                    'options' => rtTPG()->getTPGShortCodeList(),
                    'value'   => isset($settings['template_tag']) ? $settings['template_tag'] : array(),
                ),
                'template_class'    => array(
                    'type'  => 'text',
                    'name'  => 'template_class',
                    'label' => 'Template class',
                    'id'    => 'template_class',
                    'value' => isset($settings['template_class']) ? $settings['template_class'] : '',
                )
            );
        }

        function rtTPGLicenceField() {
            $settings = get_option(rtTPG()->options['settings']);
            $status = !empty($settings['license_status']) && $settings['license_status'] === 'valid' ? true : false;
            $license_status = !empty($settings['license_key']) ? sprintf("<span class='license-status'>%s</span>",
                $status ? "<input type='submit' class='button-secondary rt-licensing-btn danger' name='license_deactivate' value='" . __("Deactivate License", "the-post-grid-pro") . "'/>"
                    : "<input type='submit' class='button-secondary rt-licensing-btn button-primary' name='license_activate' value='" . __("Activate License", "the-post-grid-pro") . "'/>"
            ) : ' ';

            return array(
                "license_key" => array(
                    'type'        => 'text',
                    'name'        => 'license_key',
                    'attr'        => 'style="min-width:300px;"',
                    'label'       => __('Enter your license key', 'the-post-grid-pro'),
                    'description' => $license_status,
                    'id'          => 'license_key',
                    'value'       => isset($settings['license_key']) ? $settings['license_key'] : ''
                )
            );
        }

        function rtTPGSettingsSocialShareFields() {

            $settings = get_option(rtTPG()->options['settings']);

            return array(
                "social_share_items" => array(
                    'type'      => 'checkbox',
                    'name'      => 'social_share_items',
                    'label'     => 'Social share items',
                    'id'        => 'social_share_items',
                    'alignment' => 'vertical',
                    'multiple'  => true,
                    'options'   => rtTPG()->socialShareItemList(),
                    'value'     => isset($settings['social_share_items']) ? $settings['social_share_items'] : array()
                )
            );
        }

        function socialShareItemList() {
            return array(
                'facebook'    => 'Facebook',
                'twitter'     => 'Twitter',
                'google-plus' => 'Google +',
                'linkedin'    => 'LinkedIn',
                'pinterest'   => 'Pinterest',
                'reddit'      => 'Reddit',
                'email'       => 'Email',
            );
        }

        function templateOverrideItemList() {
            return array(
                'category-archive' => "Category archive",
                'tag-archive'      => "Tag archive",
                'author-archive'   => "Author archive",
                'search'           => "Search page",
            );
        }

        function rtTPGCommonFilterFields() {
            return array(
                'post__in'     => array(
                    "name"        => "post__in",
                    "label"       => "Include only",
                    "type"        => "text",
                    "class"       => "full",
                    "description" => 'List of post IDs to show (comma-separated values, for example: 1,2,3)'
                ),
                'post__not_in' => array(
                    "name"        => "post__not_in",
                    "label"       => "Exclude",
                    "type"        => "text",
                    "class"       => "full",
                    "description" => 'List of post IDs to hide (comma-separated values, for example: 1,2,3)'
                ),
                'limit'        => array(
                    "name"        => "limit",
                    "label"       => "Limit",
                    "type"        => "number",
                    "class"       => "full",
                    "description" => 'The number of posts to show. Set empty to show all found posts.'
                )
            );
        }

        function rtTPGPostType() {
            return array(
                'tpg_post_type' => array(
                    "label"   => "Post Type",
                    "type"    => "select",
                    "id"      => "rt-sc-post-type",
                    "class"   => "-rt-select2",
                    "options" => $this->rtPostTypes()
                )
            );
        }

        function rtTPAdvanceFilters() {
            return array(
                'post_filter' => array(
                    'type'      => "checkbox",
                    'name'      => "post_filter",
                    'label'     => "Advanced filters",
                    "alignment" => "vertical",
                    "multiple"  => true,
                    "options"   => array(
                        'tpg_taxonomy'    => "Taxonomy",
                        'order'           => "Order",
                        'author'          => "Author",
                        'tpg_post_status' => "Status",
                        's'               => "Search",
                        'date_range'      => "Date Range"
                    ),
                )
            );
        }

        function rtTPGPostStatus() {
            return array(
                'publish'    => 'Publish',
                'pending'    => 'Pending',
                'draft'      => 'Draft',
                'auto-draft' => 'Auto draft',
                'future'     => 'Future',
                'private'    => 'Private',
                'inherit'    => 'Inherit',
                'trash'      => 'Trash',
            );
        }

        function owl_property() {
            return array(
                'auto_play'   => 'Auto Play',
                'loop'        => 'Loop',
                'nav_button'  => 'Nav Button',
                'pagination'  => 'Pagination',
                'stop_hover'  => 'Stop Hover',
                'auto_height' => 'Auto Height',
                'lazy_load'   => 'Lazy Load',
                'rtl'         => 'Right to left (RTL)'
            );
        }

        function rtTPGLayoutSettingFields() {

            return array(
                'layout'                           => array(
                    "type"    => "select",
                    "name"    => "layout",
                    "label"   => "Layout",
                    "id"      => "rt-tpg-sc-layout",
                    "class"   => "rt-select2",
                    "options" => $this->rtTPGLayouts()
                ),
                'tgp_filter'                       => array(
                    "type"        => "checkbox",
                    "label"       => "Filter",
                    'holderClass' => "sc-tpg-grid-filter tpg-hidden",
                    "multiple"    => true,
                    "alignment"   => 'vertical',
                    "options"     => rtTPG()->tgp_filter_list()
                ),
                'tgp_filter_taxonomy'              => array(
                    "type"        => "select",
                    "label"       => "Taxonomy Filter",
                    'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
                    "class"       => "rt-select2",
                    "options"     => rtTPG()->rt_get_taxonomy_for_filter()
                ),
                'tgp_filter_taxonomy_hierarchical' => array(
                    "type"        => "checkbox",
                    "label"       => "Display as sub category",
                    'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
                    "option"      => "Active"
                ),
                'tgp_filter_type'                  => array(
                    "type"        => "select",
                    "label"       => "Taxonomy filter type",
                    'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
                    "class"       => "rt-select2",
                    "options"     => rtTPG()->rt_filter_type()
                ),
                'tgp_default_filter'               => array(
                    "type"        => "select",
                    "label"       => "Selected filter term (Selected item)",
                    'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
                    "class"       => "rt-select2",
                    "attr"        => "data-selected='" . get_post_meta(get_the_ID(), 'tgp_default_filter', true) . "'",
                    "options"     => array('' => __('Show All', 'the-post-grid-pro'))
                ),
                'tpg_hide_all_button'              => array(
                    "type"        => "checkbox",
                    "label"       => "Hide All (Show all) button",
                    'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
                    "option"      => 'Hide'
                ),
                'tpg_post_count'                   => array(
                    "type"        => "checkbox",
                    "label"       => "Show post count",
                    'holderClass' => "sc-tpg-grid-filter sc-tpg-filter tpg-hidden",
                    "option"      => 'Enable'
                ),
                'isotope_filter'                   => array(
                    "type"        => "select",
                    "label"       => "Isotope Filter",
                    'holderClass' => "isotope-item sc-isotope-filter tpg-hidden",
                    "id"          => "rt-tpg-sc-isotope-filter",
                    "class"       => "rt-select2",
                    "options"     => rtTPG()->rt_get_taxonomy_for_filter()
                ),
                'isotope_default_filter'           => array(
                    "type"        => "select",
                    "label"       => "Isotope filter (Selected item)",
                    'holderClass' => "isotope-item sc-isotope-default-filter tpg-hidden",
                    "id"          => "rt-tpg-sc-isotope-default-filter",
                    "class"       => "rt-select2",
                    "attr"        => "data-selected='" . get_post_meta(get_the_ID(), 'isotope_default_filter',
                            true) . "'",
                    "options"     => array('' => __('Show all', 'the-post-grid-pro'))
                ),
                'tpg_show_all_text'                => array(
                    "type"        => "text",
                    'holderClass' => "isotope-item sc-isotope-filter tpg-hidden",
                    "label"       => esc_html__("Show all text", 'the-post-grid-pro'),
                    "default"     => esc_html__("Show all", 'the-post-grid-pro')
                ),
                'isotope_filter_dropdown'          => array(
                    "type"        => "checkbox",
                    "label"       => "Isotope dropdown filter",
                    'holderClass' => "isotope-item sc-isotope-filter sc-isotope-filter-dropdown tpg-hidden",
                    "option"      => 'Enable'
                ),
                'isotope_filter_show_all'          => array(
                    "type"        => "checkbox",
                    "name"        => "isotope_filter_show_all",
                    "label"       => "Isotope filter (Show All item)",
                    'holderClass' => "isotope-item sc-isotope-filter-show-all tpg-hidden",
                    "id"          => "rt-tpg-sc-isotope-filter-show-all",
                    "option"      => 'Disable'
                ),
                'isotope_filter_count'             => array(
                    "type"        => "checkbox",
                    "label"       => "Isotope filter count number",
                    'holderClass' => "isotope-item sc-isotope-filter tpg-hidden",
                    "option"      => 'Enable'
                ),
                'isotope_filter_url'               => array(
                    "type"        => "checkbox",
                    "label"       => "Isotope filter URL",
                    'holderClass' => "isotope-item sc-isotope-filter tpg-hidden",
                    "option"      => 'Enable'
                ),
                'isotope_search_filter'            => array(
                    "type"        => "checkbox",
                    "label"       => "Isotope search filter",
                    'holderClass' => "isotope-item sc-isotope-search-filter tpg-hidden",
                    "id"          => "rt-tpg-sc-isotope-search-filter",
                    "option"      => 'Enable'
                ),
                'carousel_property'                => array(
                    "type"        => "checkbox",
                    "label"       => "Carousel property",
                    "multiple"    => true,
                    "alignment"   => 'vertical',
                    'holderClass' => "carousel-item carousel-property tpg-hidden",
                    "id"          => "carousel-property",
                    "default"     => array('pagination'),
                    "options"     => $this->owl_property()
                ),
                'tpg_carousel_speed'               => array(
                    "label"       => __("Speed", 'the-post-grid-pro'),
                    "holderClass" => "tpg-hidden carousel-item",
                    "type"        => "number",
                    'default'     => 250,
                    "description" => __('Auto play Speed in milliseconds', 'the-post-grid-pro'),
                ),
                'tpg_carousel_autoplay_timeout'    => array(
                    "label"       => __("Autoplay timeout", 'the-post-grid-pro'),
                    "holderClass" => "tpg-hidden carousel-item tpg-carousel-auto-play-timeout",
                    "type"        => "number",
                    'default'     => 5000,
                    "description" => __('Autoplay interval timeout', 'the-post-grid-pro'),
                ),
                'tgp_layout2_image_column'         => array(
                    'type'        => 'select',
                    'label'       => __('Image column', 'the-post-grid-pro'),
                    'class'       => 'rt-select2',
                    'holderClass' => "holder-layout2-image-column tpg-hidden",
                    'default'     => 4,
                    'options'     => $this->scColumns(),
                    "description" => "Content column will calculate automatically"
                ),
                'column'                           => array(
                    'type'        => 'select',
                    'label'       => __('Desktop column', 'the-post-grid-pro'),
                    'class'       => 'rt-select2',
                    'holderClass' => "offset-column-wrap",
                    'default'     => 3,
                    'options'     => $this->scColumns()
                ),
                'tpg_tab_column'                   => array(
                    'type'        => 'select',
                    'label'       => __('Tab column', 'the-post-grid-pro'),
                    'class'       => 'rt-select2',
                    'holderClass' => "offset-column-wrap",
                    'default'     => 2,
                    'options'     => $this->scColumns()
                ),
                'tpg_mobile_column'                => array(
                    'type'        => 'select',
                    'label'       => __('Mobile column', 'the-post-grid-pro'),
                    'class'       => 'rt-select2',
                    'holderClass' => "offset-column-wrap",
                    'default'     => 1,
                    'options'     => $this->scColumns()
                ),
                'ignore_sticky_posts'              => array(
                    "type"      => "radio",
                    "label"     => "Show sticky posts at the top",
                    "alignment" => "vertical",
                    "default"   => true,
                    "options"   => array(
                        false => "Yes",
                        true  => "No",
                    )
                ),
                'pagination'                       => array(
                    "type"        => "checkbox",
                    "label"       => "Pagination",
                    'holderClass' => "pagination",
                    "id"          => "rt-tpg-pagination",
                    "option"      => 'Enable'
                ),
                'posts_per_page'                   => array(
                    "type"        => "number",
                    "label"       => "Display per page",
                    'holderClass' => "pagination-item posts-per-page tpg-hidden",
                    "default"     => 5,
                    "description" => "If value of Limit setting is not blank (empty), this value should be smaller than Limit value."
                ),
                'posts_loading_type'               => array(
                    "type"        => "radio",
                    "label"       => "Post Loading Type",
                    'holderClass' => "pagination-item posts-loading-type tpg-hidden",
                    "alignment"   => "vertical",
                    "default"     => 'pagination',
                    "options"     => $this->postLoadingType(),
                ),
                'feature_image'                    => array(
                    "type"   => "checkbox",
                    "label"  => "Feature Image",
                    "id"     => "rt-tpg-feature-image",
                    "option" => 'Disable'
                ),
                'featured_image_size'              => array(
                    "type"        => "select",
                    "label"       => "Feature Image Size",
                    "class"       => "rt-select2",
                    'holderClass' => "rt-feature-image-option tpg-hidden",
                    "options"     => rtTPG()->get_image_sizes()
                ),
                'custom_image_size'                => array(
                    "type"        => "image_size",
                    "label"       => "Custom Image Size",
                    'holderClass' => "rt-feature-image-option rt-sc-custom-image-size-holder tpg-hidden",
                    "multiple"    => true
                ),
                'media_source'                     => array(
                    "type"        => "radio",
                    "label"       => "Media Source",
                    "default"     => 'feature_image',
                    "alignment"   => "vertical",
                    'holderClass' => "rt-feature-image-option tpg-hidden",
                    "options"     => $this->rtMediaSource()
                ),
                'tpg_image_type'                   => array(
                    "type"        => "radio",
                    "label"       => __("Image Type", 'the-post-grid-pro'),
                    "alignment"   => "vertical",
                    'holderClass' => "rt-feature-image-option tpg-hidden",
                    "default"     => 'normal',
                    "options"     => $this->get_image_types()
                ),
                'tpg_title_limit'                  => array(
                    "type"        => "number",
                    "label"       => esc_html__("Title limit", 'the-post-grid-pro'),
                    "description" => esc_html__("Title limit only integer number is allowed, Leave it blank for full title.", 'the-post-grid-pro')
                ),
                'tpg_title_limit_type'             => array(
                    "type"      => "radio",
                    "label"     => esc_html__("Title limit type", 'the-post-grid-pro'),
                    "alignment" => "vertical",
                    "default"   => 'character',
                    "options"   => $this->get_limit_type(),
                ),
                'excerpt_limit'                    => array(
                    "type"        => "number",
                    "label"       => esc_html__("Excerpt limit", 'the-post-grid-pro'),
                    "description" => esc_html__("Excerpt limit only integer number is allowed, Leave it blank for full excerpt.", 'the-post-grid-pro')
                ),
                'tgp_excerpt_type'                 => array(
                    "type"      => "radio",
                    "label"     => esc_html__("Excerpt Type", 'the-post-grid-pro'),
                    "alignment" => "vertical",
                    "default"   => 'character',
                    "options"   => $this->get_limit_type(),
                ),
                'tgp_excerpt_more_text'            => array(
                    "type"  => "text",
                    "label" => "Excerpt more text"
                ),
                'tgp_read_more_text'               => array(
                    "type"  => "text",
                    "label" => "Read more text"
                ),
                'tgp_not_found_text'               => array(
                    "type"    => "text",
                    "default" => __("No post found", 'the-post-grid-pro'),
                    "label"   => "Not found text"
                ),
                'margin_option'                    => array(
                    "type"        => "radio",
                    "label"       => "Margin",
                    "alignment"   => "vertical",
                    "description" => "Select the margin for layout",
                    "default"     => "default",
                    "options"     => $this->scMarginOpt()
                ),
                'grid_style'                       => array(
                    "type"        => "radio",
                    "label"       => "Grid style",
                    "alignment"   => "vertical",
                    "description" => "Select grid style for layout",
                    "default"     => "even",
                    "options"     => $this->scGridOpt()
                ),
                'link_to_detail_page'              => array(
                    "type"      => "radio",
                    "label"     => "Link To Detail Page",
                    "alignment" => "vertical",
                    "default"   => 'yes',
                    "options"   => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                'detail_page_link_type'            => array(
                    "type"        => "radio",
                    "label"       => "Detail page link type",
                    'holderClass' => "detail-page-link-type tpg-hidden",
                    "alignment"   => "vertical",
                    "default"     => "popup",
                    "options"     => array(
                        'popup'    => "PopUp",
                        'new_page' => "New Page"
                    )
                ),
                'popup_type'                       => array(
                    "type"        => "radio",
                    "label"       => "PopUp Type",
                    'holderClass' => "popup-type tpg-hidden",
                    "alignment"   => "vertical",
                    "default"     => "single",
                    "options"     => array(
                        'single' => "Single PopUp",
                        'multi'  => "Multi PopUp",
                    )
                ),
                'link_target'                      => array(
                    "type"        => "radio",
                    "label"       => "Link Target",
                    'holderClass' => "tpg-link-target tpg-hidden",
                    "alignment"   => 'vertical',
                    "options"     => array(
                        ''       => 'Same Window',
                        '_blank' => 'New Window'
                    )
                ),
                'restriction_user_role'            => array(
                    "type"        => "select",
                    "label"       => "Content will be visible for",
                    "class"       => "rt-select2",
                    "multiple"    => true,
                    "blank"       => "Allowed for all",
                    "description" => "Leave it blank for all",
                    "options"     => rtTPG()->getAllUserRoles()
                ),
                'default_preview_image'            => array(
                    "type"        => "image",
                    "label"       => "Default preview  image",
                    "description" => "Add an image for default preview"
                )
            );
        }


        function scMarginOpt() {
            return array(
                'default' => "Bootstrap default",
                'no'      => "No Margin"
            );
        }

        function scGridType() {
            return array(
                'even'    => "Even Grid",
                'masonry' => "Masonry"
            );
        }

        function rtTpgSettingsDetailFieldSelection() {
            $settings = get_option(rtTPG()->options['settings']);

            $fields = array(
                "popup_fields" => array(
                    'type'      => 'checkbox',
                    'label'     => 'Field Selection',
                    'id'        => 'popup-fields',
                    'alignment' => 'vertical',
                    'multiple'  => true,
                    'options'   => rtTPG()->detailAvailableFields(),
                    'value'     => isset($settings['popup_fields']) ? $settings['popup_fields'] : array()
                )
            );
            $cf = rtTPG()->checkWhichCustomMetaPluginIsInstalled();
            if ($cf) {
                $plist = rtTPG()->getCFPluginList();
                $pName = !empty($plist[$cf]) ? $plist[$cf] : " - ";
                $fields['cf_group'] = array(
                    "type"        => "checkbox",
                    "name"        => "cf_group",
                    "holderClass" => "tpg-hidden cfs-fields cf-group",
                    "label"       => "Custom Field group " . " ({$pName})",
                    "multiple"    => true,
                    "alignment"   => "vertical",
                    "id"          => "cf_group",
                    "options"     => rtTPG()->get_groups_by_post_type('all'),
                    "value"       => isset($settings['cf_group']) ? $settings['cf_group'] : array()
                );
                $fields['cf_hide_empty_value'] = array(
                    "type"        => "checkbox",
                    "name"        => "cf_hide_empty_value",
                    "holderClass" => "tpg-hidden cfs-fields",
                    "label"       => "Hide field with empty value",
                    "value"       => !empty($settings['cf_hide_empty_value']) ? 1 : 0
                );
                $fields['cf_show_only_value'] = array(
                    "type"        => "checkbox",
                    "name"        => "cf_show_only_value",
                    "holderClass" => "tpg-hidden cfs-fields",
                    "label"       => "Show only value of field",
                    "description" => "By default both name & value of field is shown",
                    "value"       => !empty($settings['cf_show_only_value']) ? 1 : 0
                );
                $fields['cf_hide_group_title'] = array(
                    "type"        => "checkbox",
                    "name"        => "cf_hide_group_title",
                    "holderClass" => "tpg-hidden cfs-fields",
                    "label"       => "Hide group title",
                    "value"       => !empty($settings['cf_hide_group_title']) ? 1 : 0
                );
            }

            return $fields;
        }

        function detailAvailableFields() {

            $fields = $this->rtTPGItemFields();
            $inserted = array(
                'feature_img' => 'Feature Image',
                'content'     => 'Content'
            );
            unset($fields['excerpt']);
            unset($fields['read_more']);
            unset($fields['comment_count']);
            $offset = array_search('title', array_keys($fields)) + 1;
            $newFields = array_slice($fields, 0, $offset, true) + $inserted + array_slice($fields,
                    $offset, null, true);
            $newFields['social_share'] = "Social Share";

            return $newFields;
        }

        function rtTPGStyleFields() {

            return array(
                'parent_class'                       => array(
                    "type"        => "text",
                    "label"       => "Parent class",
                    "class"       => "medium-text",
                    "description" => "Parent class for adding custom css"
                ),
                'primary_color'                      => array(
                    "type"    => "text",
                    "label"   => "Primary Color",
                    "class"   => "rt-color",
                    "default" => "#0367bf"
                ),
                'button_bg_color'                    => array(
                    "type"  => "text",
                    "name"  => "button_bg_color",
                    "label" => "Button background color",
                    "class" => "rt-color"
                ),
                'button_hover_bg_color'              => array(
                    "type"  => "text",
                    "name"  => "button_hover_bg_color",
                    "label" => "Button hover background color",
                    "class" => "rt-color"
                ),
                'button_active_bg_color'             => array(
                    "type"  => "text",
                    "label" => "Button active background color",
                    "class" => "rt-color"
                ),
                'button_border_color'                => array(
                    "type"  => "text",
                    "label" => "Button border color",
                    "class" => "rt-color"
                ),
                'button_text_bg_color'               => array(
                    "type"  => "text",
                    "label" => "Button text color",
                    "class" => "rt-color"
                ),
                'button_hover_text_color'            => array(
                    "type"  => "text",
                    "label" => "Button hover text color",
                    "class" => "rt-color"
                ),
                'tgp_gutter'                         => array(
                    'type'        => 'number',
                    'label'       => __('Gutter / Padding', 'the-post-grid-pro'),
                    'description' => __("Unit will be pixel, No need to give any unit. Only integer value will be valid.<br> Leave it blank for default",
                        'the-post-grid-pro')
                ),
                'overlay_color'                      => array(
                    "type"  => "text",
                    "label" => "Overlay color",
                    "class" => "rt-color"
                ),
                'overlay_opacity'                    => array(
                    "type"        => "select",
                    "label"       => "Overlay opacity",
                    "class"       => "rt-select2",
                    "default"     => .8,
                    "options"     => rtTPG()->overflowOpacity(),
                    "description" => __("Overlay opacity use only positive integer value", 'the-post-grid-pro')
                ),
                'overlay_padding'                    => array(
                    "type"        => "number",
                    "label"       => "Overlay top padding",
                    "class"       => "small-text",
                    "description" => __("Overlay top padding use only positive integer value, e.g : 20 (with out postfix like px, em, % etc). it will displayed by %",
                        'the-post-grid-pro')
                ),
                'tpg_read_more_button_border_radius' => array(
                    "type"        => "number",
                    "class"       => "small-text",
                    "label"       => esc_html__("Read more button border radius", "the-post-grid-pro"),
                    "description" => __("Leave it blank for default", 'the-post-grid-pro')
                ),
                'tpg_title_position'                 => array(
                    "type"        => "select",
                    "label"       => esc_html__("Title Position (Above or Below image)", "the-post-grid-pro"),
                    "class"       => "rt-select2 ",
                    "blank"       => esc_html__("Default", "the-post-grid-pro"),
                    "options"     => array(
                        'above' => esc_html__("Above image", "the-post-grid-pro"),
                        'below' => esc_html__("Below image", "the-post-grid-pro"),
                    ),
                    "description" => __("<span style='color:red'>Only Layout 1, Layout 12, Layout 14, Isotope1, Isotope8, Isotope10, Carousel Layout 1, Carousel Layout 8, Carousel Layout 10</span>", 'the-post-grid-pro')
                )
            );

        }

        function itemFields() {

            $fields = array(
                'item_fields' => array(
                    "type"      => "checkbox",
                    "name"      => "item_fields",
                    "label"     => "Field selection",
                    "id"        => "item-fields",
                    "multiple"  => true,
                    "alignment" => "vertical",
                    "default"   => array_keys($this->rtTPGItemFields()),
                    "options"   => $this->rtTPGItemFields()
                )
            );
            if ($cf = rtTPG()->checkWhichCustomMetaPluginIsInstalled()) {
                global $post;
                $post_type = get_post_meta($post->ID, 'tpg_post_type', true);
                $plist = rtTPG()->getCFPluginList();
                $fields['cf_group'] = array(
                    "type"        => "checkbox",
                    "name"        => "cf_group",
                    "holderClass" => "tpg-hidden cf-fields cf-group",
                    "label"       => "Custom Field group " . " ({$plist[$cf]})",
                    "multiple"    => true,
                    "alignment"   => "vertical",
                    "id"          => "cf_group",
                    "options"     => rtTPG()->get_groups_by_post_type($post_type, $cf)
                );
                $fields['cf_hide_empty_value'] = array(
                    "type"        => "checkbox",
                    "name"        => "cf_hide_empty_value",
                    "holderClass" => "tpg-hidden cf-fields",
                    "label"       => "Hide field with empty value",
                    "default"     => 1
                );
                $fields['cf_show_only_value'] = array(
                    "type"        => "checkbox",
                    "name"        => "cf_show_only_value",
                    "holderClass" => "tpg-hidden cf-fields",
                    "label"       => "Show only value of field",
                    "description" => "By default both name & value of field is shown"
                );
                $fields['cf_hide_group_title'] = array(
                    "type"        => "checkbox",
                    "name"        => "cf_hide_group_title",
                    "holderClass" => "tpg-hidden cf-fields",
                    "label"       => "Hide group title"
                );
            }

            return $fields;
        }

        function getCFPluginList() {
            return array(
                'acf' => "Advanced Custom Field"
            );
        }

        function rtMediaSource() {
            return array(
                "feature_image" => "Feature Image",
                "first_image"   => "First Image from content"
            );
        }

        function get_image_types() {
            return array(
                'normal' => "Normal",
                'circle' => "Circle"
            );
        }

        function get_limit_type() {
            $types = array(
                'character' => "Character",
                'word'      => "Word"
            );

            return apply_filters('tpg_limit_type', $types);
        }

        function scColumns() {
            return array(
                1 => "Column 1",
                2 => "Column 2",
                3 => "Column 3",
                4 => "Column 4",
                5 => "Column 5",
                6 => "Column 6"
            );
        }

        function tgp_filter_list() {
            return array(
                '_taxonomy_filter' => __('Taxonomy filter', "the-post-grid-pro"),
                '_author_filter'   => __('Author filter', "the-post-grid-pro"),
                '_order_by'        => __('Order - Sort retrieved posts by parameter', "the-post-grid-pro"),
                '_sort_order'      => __('Sort Order - Designates the ascending or descending order of the "orderby" parameter', "the-post-grid-pro"),
                '_search'          => __("Search filter", "the-post-grid-pro")
            );
        }

        function overflowOpacity() {
            return array(
                1 => '10%',
                2 => '20%',
                3 => '30%',
                4 => '40%',
                5 => '50%',
                6 => '60%',
                7 => '70%',
                8 => '80%',
                9 => '90%',
            );
        }

        function rtTPGLayouts() {
            $layouts = array();
            $layouts['layout1'] = __("Layout 1", "the-post-grid-pro");
            $layouts['layout2'] = __("Layout 2", "the-post-grid-pro");
            $layouts['layout3'] = __("Layout 3", "the-post-grid-pro");
            $layouts['layout4'] = __("Layout 4", "the-post-grid-pro");
            $layouts['layout5'] = __("Layout 5", "the-post-grid-pro");
            $layouts['layout6'] = __("Layout 6", "the-post-grid-pro");
            $layouts['layout7'] = __("Layout 7", "the-post-grid-pro");
            $layouts['layout8'] = __("Layout 8", "the-post-grid-pro");
            $layouts['layout9'] = __("Layout 9", "the-post-grid-pro");
            $layouts['layout10'] = __("Layout 10", "the-post-grid-pro");
            $layouts['layout11'] = __("Layout 11", "the-post-grid-pro");
            $layouts['layout12'] = __("Layout 12", "the-post-grid-pro");
            $layouts['layout13'] = __("Layout 13", "the-post-grid-pro");
            $layouts['layout14'] = __("Layout 14", "the-post-grid-pro");
            $layouts['layout15'] = __("Layout 15", "the-post-grid-pro");
            $layouts['layout16'] = __("Layout 16", "the-post-grid-pro");
            $layouts['layout17'] = __("Layout 17 Gallery layout", "the-post-grid-pro");
            $layouts['offset01'] = __("Offset 01", "the-post-grid-pro");
            $layouts['offset02'] = __("Offset 02", "the-post-grid-pro");
            $layouts['offset03'] = __("Offset 03", "the-post-grid-pro");
            $layouts['offset04'] = __("Offset 04", "the-post-grid-pro");
            $layouts['isotope1'] = __("Isotope Layout 1", "the-post-grid-pro");
            $layouts['isotope2'] = __("Isotope Layout 2", "the-post-grid-pro");
            $layouts['isotope3'] = __("Isotope Layout 3", "the-post-grid-pro");
            $layouts['isotope4'] = __("Isotope Layout 4", "the-post-grid-pro");
            $layouts['isotope5'] = __("Isotope Layout 5", "the-post-grid-pro");
            $layouts['isotope6'] = __("Isotope Layout 6", "the-post-grid-pro");
            $layouts['isotope7'] = __("Isotope Layout 7", "the-post-grid-pro");
            $layouts['isotope8'] = __("Isotope Layout 8", "the-post-grid-pro");
            $layouts['isotope9'] = __("Isotope Layout 9", "the-post-grid-pro");
            $layouts['isotope10'] = __("Isotope Layout 10", "the-post-grid-pro");
            $layouts['isotope11'] = __("Isotope Layout 11", "the-post-grid-pro");
            $layouts['isotope12'] = __("Isotope Layout 12", "the-post-grid-pro");
            $layouts['carousel1'] = __("Carousel Layout 1", "the-post-grid-pro");
            $layouts['carousel2'] = __("Carousel Layout 2", "the-post-grid-pro");
            $layouts['carousel3'] = __("Carousel Layout 3", "the-post-grid-pro");
            $layouts['carousel4'] = __("Carousel Layout 4", "the-post-grid-pro");
            $layouts['carousel5'] = __("Carousel Layout 5", "the-post-grid-pro");
            $layouts['carousel6'] = __("Carousel Layout 6", "the-post-grid-pro");
            $layouts['carousel7'] = __("Carousel Layout 7", "the-post-grid-pro");
            $layouts['carousel8'] = __("Carousel Layout 8", "the-post-grid-pro");
            $layouts['carousel9'] = __("Carousel Layout 9", "the-post-grid-pro");
            $layouts['carousel10'] = __("Carousel Layout 10", "the-post-grid-pro");
            $layouts['carousel11'] = __("Carousel Layout 11", "the-post-grid-pro");
            $layouts['carousel12'] = __("Carousel Layout 12", "the-post-grid-pro");
            if (class_exists('Easy_Digital_Downloads')) {
                $layouts['edd1'] = __("EDD Layout 1", "the-post-grid-pro");
                $layouts['edd2'] = __("EDD Layout 2", "the-post-grid-pro");
                $layouts['edd3'] = __("EDD Layout 3", "the-post-grid-pro");
                $layouts['edd-carousel1'] = __("EDD Carousel Layout 1", "the-post-grid-pro");
                $layouts['edd-carousel2'] = __("EDD Carousel Layout 2", "the-post-grid-pro");
                $layouts['edd-isotope1'] = __("EDD Isotope Layout 1", "the-post-grid-pro");
                $layouts['edd-isotope2'] = __("EDD Isotope Layout 2", "the-post-grid-pro");
            }
            if (class_exists('WooCommerce')) {
                $layouts['wc1'] = __("WooCommerce Layout 1", "the-post-grid-pro");
                $layouts['wc2'] = __("WooCommerce Layout 2", "the-post-grid-pro");
                $layouts['wc3'] = __("WooCommerce Layout 3", "the-post-grid-pro");
                $layouts['wc4'] = __("WooCommerce Layout 4", "the-post-grid-pro");
                $layouts['wc-carousel1'] = __("WooCommerce Carousel Layout 1", "the-post-grid-pro");
                $layouts['wc-carousel2'] = __("WooCommerce Carousel Layout 2", "the-post-grid-pro");
                $layouts['wc-isotope1'] = __("WooCommerce Isotope Layout 1", "the-post-grid-pro");
                $layouts['wc-isotope2'] = __("WooCommerce Isotope Layout 2", "the-post-grid-pro");
            }

            return apply_filters('tpg_layouts', $layouts);
        }

        function rtTPGItemFields() {

            $items = array(
                'title'         => "Title",
                'excerpt'       => "Excerpt",
                'read_more'     => "Read More",
                'post_date'     => "Post Date",
                'author'        => "Author",
                'categories'    => "Categories",
                'tags'          => "Tags",
                'comment_count' => "Comment count",
                'social_share'  => "Social share"
            );
            if (class_exists('WooCommerce')) {
                $items['rating'] = __("Rating (WooCommerce)", "the-post-grid-pro");
            }
            if ($cf = rtTPG()->checkWhichCustomMetaPluginIsInstalled()) {
                $items['cf'] = "Custom Fields";
            }

            return $items;
        }

        function postLoadingType() {
            return array(
                'pagination'      => "Pagination",
                'pagination_ajax' => "Ajax Number Pagination ( Only for Grid )",
                'load_more'       => "Load more button (by ajax loading)",
                'load_on_scroll'  => "Load more on scroll (by ajax loading)",
            );
        }

        function scGridOpt() {
            return array(
                'even'    => "Even",
                'masonry' => "Masonry"
            );
        }

        function extraStyle() {
            return array(
                'title'       => "Title",
                'title_hover' => "Title hover",
                'excerpt'     => "Excerpt",
                'meta_data'   => "Meta Data"
            );
        }

        function scFontSize() {
            $num = array();
            for ($i = 10; $i <= 50; $i++) {
                $num[$i] = $i . "px";
            }

            return $num;
        }

        function scAlignment() {
            return array(
                'left'    => "Left",
                'right'   => "Right",
                'center'  => "Center",
                'justify' => "Justify"
            );
        }

        function scReadMoreButtonPositionList() {
            return array(
                'left'   => "Left",
                'right'  => "Right",
                'center' => "Center"
            );
        }


        function scTextWeight() {
            return array(
                'normal'  => "Normal",
                'bold'    => "Bold",
                'bolder'  => "Bolder",
                'lighter' => "Lighter",
                'inherit' => "Inherit",
                'initial' => "Initial",
                'unset'   => "Unset",
                100       => '100',
                200       => '200',
                300       => '300',
                400       => '400',
                500       => '500',
                600       => '600',
                700       => '700',
                800       => '800',
                900       => '900',
            );
        }

        function imageCropType() {
            return array(
                'soft' => "Soft Crop",
                'hard' => "Hard Crop",
            );
        }

        function rt_filter_type() {
            return array(
                'dropdown' => "Dropdown",
                'button'   => "Button"
            );
        }


    }

endif;