/**
This is used to init the forms on the front end.
*/
/* global jQuery, Ink, wcv_fronted_forms */
(function( $, Ink ) {
    $( window ).on( 'load', function() {
        validate_forms();

        if( ! $( '#_wcv_vendor_enable_store_notice' ).is(":checked") ) {
            $('#wp-_wcv_vendor_store_notice-wrap').hide();
        }else{
            $('#wp-_wcv_vendor_store_notice-wrap').show();
        }

        $('#_wcv_vendor_enable_store_notice').click( function(e){
            $('#wp-_wcv_vendor_store_notice-wrap').toggle();
        });

        $('#_wcv_shipping_type').on( 'change', function(e){
            var selected_value = $(this).val()
            if ( selected_value == 'flat' ) {
                $('#shipping-flat-rates').removeClass('hidden');
                $('#shipping-country-rates').addClass('hidden');
            }else if ( selected_value == 'country' ) {
                $('#shipping-flat-rates').addClass('hidden');
                $('#shipping-country-rates').removeClass('hidden');
            } else {
	            $('#shipping-flat-rates').addClass('hidden');
	            $('#shipping-country-rates').addClass('hidden');
            }
        });


    } );

    $(window).on ( 'load', 
        // Hide flat and country rates using JS
        debounce( function () {

            var selected_value = wcv_fronted_forms.vendor_select ? $('#_wcv_shipping_type').val() : wcv_fronted_forms.shipping_type;

            if (selected_value === 'flat') {
                $('#shipping-flat-rates').removeClass('hidden');
                $('#shipping-country-rates').addClass('hidden');
            } else if (selected_value === 'country') {
                $('#shipping-flat-rates').addClass('hidden');
                $('#shipping-country-rates').removeClass('hidden');
            } else {
                if ( 'country' === wcv_fronted_forms.shipping_type ) {
                    $('#shipping-flat-rates').addClass('hidden');
                    $('#shipping-country-rates').removeClass('hidden');
                } else if( 'flat' === wcv_fronted_forms.shipping_type ) {
                    $('#shipping-flat-rates').removeClass('hidden');
                    $('#shipping-country-rates').addClass('hidden');
                }
            }

        }, 100)
    );

    function validate_forms() {

        window.Parsley.on('form:error', debounce( function() {

            $( 'html, body' ).animate( {
                scrollTop: $( '.parsley-error:first' ).offset().top - 200,
            }, 'slow' );

            $( '.parsley-error:first' ).focus();
        }, 100 ) );

        if ( ! $( '.wcv-form' ).length ) return;

        var formInstance = Ink.Common_1.getInstance( '.wcv-form' )[0];

        if ( typeof formInstance === 'undefined' ) {
            return;
        }

        var oldHandler = formInstance._options.onError;

        /**
         * Custom validation error handler. Scrolls the erroring field
         * into view.
         *
         * @param FormValidator.FormElement[] errors
         */
        formInstance._options.onError = function( errors ) {
            if ( errors.length < 1 ) {
                return;
            }

            /* Get first element with errors */
            var $element = $( errors[0].getElement() );

            /* If the element is being displayed in a tab pane, focus that tab */
            var $pane = $element.closest( '.tabs-content' );

            if ( $pane && ! $pane.hasClass( 'active' ) ) {
                var tabsInstance = Ink.Common_1.getInstance( '.wcv-tabs' )[0];

                if ( typeof tabsInstance !== 'undefined' ) {
                    tabsInstance.changeTab( '#' + $pane.attr( 'id' ) );
                }
            }

            /* Scroll element into view */
            var $group = $element.closest( '.control-group' );

            $( 'html, body' ).animate( {
                scrollTop: $group.offset().top,
            }, {
                duration: 500,
            } );

            /* Call original error handler, if any */
            if ( typeof oldHandler !== 'undefined' ) {
                oldHandler( errors );
            }
        }
    }

    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };
})( jQuery, Ink.UI );
