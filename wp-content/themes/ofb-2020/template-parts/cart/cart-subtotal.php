<?php global $woocommerce; ?>
<div class="subtotal-wrap">
    <h4>Subtotal
        <span class="cart-total"><?php echo wc_price($woocommerce->cart->cart_contents_total); ?></span>
        <small style="display:block; line-height: normal;">( excluding shipping and taxes )</small>
    </h4>
</div>