<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Store Social Media Widget.
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/widgets
 * @author     Lindeni Mahlalela
 * @version    1.5.6
 * @extends    WC_Widget
 */
class WCV_Widget_Store_Social_Media extends WC_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'wcv widget_store_store_social_media';
		$this->widget_description = __( 'Shows social media icons.', 'wcvendors-pro' );
		$this->widget_id          = 'wcv_store_store_social_media';
		$this->widget_name        = __( 'WC Vendors Pro Store Social Media', 'wcvendors-pro' );
		$this->settings           = array(
			'title'           => array(
				'type'  => 'text',
				'std'   => __( 'Store Social Media', 'wcvendors-pro' ),
				'label' => __( 'Title', 'wcvendors-pro' ),
			),
			'show_facebook'   => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show Facebook', 'wcvendors-pro' ),
			),
			'show_googleplus' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show Google Plus', 'wcvendors-pro' ),
			),
			'show_instagram'  => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show Instagram', 'wcvendors-pro' ),
			),
			'show_linkedin'   => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show LinkedIn', 'wcvendors-pro' ),
			),
			'show_pinterest'  => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show Pinterest', 'wcvendors-pro' ),
			),
			'show_snapchat'   => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show Snapchat', 'wcvendors-pro' ),
			),
			'show_twitter'    => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show Twitter', 'wcvendors-pro' ),
			),
			'show_youtube'    => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show YouTube', 'wcvendors-pro' ),
			),
			'icon_size'       => array(
				'type'    => 'select',
				'std'     => 'sm',
				'label'   => __( 'Icon Size', 'wcvendors-pro' ),
				'options' => apply_filters(
					'wcv_social_widget_icon_sizes',
					array(
						'sm' => 'Small',
						'md' => 'Medium',
						'lg' => 'Large',
					)
				),
			),
			'heading'         => array(
				'type'  => 'text',
				'std'   => __( 'Like us on social media.', 'wcvendors-pro' ),
				'label' => __( 'Heading', 'wcvendors-pro' ),
			),
			'show_heading'    => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show Heading', 'wcvendors-pro' ),
			),
		);

		parent::__construct();
	}

	/**
	 * Output the social media icons widget.
	 *
	 * @see   WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @since 1.5.6
	 */
	public function widget( $args, $instance ) {
		global $post;

		if ( ! is_woocommerce() ) {
			return;
		}

		if ( ! $post ) return; 		

		if ( ! WCV_Vendors::is_vendor_page() && ! WCV_Vendors::is_vendor_product_page( $post->post_author ) ) {
			return;
		}

		if ( WCV_Vendors::is_vendor_page() ) {
			$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
			$vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );
		} elseif ( is_singular( 'product' ) && WCV_Vendors::is_vendor_product_page( $post->post_author ) ) {
			$vendor_id = $post->post_author;
		} else {
			if ( isset( $_GET['wcv_vendor_id'] ) ) {
				$vendor_id = $_GET['wcv_vendor_id'];
			}
		}

		if ( ! isset( $vendor_id ) ) {
			return;
		}

		$facebook_url       = get_user_meta( $vendor_id, '_wcv_facebook_url', true );
		$googleplus_url     = get_user_meta( $vendor_id, '_wcv_googleplus_url', true );
		$instagram_username = get_user_meta( $vendor_id, '_wcv_instagram_username', true );
		$linkedin_url       = get_user_meta( $vendor_id, '_wcv_linkedin_url', true );
		$pinterest_url      = get_user_meta( $vendor_id, '_wcv_pinterest_url', true );
		$snapchat_username  = get_user_meta( $vendor_id, '_wcv_snapchat_username', true );
		$twitter_username   = get_user_meta( $vendor_id, '_wcv_twitter_username', true );
		$youtube_url        = get_user_meta( $vendor_id, '_wcv_youtube_url', true );

		$show_facebook   = isset( $instance['show_facebook'] ) ? $instance['show_facebook'] : $this->settings['show_facebook']['std'];
		$show_googleplus = isset( $instance['show_googleplus'] ) ? $instance['show_googleplus'] : $this->settings['show_googleplus']['std'];
		$show_instagram  = isset( $instance['show_instagram'] ) ? $instance['show_instagram'] : $this->settings['show_instagram']['std'];
		$show_linkedin   = isset( $instance['show_linkedin'] ) ? $instance['show_linkedin'] : $this->settings['show_linkedin']['std'];
		$show_pinterest  = isset( $instance['show_pinterest'] ) ? $instance['show_pinterest'] : $this->settings['show_pinterest']['std'];
		$show_snapchat   = isset( $instance['show_snapchat'] ) ? $instance['show_snapchat'] : $this->settings['show_snapchat']['std'];
		$show_twitter    = isset( $instance['show_twitter'] ) ? $instance['show_twitter'] : $this->settings['show_twitter']['std'];
		$show_youtube    = isset( $instance['show_youtube'] ) ? $instance['show_youtube'] : $this->settings['show_youtube']['std'];

		$icon_size = isset( $instance['icon_size'] ) ? $instance['icon_size'] : $this->settings['icon_size']['std'];

		$show_heading = isset( $instance['show_heading'] ) ? $instance['show_heading'] : $this->settings['show_heading']['std'];
		$heading      = isset( $instance['heading'] ) ? $instance['heading'] : $this->settings['heading']['std'];

		$this->widget_start( $args, $instance );

		echo $show_heading ? '<p class="wcv-widget-description-heading">' . esc_attr( $heading ) . '</p>' : '';

		?>
		<ul class="social-icons">
		<?php if ( $facebook_url != '' && $show_facebook ) { ?>
			<li>
			<a href="<?php echo $facebook_url; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-<?php echo $icon_size; ?>">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-facebook-square"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $instagram_username != '' && $show_instagram ) { ?>
			<li>
			<a href="//instagram.com/<?php echo $instagram_username; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-<?php echo $icon_size; ?>">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-instagram"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $twitter_username != '' && $show_twitter ) { ?>
			<li>
			<a href="//twitter.com/<?php echo $twitter_username; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-<?php echo $icon_size; ?>">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-twitter-square"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $googleplus_url != '' && $show_googleplus ) { ?>
			<li>
			<a href="<?php echo $googleplus_url; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-<?php echo $icon_size; ?>">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-google-plus"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $pinterest_url != '' && $show_pinterest ) { ?>
			<li>
			<a href="<?php echo $pinterest_url; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-<?php echo $icon_size; ?>">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-pinterest-square"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $youtube_url != '' && $show_youtube ) { ?>
			<li>
			<a href="<?php echo $youtube_url; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-<?php echo $icon_size; ?>">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-youtube-square"></use>
				</svg>
			</a>
			</li><?php } ?>


		<?php if ( $linkedin_url != '' && $show_linkedin ) { ?>
			<li>
			<a href="<?php echo $linkedin_url; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-<?php echo $icon_size; ?>">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-linkedin"></use>
				</svg>
			</a>
			</li><?php } ?>

		<?php if ( $snapchat_username != '' && $show_snapchat ) { ?>
			<li>
			<a href="//www.snapchat.com/add/<?php echo $snapchat_username; ?>" target="_blank">
				<svg class="wcv-icon wcv-icon-<?php echo $icon_size; ?>">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-snapchat"></use>
				</svg>
			</a>
			</li><?php } ?>
		</ul>
		<?php

		$this->widget_end( $args );
	}
}
