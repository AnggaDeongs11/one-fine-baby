<?php

$html = $htmlDetail = $htmlTitle =null;
$price = null;
if(edd_has_variable_prices(get_the_ID())){
    $price = "<span class='price'>".edd_price_range(get_the_ID(), false)."</span>";
}else{
    $price = "<span class='price'>".edd_price(get_the_ID(), false)."</span>";
}
$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';
    $html .= '<div class="rt-img-holder">';
        $html .= '<div class="overlay">';
            $html .= "<div class='product-more'>
                        <ul>
                            <li>".edd_get_purchase_link()."</li>
                            <li><a class='{$anchorClass}' data-id='{$pID}' href='{$pLink}'{$link_target}><i class='fa fa-search'></i></a></li>
                        </ul>
                      </div> ";
            $html .= '</div>';
        if($imgSrc) {
            $html .= "<a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'{$link_target}><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
        }
    $html .= '</div> ';
    if(in_array('title', $items)){
        $title = "<h3  class='product-title'><a class='{$anchorClass}' data-id='{$pID}' href='{$pLink}'{$link_target}>{$title}</a></h3>";
    }
    $html .= "<div class='rt-woo-info rt-edd-info'>{$title}{$price}</div>";
    $html .= '</div>';
$html .='</div>';

echo $html;