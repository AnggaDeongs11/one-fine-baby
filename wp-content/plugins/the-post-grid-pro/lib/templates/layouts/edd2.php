<?php
$price = null;
if(edd_has_variable_prices(get_the_ID())){
    $price = "<span class='price'>".edd_price_range(get_the_ID(), false)."</span>";
}else{
    $price = "<span class='price'>".edd_price(get_the_ID(), false)."</span>";
}
$html = $htmlDetail = $htmlTitle = $pType = null;

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
				if($excerpt) {
                    $html .= "<p>{$excerpt}</p>";
                }
				$html .= sprintf("<div class='product-meta'>%s</div>", edd_get_purchase_link());
			$html .= '</div>';
		$html .= '</div>';
	$html .= '</div>';
$html .='</div>';

echo $html;