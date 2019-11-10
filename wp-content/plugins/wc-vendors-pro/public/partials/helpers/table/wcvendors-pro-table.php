<?php

/**
 * Table template
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.wcvendors.com
 * @since      1.0.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/helpers/table
 */

global $wp;
?>

<?php if ($this->container_wrap) : ?>
<div class="wcv-cols-group wcv-horizontal-gutters">
    <div class="all-100">
        <?php endif; ?>
        <?php if ($table) : ?>
            <?php if ($wp->request == 'dashboard/order'): ?>
                <div class="section-header">
                    <div class="row">
                        <div class="col">Recent Orders</div>
                        <div class="col section-header__actions d-flex justify-content-end">
                            <span>Sort by:</span>
                            <select class="section-header__actions-select">
                                <option>Price</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <table role="grid" class="wcvendors-table wcvendors-table-<?php echo $this->id; ?> wcv-table">
                <?php $this->display_columns(); ?>
                <?php $this->display_rows(); ?>
            </table>
        <?php else : ?>
            <?php $this->display_grid(); ?>
        <?php endif; ?>


        <?php if ($this->container_wrap) : ?>
    </div>
</div>
<?php endif; ?>
