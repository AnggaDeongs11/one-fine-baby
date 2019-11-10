<?php

$_product = wc_get_product( $pID );
$pType = $_product->get_type();
$html = $htmlDetail = $htmlTitle = $_rating;
$button = null;
if($_product->is_purchasable()) {
	if($_product->is_in_stock()){
		$button = "<li><a href='{$pLink}?add-to-cart={$pID}' class='rt-wc-add-to-cart' data-id='{$pID}' data-type='{$pType}'><i class='fa fa-shopping-cart'></i></a></li>";
	}
}
if(in_array('rating', $items)) {
    $_rating_count = $_product->get_rating_count();
    $_rating = wc_get_rating_html( $_rating_count? $_rating_count : .01);
    $_rating = "<div class='product-rating'>".$_rating."</div>";
}
$html .= "<div class='{$grid} {$class} {$isoFilter}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';

if($imgSrc) {
	$html .= '<div class="rt-img-holder">';
	$html .= '<div class="overlay">';
	$html .= "<div class='product-more'>
                            <ul>{$button}
                                <li><a class='{$anchorClass}' data-id='{$pID}' href='{$pLink}'><i class='fa fa-search'></i></a></li>
                            </ul>
                        </div> ";
	$html .= '</div>';
	$html .= "<a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'{$link_target}><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
	$html .= '</div> ';
}
            if(in_array('title', $items)){
                $title = "<h3 class='product-title'><a class='{$anchorClass}' data-id='{$pID}' href='{$pLink}'{$link_target}>{$title}</a></h3>";
            }
            $price = "<span class='price'>".$_product->get_price_html()."</span>";

            $html .= "<div class='rt-woo-info rt-detail'>{$title}{$_rating}{$price}</div>";

    $html .= '</div>';
$html .='</div>';

echo $html;