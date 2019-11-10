<?php
/* separate from table_giftregistry_public_view.php because filter ajax*/
/**
 * Created by PhpStorm.
 * User: mdq
 * Date: 21/12/2018
 * Time: 15:20
 */
$wishlist = Magenest_Giftregistry_Model::get_wishlist($id);
wp_enqueue_script('table_giftregistry');
?>
<div style="overflow-x:auto" id="table">
    <table class="shop_table cart" cellspacing="0">
        <thead>
        <tr>
            <th class="product-thumbnail"><?= __('Image', GIFTREGISTRY_TEXT_DOMAIN); ?>
            </th>
            <th class="product-name"><?= __('Product', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
            <th class="product-price"><?= __('Price', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
            <th class="product-quantity"><?= __('Desired Quantity', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
            <th class="product-priority"><?= __('Priority', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
            <th class="product-quantity"><?= __('Quantity', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
            <th class="product-buy"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $remain_qty = 0;
        if (!empty ($items)) {
            foreach ($items as $item) {
                $_product = wc_get_product($item ['product_id']);

                $request = unserialize($item['info_request']);

                $request_st = Magenest_Giftregistry_Model::show_info_request($item, $id);

                if (isset($item['quantity'])) {
                    $receive_qty = 0;
                    if (isset($item['received_qty'])) {
                        $receive_qty = $item['received_qty'];
                    }
                    $remain_qty = $item['quantity'] - $receive_qty;//received_qty
                    if ($remain_qty < 0) {
                        $remain_qty = 0;
                    }
                }
                /*disable item when desired qty = 0 */
                if ($remain_qty == 0) {
                    ?>
                    <tr>

                        <td class="product-thumbnail" data-name="Product Image">
                            <?php
                            $thumbnail = $_product->get_image();
                            printf($thumbnail);
                            ?>
                        </td>
                        <td class="product-name" data-name="Product Name">
                            <?php
                            echo sprintf('<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_name());
                            ?>
                        </td>
                        <td class="product-price" data-name="Price">
                            <?php
                            echo $_product->get_price_html();
                            ?>
                        </td>
                        <td style="width: 30px;" class="product-quantity" data-name="Product Quantity">
                            <?php if (isset($item['quantity'])) {
                                if ($remain_qty < 0) {
                                    $remain_qty = 0;
                                }
                                ?>
                                <input style="width: 40px;background-color: #ffffff" type="hidden" disabled
                                       id="desired_qty<?php echo $item['id'] ?>" value="<?= $remain_qty ?>"/>
                                <?php
                                echo $remain_qty;
                            }
                            ?>
                        </td>
                        <td class="product-priority" data-name="Priority">
                            <?php
                            $priority = $item['priority'];
                            if ($priority == 1) {
                                echo __('High', GIFTREGISTRY_TEXT_DOMAIN);
                            } else {
                                echo __('Low', GIFTREGISTRY_TEXT_DOMAIN);
                            }
                            ?>
                        </td>
                        <td class="product-quantity">
                            <?php
                            if ($wishlist->option_quantity) {
                                ?>
                                <input style="width: 40px" type="text" id="qty<?php echo $item['id'] ?>" value="1"/>
                                <?php
                            } else {
                                ?>
                                <select id="qty<?php echo $item['id'] ?>" style="width: 70%" disabled>
                                    <?php
                                    for ($quantity = 1; $quantity <= $remain_qty; $quantity++) {
                                        ?>
                                        <option value="<?= $quantity ?>"><?= $quantity ?></option>
                                        <?php
                                    }
                                    if ($remain_qty == 0) {
                                        ?>
                                        <option value="<?= 0 ?>"><?= 0 ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                            $stock_status = $_product->get_stock_status();
                            if ($stock_status == 'instock') {
                                ?>
                                <button data-buy="<?php echo $request_st ?>"
                                        name="<?php echo $item['id'] ?>"
                                        class="single_add_to_cart_button button alt"
                                        onclick="giftit(this)"
                                    <?= $wishlist->option_quantity == '0' ? 'disabled' : '1'; ?>
                                >
                                    <?php echo __('Add to cart', GIFTREGISTRY_TEXT_DOMAIN) ?>
                                </button>
                                <?php
                            } else {
                                ?>
                                <button class="single_add_to_cart_button button alt">
                                    <?php echo __('Out stock', GIFTREGISTRY_TEXT_DOMAIN) ?>
                                </button>
                                <?php
                            }
                            ?>

                        </td>

                    </tr>
                    <?php
                } else {
                    ?>
                    <tr>

                        <td class="product-thumbnail" data-name="Product Image">
                            <?php
                            $image = wp_get_attachment_image_src(get_post_thumbnail_id($_product->get_id()), 'single-post-thumbnail');
                            $thumbnail = $_product->get_image();
                            echo "<img src='" . $image[0] . "'/>";
                            ?>
                        </td>
                        <td class="product-name" data-name="Name">
                            <?php
                            echo sprintf('<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_name());
                            ?>
                        </td>
                        <td class="product-price" data-name="Price">
                            <?php
                            echo $_product->get_price_html();
                            ?>
                        </td>
                        <td style="width: 30px;" class="product-quantity" data-name="Quantity">
                            <?php if (isset($item['quantity'])) {
                                if ($remain_qty < 0) {
                                    $remain_qty = 0;
                                }
                                ?>
                                <input style="width: 40px;background-color: #ffffff" type="hidden" disabled
                                       id="desired_qty<?php echo $item['id'] ?>" value="<?= $remain_qty ?>"/>
                                <?php
                                echo $remain_qty;
                            }
                            ?>
                        </td>
                        <td class="product-priority" data-name="Priority">
                            <?php
                            $priority = $item['priority'];
                            if ($priority == 1) {
                                echo __('High', GIFTREGISTRY_TEXT_DOMAIN);
                            } else {
                                echo __('Low', GIFTREGISTRY_TEXT_DOMAIN);
                            }
                            ?>
                        </td>
                        <td class="product-quantity" data-name="Quantity">
                            <?php
                            if ($wishlist->option_quantity) {
                                ?>
                                <input style="width: 40px" type="text" id="qty<?php echo $item['id'] ?>" value="1"/>
                                <?php
                            } else {
                                ?>
                                <select id="qty<?php echo $item['id'] ?>" style="width: 70%">
                                    <?php
                                    for ($quantity = 1; $quantity <= $remain_qty; $quantity++) {
                                        ?>
                                        <option value="<?= $quantity ?>"><?= $quantity ?></option>
                                        <?php
                                    }
                                    if ($remain_qty == 0) {
                                        ?>
                                        <option value="<?= 0 ?>"><?= 0 ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                            $stock_status = $_product->get_stock_status();
                            if ($stock_status == 'instock') {
                                ?>
                                <button data-buy="<?php echo $request_st ?>"
                                        name="<?php echo $item['id'] ?>"
                                        class="single_add_to_cart_button button alt"
                                        onclick="giftit(this)"
                                >
                                    <?php echo __('Add to cart', GIFTREGISTRY_TEXT_DOMAIN) ?>
                                </button>
                                <?php
                            } else {
                                ?>
                                <button class="single_add_to_cart_button button alt">
                                    <?php echo __('Out stock', GIFTREGISTRY_TEXT_DOMAIN) ?>
                                </button>
                                <?php
                            }
                            ?>

                        </td>

                    </tr>
                    <?php
                }
            }
        }
        ?>
        </tbody>
    </table>
</div>
