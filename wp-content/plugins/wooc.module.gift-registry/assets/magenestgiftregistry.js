function MagenestGiftRegistry() {
	
}
MagenestGiftRegistry.prototype.submitRegistry = function () {
	//var productId = jQuery("button[name='add-to-cart']").val();
	//jQuery("input[name='add-to-giftregistry']").val(productId);

	jQuery('#add-registry').val(1);
	jQuery('.cart').attr('url' ,'http://localhost/giftRegistry');
	jQuery('.cart').submit();
	
}
var giftRegistry = new MagenestGiftRegistry();