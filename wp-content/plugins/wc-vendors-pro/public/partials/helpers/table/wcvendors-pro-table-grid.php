<?php

/**
 * Table data template
 *
 * This file is used to display the table data
 *
 * @link       http://www.wcvendors.com
 * @since      1.0.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/helpers/table
 */

global $wp;
$isDashboard = $wp->request === 'dashboard';
?>

<div class="row <?php if (!$isDashboard) : ?> product-header <?php endif; ?>">
    <div class="col">
        <?php if ($wp->request !== 'dashboard') : ?>
            <?php $search = isset($_GET['wcv-search']) ? $_GET['wcv-search'] : ''; ?>

            <span class="product-header__title">
                <?php if (!empty($search)) : ?>
                    <?php printf(__('Search results for "%s" (%s)', 'wcvendors-pro'), $search, count($this->rows)); ?>
                <?php else : ?>
                    All Products (<?php echo count($this->rows); ?>)
                <?php endif; ?>
            </span>
        <?php endif; ?>
    </div>
</div>
<div class="row mb-3">
    <div class="col">
        <div class="blue-border-1"></div>
    </div>
</div>
<div class="row">
    <?php if ($isDashboard) {
        $limit = 3;
    }
    ?>
    <?php foreach ($this->rows as $row) : ?>
        <?php
        if ($isDashboard && $limit == 0) {
            break;
        }
        if (isset($row->action_before)) {
            echo $row->action_before;
        }
        $product = wc_get_product($row->ID) ?: wc_get_product($row->product_id);
        if ($product) {
            $editLink = WCVendors_Pro_Product_Controller::get_product_edit_link($product->get_id());
            $deleteLink = WCVendors_Pro_Dashboard::get_dashboard_page_url('product/delete/' . $product->get_id());
            $viewLink = get_permalink($product->get_id());
            ?>

            <div class="col-4">
                <div class="card product-card">
                    <?php echo get_the_post_thumbnail($product->get_id(), array(220, 220), ['class' => 'card-img-top']); ?>
                    <div class="product-card__body">
                        <div class="product-card__title"><?php echo $product->get_title(); ?></div>
                        <div class="product-card__price"><?php echo wc_price(wc_get_price_to_display($product) . $product->get_price_suffix()); ?></div>

                        <div class="product-card__actions d-flex">
                            <div class="col-7 p-0">
                                <a href="<?php echo $editLink; ?>" class="btn btn--secondary w-100">Edit</a>
                            </div>
                            <div class="col p-0 d-flex justify-content-center product-card__actions-delete">
                                <a href="#" class="product-delete" data-url="<?php echo $deleteLink; ?>"
                                   data-toggle="modal" data-target="#deleteProductModal" data-product="<?php echo $product->get_title(); ?> ">
                                    <i class="fa fa-trash"></i></a>
                            </div>
                            <div class="col p-0 d-flex justify-content-center product-card__actions-view">
                                <a href="<?php echo $viewLink; ?>" target="_blank"><i class="fa fa-eye"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($isDashboard) {
                $limit--;
            }
        ?>
    <?php } else { //var_dump($row);
        }
    ?>
    <?php endforeach; ?>
</div>
