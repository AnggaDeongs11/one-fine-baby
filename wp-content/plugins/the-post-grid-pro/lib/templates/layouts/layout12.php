<?php
$html = $metaHtmltop= $metaHtmlbottom = $titleHtml= null;
if(in_array('title', $items)){
    $titleHtml = "<h3 class='entry-title'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$title}</a></h3>";
}
if(in_array('post_date', $items) && $date){
    $metaHtmltop .= "<span class='date-meta'><i class='fa fa-calendar'></i> {$date}</span>";
}
if(in_array('author', $items)){
    $metaHtmltop .= "<span class='author'><i class='fa fa-user'></i>{$author}</span>";
 }
if(in_array('categories', $items) && $categories){
    $metaHtmltop .= "<span class='categories-links'><i class='fa fa-folder-open-o'></i>{$categories}</span>";
}
if(in_array('tags', $items) && $tags){
    $metaHtmltop .= "<span class='post-tags-links'><i class='fa fa-tags'></i>{$tags}</span>";
}
$num_comments = get_comments_number(); // get_comments_number returns only a numeric value
if(in_array('comment_count', $items) && $comment){
    $metaHtmltop .= '<span class="comment-count"><a href="' . get_comments_link() .'"><i class="fa fa-comments-o"></i> '. $num_comments.'</a></span>';
}
$postMetaBottom = null;
if(in_array('social_share', $items)){
    $postMetaBottom .= rtTPG()->rtShare($pID);
}
if(in_array('read_more', $items)){
    $postMetaBottom .= "<span class='read-more'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$read_more_text}</a></span>";
}


$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
    $html .= sprintf('<div class="rt-holder%s">',$tpg_title_position ? " rt-with-title-".$tpg_title_position : null);
        if($tpg_title_position == 'above'){
            $html .= sprintf('<div class="rt-detail rt-with-title">%s</div>', $titleHtml);
        }
		if($imgSrc) {
			$html .= '<div class="rt-img-holder">';
			$html .= "<a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
			$html .= '</div> ';
		}
        $html .="<div class='rt-detail'>";
            if($tpg_title_position == 'below'){
                $html .= $titleHtml;
            }
            $html .= sprintf('<div class="post-meta-user">%s</div>',$metaHtmltop);
            if(!$tpg_title_position){
                $html .=$titleHtml;
            }
            if(in_array('excerpt', $items)){
                $html .= "<div class='tpg-excerpt'>{$excerpt}</div>";
            }
           if(!empty($postMetaBottom)){
                $html .= "<div class='post-meta'>$postMetaBottom</div>";
            }
        $html .= "</div>";
    $html .= '</div>';
$html .='</div>';

echo $html;

