
</main>
<?php if (!is_vendor_page()) : ?>
<footer class="footer marketplace-footer">

    <div class="footer-bottom">
        <div class="padded-row">
            <div class="footer-left">
                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer - Left")) : ?>
                <?php endif; ?>
            </div>
            <div class="footer-middle-left">
                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer - Middle Left")) : ?>
                <?php endif; ?>
            </div>
            <div class="footer-middle-right">
                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer - Middle Right")) : ?>
                <?php endif; ?>
            </div>
            <div class="footer-right">
                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer - Right")) : ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</footer>
<?php endif; ?>
<!-- begin::Cart Modal -->
<div class="cart-modal" style="display: none">
    <?php global $woocommerce; ?>
    <div class="backdrop">
        <div class="container">
            <div class="crt-modal">
                <div class="crt-modal-head">
                    <span class="crt-modal-close"><i class="fa fa-times"></i></span>
                    <h4><?php echo __('Your Cart'); ?></h4>
                </div>
                <div class="crt-modal-body">

                </div>                
                <div class="crt-modal-footer">
                    <div class="subtotal-outer-wrap">
                    </div>
                    <a href="<?php echo home_url('checkout') ?>" name="add-to-cart" class="checkout-button button alt wc-forward"><span>Go to checkout</span></a>
                </div>
                <div class="st-modal-loader" id="st-modal-loader" style="display: none"><img class="loader-img" src="<?php echo admin_url('images/wpspin_light-2x.gif'); ?>"></div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- end::Cart Modal -->
<?php wp_footer(); ?>
<script>
    jQuery(function () {
        jQuery(".st-select").selectmenu({icons: {button: "custom-chevron-down"}});
    });
</script>
<script>
    jQuery('#newarrivals,#trending,#related_products').owlCarousel({
        loop: false,
        margin: 20,
        nav: false,
        dots: true,
        responsive: {
            0: {
                items: 1,
                dots: true
            },
            460: {
                items: 2,
                dots: true
            },
            560: {
                items: 2,
                dots: true
            },
            767: {
                items: 2,
                dots: true
            },
            991: {
                items: 3,
                dots: true
            },
            1000: {
                items: 4,
                dots: true
            }
        }
    });
    var cmodal = {
        events() {
            const _this = this;
            jQuery(document).on('click', '.cart-modal .backdrop', function (e) {
                if (jQuery(e.target).hasClass('backdrop')) {
                    _this.hide();
                }

            });
            jQuery(document).on('click', '.cart-modal .crt-modal-close', function (e) {
                _this.hide();
            });
            jQuery('.cart-modal').on('click', '.st-cart-item-remove', function (e) {
                e.preventDefault();
                var cat_item_key = jQuery(this).attr('data-cart-item-key');
                _this.removeItem(cat_item_key);
            });
            jQuery('.cart-modal').on('change', '.st-quantity select.qty', function () {
                let name = jQuery(this).attr('name');
                var cart_key = name.substring(
                        name.indexOf("[") + 1,
                        name.indexOf("]")
                        );
                var qty = jQuery(this).find('option:selected').val();
                _this.updateQty(cart_key, qty);

            });
        },
        show() {
            const _this = this;
            _this.getCart();
            jQuery('.cart-modal').fadeIn();
            jQuery('.cart-modal .crt-modal').addClass('active');
        },
        hide() {
            jQuery('.cart-modal .crt-modal').removeClass('active');
            jQuery('.cart-modal').delay(600).fadeOut('slow');
        },
        removeItem(key) {
            const _this = this;
            var data = {
                'cart-item-key': key
            };
            _this.loader.show();
            var url = wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'st_remove_cart_item');
            jQuery.get(url, data, function (response) {
                if (response) {
                    _this.cartRefresh(response.cart);
                    _this.updateSubtotal(response.subtotal);
                    _this.loader.hide();
                }
            });
        },
        updateQty(cart_key, qty) {
            const _this = this;
            var url = wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'st_cart_item_quantity_change');
            let  data = {
                'cart-item-key': cart_key,
                'qty': qty
            };
            _this.loader.show();
            jQuery.get(url, data, function (response) {
                if (response) {
                    _this.cartRefresh(response.cart);
                    _this.updateSubtotal(response.subtotal);
                    _this.loader.hide();
                }
            });
        },
        cartRefresh(html) {
            jQuery('.crt-modal-body').empty().append(html);
            jQuery('.cart-modal .st-select2').select2({
                minimumResultsForSearch: -1
            });
        },
        updateSubtotal(subtotal) {
            jQuery('.crt-modal-footer .subtotal-outer-wrap').empty().append(subtotal);
        },
        getCart() {
            const _this = this;
            var url = wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'st_cart_template');
            let  data = {
                echo: true
            };
            _this.loader.show();
            jQuery.get(url, data, function (response) {
                if (response) {
                    _this.cartRefresh(response.cart);
                    _this.updateSubtotal(response.subtotal);
                    _this.loader.hide();
                }
            });
        },
        loader: {
            show() {
                jQuery('#st-modal-loader').show();
            },
            hide() {
                jQuery('#st-modal-loader').hide();
            }
        }
    };

    jQuery(document).ready(function () {
        jQuery.each(jQuery(".woocommerce-widget-layered-nav-list__item.wc-layered-nav-term"), function () {
            if (jQuery(this).hasClass("woocommerce-widget-layered-nav-list__item--chosen")) {
                jQuery(this).find("a").prepend('<i class="fa fa-check-circle-o"></i>');
            } else {
                jQuery(this).find("a").prepend('<i class="fa fa-circle-o"></i>');
            }
        });
        cmodal.events();

        jQuery(document).ajaxComplete(function (e, x, s) {
            const res = x.responseJSON;
            if (res && res.wishlists && res.status) {
                const product_id = jQuery(e.delegateTarget.activeElement).data('tinv-wl-product');
                const variation_id = (jQuery('[name="variation_id"]').val()) || jQuery(e.delegateTarget.activeElement).data('tinv-wl-productvariation');
                var url = wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'st_item_in_wishlist');
                var data = {
                    'product_id': product_id,
                    'variation_id': variation_id
                };
                jQuery.get(url, data, function (response) {
                    if (response) {
                        jQuery(e.delegateTarget.activeElement).parents('.ti-add-to-wishlist').hide();
                        jQuery(e.delegateTarget.activeElement).parents('.action.wishlist').find('.ti-remove-from-wishlist').find('.ti-remove-wishlist-btn').attr('data-wishlist-id', response.ID);
                        jQuery(e.delegateTarget.activeElement).parents('.action.wishlist').find('.ti-remove-from-wishlist').show();

                    }
                });

            }
        });
        jQuery(document).on('click', '.ti-remove-wishlist-btn', function (e) {
            var url = wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'st_remove_wishlist_item');
            var data = {
                'removal_id': jQuery(this).data('wishlist-id')
            };
            jQuery.get(url, data, function (response) {
                if (response) {
                    jQuery(e.target).parents('.ti-remove-from-wishlist').hide();
                    jQuery(e.target).parents('.action.wishlist').find('.ti-add-to-wishlist').show().find('.tinvwl_add_to_wishlist_button').removeClass('tinvwl-product-in-list');
                }
            });
        });
        jQuery(document).on('click', '.st-accordion-btn', function (e) {
            e.preventDefault();
            const target = jQuery(this).data('target');
            jQuery(this).parents('.st-accordion').removeClass('active').find('.st-accordion-content').slideUp();
            jQuery(target).addClass('active').find('.st-accordion-content').slideDown();
        });
        jQuery(document).on('click', '.st-accordion h3', function (e) {
            e.preventDefault();
            jQuery('.st-accordion').each(function () {
                jQuery(this).removeClass('active').find('.st-accordion-content').slideUp();
            });
            jQuery(this).parents('.st-accordion').addClass('active').find('.st-accordion-content').slideDown();
        });
        jQuery('.st-select2').select2({
            minimumResultsForSearch: -1
        });
        
        jQuery(document).on('change', '#pa_color', function () {
            const color = jQuery(this).find('option:selected').val();
            jQuery('#user-selected-color').text(color);
        });

        jQuery('.product-delete').on('click', function() {
          var url = jQuery(this).data('url');
          var product = jQuery(this).data('product')

          jQuery('.modal-description__product-name').html(product);
          jQuery('.modal-delete-link').attr('href',url);
        });
    });
</script>
</body>
</html>
