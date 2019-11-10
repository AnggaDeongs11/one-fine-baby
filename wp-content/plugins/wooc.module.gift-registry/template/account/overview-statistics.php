<?php
/**
 * Created by PhpStorm.
 * User: mdq
 * Date: 17/09/2018
 * Time: 11:22
 */
$wid = Magenest_Giftregistry_Model::get_wishlist_id();
$wishlist = Magenest_Giftregistry_Model::get_wishlist($wid);

/*Name of registrant and co-registrant*/
if (is_object($wishlist)) {
    $name = $wishlist->registrant_firstname . ' ' . $wishlist->registrant_lastname;
    if (!empty($wishlist->coregistrant_firstname) && !empty($wishlist->coregistrant_lastname) && $wishlist->enable_coregistrant == "1") {
        $name .= ' & ' . $wishlist->coregistrant_firstname . ' ' . $wishlist->coregistrant_lastname;
    }
}

/*Number of day before or after event date*/
if (is_object($wishlist)) {
    $event_date = new DateTime($wishlist->event_date_time);
    $current_time = new DateTime();
    $diff = date_diff(new DateTime($current_time->format('Y-m-d')), new DateTime($event_date->format('Y-m-d')));
    $days = $diff->format("%R%a");
}

/*Get total receive item, total gifts in list*/
$items = Magenest_Giftregistry_Model::get_wishlist_items_for_current_user();

if(empty(get_user_meta(get_current_user_id(), 'gr_purchased'))){
    $purchased = 0;
}else{
    $purchased =get_user_meta(get_current_user_id(), 'gr_purchased')[0];
}

$total_gifts = 0;
if (is_array($items)) {
    foreach ($items as $item) {
        $total_gifts += $item['quantity'];
    }
}
if (is_object($wishlist)) {
    ?>
    <h3 style="text-align: center"><strong> <?= $name ?></strong></h3>
    <table class="overview-statistics">
        <tr>
            <td><?= abs($days) ?></td>
            <td><?= $total_gifts ?></td>
            <td style="border-right:none"><?= $purchased ?></td>
        </tr>
        <tr>
            <?php
            if ($days >= 2) {
                ?>
                <th><?=__('Days to go',GIFTREGISTRY_TEXT_DOMAIN)?></th>
                <?php
            } else if ($days >= 0 && $days < 2) {
                ?>
                <th><?=__('Day to go',GIFTREGISTRY_TEXT_DOMAIN)?></th>
                <?php
            } else if ($days <= -2) {
                ?>
                <th><?=__('Days past',GIFTREGISTRY_TEXT_DOMAIN)?></th>
                <?php
            } else {
                ?>
                <th><?=__('Day past',GIFTREGISTRY_TEXT_DOMAIN)?></th>
                <?php
            }
            if ($total_gifts >= 2) {
                ?>
                <th><?=__('Total Gifts',GIFTREGISTRY_TEXT_DOMAIN)?></th>
                <?php
            } else {
                ?>
                <th><?=__('Total Gift',GIFTREGISTRY_TEXT_DOMAIN)?></th>
                <?php
            }
            ?>
            <th><?=__('Purchased',GIFTREGISTRY_TEXT_DOMAIN)?></th>
        </tr>
    </table>
    <?php
}
?>