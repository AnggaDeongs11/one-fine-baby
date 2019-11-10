<?php

$html = $htmlDetail =null;
if(!empty($offset) && $offset == "big"){
	$grid = "rt-col-xs-12";
	$class = $class ." offset-big";

	$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
	$html .= '<div class="rt-holder">';
	$html .= '<div class="rt-img-holder">';
	if($imgSrc) {
		$html .= "<a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
	}
	$html .= '</div> ';

	if(in_array('title', $items)){
		$htmlDetail .= "<h3 class='entry-title'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$title}</a></h3>";
	}
	$postMetaTop = $postMetaMid =null;

	if(in_array('author', $items)){
		$postMetaTop .= "<span class='author'><i class='fa fa-user'></i>{$author}</span>";
	}
	if(in_array('post_date', $items) && $date){
		$postMetaTop .= "<span class='date'><i class='fa fa-calendar'></i>{$date}</span>";
	}
	if(in_array('comment_count', $items) && $comment){
		$postMetaTop .= "<span class='comment-link'><i class='fa fa-comments-o'></i>{$comment}</span>";
	}
	if(in_array('categories', $items) && $categories){
		$postMetaTop .= "<span class='categories-links'><i class='fa fa-folder-open-o'></i>{$categories}</span>";
	}
	if(in_array('tags', $items) && $tags){
		$postMetaTop .= "<span class='post-tags-links'><i class='fa fa-tags'></i>{$tags}</span>";
	}
	if(!empty($postMetaTop)){
		$htmlDetail .= "<div class='post-meta-user'>{$postMetaTop}</div>";
	}
	if(!empty($postMetaMid)){
		$htmlDetail .= "<div class='post-meta-tags'>{$postMetaMid}</div>";
	}
	if(in_array('excerpt', $items)){
		$htmlDetail .= "<div class='tpg-excerpt'>{$excerpt}</div>";
	}
	$postMetaBottom = null;
	if(in_array('social_share', $items)){
		$postMetaBottom .= rtTPG()->rtShare($pID);
	}
	if(in_array('read_more', $items)){
		$postMetaBottom .= "<span class='read-more'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$read_more_text}</a></span>";
	}
	if(!empty($postMetaBottom)){
		$htmlDetail .= "<div class='post-meta'>$postMetaBottom</div>";
	}
	if(!empty($htmlDetail)){
		$html .="<div class='rt-detail'>$htmlDetail</div>";
	}
	$html .= '</div>';
	$html .='</div>';

}elseif(!empty($offset) && $offset == "small"){
	$dCol = $tCol = $mCol = 12;
	$class = $class ." offset-small";
	if(!empty($offsetCol[0]) && $offsetCol[0] ==4){
		$dCol = 6;
	}if(!empty($offsetCol[1]) && $offsetCol[1] ==4){
		$tCol = 6;
	}if(!empty($offsetCol[2]) && $offsetCol[2] ==4){
		$mCol = 6;
	}
	$grid = "rt-col-md-{$dCol} rt-col-sm-{$tCol} rt-col-xs-{$mCol}";

	$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
		$html .= '<div class="rt-holder">';
		$html .= '<div class="rt-img-holder">';
		if($imgSrc) {
			$html .= "<a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
		}
		$html .= '</div> ';

		if(in_array('title', $items)){
			$htmlDetail .= "<h3 class='entry-title'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$title}</a></h3>";
		}
		$postMetaTop = $postMetaMid =null;

		if(in_array('author', $items)){
			$postMetaTop .= "<span class='author'><i class='fa fa-user'></i>{$author}</span>";
		}
		if(in_array('post_date', $items) && $date){
			$postMetaTop .= "<span class='date'><i class='fa fa-calendar'></i>{$date}</span>";
		}
		if(in_array('comment_count', $items) && $comment){
			$postMetaTop .= "<span class='comment-link'><i class='fa fa-comments-o'></i>{$comment}</span>";
		}
		if(in_array('categories', $items) && $categories){
			$postMetaTop .= "<span class='categories-links'><i class='fa fa-folder-open-o'></i>{$categories}</span>";
		}
		if(in_array('tags', $items) && $tags){
			$postMetaTop .= "<span class='post-tags-links'><i class='fa fa-tags'></i>{$tags}</span>";
		}
		if(!empty($postMetaTop)){
			$htmlDetail .= "<div class='post-meta-user'>{$postMetaTop}</div>";
		}
		if(!empty($postMetaMid)){
			$htmlDetail .= "<div class='post-meta-tags'>{$postMetaMid}</div>";
		}

		if(!empty($htmlDetail)){
			$html .="<div class='rt-detail'>$htmlDetail</div>";
		}
		$html .= '</div>';
	$html .='</div>';
}
echo $html;