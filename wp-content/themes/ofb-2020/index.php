<?php
$pv_shop_name = get_query_var('vendor_shop');
$user = get_users(array('meta_key' => 'pv_shop_slug', 'meta_value' => $pv_shop_name));
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type' => 'product',
    'author' => $user[0]->ID
);
$p = new WP_Query($args);
//$products = $query->get_products();
//    var_dump($product->found_posts);
if ($pv_shop_name):
    get_header();
    ?>

    <div id="primary" class="row-fluid">
        <div id="content" role="main" class="span12">
            <div class="woocommerce column-3">
                <?php if ($p->have_posts()) : ?>
                    <?php
                    do_action('woocommerce_before_main_content');
                    ?>
                    <header class="woocommerce-products-header">
                        <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                            <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
                        <?php endif; ?>

                        <?php
                        do_action('woocommerce_archive_description');
                        ?>
                    </header>
                    <?php
                    if (woocommerce_product_loop()) {
                        do_action('woocommerce_before_shop_loop');

                        woocommerce_product_loop_start();

                        while ($p->have_posts()) : $p->the_post();

                            wc_get_template('content-product.php');
                        endwhile;
                        woocommerce_product_loop_end();


                        do_action('woocommerce_after_shop_loop');
                    } else {

                        do_action('woocommerce_no_products_found');
                    }

                    do_action('woocommerce_after_main_content');
                    ?>
                <?php else : ?>

                    <article class="post error">
                        <h1 class="404">Nothing here!</h1>
                    </article>

                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
    get_footer();
else:
    ?>
    <?php get_header(); ?>

    <div id="primary" class="row-fluid">
        <div id="content" role="main" class="span8 offset2">
            <?php if (have_posts()) : ?>

                <?php while (have_posts()) : the_post(); ?>

                    <article class="post">

                        <?php the_post_thumbnail('large'); ?>

                        <h1 class="title">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h1>
                        <div class="post-meta">
                            <?php the_time('m/d/Y'); ?> | 
                            <?php if (comments_open()) : ?>
                                <span class="comments-link">
                                    <?php comments_popup_link(__('Comment', 'break'), __('1 Comment', 'break'), __('% Comments', 'break')); ?>
                                </span>
                            <?php endif; ?>

                        </div>

                        <div class="the-content">
                            <?php the_content('Continue...'); ?>

                            <?php wp_link_pages(); ?>
                        </div>

                        <div class="meta clearfix">
                            <div class="category"><?php echo get_the_category_list(); ?></div>
                            <div class="tags"><?php echo get_the_tag_list('| &nbsp;', '&nbsp;'); ?></div>
                        </div>

                    </article>

                <?php endwhile; ?>

                <div id="pagination" class="clearfix">
                    <div class="past-page"><?php previous_posts_link('newer'); ?></div>
                    <div class="next-page"><?php next_posts_link('older'); ?></div>
                </div>


            <?php else : ?>

                <article class="post error">
                    <h1 class="404">Nothing here!</h1>
                </article>

            <?php endif; ?>
        </div>
    </div>
    <?php get_footer();?>
<?php endif; ?>
