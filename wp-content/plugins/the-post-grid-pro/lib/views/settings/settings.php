<div class="wrap rttpg-wrapper">
    <div id="upf-icon-edit-pages" class="icon32 icon32-posts-page"><br/></div>
    <h2><?php _e( 'The Post Grid Settings', 'the-post-grid-pro' ); ?></h2>
    <h3><?php _e( 'General settings', 'the-post-grid-pro' ); ?>
        <a style="margin-left: 15px; font-size: 15px;"
           href="http://demo.radiustheme.com/wordpress/plugins/the-post-grid/"
           target="_blank"><?php _e( 'Documentation', 'the-post-grid-pro' ) ?></a>
    </h3>

    <div class="rt-setting-wrapper">
        <div class="rt-response"></div>
        <form id="rt-tpg-settings-form">
			<?php
			$settings = get_option( rtTPG()->options['settings'] );
			$last_tab = isset( $settings['_tpg_last_active_tab'] ) ? trim( $settings['_tpg_last_active_tab'] ) : 'popup-fields';
			$html     = null;
			$html     .= '<div id="settings-tabs" class="rt-tabs rt-tab-container">';
			$html     .= sprintf( '<ul class="tab-nav rt-tab-nav">
                                <li%s><a href="#popup-fields">%s</a></li>
                                <li%s><a href="#social-share">%s</a></li>
                                <li%s><a href="#custom-script">%s</a></li>
                                <li%s><a href="#other-settings">%s</a></li>
                                <li%s><a href="#plugin-license">%s</a></li>
                              </ul>',
				$last_tab == "popup-fields" ? ' class="active"' : '',
				__( 'PopUp field selection', 'the-post-grid-pro' ),
				$last_tab == "social-share" ? ' class="active"' : '',
				__( 'Social Share', 'the-post-grid-pro' ),
				$last_tab == "custom-script" ? ' class="active"' : '',
				__( 'Custom Script', 'the-post-grid-pro' ),
				$last_tab == "other-settings" ? ' class="active"' : '',
				__( 'Other Settings', 'the-post-grid-pro' ),
				$last_tab == "plugin-license" ? ' class="active"' : '',
				__( 'Plugin License', 'the-post-grid-pro' )
			);

			$html .= sprintf( '<div id="popup-fields" class="rt-tab-content"%s>', $last_tab == "popup-fields" ? ' style="display:block"' : '' );
			$html .= rtTPG()->rtFieldGenerator( rtTPG()->rtTpgSettingsDetailFieldSelection() );
			$html .= '</div>';

			$html .= sprintf( '<div id="social-share" class="rt-tab-content"%s>', $last_tab == "social-share" ? ' style="display:block"' : '' );
			$html .= rtTPG()->rtFieldGenerator( rtTPG()->rtTPGSettingsSocialShareFields() );
			$html .= '</div>';

			$html .= sprintf( '<div id="custom-script" class="rt-tab-content"%s>', $last_tab == "custom-script" ? ' style="display:block"' : '' );
			$html .= rtTPG()->rtFieldGenerator( rtTPG()->rtTPGSettingsCustomScriptFields() );
			$html .= '</div>';

			$html .= sprintf( '<div id="other-settings" class="rt-tab-content"%s>', $last_tab == "other-settings" ? ' style="display:block"' : '' );
			$html .= rtTPG()->rtFieldGenerator( rtTPG()->rtTPGSettingsOtherSettingsFields(), true );
			$html .= '</div>';

			$html .= sprintf( '<div id="plugin-license" class="rt-tab-content"%s>', $last_tab == "plugin-license" ? ' style="display:block"' : '' );
			$html .= rtTPG()->rtFieldGenerator( rtTPG()->rtTPGLicenceField() );
			$html .= '</div>';
			$html .= sprintf( '<input type="hidden" id="_tpg_last_active_tab" name="_tpg_last_active_tab"  value="%s"/>', $last_tab );
			$html .= '</div>';

			echo $html;
			?>
            <p class="submit-wrap"><input type="submit" name="submit" class="button button-primary rtSaveButton"
                                          value="Save Changes"></p>

			<?php wp_nonce_field( rtTPG()->nonceText(), rtTPG()->nonceId() ); ?>
        </form>

        <div class="rt-response"></div>
    </div>
</div>
