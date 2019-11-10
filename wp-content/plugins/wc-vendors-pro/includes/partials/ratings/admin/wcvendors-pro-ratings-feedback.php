<?php

/**
 * Feedback style for the vendor feedback table
 *
 * This file is used to display the ratings link
 *
 * @link       http://www.wcvendors.com
 * @since      1.0.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/includes/partials/ratings
 */

$rating   = '';
$title    = '';
$comments = '';

$comments = '<p>' . $fb->comments . '</p>';

for ( $i = 1; $i <= stripslashes( $fb->rating ); $i ++ ) {
	$rating .= '<svg class="wcv-icon wcv-icon-sm">
                    <use xlink:href="' . WCV_PRO_PUBLIC_ASSETS_URL . 'svg/wcv-icons.svg#wcv-icon-star"></use>
                </svg>';
}
for ( $i = stripslashes( $fb->rating ); $i < 5; $i ++ ) {
	$rating .= '<svg class="wcv-icon wcv-icon-sm">
                    <use xlink:href="' . WCV_PRO_PUBLIC_ASSETS_URL . 'svg/wcv-icons.svg#wcv-icon-star-o"></use>
                </svg>';
}

$title = '<h6>' . $fb->rating_title . '    ' . $rating . '</h6>';


$feedback = $title . $comments;
