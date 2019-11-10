<?php
get_header();
global $post;
$settings       = get_option( rtTPG()->options['settings'] );
$layout         = ! empty( $settings['template_tag'] ) ? absint( $settings['template_tag'] ) : null;
$class          = ! empty( $settings['template_class'] ) ? " ".$settings['template_class'] : null;
?>
    <main id="rt-main" class="site-main<?php echo $class; ?>" role="main">
        <header class="page-header">
			<?php
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
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