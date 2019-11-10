<?php get_header(); ?>

	<div id="primary" class="row-fluid">
		<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<article class="post">
						
						<div class="the-content">
							<?php the_content(); ?>
							
							<?php wp_link_pages(); ?>
						</div>
						
						<div class="meta clearfix">
							<div class="category"><?php echo get_the_category_list(); ?></div>
							<div class="tags"><?php echo get_the_tag_list( '| &nbsp;', '&nbsp;' ); ?></div>
						</div>
						
					</article>

				<?php endwhile; ?>

			<?php else : ?>
				
				<article class="post error">
					<h1 class="404">Nothing here!</h1>
				</article>

			<?php endif; ?>

		</div>
	</div>
	
<?php get_footer(); ?>
