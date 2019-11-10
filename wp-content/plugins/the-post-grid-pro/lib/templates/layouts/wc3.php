<?php
$_product = wc_get_product( $pID );
$price = "<span class='price'>".$_product->get_price_html()."</span>";
$_rating_count = $_product->get_rating_count();
$_rating = wc_get_rating_html( $_rating_count? $_rating_count : .01);
$pType = $_product->get_type();
$html = $htmlDetail = $htmlTitle =null;

$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';
if($imgSrc) {
	$html .= "<div class='rt-col-xs-12 rt-col-sm-12 rt-col-md-5 rt-col-lg-5'>";
	$html .= "<div class='grid-img rt-img-holder'><a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'{$link_target}><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a></div>";
	$html .= "</div>";
	$content_area = "rt-col-xs-12 rt-col-sm-12 rt-col-md-7 rt-col-lg-7";
}else{
	$content_area = "rt-col-md-12";
}
        $html .= "<div class='{$content_area}'>";
            $html .= "<div class='rt-detail rt-woo-info'>";
                if(in_array('title', $items)){
                    $html .= "<h3 class='product-title'><a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'{$link_target}>{$title}</a> {$price}</h3>";
                }
                if(in_array('rating', $items)) {
                    $_rating = "<div class='product-rating'>".$_rating."</div>";
                }
                $html .="<p>{$excerpt}</p>";
				if($_product->is_purchasable()) {
					if($_product->is_in_stock()){
						$html .= "<a href='?add-to-cart={$pID}' class='rt-wc-add-to-cart' data-id='{$pID}' data-type='{$pType}'>" . __( "Add To Cart", "the-post-grid-pro" ) . "</a>";
					}else{
						$html .= '<mark class="outofstock">' . __( 'Out of stock', 'woocommerce' ) . '</mark>';
					}
				}
            $html .= '</div>';
        $html .= '</div>';
    $html .= '</div>';
$html .='</div>';

echo $html;