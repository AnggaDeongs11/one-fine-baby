<?php
//wc_print_notices();
wp_enqueue_style( 'thickbox' );
wp_enqueue_script( 'thickbox' );

$acount_page = get_page_by_path( 'my-account' );

$account_link        = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . 'my-gift-registry/';
$my_account_page_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
$w_page              = get_permalink( get_option( 'follow_up_emailgiftregistry_page_id' ) );
$http_schema         = 'http://';
if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] ) {
	$http_schema = 'https://';
}

$request_link = $http_schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

if ( strpos( $request_link, '?' ) > 0 ) {
	$buy_link = $w_page . '&giftregistry_id=';
} else {
	$buy_link = $w_page . '?giftregistry_id=';

}

$giftregistry_id = get_option( 'follow_up_emailgiftregistry_page_id' );

$giftregistry_page_path = get_permalink( $giftregistry_id );

//hidden image
//$flow_img = GIFTREGISTRY_URL . '/assets/flow.jpg';

?>

<!--<img src = "--><?php //echo $flow_img ?><!--" />-->
<!-- Table of result -->

<?php
$collection = array();
global $giftregistryresult;

if ( isset( $_SESSION['registryresult'] ) ) {
	$collection = $_SESSION['registryresult'];
}
$collection = $giftregistryresult;

if ( ! empty( $collection ) ) {
	?>
	<table>
        <tr>
            <th><?php echo __( 'Name', GIFTREGISTRY_TEXT_DOMAIN ) ?></th>
            <th><?php echo __( 'Co-registrant name', GIFTREGISTRY_TEXT_DOMAIN ) ?></th>
            <th><?php echo __( 'Email', GIFTREGISTRY_TEXT_DOMAIN ) ?></th>
            <th><?php echo __( 'Co-registrant email', GIFTREGISTRY_TEXT_DOMAIN ) ?></th>
            <th><?php echo __( 'View', GIFTREGISTRY_TEXT_DOMAIN ) ?></th>
        </tr>
		<?php foreach ( $collection as $item ) {
			$link = $buy_link . $item['id'];
			global $wpdb;
			$prefix      = $wpdb->prefix;
			$wishlistTbl = $prefix . 'magenest_giftregistry_wishlist';
			$sql         = $wpdb->prepare( "SELECT * FROM $wishlistTbl WHERE `id` = %d", $item['id'] );
			$result      = $wpdb->get_row( $sql, ARRAY_A );
			$role        = $result['role'];
			?>
			<tr>
                <td><?php echo $item['registrant_firstname'] . ' ' . $item['registrant_lastname'] ?></td>


                <td><?php echo $item['coregistrant_firstname'] . ' ' . $item['coregistrant_lastname'] ?></td>


                <td><?php echo $item['registrant_email'] ?></td>


                <td><?php echo $item['coregistrant_email'] ?></td>


                <td>
                    <?php
                    if ( $role == 0 ) {
	                    ?>
	                    <a href = "<?php echo $link ?>">
                            <input type = "submit" value = "<?php echo __( "View", GIFTREGISTRY_TEXT_DOMAIN ) ?>">
                        </a>
	                    <?php
                    } else {

	                    ?>
	                    <form action = "" method = "get">
                            <input type = "hidden" name = "wishlist_id" value = "<?= $item['id'] ?>">
                            <input name = "checkpass_giftregistry" id = "checkpass_giftregistry" type = "hidden"
                                   value = "1" />
                            <input type = "submit" value = "<?php echo __("Private", GIFTREGISTRY_TEXT_DOMAIN) ?>">
                        </form>
	                    <?php
                    }

                    ?>
                </td>
            </tr>
		<?php } ?>
    </table>
<?php }
if ( isset( $_REQUEST['checkpass_giftregistry'] ) ) {
	$wishlis_id             = isset( $_REQUEST['wishlist_id'] ) ? $_REQUEST['wishlist_id'] : 0;
	$checkpass_giftregistry = isset( $_REQUEST['checkpass_giftregistry'] ) ? $_REQUEST['checkpass_giftregistry'] : '';
	if ( $checkpass_giftregistry == 0 ) {
		echo '<b style="color: red;"><?=__("Password incorrect! Please try again.",GIFTREGISTRY_TEXT_DOMAIN)?></b>';
	}
	?>
	<h4 style = "margin-top: 50px;"><?=__('The gift registry is protected by password. Please enter the password.',GIFTREGISTRY_TEXT_DOMAIN)?></h4>
	<h2><?=__('Password',GIFTREGISTRY_TEXT_DOMAIN)?></h2>
	<form action = "<?php echo $giftregistry_page_path ?>" method = "post">
        <input type = "hidden" name = "wishlist_id" value = "<?= $wishlis_id ?>">
        <input type = "text" name = "password">
        <input type = "submit" name = "btnSubmit" value = "<?php echo __('Submit',GIFTREGISTRY_TEXT_DOMAIN) ?>">
    </form>

	<?php

} else {


	if ( isset( $_REQUEST['btnSubmit'] ) ) {
		$wishlis_id = isset( $_REQUEST['wishlist_id'] ) ? $_REQUEST['wishlist_id'] : 0;
		global $wpdb;
		$prefix      = $wpdb->prefix;
		$wishlistTbl = $prefix . 'magenest_giftregistry_wishlist';
		$query       = $wpdb->prepare( "SELECT * FROM $wishlistTbl WHERE `id` = %d", $wishlis_id );
		//$query = 'SELECT * FROM '.$wishlistTbl.' WHERE id='.$wishlis_id;
		$row      = $wpdb->get_row( $query, ARRAY_A );
		$pass     = $row['password'];
		$password = $_REQUEST['password'];


		if ( $password == $pass ) {
			$http_schema = 'http://';
			if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] ) {
				$http_schema = 'https://';
			}
			$w_page = get_permalink( get_option( 'follow_up_emailgiftregistry_page_id' ) );

			$request_link = $http_schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . $_SERVER["QUERY_STRING"];
			if ( strpos( $request_link, '?' ) > 0 ) {
				$buy_link = $w_page . '&giftregistry_id=';
			} else {
				$buy_link = $w_page . '?giftregistry_id=';

			}
			$link = $buy_link . $wishlis_id;

			echo '<script type="text/javascript">window.location.href = "' . $link . '";</script>';
			exit;
			?>

			<h3><a href = "<?php echo $link ?>">
                    <?php echo __( "View", GIFTREGISTRY_TEXT_DOMAIN ) ?>
                </a></h3>

			<?php
		} else {
			if ( strpos( $request_link, '?' ) > 0 ) {
				$buy_link = $w_page . '&wishlist_id=';
			} else {
				$buy_link = $w_page . '?wishlist_id=';
			}
			$link = $buy_link . $wishlis_id . '&checkpass_giftregistry=0';
			echo '<script type="text/javascript">window.location.href = "' . $link . '";</script>';
			exit;

		}
	}
	?>

	<form id = "searchgiftregistry" method = "POST" action = "<?php echo $giftregistry_page_path ?>">
        <input name = "searchgiftregistry" value = "1" type = "hidden" />
        <table class = "table" style = "margin-top: 10px; margin-bottom: 10px;">
            <tr>
                <td><label><?php echo __( 'By name', GIFTREGISTRY_TEXT_DOMAIN ) ?></label></td>
                <td><input type = "text"
                           name = "grname" <?php if ( isset( $_SESSION['registrynamesearch'] ) ) { ?> value = "<?php echo $_SESSION['registrynamesearch'] ?>" <?php } ?>>
                </td>
            </tr>
            <tr>
                <td><label><?php echo __( 'By email', GIFTREGISTRY_TEXT_DOMAIN ) ?></label></td>
                <td><input type = "text"
                           name = "email" <?php if ( isset( $_SESSION['registryemailsearch'] ) ) { ?> value = "<?php echo $_SESSION['registryemailsearch'] ?>" <?php } ?> >
                </td>
            </tr>
            <tr>
                <td colspan = "2">
	                <input type = "submit" name = "submit"
	                       value = "<?php echo __( 'Search gift registry', GIFTREGISTRY_TEXT_DOMAIN ) ?>">
                </td>
            </tr>
        </table>
    </form>
	<!-- end table of search -->
	<?php
	$user_id    = get_current_user_id();
	$permission = get_option( 'giftregistry_enable_permission', true );
	if ( $permission == 'yes' || $user_id > 0 ) {
		?>
		<div>
            <span style = "list-style-type: none; margin-right: 5px;"><a
		            href = "<?php echo $account_link ?>"
		            class = "button"> <?php echo __( 'Create gift registry', GIFTREGISTRY_TEXT_DOMAIN ) ?></a>
            </span>

			<?php if ( ! class_exists( 'Magenest_Giftregistry_Model' ) ) {
				include_once GIFTREGISTRY_PATH . 'model/magenest-giftregistry-model.php';
			}

			$gr_id = Magenest_Giftregistry_Model::get_wishlist_id();
			if ( $gr_id ) {
				{ ?>
					<span style = "list-style-type: none; "><a
							href = "<?php echo $buy_link . $gr_id ?>"
							class = "button"> <?php echo __( 'View my gift registry', GIFTREGISTRY_TEXT_DOMAIN ) ?></a>
                    </span>
				<?php }
			} ?>
			<!-- end my gift registry -->
        </div>
		<?php
	}
	?>


	<?php
}
?>
<!-- End of result -->
<!-- table of search -->