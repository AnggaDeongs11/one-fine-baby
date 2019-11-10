<?php
/**
 * Created by PhpStorm.
 * User: mdq
 * Date: 07/08/2018
 * Time: 15:22
 */
//wc_print_notices();
wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');

$acount_page = get_page_by_path('my-account');

$account_link = get_permalink(get_option('woocommerce_myaccount_page_id')) . 'my-gift-registry/';
$my_account_page_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
$w_page = get_permalink(get_option('follow_up_emailgiftregistry_page_id'));
$http_schema = 'http://';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
    $http_schema = 'https://';
}

$request_link = $http_schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

if (strpos($request_link, '?') > 0) {
    $buy_link = $w_page . '&giftregistry_id=';
} else {
    $buy_link = $w_page . '?giftregistry_id=';

}

$giftregistry_id = get_option('follow_up_emailgiftregistry_page_id');

$giftregistry_page_path = get_permalink($giftregistry_id);

//hidden image
//$flow_img = GIFTREGISTRY_URL . '/assets/flow.jpg';

$collection = array();
global $giftregistryresult;

if (isset($_REQUEST['end_buy_giftregistry']) && isset($_SESSION['buy_for_giftregistry_id'])) {
    unset($_SESSION['buy_for_giftregistry_id']);
    unset($_SESSION['giftregistry_id']);
}

if (isset($_SESSION['registryresult'])) {
    $collection = $_SESSION['registryresult'];
}
$collection = $giftregistryresult;

if (isset($_REQUEST['checkpass_giftregistry'])) {
    ?>
    </div>
    <div class="check-password-view-search">
        <?php
        wp_enqueue_style('checkpass_giftregistry');
        $wishlist_id = isset($_REQUEST['wishlist_id']) ? $_REQUEST['wishlist_id'] : 0;
        $checkpass_giftregistry = isset($_REQUEST['checkpass_giftregistry']) ? $_REQUEST['checkpass_giftregistry'] : '';
        if ($checkpass_giftregistry == 0) {
            echo '<b style="color: red;">' . __('Password incorrect! Please try again.', GIFTREGISTRY_TEXT_DOMAIN) . '</b>';
        }
        ?>
        <h4 style="margin-top: 50px;"><?= __('The gift registry is protected by password. Please enter the password.', GIFTREGISTRY_TEXT_DOMAIN) ?></h4>
        <h2><?=__('Password',GIFTREGISTRY_TEXT_DOMAIN);?></h2>
        <form action="<?php echo $giftregistry_page_path ?>" method="post">
            <input type="hidden" name="wishlist_id" value="<?= $wishlist_id ?>">
            <input type="text" name="password">
            <input type="submit" name="giftregistry_pw" value="<?php echo __('Submit', GIFTREGISTRY_TEXT_DOMAIN) ?>">
        </form>
    </div>
    <?php

} else {
    ?>
    <div class="search">

        <h2><?= __('Search gift registry', GIFTREGISTRY_TEXT_DOMAIN) ?></h2>


        <form id="searchgiftregistry" method="POST" action="<?php echo $giftregistry_page_path ?>">
            <div class="a-row wr-search-input-row">
                <input type="text"
                       name="grname"
                       value="<?php echo isset($_SESSION['registrynamesearch']) ? $_SESSION['registrynamesearch'] : '' ?>"
                       placeholder="<?=__('Search by name',GIFTREGISTRY_TEXT_DOMAIN)?>">
                <input type="text"
                       name="email"
                       value="<?php echo isset($_SESSION['registryemailsearch']) ? $_SESSION['registryemailsearch'] : '' ?>"
                       placeholder="<?=__('Search by email',GIFTREGISTRY_TEXT_DOMAIN)?>">
                <input type="hidden" name="searchgiftregistry">
            </div>
            <input type="submit" id="search_giftregistry"
                   value="<?php echo __('Search gift registry', GIFTREGISTRY_TEXT_DOMAIN) ?>">
        </form>

    </div>
    </div>
    </div>
    <?php
}
if (!empty($collection)) {
    wp_enqueue_style('search_result');
    $coregistrant_name = false;
    foreach ($collection as $item) {
        if (!empty($item['coregistrant_firstname']) || !empty($item['coregistrant_lastname'])) {
            $coregistrant_name = true;
            break;
        }
    }
    $coregistrant_email = false;
    foreach ($collection as $item) {
        if (!empty($item['coregistrant_email'])) {
            $coregistrant_email = true;
            break;
        }
    }
    ?>
    </div>
    <div class="search-result">
        <table>
            <tr>

                <th><?php echo __('Name', GIFTREGISTRY_TEXT_DOMAIN) ?></th>
                <?php
                if ($coregistrant_name) {
                    ?>
                    <th><?php echo __('Co-registrant name', GIFTREGISTRY_TEXT_DOMAIN) ?></th>
                    <?php
                }
                ?>
                <th><?php echo __('Email', GIFTREGISTRY_TEXT_DOMAIN) ?></th>
                <?php
                if ($coregistrant_email) {
                    ?>
                    <th><?php echo __('Co-registrant email', GIFTREGISTRY_TEXT_DOMAIN) ?></th>
                    <?php
                }
                ?>
                <th><?php echo __('View', GIFTREGISTRY_TEXT_DOMAIN) ?></th>
            </tr>
            <?php foreach ($collection as $item) {
                $link = $buy_link . $item['id'];
                global $wpdb;
                $prefix = $wpdb->prefix;
                $wishlistTbl = $prefix . 'magenest_giftregistry_wishlist';
                $sql = $wpdb->prepare("SELECT * FROM $wishlistTbl WHERE `id` = %d", $item['id']);
                $result = $wpdb->get_row($sql, ARRAY_A);
                $role = $result['role'];
                ?>
                <tr>
                    <td><?php echo $item['registrant_firstname'] . ' ' . $item['registrant_lastname'] ?></td>


                    <?php
                    if ($coregistrant_name) {
                        ?>
                        <td><?php echo $item['coregistrant_firstname'] . ' ' . $item['coregistrant_lastname'] ?></td>
                        <?php
                    }
                    ?>


                    <td><?php echo $item['registrant_email'] ?></td>


                    <?php
                    if ($coregistrant_email) {
                        ?>
                        <td><?php echo $item['coregistrant_email'] ?></td>
                        <?php
                    }
                    ?>


                    <td>
                        <?php
                        if ($role == 0) {
                            ?>
                            <a href="<?php echo $link ?>">
                                <input type="submit" value="<?php echo __("View", GIFTREGISTRY_TEXT_DOMAIN) ?>">
                            </a>
                            <?php
                        } else {

                            ?>
                            <form action="" method="get">
                                <input type="hidden" name="wishlist_id" value="<?= $item['id'] ?>">
                                <input name="checkpass_giftregistry" id="checkpass_giftregistry" type="hidden"
                                       value="1"/>
                                <input type="submit" value="<?php echo __("Private", GIFTREGISTRY_TEXT_DOMAIN) ?>">
                            </form>
                            <?php
                        }

                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
    /* Delete session after search*/
    if (isset($_SESSION['registrynamesearch'])) {
        unset($_SESSION['registrynamesearch']);
    }
    if (isset($_SESSION['registryemailsearch'])) {
        unset($_SESSION['registryemailsearch']);
    }
    if (isset($_SESSION['registryresult'])) {
        unset($_SESSION['registryresult']);
    }
} else if (isset($_SESSION['registrynamesearch']) || isset($_SESSION['registryemailsearch'])) {
    wp_enqueue_style('search_result');
    ?>
    </div>
    <div class="search-result">
        <p><?=__('No results found',GIFTREGISTRY_TEXT_DOMAIN)?></p>
    </div>
    <?php
    if (isset($_SESSION['registrynamesearch'])) {
        unset($_SESSION['registrynamesearch']);
    }
    if (isset($_SESSION['registryemailsearch'])) {
        unset($_SESSION['registryemailsearch']);
    }
    if (isset($_SESSION['registryresult'])) {
        unset($_SESSION['registryresult']);
    }
}
?>
