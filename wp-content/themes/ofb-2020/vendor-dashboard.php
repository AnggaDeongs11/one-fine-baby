<?php /* Template Name: Vendor Dashboard */ ?>

<?php get_header(); ?>

<?php global $wp; ?>

<div id="primary" class="container">
    <?php if (is_vendor_page()) : ?>
        <div class="row">
            <?php include_once('vendor-header.php'); ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <?php if (is_vendor_page()) : ?>
            <div class="vendor-sidebar col-lg-3">
                <div class="profile-image d-flex">
                    <img src="/wp-content/uploads/2019/10/profile-placeholder.png" class="align-self-end"/>
                </div>
                <?php  get_template_part( 'vendor-sidebar', 'page' ); ?>
            </div>
            <div class="col-12 col-lg-9 ">

                <img src="/wp-content/uploads/2019/10/banner.png" class="img-fluid"/>
        <?php endif; ?>
            <div class="col-12 ">
                <?php if (have_posts()) : ?>

                    <?php while (have_posts()) : the_post(); ?>
                        <article class="post">
                            <?php if (is_vendor_page()) : ?>
                            <div class="vendor-content">
                                <div class="row">
                                    <div class="col" style="margin-bottom: 40px;">
                                        <div class="vendor-content__page-title">
                                            <h2><?php echo get_page_title($object_id); ?></h2>
                                        </div>
                                        <div class="vendor-content__page-subtitle">
                                            <?php $subtitle = get_page_subtitle($object_id);
                                            if (!empty($subtitle)) :?>
                                                <h4><?php echo $subtitle; ?></h4>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php
                                    if ($wp->request === 'dashboard/product') :?>
                                    <div class="col">
                                        <div class="float-right d-flex flex-column flex-md-row" style="margin-top: 30px;">
                                            <a class="btn btn--primary" href="<?php echo get_site_url();?>/dashboard/product/edit" style="width: 200px;"><span>ADD PRODUCT</span></a>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                </div>
                                <div class="light-border-1 mb-4"></div>
                            <?php endif; ?>
                                <div class="row">
                                    <div class="col-12">
                                        <?php the_content(); ?>
                                        <?php wp_link_pages(); ?>
                                    </div>
                                </div>
                            <?php if (is_vendor_page()) : ?>
                            </div>
                        <?php endif; ?>
                        </article>

                    <?php endwhile; ?>

                <?php else : ?>

                    <article class="post error">
                        <h1 class="404">Nothing here!</h1>
                    </article>

                <?php endif; ?>
            </div>
    </div>
</div>

<?php if(is_vendor_page()) : ?>
<div class="modal fade vendor-modal" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div clas="modal-body__content">
                    <div class="modal-title mt-5 d-flex justify-content-center">
                        Are you sure you<br/>want to delete ?
                    </div>
                    <div class="modal-description justify-content-center">
                        <strong><span class="modal-description__product-name"></span></strong> will be permantly deleted.<br/>You will not be able to reverse this action.
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <a href="#" class="btn btn--primary btn-longest btn-thick my-2 modal-delete-link d-flex justify-content-center align-items-center">I'm sure</a>
                        <button type="button" class="btn btn-link btn-thick mb-5" data-dismiss="modal">I'm not sure</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php get_footer(); ?>
