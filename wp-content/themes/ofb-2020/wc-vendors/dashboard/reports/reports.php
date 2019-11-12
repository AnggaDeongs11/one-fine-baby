<?php
/**
 * The template for displaying the vendor store graphs, recent products and recent orders
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/report
 *
 * @package    WCVendors_Pro
 * @version    1.2.5
 */
?>


<div class="section-header d-flex">
    <?php _e( 'Order Totals', 'wcvendors-pro' ); ?>
</div>
<div class="col-12">
    <?php $order_chart_data = $store_report->get_order_chart_data(); ?>
    <?php

    $dates = json_decode($order_chart_data['labels']);
    $data = json_decode($order_chart_data['data']);

    $date_now = date('Y-m-d');
    $date_30 = date('Y-m-d', strtotime($date.' - 29 days'));
    $new_date = array();
    $new_data = array();
     for($i=0; $i<= 30; $i++) {
       $d =date('Y-m-d', strtotime($date_30."+$i day"));
       $data_val = 0;
        foreach ($dates as $key => $value) {
          if($d==$value) {
            $data_val = $data[$key];
          }
        }
      $days = date('D', strtotime($d));
      $daySuffix = date('dS', strtotime($d));
      array_push($new_date, $days." ".$daySuffix);
      array_push($new_data, $data_val);
     }

     $label_new =  json_encode( array_values( $new_date ) );
     $data_new =  json_encode( array_values( $new_data ) );


     ?>
    <?php if ( ! $order_chart_data ) : ?>
        <p><?php _e( 'No orders for this period. Adjust your dates above and click Update, or list new products for customers to buy.', 'wcvendors-pro' ); ?></p>
    <?php else : ?>

        <canvas id="orders_chart" width="350" height="200" style="margin-top:10px" ></canvas>
        <script type="text/javascript">
          var orders_chart_label = <?php echo $label_new; ?>;

          var orders_chart_data = <?php echo $data_new; ?>;

        </script>
    <?php endif; ?>
</div>

<!-- Load the Overview section of the dashboard page -->
<?php
wc_get_template(
    'overview.php',
    array(
        'store_report'      => $store_report,
        'products_disabled' => $products_disabled,
        'orders_disabled'   => $orders_disabled,
    ),
    'wc-vendors/dashboard/reports/',
    WCVendors_Pro::get_path() . 'templates/dashboard/reports/'
);
?>

<div class="wcv_dashboard_overview wcv-cols-group wcv-horizontal-gutters dashboard-section-hr-top mt-5">
  <div class="xlarge-100 large-100 medium-100 small-100 tiny-100">
  	<h3 class="font-default"><?php _e( 'Recent Orders', 'wcvendors-pro' ); ?></h3>
      <div class="blue-border-1"></div>
  	<span class="recent-orders"><?php $recent_orders = $store_report->recent_orders_table(); ?>
  	<?php if ( ! $orders_disabled ) : ?>
  		<?php if ( ! empty( $recent_orders ) ) : ?>
  			<a href="<?php echo WCVendors_Pro_Dashboard::get_dashboard_page_url( 'order' ); ?>"
  			   class="wcv-button button"><?php _e( 'View All', 'wcvendors-pro' ); ?></a>
  		<?php endif; ?>
  	<?php endif; ?></span>
  </div>
</div>

<div class="wcv_dashboard_overview wcv-cols-group wcv-horizontal-gutters dashboard-section-hr-top mt-5">
  <div class="xlarge-100 large-100 medium-100 small-100 tiny-100">
  	<h3 class="font-default"><?php _e( 'Latest Products', 'wcvendors-pro' ); ?></h3>
  	<?php $recent_products = $store_report->recent_products_table(); ?>
  	<?php if ( ! $products_disabled ) : ?>
  		<?php if ( ! empty( $recent_products ) ) : ?>
  			<a href="<?php echo WCVendors_Pro_Dashboard::get_dashboard_page_url( 'product' ); ?>"
  			   class="wcv-button button"><?php _e( 'View All', 'wcvendors-pro' ); ?></a>
  		<?php endif; ?>
  	<?php endif; ?>
  </div>
</div>
