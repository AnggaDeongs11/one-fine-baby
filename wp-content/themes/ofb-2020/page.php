<?php get_header(); ?>
<?php

$filter_search = filter_input(INPUT_GET, 'search');
if ($filter_search !== null):
    ?>
    <ul class="products">
        <?php
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 12
        );
        $loop = new WP_Query($args);
        if ($loop->have_posts()) {
            while ($loop->have_posts()) : $loop->the_post();
                wc_get_template_part('content', 'product');
            endwhile;
        } else {
            echo __('No products found');
        }
        wp_reset_postdata();
        ?>
    </ul><!--/.products-->
    <?php
endif;
?>
<div id="primary">
    <div id="content" role="main">
        <?php if (have_posts()) : ?>

            <?php while (have_posts()) : the_post(); ?>

                <article class="post">
                    <div class="the-content container">
                        <div class="row-fluid">
                            <div class="span12">    
                                <?php the_content(); ?>
                                <?php wp_link_pages(); ?>
                            </div>
                        </div>
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