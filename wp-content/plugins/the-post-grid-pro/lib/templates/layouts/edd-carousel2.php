<?php
$price = null;
if(edd_has_variable_prices(get_the_ID())){
    $price = "<span class='price'>".edd_price_range(get_the_ID(), false)."</span>";
}else{
    $price = "<span class='price'>".edd_price(get_the_ID(), false)."</span>";
}
$html = $htmlTitle = $pType = null;

$html = $htmlTitle = $html_info =null;

$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
	$html .= '<div class="rt-holder">';
	if($imgSrc) {
		$html .= '<div class="rt-img-holder">';
			$html .= '<div class="overlay">';
				$html .= "<a class='view-search' href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'><i class='fa fa-search'></i></a>";
			$html .= '</div> ';
			$html .= "<a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'{$link_target}><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
		$html .= '</div> ';
	}
	if(in_array('title', $items)){
		$htmlTitle = "<h3 class='product-title'><a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'{$link_target}>{$title}</a></h3>";
	}
    $html .= sprintf("<div class='rt-detail rt-woo-info'>%s<div class='product-meta'>%s</div></div>", $htmlTitle, edd_get_purchase_link());
	$html .= '</div>';
$html .='</div>';

echo $html;