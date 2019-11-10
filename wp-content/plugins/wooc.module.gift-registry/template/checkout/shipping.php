<script type="text/javascript">
    alert('test');
</script>
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
//error_log( 'chomped all downed ' . $w_id );
$wishlist = Magenest_Giftregistry_Model::get_wishlist( $w_id );
echo $wishlist->id;

echo "hehe";
?>
