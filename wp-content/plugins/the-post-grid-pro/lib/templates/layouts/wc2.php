<?php
$_product = wc_get_product( $pID );
$price = "<span class='price'>".$_product->get_price_html()."</span>";
$_rating_count = $_product->get_rating_count();
$_rating = wc_get_rating_html( $_rating_count? $_rating_count : .01);
$pType = $_product->get_type();
if(!empty($_rating)){
    $_rating = "<div class='product-rating'>".$_rating."</div>";
}
$button = null;
if($_product->is_purchasable()) {
	if($_product->is_in_stock()){
		$button = "<a href='{$pLink}?add-to-cart={$pID}' class='rt-wc-add-to-cart' data-id='{$pID}' data-type='{$pType}'>".__("Add To Cart", "the-post-grid-pro")."</a>";
	}
}
$html = $htmlDetail = $htmlTitle =null;

$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';

        $html .= '<div class="rt-img-holder">';
            $html .= '<div class="overlay">';
                $html .=    $price;
                if(in_array('rating', $items)) {
                    $html .= $_rating;
                }
                $html .= "<a class='view-search' href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'><i class='fa fa-search'></i></a>";
            $html .= '</div> ';
		if($imgSrc) {
			$html .= "<a href='{$pLink}'><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
		}
        $html .= '</div> ';

            if(in_array('title', $items)){
                $html .= "<div class='rt-detail rt-woo-info'>
                    <h3 class='product-title'>
                    <a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'{$link_target}>{$title}</a>
                    </h3>{$button}<span class='price-area'>".$price ."</span>
                    </div>";
            }
    $html .= '</div>';
$html .='</div>';

echo $html;