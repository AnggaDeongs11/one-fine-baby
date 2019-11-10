<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */
if (!defined('ABSPATH')) {
    exit;
}
global $wp_query;
$vars = $wp_query->query_vars;
$gender = $_GET['filter_gender'];
$odrby = $_GET['orderby'];
$vendor_shop = get_query_var('vendor_shop');
$page_name = 'All Products';
if (isset($vars['pwb-brand'])) {
    $term = get_term_by('slug', $vars['pwb-brand'], 'pwb-brand');
    $page_name = $term->name;
} else if (isset($vars['product_cat'])) {
    $term = get_term_by('slug', $vars['product_cat'], 'product_cat');
    $page_name = $term->name;
}
?>
<div class="container">
    <?php if (is_single() === false && !$vendor_shop): ?>
        <div class="filter-sec">
            <div class="filter-sec-padding-box">
                <h1><?php echo $page_name; ?></h1>
                <?php woocommerce_result_count(); ?>
                <div class="accordion" style="border-bottom: none;">
                    <label for="tm" class="accordionitem" style="border-bottom: 1px solid #030753;"><h3>Sort by</h3></label>
                    <input type="checkbox" id="tm" checked/>
                    <!--<p class="hiddentext">-->
                    <form class="woocommerce-ordering hiddentext" method="get">
                        <select name="orderby" class="orderby st-select" aria-label="Shop order" id="orderby">
                            <option value="menu_order" <?php echo($odrby === null) ? 'selected' : ''; ?>>Best Match</option>
                            <option value="popularity" <?php echo($odrby == 'popularity') ? 'selected' : ''; ?>>Sort by popularity</option>
                            <option value="rating" <?php echo($odrby == 'rating') ? 'selected' : ''; ?>>Sort by average rating</option>
                            <option value="date" <?php echo($odrby === 'date') ? 'selected' : ''; ?>>Sort by latest</option>
                            <option value="price" <?php echo($odrby == 'price') ? 'selected' : ''; ?>>Sort by price: low to high</option>
                            <option value="price-desc" <?php echo($odrby == 'price-desc') ? 'selected' : ''; ?>>Sort by price: high to low</option>
                        </select>
                        <input type="hidden" name="paged" value="1">
                    </form>
                    <!--</p>-->
                </div>
                <div class="accordion">
                    <label for="tm1" class="accordionitem"><h3 style="width: 75%;display: inline-block;">Refine</h3><a href="<?php echo home_url('/all-products'); ?>" style="text-align: right;font-size: 14px;display: inline-block;width: 25%;text-decoration: underline;">Clear All</a></label>
                    <!--<input type="checkbox" id="tm1"/>-->
                    <!--<p class="hiddentext"></p>-->
                </div>
                <div class="accordion">
                    <?php
                    $category_slug = get_query_var('product_cat');
                    $parent_category = get_term_by('slug', $category_slug, 'product_cat');
                    ?>
                    <label for="tm2" class="accordionitem"><h4><?php echo (!$parent_category) ? 'Category' : 'Subcategory' ?></h4><i class="fas fa-chevron-down"></i></label>
                    <input type="checkbox" id="tm2" checked/>
                    <?php $exclude_categories = get_option('wcvendors_hide_categories_list'); ?>
                    <div class='hiddentext'>
                        <?php
                        $orderby = 'name';
                        $order = 'asc';
                        $hide_empty = false;
                        $term_children = get_term_children($parent_category->term_id, 'product_cat');
                        $ancestors = get_ancestors($parent_category->term_id, 'product_cat', 'taxonomy');
                        $term_ancestor = !empty($ancestors) ? $ancestors[count($ancestors) - 1] : $parent_category->term_id;
                        $main_categories = st_category_heirarchy();
                        $category_heirarchy = $main_categories[$term_ancestor];
                        if (!empty($category_heirarchy)):
                            ?>
                            <ul class="subcategory-level-1">
                                <?php
                                foreach ($category_heirarchy as $key => $val) :
                                    $category = get_term_by('id', $key, 'product_cat');
                                    ?>
                                    <li class="<?php echo(strtolower($category->slug) === $vars['product_cat']) ? 'active' : ''; ?>">
                                        <a href="<?php echo get_term_link($category); ?>" > 
                                            <?php
                                            if (strtolower($category->slug) === $vars['product_cat'] || in_array($category->term_id, $ancestors)):
                                                echo '<i class="fa fa-check-circle-o"></i>';
                                            else:
                                                echo '<i class="fa fa-circle-o"></i>';
                                            endif;
                                            echo $category->name;
                                            ?>
                                        </a>
                                        <?php if (!empty($val) && ($category->slug === $vars['product_cat'] || in_array($category->term_id, $ancestors))): ?>
                                            <ul class="subcategory-level-2">
                                                <?php
                                                foreach ($val as $key => $sub_val) :
                                                    $category = get_term_by('id', $key, 'product_cat');
                                                    ?>
                                                    <li class="<?php echo(strtolower($category->slug) === $vars['product_cat']) ? 'active' : ''; ?>">
                                                        <a href="<?php echo get_term_link($category); ?>" > 
                                                            <?php
                                                            if (strtolower($category->slug) === $vars['product_cat']):
                                                                echo '<i class="fa fa-check-circle-o"></i>';
                                                            else:
                                                                echo '<i class="fa fa-circle-o"></i>';
                                                            endif;
                                                            echo $category->name;
                                                            ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>

                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                    <?php
                                endforeach;
                                ?>
                            </ul>

                        <?php else:
                            ?>
                            <ul class="subcategory-level-1">
                                <?php
                                foreach ($main_categories as $key => $cat) :
                                    $category = get_term_by('id', $key, 'product_cat');
                                    ?>
                                    <li class="<?php echo(strtolower($category->slug) === $vars['product_cat']) ? 'active' : ''; ?>">
                                        <a href="<?php echo get_term_link($category); ?>" > 
                                            <?php
                                            if (strtolower($category->slug) === $vars['product_cat']):
                                                echo '<i class="fa fa-check-circle-o"></i>';
                                            else:
                                                echo '<i class="fa fa-circle-o"></i>';
                                            endif;
                                            echo $category->name;
                                            ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>

                            </ul>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
                <div class="accordion">
                    <label for="tm3" class="accordionitem"><h4>Gender</h4><i class="fas fa-chevron-down"></i></label>
                    <input type="checkbox" id="tm3"/>
                    <div class="hiddentext">
                        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("Gender")) : ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="accordion">
                    <label for="tm4" class="accordionitem"><h4>Age Group</h4><i class="fas fa-chevron-down"></i></label>
                    <input type="checkbox" id="tm4"/>
                    <div class="hiddentext">
                        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("Age Group")) : ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="accordion">
                    <label for="tm5" class="accordionitem"><h4>Size</h4><i class="fas fa-chevron-down"></i></label>
                    <input type="checkbox" id="tm5"/>
                    <div class="hiddentext">
                        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("Size")) : ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="accordion">
                    <label for="tm6" class="accordionitem"><h4>Brands</h4><i class="fas fa-chevron-down"></i></label>
                    <input type="checkbox" id="tm6"/>
                    <div class="hiddentext">
                        <?php
                        $orderby = 'name';
                        $order = 'asc';
                        $hide_empty = false;
                        $cat_args = array(
                            'orderby' => $orderby,
                            'order' => $order,
                            'hide_empty' => $hide_empty,
                        );

                        $product_brands = get_terms('pwb-brand', $cat_args);
                        if (!empty($product_brands)) {
                            echo '
 
<ul>';
                            foreach ($product_brands as $key => $brand) {
                                ?>
                                <li class="<?php echo(strtolower($brand->slug) === $vars['pwb-brand']) ? 'active' : ''; ?>">
                                    <?php
                                    echo '<a href="' . get_term_link($brand) . '" > ';
                                    if (strtolower($brand->slug) === $vars['pwb-brand']):
                                        echo '<i class="fa fa-check-circle-o"></i>';
                                    else:
                                        echo '<i class="fa fa-circle-o"></i>';
                                    endif;
                                    echo $brand->name;
                                    echo '</a>';
                                    echo '</li>';
                                }
                                echo '</ul>
 
 
';
                            }
                            ?>
                    </div>
                </div>
                <div class="accordion">
                    <label for="tm7" class="accordionitem"><h4>Color</h4><i class="fas fa-chevron-down"></i></label>
                    <input type="checkbox" id="tm7"/>
                    <div class="hiddentext">
                        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("Color")) : ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <ul class="products columns-<?php echo (is_single() === TRUE && $vendor_shop ) ? '4' : '3'; ?>" style="<?php echo (is_single() === TRUE) ? 'width: 100%' : 'display: inline-block;width: 75%;'; ?>">
