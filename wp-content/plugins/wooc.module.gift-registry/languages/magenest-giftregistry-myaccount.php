<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Magenest_Giftregistry_MyAccount {

	public function __construct() {
		//add_action('woocommerce_after_add_to_cart_button', array($this,'show_gift_registry_link'));

		//account page
		//add_action('woocommerce_after_my_account', array($this,'show_my_registry'));
	}

	public function show_gift_registry_link() {
		?>

		<script type="text/javascript">
			function add_Registry() {
				jQuery("input[name='add-to-cart']").attr('name' ,'add-to-giftregistry');
				jQuery('#add-registry').val(1);
				jQuery('.cart').attr('url' ,'');
				jQuery('.cart').submit();
			}
		</script>
		<br>
		<div class="add-gift-registry button alt" style="margin-top: 10px; width: 33.5%;">
			<?php
			$product_id = get_the_ID();
			?>
			<input type="hidden" name="add-registry" id="add-registry"/>
			<input type="hidden" name="add-to-giftregistry" id="add-to-giftregistry" value="<?= $product_id; ?>"/>
			<?php
				echo '<a href="#" onclick="add_Registry()" class="single_add_to_cart_button button">'. __('Add to gift registry', GIFTREGISTRY_TEXT_DOMAIN) .'</a>'
			?>

		</div>
	<?php
	}

	public function show_my_registry() {

	echo	$this->show_create_giftregistry_part();

	$wl_items = Magenest_Giftregistry_Model::get_wishlist_items_for_current_user();

	echo $this->show_my_giftregistry_part($wl_items);

	//shared part
	$giftregistry_page_url = get_permalink( get_option('follow_up_emailgiftregistry_page_id'));

	$wid = Magenest_Giftregistry_Model::get_wishlist_id();

	$http_schema = 'http://';
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])  {
		$http_schema = 'https://';
	}

	$request_link = $http_schema . $_SERVER ["HTTP_HOST"] . $_SERVER ["REQUEST_URI"];

		if (strpos ( $request_link, '?' ) > 0) {
			$giftregistry_page_url = $giftregistry_page_url. '&giftregistry_id=' . $wid;
		} else {
			$giftregistry_page_url = $giftregistry_page_url . '?giftregistry_id=' . $wid;
		}

		echo $this->share_links($giftregistry_page_url);
	}

	/**
	 * @return string
	 */
	public  function show_create_giftregistry_part() {

		$wid = Magenest_Giftregistry_Model::get_wishlist_id();

		//if (is_numeric($wid))
		ob_start();

		$template_path = GIFTREGISTRY_PATH.'template/account/';
		$default_path = GIFTREGISTRY_PATH.'template/account/';


		wc_get_template( 'add-giftregistry.php', array(
		'wid' 		=>$wid,
		'order_id' => '2',
		),$template_path,$default_path
		);
		return ob_get_clean();
	}

	/**
	 *
	 * @param unknown $items
	 * @return string
	 */
	public function show_my_giftregistry_part($items) {
		$wid = Magenest_Giftregistry_Model::get_wishlist_id();

		ob_start();

		$template_path = GIFTREGISTRY_PATH.'template/account/';
		$default_path = GIFTREGISTRY_PATH.'template/account/';


		wc_get_template( 'my-giftregistry.php', array(
		'items' 		=>$items,
		'wid' 		=>$wid
		),$template_path,$default_path
		);
		return ob_get_clean();
	}
	public function share_links($url) {


		ob_start();

		$template_path = GIFTREGISTRY_PATH.'template/account/';
		$default_path = GIFTREGISTRY_PATH.'template/account/';


		wc_get_template( 'giftregistry-share.php', array(
		'url' 		=>$url,
		),$template_path,$default_path
		);
		return  ob_get_clean();
	}
}

return new Magenest_Giftregistry_MyAccount();