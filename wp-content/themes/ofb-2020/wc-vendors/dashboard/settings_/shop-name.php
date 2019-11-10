<?php
/**
 * Shop Name Template
 *
 * This template can be overridden by copying it to yourtheme/wc-vendors/dashboard/settings/shop-name.php
 *
 * @author        Jamie Madden, WC Vendors
 * @package       WCVendors/Templates/Emails/HTML
 * @version       2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<label for="pv_shop_name">Store Name</label>
<?php if ($readonly): ?>
<div class="readonly-text">
    <?php echo get_user_meta($user_id, 'pv_shop_name', true); ?>
</div>
<?php else : ?>
<input class="form-control" type="text" name="pv_shop_name" id="pv_shop_name" placeholder="Enter Store Name"
       value="<?php echo get_user_meta($user_id, 'pv_shop_name', true); ?>"/>
<?php endif; ?>
