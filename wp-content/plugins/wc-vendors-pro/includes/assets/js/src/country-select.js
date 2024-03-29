/*global wcv_country_select_params  */
jQuery( function( $ ) {

	// wcv_country_select_params is required to continue, ensure the object exists
	if ( typeof wcv_country_select_params === 'undefined' ) {
		return false;
	}

	// Select2 Enhancement if it exists
	if ( $().select2 ) {
	
		var wcv_country_select_select2 = function() {
			$( 'select.country_select:visible, select.state_select:visible' ).each( function() {

				var select2_args = {
					placeholderOption: 'first',
					width: '100%'
				}; 
				
				$( this ).select2( select2_args );

			});
		};

		wcv_country_select_select2();

		$( document.body ).bind( 'country_to_state_changed', function() {
			wcv_country_select_select2();
		});
	}

	/* State/Country select boxes */
	var states_json = wcv_country_select_params.countries.replace( /&quot;/g, '"' ),
		states = $.parseJSON( states_json );

	$( document.body ).on( 'change', 'select.country_to_state', function() {
		// Grab wrapping element to target only stateboxes in same 'group'
		var $wrapper    = $( this ).closest( '.wcv_shipping_rates' );

		var country     = $( this ).val(),
			$statebox   = $( this ).parent().next(), 
			state_input = $statebox.find( '.shipping_state' ),
			$parent     = $statebox.parent(),
			input_name  = state_input.attr( 'name' ),
			value       = state_input.val(),
			placeholder = state_input.attr( 'placeholder' ) || '';

		if ( states[ country ] ) {

			var options = '',
				state = states[ country ];

			for( var index in state ) {
				if ( state.hasOwnProperty( index ) ) {
					options = options + '<option value="' + index + '">' + state[ index ] + '</option>';
				}
			}

			state_select = $( '<select name="' + input_name + '" class="state_select shipping_state" placeholder="' + placeholder + '"><option value="">' + wcv_country_select_params.i18n_select_state_text + '</option>' + options + '</select>' );	
			$statebox.html( state_select );
			state_select.val( value ).change(); 
		
			$( document.body ).trigger( 'country_to_state_changed', [country, $wrapper ] );

		} else {

			if ( state_input.is( 'select' ) ) {

				state_select = $( '<input type="text" class="shipping_state" name="' + input_name + '" placeholder="' + placeholder + '" />' );	
				$statebox.html( state_select );

				$( document.body ).trigger( 'country_to_state_changed', [ country, $wrapper ] );

			} 
		}

		$( document.body ).trigger( 'country_to_state_changing', [country, $wrapper ] );

	});

	$( '.country_to_state' ).change(); 

});