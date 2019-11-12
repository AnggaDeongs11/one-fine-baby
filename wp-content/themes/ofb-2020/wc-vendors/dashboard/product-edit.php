<?php
/**
 * The template for displaying the Product edit form
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/
 *
 * @package    WCVendors_Pro
 * @version    1.6.3
 */
/**
 *   DO NOT EDIT ANY OF THE LINES BELOW UNLESS YOU KNOW WHAT YOU'RE DOING
 */
$title = ( is_numeric($object_id) ) ? __('Save', 'wcvendors-pro') : __('Save', 'wcvendors-pro');
$page_title = ( is_numeric($object_id) ) ? __('Edit Product', 'wcvendors-pro') : __('Add Product', 'wcvendors-pro');
$product = ( is_numeric($object_id) ) ? wc_get_product($object_id) : null;
$post = ( is_numeric($object_id) ) ? get_post($object_id) : null;
$brand = get_the_terms($object_id, 'pwb-brand');
// Get basic information for the product
$product_title = ( isset($product) && null !== $product ) ? $product->get_title() : '';
$product_description = ( isset($product) && null !== $product ) ? $post->post_content : '';
$product_short_description = ( isset($product) && null !== $product ) ? $post->post_excerpt : '';
$post_status = ( isset($product) && null !== $product ) ? $post->post_status : '';

/**
 *  Ok, You can edit the template below but be careful!
 */
?>

<?php do_action('wcvendors_before_product_form'); ?>

<!-- Product Edit Form -->
<form method="post" action="" id="wcv-product-edit" class="wcv-form">
    <div class="form-actions d-flex">
    <?php WCVendors_Pro_Product_Form::form_data( $object_id, $post_status ); ?>
    <?php WCVendors_Pro_Product_Form::draft_button( __( 'Preview', 'wcvendors-pro' ) ); ?>
    <?php WCVendors_Pro_Product_Form::save_button( $title ); ?>
    </div>
    <div class="product-settings">
            <div class="form-row">
               <div class="col-md-2">
                 <div class="icon"><img src="/wp-content/themes/ofb-2020/images/Settings.png"></div>
               </div>
                <div class="col-md-5">
                    <div class="info-title">
                      Product Settings
                    </div>
                    <p>Manage how your products appears on your page</p>
                </div>
                  <div class="col-md-5">
                    <!--<div class="custom-control custom-switch">
                    <?php WCVendors_Pro_Product_Form::private_listing( $object_id ); ?>
                  </div> !-->
                    <div class="custom-control custom-switch switch-hide">
                        <input type="checkbox" class="custom-control-input" id="_private_listing"
                            <?php if (get_post_meta( $object_id, '_private_listing', true ) == 'yes') {echo 'checked="checked"';}; ?>
                               name="_private_listing">
                        <label class="custom-control-label" for="_private_listing">HIDE PRODUCT</label>
                    </div>
                  </div>
            </div>
    </div>
    <div class="section-header">
        Information
    </div>
    <!-- Basic Product Details -->
    <div class="wcv-product-basic wcv-product">
        <?php do_action('wcv_before_product_details', $object_id); ?>
        <!-- Product Title -->
        <div class="form-group">
            <div class="form-row">
                <div class="col-12 col-md-6">
                    <?php
                    WCVendors_Pro_Form_Helper::input(
                        apply_filters(
                            'wcv_product_title',
                            array(
                                'post_id'           => $post_id,
                                'id'                => 'post_title',
                                'label'             => __( 'Product Name', 'wcvendors-pro' ),
                                'value'             => $product_title,
                                'placeholder' => 'Enter Name',
                                'class' => 'form-control',
                                'custom_attributes' => array(
                                    'required' => '',
                                    'data-parsley-maxlength'     => '100',
                                    'data-parsley-error-message' => __( 'Product name is required or too long.', 'wcvendors-pro' ),
                                ),
                            )
                        )
                    );
                    ?>
                </div>
                <div class="col-12 col-md-6">
                    <?php

                    WCVendors_Pro_Form_Helper::input(array(
                            'post_id' => $object_id,
                            'id' => '_wcv_custom_taxonomy_pwb-brand',
                            'class' => ' form-control',
                            'value' => $brand[0]->term_id,
                            'placeholder' => 'Enter Brand',
                            'label' => ($multiple) ? __('Brands', 'wcvendors-pro') : __('Brand', 'wcvendors-pro'),
                        )
                    );

                    ?>
                </div>
                <div class="col-12 col-md-6">
                    <?php
                    WCVendors_Pro_Form_Helper::input(array(
                            'post_id' => $object_id,
                            'id' => 'post_excerpt',
                            'class' => ' form-control',
                            'value' => $product_short_description,
                            'placeholder' => 'Enter Description',
                            'label' => 'Short Description',
                        )
                    );
                    ?>
                </div>
                <div class="col-12 col-md-6">
                    <?php WCVendors_Pro_Product_Form::categories($object_id); ?>
                </div>
                <div class="col-12 col-md-6">
                    <?php
                    WCVendors_Pro_Form_Helper::textarea(array(
                        'post_id' => $object_id,
                        'id' => 'st_custom_product_information',
                        'label' => __('Product Information', 'wcvendors-pro'),
                        'placeholder' => __('Provide information about your product', 'wcvendors-pro'),
                    ));
                    ?>
                </div>
                <div class="col-12 col-md-6">
                    <?php  WCVendors_Pro_Product_Form::tags($object_id, true); ?>
                </div>
            </div>
        </div>
        <div class="section-header">
            Images
        </div>
        <div class="form-row">
            <div class="col">
                <?php do_action('wcv_before_product_media', $object_id); ?>
                <!-- Media uploader -->
                <div class="wcv-product-media">
                    <?php do_action('wcv_before_media', $object_id); ?>
                    <?php WCVendors_Pro_Form_helper::product_media_uploader($object_id); ?>
                    <?php do_action('wcv_after_media', $object_id); ?>

                </div>
                <?php do_action('wcv_after_product_media', $object_id); ?>
            </div>
        </div>

        <div class="section-header">
            Inventory Settings
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col-12 col-md-6">
                  <?php
                   $required_field    = 'yes' === get_option( 'wcvendors_required_product_general_price' ) ? array(
                       'required'                   => '',
                        'data-parsley-error-message' => __( 'Price is required', 'wcvendors-pro' ),
                   ) : array();
                    $custom_attributes = array();
                    $custom_attributes = array_merge( $custom_attributes, $required_field );
                    WCVendors_Pro_Form_Helper::input(
                     apply_filters(
                            'wcv_product_price',
                            array(
                               'post_id'           => $object_id,
                               'id'                => '_regular_price',
                                'label'             => __( 'Price', 'wcvendors-pro' ),// . ' (' . get_woocommerce_currency_symbol() . ')',
                               'data_type'         => 'price',
                                'custom_attributes' => $custom_attributes,
                            )
                       )
                    ); ?>
                </div>
                <div class="col-12 col-md-6">
                  <?php WCVendors_Pro_Product_Form::sku($object_id); ?>
                </div>
               <div class="col-12 col-md-6 sale_price">
                 <?php WCVendors_Pro_Product_Form::sale_price($object_id); ?>
                </div>
               <div class="col-12 col-md-6">
                    <?php WCVendors_Pro_Product_Form::stock_status($object_id); ?>
                </div>

                    <?php WCVendors_Pro_Product_Form::product_type( $object_id ); ?>
            </div>

            <div class="section-header">
                Variants
            </div>
            <div class="form-group">
              <div class="form-row">
                <div class="col-12">
                  <p>Add attributes to allow users to order variations of your product.</p>
                </div>
              </div>
            </div>
            <div class="form-row">
                <div class="col-12">
                    <div class="wcv-tabs top" data-prevent-url-change="true" style="margin-top:-30px">

                        <?php do_action( 'wcv_before_product_meta_tabs', $object_id ); ?>

                        <?php WCVendors_Pro_Product_Form::product_meta_tabs(); ?>

                        <!-- General Product Options -->
                        <div class="wcv-product-general tabs-content" id="general">

                            <div class="hide_if_grouped">
                                <!-- SKU  -->
                                <?php WCVendors_Pro_Product_Form::sku( $object_id ); ?>
                                <!-- Private listing  -->

                            </div>

                            <div class="show_if_simple show_if_external show_if_variable">
                                <!-- Tax -->
                                <?php WCVendors_Pro_Product_Form::tax( $object_id ); ?>
                            </div>

                            <?php do_action( 'wcv_product_options_general_product_data', $object_id ); ?>

                        </div>

                        <?php do_action( 'wcv_after_general_tab', $object_id ); ?>

                        <?php do_action( 'wcv_before_inventory_tab', $object_id ); ?>

                        <!-- Inventory -->
                        <div class="wcv-product-inventory inventory_product_data tabs-content" id="inventory">

                            <?php WCVendors_Pro_Product_Form::manage_stock( $object_id ); ?>

                            <?php do_action( 'wcv_product_options_stock', $object_id ); ?>

                            <div class="stock_fields show_if_simple show_if_variable" style="padding-left: 20px;">
                                <?php WCVendors_Pro_Product_Form::stock_qty( $object_id ); ?>
                                <?php WCVendors_Pro_Product_Form::backorders( $object_id ); ?>
                                <?php WCVendors_Pro_Product_Form::low_stock_threshold( $object_id ); ?>
                            </div>

                            <?php WCVendors_Pro_Product_Form::stock_status( $object_id ); ?>
                            <div class="options_group show_if_simple show_if_variable">
                                <?php WCVendors_Pro_Product_Form::sold_individually( $object_id ); ?>
                            </div>

                            <?php do_action( 'wcv_product_options_sold_individually', $object_id ); ?>

                            <?php do_action( 'wcv_product_options_inventory_product_data', $object_id ); ?>

                        </div>

                        <?php do_action( 'wcv_after_inventory_tab', $object_id ); ?>

                        <!-- Attributes -->

                        <?php do_action( 'wcv_before_attributes_tab', $object_id ); ?>

                        <div class="wcv_product_attributes tabs-content" id="attributes">

                            <div class="attributes-validation-error"></div>

                            <?php WCVendors_Pro_Product_Form::product_attributes( $object_id ); ?>

                            <?php do_action( 'wcv_product_options_attributes_product_data', $object_id ); ?>

                        </div>

                        <?php do_action( 'wcv_after_attributes_tab', $object_id ); ?>

                        <!-- Variations -->

                        <?php do_action( 'wcv_before_variations_tab', $object_id ); ?>

                        <div class="wcv_product_variations tabs-content" id="variations">

                            <?php WCVendors_Pro_Product_Form::product_variations( $object_id ); ?>

                            <?php do_action( 'wcv_product_options_variations_product_data', $object_id ); ?>

                        </div>

                        <?php do_action( 'wcv_after_variations_tab', $object_id ); ?>



                        <?php do_action( 'wcv_after_product_meta_tabs', $object_id ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<input type="text" id="counter" style="visibility:hidden">

<?php //do_action('wcvendors_after_product_form'); ?>

<script type="text/javascript">
  $(document).ready(function(){

    $( '.tabs-nav li:visible' ).eq( 3 ).find( 'a' ).click();

    $('.tabs-nav li:eq(3)').addClass('active');

  });

  $(window).ready(function(){
      console.log('ready');

      var postid = '<?php echo $object_id; ?>';
      console.log(postid);
      if(postid) {
        $('.loading').fadeOut(100);
        $('.heading-product').fadeIn(100);
        $('.btnAddAttr').fadeIn(100);
      }else {
        $('.loading').fadeIn(100);
        i = 0;
        $('.wcv_product_attributes .attribute_taxonomy option').each(function(){

         $(".wcv_product_attributes .attribute_taxonomy option:eq(1)").attr("selected", "selected");
         $('#counter').val(1);
               window.setTimeout(function() {

                    $('.add_attribute').trigger('click');

                }, 13000*i);
               i++;
        });


      }



      heading_product();



  });



function heading_product() {
  html = '<div class="all-10">Image</div>';
  html += '<div class="all-10">SKU</div>';

  var name = "";
  var res  = "";

  $('.wcv_product_attributes .attribute_taxonomy option').each(function(){

  name = $(this).val();

  res = name.replace("pa_", " ");

  res = res.replace("_", " ");

  res = res.replace("-", " ");

  res = res.replace("pa", " ");


  html += '<div class="all-15" style="text-transform:capitalize">'+res+'</div>';

  });

  html += '<div class="all-15">Price</div>';
  html += '<div class="all-15">Sale</div>';


  $('.heading_title').html(html);
}


function delayedTrigger(elem, delay, option)
{

    setTimeout( function() { $(elem).trigger('click');   $('.wcv_product_attributes .attribute_taxonomy').val(option); }, delay );
}
</script>
