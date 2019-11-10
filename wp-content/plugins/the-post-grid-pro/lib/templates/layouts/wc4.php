<?php
$_product = wc_get_product( $pID );
$price = "<span class='price'>".$_product->get_price_html()."</span>";
$_rating = null;
$pType = $_product->get_type();
$html = $htmlDetail = $htmlTitle = $html_pinfo =null;
$button = null;
if($_product->is_purchasable()) {
	if($_product->is_in_stock()){
		$button = "<div><a href='{$pLink}?add-to-cart={$pID}' class='rt-wc-add-to-cart' data-id='{$pID}' data-type='{$pType}'>".__("Add To Cart", "the-post-grid-pro")."</a></div>";
	}
}
if(in_array('rating', $items)) {
    $_rating_count = $_product->get_rating_count();
    $_rating = wc_get_rating_html( $_rating_count? $_rating_count : .01);
    $_rating = "<div class='product-rating'>".$_rating."</div>";
}
$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
$html .= '<div class="rt-holder">';

if($imgSrc) {
	$html .= '<div class="rt-img-holder">';
	$html .= '<div class="overlay">';
	$html .= "<a class='view-search' href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'><i class='fa fa-search'></i></a>";
	$html .= '</div> ';
	$html .= "<a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
	$html .= '</div> ';
}
        if(in_array('title', $items)){
            $htmlTitle = "<h3 class='product-title'><a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'{$link_target}>{$title}</a></h3>";
        }
        $html_pinfo .="<div class='product-meta'><div class='price-area'>{$price}</div>{$button}</div>";
        $htmlDetail .= $htmlTitle . $_rating. $html_pinfo;
        if(!empty($htmlDetail)){
            $html .= "<div class='rt-detail rt-woo-info'>{$htmlDetail}</div>";
        }


$html .= '</div>';

$html .='</div>';

echo $html;