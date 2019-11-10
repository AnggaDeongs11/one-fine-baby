<?php

$html = $htmlDetail =null;

$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';
        $html .= '<div class="rt-row">';
			if($imgSrc) {
				$html .= "<div class='{$image_area}'>";
				$html .= '<div class="rt-img-holder">';
				$html .= "<a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
				$html .= '</div>';
				$html .= '</div>';
			}else{
				$content_area = "rt-col-xs-12";
			}
            $html .= "<div class='{$content_area}'>";

                    if(in_array('title', $items)){
                        $htmlDetail .= "<h3 class='entry-title'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$title}</a></h3>";
                    }

                    $metaHtml = null;
                    
                    if(in_array('post_date', $items) && $date){
                        $metaHtml .= "<span class='date-meta'><i class='fa fa-calendar'></i> {$date}</span>";
                    }
                    if(in_array('author', $items)){
                        $metaHtml .= "<span class='author'><i class='fa fa-user'></i>{$author}</span>";
                     }
                    if(in_array('categories', $items) && $categories){
                        $metaHtml .= "<span class='categories-links'><i class='fa fa-folder-open-o'></i>{$categories}</span>";
                    }
                    if(in_array('tags', $items) && $tags){
                        $metaHtml .= "<span class='post-tags-links'><i class='fa fa-tags'></i>{$tags}</span>";
                    }
                    if(in_array('comment_count', $items) && $comment){
                        $metaHtml .= "<span class='comment-link'><i class='fa fa-comments-o'></i>{$comment}</span>";
                    }
                    if(!empty($metaHtml)){
                        $htmlDetail .="<div class='post-meta-user'>$metaHtml</div>";
                    }

                    if(in_array('excerpt', $items)){
                        $htmlDetail .= "<div class='tpg-excerpt'>{$excerpt}</div>";
                    }

					if(in_array('social_share', $items)){
						$htmlDetail .= rtTPG()->rtShare($pID);
					}

                    if(in_array('read_more', $items)){
                        $htmlDetail .= "<span class='read-more'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$read_more_text}</a></span>";
                    }
                if(!empty($htmlDetail)){
                    $html .= "<div class='rt-detail'>$htmlDetail</div>";
                }
            $html .= '</div>';
        $html .= '</div>';
    $html .= '</div>';
$html .='</div>';

echo $html;