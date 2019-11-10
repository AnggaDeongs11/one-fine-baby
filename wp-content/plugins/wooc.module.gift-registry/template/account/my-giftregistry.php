<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

global $post;
$wishlist = '';
if ($wid) {
    $wishlist = Magenest_Giftregistry_Model::get_wishlist($wid);
}
wp_enqueue_script('my-giftregistry');
?>
    <div class="tab-pane" id="tab2">

    <h3> <?php echo __('Gift registry items', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>
<?php
if (!empty ($items)) {
    ?>
    <form id="giftregistry-item-form" method="POST">
        <input type="hidden" name="giftregistry_id" id="giftregistry_id"
               value="<?php if (is_object($wishlist)) : echo $wishlist->id; endif; ?>"/>
        <input type="hidden" name="update_giftregistry_item" value="1"/>
        <table class="table" <?php if (is_admin()) : ?> id="admin-gift-registry" <?php endif; ?>>
            <thead>
            <tr>
                <th class="product-remove">&nbsp;
                    <span class="checkmark" style="display: inline-block;"></span>
                    <input type="checkbox" id="select_all">
                </th>
                <th class="product-thumbnail"><?= __('Image', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-name"><?= __('Product', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-price"><?= __('Price', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-priority"><?= __('Priority', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-quantity"><?= __('Desired Quantity', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-buy"><?= __('Received Quantity', GIFTREGISTRY_TEXT_DOMAIN); ?></th>
                <th class="product-delete">&nbsp</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!empty ($items)) {
                foreach ($items as $item) {
                    $_product = wc_get_product($item ['product_id']);

                    $request_link = wc_get_page_permalink('myaccount');

//                if (strpos($request_link, '?') > 0) {
//                    $delete_link = $request_link . '&update_giftregistry_item=1&remove_item=1&item_id=' . $item['id'];
//                } else {
//                    $delete_link = $request_link . '?update_giftregistry_item=1&remove_item=1&item_id=' . $item['id'];
//                }
                    ?>
                    <tr>
                        <td class="product-remove" style="padding: 0px;">
                            <input type="checkbox" value="<?= $item['id'] ?>"
                                   name="delete[<?php echo $item['id'] ?>]"
                                   class="items">
                            <span class="checkmark" style="display: inline-block;"></span>

                        </td>
                        <td class="product-thumbnail">
                            <?php
                            $thumbnail = $_product->get_image();
                            printf('<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail);
                            ?>
                        </td>
                        <td class="product-name" style="text-align: center">
                            <?php
                            echo sprintf('<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_name())
                            ?>
                        </td>
                        <td class="product-price">
                            <?php
                            echo $_product->get_price_html();
                            ?>
                        </td>
                        <td class="product-priority">
                            <select name="priority[<?php echo $item['id'] ?>]">
                                <?php
                                $priority = $item['priority'];
                                if ($priority == '1') {
                                    ?>
                                    <option value="1" selected="selected"><?=__('High',GIFTREGISTRY_TEXT_DOMAIN)?></option>
                                    <option value="3"><?=__('Low',GIFTREGISTRY_TEXT_DOMAIN)?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="1"><?=__('High',GIFTREGISTRY_TEXT_DOMAIN)?></option>
                                    <option value="3" selected="selected"><?=__('Low',GIFTREGISTRY_TEXT_DOMAIN)?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>

                        <td class="product-quantity">
                            <?php if (isset($item['quantity'])) {
                                $receive_qty = 0;
                                if (isset($item['received_qty'])) {
                                    $receive_qty = $item['received_qty'];
                                }
                                $remain_qty = $item['quantity'] - $receive_qty;//received_qty
                                if ($remain_qty < 0) {
                                    $remain_qty = 0;
                                }
                                ?>
                                <input style="width: 40px;background-color: #ffffff" type="text"
                                       id="desired_qty<?php echo $item['id'] ?>" value="<?= $remain_qty ?>"
                                       name="desired_item[<?php echo $item['id'] ?>]"/>
                                <?php
                            }
                            ?>
                        </td>
                        <td class="received-quantity">
                            <?php
                            if (isset($item['received_qty'])) {
                                echo $item['received_qty'];
                            } else {
                                echo 0;
                            }
                            ?>
                        </td>
                        <td class="product-delete">
                            <button name="trash[<?= $item['id'] ?>]" class="trash" type="submit"
                                    style="background-image: url(<?= GIFTREGISTRY_URL . '/assets/img/trash.png' ?>);">
                                <span class="tooltiptext"><?= __('Delete', GIFTREGISTRY_TEXT_DOMAIN) ?></span>
                            </button>
                            <!--                                    <img id="trash" src="-->
                            <?//=GIFTREGISTRY_URL.'/assets/img/trash.png'?><!--" name="trash[-->
                            <?php //echo $item['id'] ?><!--]">-->
                        </td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>
        <?php
        if(!is_admin()){
            ?>
            <a class="button no-empty button-primary" style="margin: 5px" href="<?= wc_get_page_permalink('shop') ?>">
                <?=__('Add More Products',GIFTREGISTRY_TEXT_DOMAIN)?>
            </a>
            <?php
        }
        ?>
        <input id="wishlist_id_for_items" name="wishlist_id" type="hidden"/>
        <input type="submit" class="button button-primary" id="delete" name="delete_button"
               value="<?php echo __('Delete', GIFTREGISTRY_TEXT_DOMAIN) ?>" style="margin: 5px; width: 30% !important;">
        <input type="submit" class="button button-primary"
               value="<?php echo __('Save', GIFTREGISTRY_TEXT_DOMAIN) ?>"
               title="<?php echo __('Save', GIFTREGISTRY_TEXT_DOMAIN) ?>" style="margin: 5px; width: 30% !important;">
    </form>
    </div>
    <?php
} else {
    ?>
    <div class="woocommerce"><p class="cart-empty"><?=__('Your gift registry item list is empty',GIFTREGISTRY_TEXT_DOMAIN)?></p>
        <p class="return-to-shop">
            <a class="button wc-backward button-primary" href="<?= wc_get_page_permalink('shop') ?>">
                <?=__('Add More Products',GIFTREGISTRY_TEXT_DOMAIN)?>
            </a>
        </p>
    </div>
    </div>
    <?php
}
?>