<?php get_header(); ?>

	<div id="primary" class="row-fluid">
		<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<article class="post">
						
						<div class="the-content">
							<?php the_content(); ?>
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
