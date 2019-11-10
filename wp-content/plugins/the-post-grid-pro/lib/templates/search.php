<?php
get_header();
global $post;
$settings       = get_option( rtTPG()->options['settings'] );
$layout         = ! empty( $settings['template_search'] ) ? absint( $settings['template_search'] ) : null;
$class          = ! empty( $settings['template_class'] ) ? " ".$settings['template_class'] : null;
?>
	<main id="rt-main" class="site-main<?php echo $class; ?>" role="main">
		<header class="page-header">
			<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentysixteen' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
		</header><!-- .page-header -->
		<div class="rt-single-container">
			<div class="rt-row">
				<?php
				if ( $layout ) {
					echo do_shortcode( '[the-post-grid id="' . $layout . '"]' );
				}

				?>
			</div>
		</div>
	</main>
<?php
get_footer();