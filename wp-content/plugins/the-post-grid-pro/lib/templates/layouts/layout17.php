<?php

$html = null;
if($imgSrc){
	$fullSrc = rtTPG()->getFeatureImageSrc( $pID, 'full' );
	$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
	$html .= '<div class="rt-holder">';
	$html .= '<div class="overlay">';
	$html .= "<a class='tpg-zoom' title='{$title}' href='{$fullSrc}'><i class='fa fa-plus' aria-hidden='true'></i></a>";
	$html .= '</div>';
	$html .= "<img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'>";
	$html .= '</div>';
	$html .='</div>';
}


echo $html;
