jQuery( document ).ready( function( $ ) {
	var $storageTabLink = $( '#cf7-storage-tab a' );

	if ( '#cf7-storage' === location.hash && $storageTabLink.length ) {
		$storageTabLink.click();
	}
} );
