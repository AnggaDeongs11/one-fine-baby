<?php

$html = $metaHtml = $titleHtml =null;
if(in_array('title', $items)){
	$titleHtml .= "<h3 class='entry-title'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$title}</a></h3>";
}

if(!empty($offset) && $offset == "big"){
	$grid = "rt-col-xs-12";
	$class = $class ." offset-big";

	$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
		$html .= '<div class="rt-holder">';
			$html .= "<div class='overlay'>";
			$html .= "<a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";

			$html .="</div>";
			$html .= "<div class='post-info'>{$titleHtml}</div>";
		$html .= '</div>';
	$html .='</div>';

}elseif(!empty($offset) && $offset == "small"){
	$dCol = $tCol = $mCol = 6;
	$class = $class ." offset-small";
	$grid = "rt-col-md-{$dCol} rt-col-sm-{$tCol} rt-col-xs-{$mCol}";
	$imgSrc = rtTPG()->getFeatureImageSrc( $pID, 'medium');
	$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
		$html .= '<div class="rt-holder">';
			$html .= "<div class='overlay'>";
				$html .= "<a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
			$html .="</div>";
			$html .= "<div class='post-info'>{$titleHtml}</div>";
		$html .= '</div>';
	$html .='</div>';
}
echo $html;