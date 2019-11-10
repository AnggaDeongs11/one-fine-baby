<?php

$html = $htmlDetail = $iTitle = null;
if(in_array('title', $items)){
    $iTitle = "<h3 class='entry-title'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$title}</a></h3>";
}

$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';
            if($tpg_title_position == 'above'){
                $html .= sprintf('<div class="rt-detail rt-with-title">%s</div>', $iTitle);
            }
			if($imgSrc) {
			    $html .= sprintf('<div class="rt-img-holder">
<a data-id="%s" class="%s" href="%s"%s><img class="rt-img-responsive" src="%s" alt="%s"></a>
</div>', $pID, $anchorClass, $pLink, $link_target, $imgSrc, $title);
			}

            $postMetaTop = $postMetaMid =null;
            if($tpg_title_position != 'above'){
                $htmlDetail .= $iTitle;
            }
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
            if(in_array('cf', $items) && !empty($cf_group)){
	            $htmlDetail .= rtTPG()->get_cf_formatted_fields($cf_group, $format);
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

echo $html;