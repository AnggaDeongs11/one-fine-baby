<?php

/**
 * Product Table Main Actions
 *
 * This file is used to add the table actions before and after a table
 *
 * @link       http://www.wcvendors.com
 * @since      1.2.4
 * @version    1.4.4
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/product
 */

?>
<div class="wcv_dashboard_table_header wcv-cols-group wcv-search">
    <form method="get">
        <div class="form-row">
            <div class="col">
                <input type="text" name="wcv-search" id="wcv-search" class="form-control" value="<?php echo $search; ?>" placeholder="Search">
            </div>
            <div class="col">
                <select class="form-control" id="wcv-stocks" name="wcv-stocks" placholder="Dropdown">
                    <option value="" <?php if ($stocks === '') {echo 'selected'; } ;?>>All</option>
                    <option value="instock" <?php if ($stocks === 'instock') {echo 'selected'; } ;?>>In Stock</option>
                    <option value="outofstock" <?php if ($stocks === 'outofstock') {echo 'selected'; } ;?>>Out of Stock</option>
                </select>
            </div>
            <div class="col-2">
                <button class="btn btn--secondary w-100" style="text-transform: uppercase;"><?php echo __( 'Search', 'wcvendors-pro' ); ?></button>
            </div>
        </div>

    </form>
</div>

<?php /*
<div class="wcv_actions wcv-cols-group">
	<div class="all-50">
		<?php if ( ! $lock_new_products ) : ?>
			<?php foreach ( $template_overrides as $key => $template_data ) : ?>
				<a href="<?php echo $template_data['url']; ?>"
				   class="wcv-button button"><?php echo sprintf( __( 'Add %s ', 'wcvendors-pro' ), $template_data['label'] ); ?></a>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<div class="all-50" style="float:right">
		<?php
		echo $pagination_wrapper['wrapper_start'];
		echo paginate_links(
			apply_filters(
				'wcv_product_pagination_args',
				array(
					'base'      => add_query_arg( 'paged', '%#%' ),
					'format'    => '',
					'current'   => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
					'total'     => $this->max_num_pages,
					'prev_next' => true,
					'type'      => 'list',
				),
				( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
				$this->max_num_pages
			)
		);
		echo $pagination_wrapper['wrapper_end'];
		?>

	</div>
</div>
